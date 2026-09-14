<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use App\Models\EMCustomerCredit;
use App\Models\EMCustomerCreditLog;
use App\Services\Training\TrainingFamilyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChildCreditController extends Controller
{
    public function options(TrainingFamilyService $families)
    {
        $children = EMCustomerChild::with(['parentRelations.customer'])
            ->where('is_active', 1)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->map(function (EMCustomerChild $child) use ($families) {
                $parents = $child->parentRelations
                    ->filter(fn ($relation) => !empty($relation->customer) && (bool) $relation->customer->active)
                    ->sortByDesc(fn ($relation) => (int) $relation->is_primary)
                    ->unique('customer_id')
                    ->values()
                    ->map(function ($relation) use ($families) {
                        $parent = $relation->customer;
                        $familyIds = $families->memberIds($parent);

                        return [
                            'id' => (int) $parent->id,
                            'name' => $parent->display_name,
                            'email' => $parent->email,
                            'phone' => $parent->phone,
                            'relationship' => $relation->relationship ?: ((int) $parent->parent_type === 2 ? 'Parent 2' : 'Parent 1'),
                            'family_balance' => $this->familyBalance($familyIds),
                        ];
                    });

                return [
                    'id' => (int) $child->id,
                    'name' => $child->full_name,
                    'team' => $child->team,
                    'class_year' => $child->class_year,
                    'parents' => $parents->all(),
                ];
            })
            ->filter(fn ($child) => !empty($child['parents']))
            ->values();

        return response()->json([
            'status' => true,
            'children' => $children,
            'validity' => 'No Expiration — credits remain valid until used.',
        ]);
    }

    public function store(Request $request, TrainingFamilyService $families)
    {
        $data = $request->validate([
            'child_id' => ['required', 'integer', 'exists:em_customer_children,id'],
            'customer_id' => ['required', 'integer', 'exists:em_customers,id'],
            'credit_option' => ['required', 'string', 'in:1,6,12,custom'],
            'custom_credits' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $child = EMCustomerChild::query()
            ->whereKey($data['child_id'])
            ->where('is_active', 1)
            ->firstOrFail();

        $parent = EMCustomer::query()
            ->whereKey($data['customer_id'])
            ->where('active', 1)
            ->firstOrFail();

        $linked = EMCustomerChildParent::query()
            ->where('child_id', $child->id)
            ->where('customer_id', $parent->id)
            ->exists();

        if (!$linked) {
            return response()->json([
                'status' => false,
                'message' => 'The selected parent is not linked to this child.',
            ], 422);
        }

        $amount = $data['credit_option'] === 'custom'
            ? (int) ($data['custom_credits'] ?? 0)
            : (int) $data['credit_option'];

        if ($amount < 1) {
            return response()->json(['status' => false, 'message' => 'Enter a valid credit amount.'], 422);
        }

        $familyIds = $families->memberIds($parent);
        $balanceBefore = $this->familyBalance($familyIds);
        $note = trim((string) ($data['note'] ?? ''));

        $credit = DB::transaction(function () use ($request, $parent, $child, $amount, $note) {
            $credit = EMCustomerCredit::create([
                'customer_id' => $parent->id,
                'package_id' => null,
                'order_id' => null,
                'total_classes' => $amount,
                'used_classes' => 0,
                'remaining_classes' => $amount,
                'valid_from' => null,
                'valid_until' => null,
                'status' => 'active',
            ]);

            $adminName = optional($request->user())->name ?: 'Admin';
            $message = $adminName . ' added ' . $amount . ' shared family credit' . ($amount === 1 ? '' : 's') .
                ' for ' . $child->full_name . ' through ' . $parent->display_name . '. Credits do not expire and remain valid until used.';

            if ($note !== '') {
                $message .= ' Internal note: ' . $note;
            }

            EMCustomerCreditLog::create([
                'customer_id' => $parent->id,
                'credit_id' => $credit->id,
                'booking_id' => null,
                'type' => 'credit',
                'classes' => $amount,
                'note' => $message,
                'description' => 'Admin-added ACES shared family Training credits.',
            ]);

            return $credit;
        });

        return response()->json([
            'status' => true,
            'message' => $amount . ' shared family credit' . ($amount === 1 ? '' : 's') . ' added successfully.',
            'credit_id' => (int) $credit->id,
            'family_balance_before' => $balanceBefore,
            'family_balance_after' => $balanceBefore + $amount,
            'validity' => 'No Expiration — Until Used',
        ]);
    }

    private function familyBalance(array $familyIds): int
    {
        if ($familyIds === []) {
            return 0;
        }

        return (int) EMCustomerCredit::query()
            ->whereIn('customer_id', $familyIds)
            ->available()
            ->sum('remaining_classes');
    }
}
