<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EMAdminCreditAdditionLog;
use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use App\Models\EMCustomerCredit;
use App\Models\EMCustomerCreditLog;
use App\Services\Training\TrainingFamilyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChildCreditController extends Controller
{
    private const TIMEZONE = 'America/Los_Angeles';

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
                        $linkedParentIds = $families->memberIds($parent);

                        return [
                            'id' => (int) $parent->id,
                            'name' => $parent->display_name,
                            'email' => $parent->email,
                            'phone' => $parent->phone,
                            'relationship' => $relation->relationship ?: ((int) $parent->parent_type === 2 ? 'Parent 2' : 'Parent 1'),
                            'credit_balance' => $this->creditBalance($linkedParentIds),
                        ];
                    });

                return [
                    'id' => (int) $child->id,
                    'name' => $child->full_name,
                    'team' => $child->team,
                    'spring_team' => $child->spring_team,
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

    public function logs(Request $request)
    {
        if (!Schema::hasTable('em_admin_credit_addition_logs')) {
            return response()->json([
                'status' => true,
                'logs' => [],
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 10,
                    'total' => 0,
                    'from' => 0,
                    'to' => 0,
                ],
                'migration_required' => true,
            ]);
        }

        $perPage = min(50, max(5, (int) $request->integer('per_page', 10)));

        $paginator = EMAdminCreditAdditionLog::with(['adminUser', 'child', 'customer'])
            ->latest('id')
            ->paginate($perPage);

        $logs = collect($paginator->items())->map(function (EMAdminCreditAdditionLog $log) {
            $adminName = trim((string) optional($log->adminUser)->name);

            return [
                'id' => (int) $log->id,
                'created_at' => $log->created_at
                    ? Carbon::parse($log->created_at)->timezone(self::TIMEZONE)->format('M d, Y g:i A')
                    : '—',
                'child' => $log->child ? $log->child->full_name : 'Deleted child',
                'parent' => $log->customer ? $log->customer->display_name : 'Deleted parent',
                'parent_email' => optional($log->customer)->email,
                'credits' => (int) $log->credit_amount,
                'option' => $log->credit_option,
                'balance_before' => (int) $log->balance_before,
                'balance_after' => (int) $log->balance_after,
                'admin' => $adminName !== '' ? $adminName : 'Admin #' . ($log->admin_user_id ?: '—'),
                'note' => $log->note,
            ];
        })->values();

        return response()->json([
            'status' => true,
            'logs' => $logs,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem() ?: 0,
                'to' => $paginator->lastItem() ?: 0,
            ],
        ]);
    }

    public function store(Request $request, TrainingFamilyService $families)
    {
        if (!Schema::hasTable('em_admin_credit_addition_logs')) {
            return response()->json([
                'status' => false,
                'message' => 'Credit Addition Log is not ready yet. Please run php artisan migrate first.',
            ], 500);
        }

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

        $linkedParentIds = $families->memberIds($parent);
        $balanceBefore = $this->creditBalance($linkedParentIds);
        $balanceAfter = $balanceBefore + $amount;
        $note = trim((string) ($data['note'] ?? ''));

        $result = DB::transaction(function () use (
            $request,
            $parent,
            $child,
            $amount,
            $data,
            $note,
            $balanceBefore,
            $balanceAfter
        ) {
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
            $message = $adminName . ' added ' . $amount . ' Training credit' . ($amount === 1 ? '' : 's') .
                ' for ' . $child->full_name . ' through ' . $parent->display_name . '. Credits remain valid until used.';

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
                'description' => 'Admin-added ACES Training credits.',
            ]);

            $additionLog = EMAdminCreditAdditionLog::create([
                'admin_user_id' => optional($request->user())->id,
                'child_id' => $child->id,
                'customer_id' => $parent->id,
                'credit_id' => $credit->id,
                'credit_amount' => $amount,
                'credit_option' => $data['credit_option'],
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'note' => $note !== '' ? $note : null,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
            ]);

            return compact('credit', 'additionLog');
        });

        return response()->json([
            'status' => true,
            'message' => $amount . ' credit' . ($amount === 1 ? '' : 's') . ' added successfully.',
            'credit_id' => (int) $result['credit']->id,
            'log_id' => (int) $result['additionLog']->id,
            'credit_balance_before' => $balanceBefore,
            'credit_balance_after' => $balanceAfter,
            'validity' => 'No Expiration — Until Used',
        ]);
    }

    private function creditBalance(array $linkedParentIds): int
    {
        if ($linkedParentIds === []) {
            return 0;
        }

        return (int) EMCustomerCredit::query()
            ->whereIn('customer_id', $linkedParentIds)
            ->available()
            ->sum('remaining_classes');
    }
}
