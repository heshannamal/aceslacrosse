<?php

namespace App\Services\Training;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use Illuminate\Support\Collection;

class TrainingFamilyService
{
    /**
     * Return one deterministic key for a linked Parent 1 / Parent 2 pair.
     */
    public function familyKey(?EMCustomer $customer): int
    {
        $ids = $this->memberIds($customer);

        return $ids === [] ? 0 : min($ids);
    }

    /**
     * Resolve the Parent 1 / Parent 2 records that belong to the same Training
     * account relationship.
     *
     * relational_id is authoritative whenever present. For older/imported
     * records with no relational_id at all, a common-child fallback is used
     * only when it identifies exactly one unambiguous co-parent. This avoids
     * merging unrelated co-parents in blended-household data.
     */
    public function memberIds(?EMCustomer $customer): array
    {
        if (!$customer) {
            return [];
        }

        $customerId = (int) $customer->id;
        $relationalId = (int) ($customer->relational_id ?? 0);

        if ($relationalId > 0) {
            return EMCustomer::query()
                ->where(function ($query) use ($customerId, $relationalId) {
                    $query->where('id', $customerId)
                        ->orWhere('id', $relationalId)
                        ->orWhere('relational_id', $relationalId);
                })
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->all();
        }

        // Parent 1 commonly has relational_id = NULL while Parent 2 points to
        // Parent 1. Resolve that explicit reverse relationship first.
        $explicitIds = EMCustomer::query()
            ->where('id', $customerId)
            ->orWhere('relational_id', $customerId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();

        if (count($explicitIds) > 1) {
            return $explicitIds;
        }

        // Legacy fallback: only accept one unique co-parent found through the
        // same child. If multiple different co-parents exist, keep the account
        // isolated rather than risk exposing another household's data.
        $childIds = EMCustomerChildParent::query()
            ->where('customer_id', $customerId)
            ->pluck('child_id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($childIds === []) {
            return [$customerId];
        }

        $coParentIds = EMCustomerChildParent::query()
            ->whereIn('child_id', $childIds)
            ->where('customer_id', '!=', $customerId)
            ->pluck('customer_id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (count($coParentIds) !== 1) {
            return [$customerId];
        }

        $ids = [$customerId, (int) $coParentIds[0]];
        sort($ids);

        return array_values(array_unique($ids));
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
