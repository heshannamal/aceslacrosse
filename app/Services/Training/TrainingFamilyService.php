<?php

namespace App\Services\Training;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use Illuminate\Support\Collection;

class TrainingFamilyService
{
    public function familyKey(?EMCustomer $customer): int
    {
        if (!$customer) {
            return 0;
        }

        return (int) ($customer->relational_id ?: $customer->id);
    }

    public function memberIds(?EMCustomer $customer): array
    {
        if (!$customer) {
            return [];
        }

        $rootId = $this->familyKey($customer);

        $ids = EMCustomer::query()
            ->where(function ($query) use ($rootId) {
                $query->where('id', $rootId)
                    ->orWhere('relational_id', $rootId);
            })
            ->where('active', 1)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $ids[] = (int) $customer->id;

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
        $familyIds = $this->memberIds($customer);

        if ($familyIds === []) {
            return [];
        }

        return EMCustomerChildParent::query()
            ->whereIn('customer_id', $familyIds)
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
