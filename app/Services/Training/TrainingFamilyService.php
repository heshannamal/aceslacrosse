<?php

namespace App\Services\Training;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use Illuminate\Support\Collection;

class TrainingFamilyService
{
    /**
     * Return a stable key for the linked Parent 1 / Parent 2 account group.
     *
     * Some older/imported records may not have relational_id populated
     * consistently, so the group is also resolved through children shared by
     * both parent records. Using the smallest customer id gives reminders and
     * other grouped operations one deterministic key from either parent.
     */
    public function familyKey(?EMCustomer $customer): int
    {
        $ids = $this->memberIds($customer);

        return $ids === [] ? 0 : min($ids);
    }

    /**
     * Resolve every customer record linked to this parent account.
     *
     * We intentionally keep inactive ids in this result because an active
     * parent must still be able to see historical bookings, cart rows or
     * credits that were originally stored against the other parent record.
     * Email recipients are filtered to active customers by members().
     */
    public function memberIds(?EMCustomer $customer): array
    {
        if (!$customer) {
            return [];
        }

        $ids = [(int) $customer->id];

        // Expand both the legacy relational_id link and common-child parent
        // links. A few passes safely resolves either direction of older data.
        for ($pass = 0; $pass < 5; $pass++) {
            $before = $ids;

            $customers = EMCustomer::query()
                ->whereIn('id', $ids)
                ->get(['id', 'relational_id']);

            $relationRoots = $customers
                ->map(fn ($item) => (int) ($item->relational_id ?: $item->id))
                ->merge($ids)
                ->filter()
                ->unique()
                ->values()
                ->all();

            if ($relationRoots !== []) {
                $relationIds = EMCustomer::query()
                    ->where(function ($query) use ($relationRoots) {
                        $query->whereIn('id', $relationRoots)
                            ->orWhereIn('relational_id', $relationRoots);
                    })
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $ids = array_merge($ids, $relationIds);
            }

            $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));

            $childIds = EMCustomerChildParent::query()
                ->whereIn('customer_id', $ids)
                ->pluck('child_id')
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->unique()
                ->values()
                ->all();

            if ($childIds !== []) {
                $coParentIds = EMCustomerChildParent::query()
                    ->whereIn('child_id', $childIds)
                    ->pluck('customer_id')
                    ->map(fn ($id) => (int) $id)
                    ->filter()
                    ->all();

                $ids = array_values(array_unique(array_merge($ids, $coParentIds)));
            }

            sort($ids);
            $comparison = $before;
            sort($comparison);

            if ($ids === $comparison) {
                break;
            }
        }

        return array_values(array_unique(array_filter($ids)));
    }

    public function members(?EMCustomer $customer): Collection
    {
        $ids = $this->memberIds($customer);

        if ($ids === []) {
            return collect();
        }

        return EMCustomer::query()
            ->whereIn('id', $ids)
            ->where('active', 1)
            ->orderByRaw('CASE WHEN parent_type = 1 THEN 0 WHEN parent_type = 2 THEN 1 ELSE 2 END')
            ->orderBy('id')
            ->get();
    }

    public function childIds(?EMCustomer $customer): array
    {
        $parentIds = $this->memberIds($customer);

        if ($parentIds === []) {
            return [];
        }

        return EMCustomerChildParent::query()
            ->whereIn('customer_id', $parentIds)
            ->pluck('child_id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function children(?EMCustomer $customer): Collection
    {
        $childIds = $this->childIds($customer);

        if ($childIds === []) {
            return collect();
        }

        return EMCustomerChild::query()
            ->whereIn('id', $childIds)
            ->where('is_active', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    public function recipients(?EMCustomer $customer, array $extraEmails = []): array
    {
        $recipients = [];
        $seen = [];
        $currentCustomerId = (int) ($customer?->id ?? 0);

        $parents = $this->members($customer)
            ->sortBy(function ($parent) use ($currentCustomerId) {
                if ((int) $parent->id === $currentCustomerId) {
                    return '0-' . str_pad((string) $parent->id, 10, '0', STR_PAD_LEFT);
                }

                $parentOrder = (int) ($parent->parent_type ?? 0) === 1 ? 1 : ((int) ($parent->parent_type ?? 0) === 2 ? 2 : 3);
                return $parentOrder . '-' . str_pad((string) $parent->id, 10, '0', STR_PAD_LEFT);
            });

        foreach ($parents as $parent) {
            $email = $this->normalizeEmail($parent->email);
            if (!$email || isset($seen[$email])) {
                continue;
            }

            $seen[$email] = true;
            $name = trim(($parent->first_name ?? '') . ' ' . ($parent->last_name ?? ''));
            $recipients[] = [
                'customer_id' => (int) $parent->id,
                'email' => $email,
                'name' => $name !== '' ? $name : 'Parent',
            ];
        }

        foreach ($extraEmails as $extraEmail) {
            $email = $this->normalizeEmail(is_array($extraEmail) ? ($extraEmail['email'] ?? null) : $extraEmail);
            if (!$email || isset($seen[$email])) {
                continue;
            }

            $seen[$email] = true;
            $recipients[] = [
                'customer_id' => null,
                'email' => $email,
                'name' => is_array($extraEmail) && !empty($extraEmail['name']) ? $extraEmail['name'] : 'Parent',
            ];
        }

        return $recipients;
    }

    private function normalizeEmail($email): ?string
    {
        $email = strtolower(trim((string) $email));

        return $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }
}
