<?php

namespace App\Http\Controllers;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use App\Models\EMCustomerChildParent;
use App\Models\EMSessionBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TrainingProfileController extends Controller
{
    public function index()
    {
        $customer = $this->customer();
        $children = $customer->activeChildren()->orderBy('first_name')->orderBy('last_name')->get();

        return view('pages.customer_sessions.portal.profile', compact('customer', 'children'));
    }

    public function update(Request $request)
    {
        $customer = $this->customer();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190', Rule::unique('em_customers', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:80'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (!empty($data['password'])) {
            if (!$customer->password || !Hash::check((string) $data['current_password'], $customer->password)) {
                return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
            }
            $customer->password = Hash::make($data['password']);
        }

        if ($request->hasFile('profile_photo')) {
            if ($customer->profile_photo && !Str::startsWith($customer->profile_photo, ['http://', 'https://'])) {
                Storage::disk('public')->delete($customer->profile_photo);
            }
            $customer->profile_photo = $request->file('profile_photo')->store('training-customers', 'public');
        }

        $customer->first_name = trim($data['first_name']);
        $customer->last_name = $this->nullableTrim($data['last_name'] ?? null);
        $customer->email = strtolower(trim($data['email']));
        $customer->phone = $this->nullableTrim($data['phone'] ?? null);
        $customer->save();

        session(['em_customer_name' => $customer->display_name]);

        return back()->with('success', 'Training profile updated.');
    }

    public function storeChild(Request $request)
    {
        $customer = $this->customer();
        $data = $this->childData($request);

        DB::transaction(function () use ($customer, $data) {
            $child = EMCustomerChild::create($data + ['is_active' => 1]);
            EMCustomerChildParent::create([
                'customer_id' => $customer->id,
                'child_id' => $child->id,
                'relationship' => 'Parent 1',
                'is_primary' => 1,
                'can_book' => 1,
                'can_pay' => 1,
                'can_pickup' => 0,
                'notes' => null,
            ]);
        });

        return back()->with('success', 'Player added.');
    }

    public function updateChild(Request $request, EMCustomerChild $child)
    {
        $customer = $this->customer();
        $this->ensureChildOwnership($customer, $child);
        $child->update($this->childData($request));

        return back()->with('success', 'Player updated.');
    }

    public function deleteChild(EMCustomerChild $child)
    {
        $customer = $this->customer();
        $this->ensureChildOwnership($customer, $child);

        $hasUpcoming = EMSessionBooking::query()
            ->where('customer_id', $customer->id)
            ->where(function ($query) use ($child) {
                $query->where('child_id', $child->id)->orWhere('customer_child_id', $child->id);
            })
            ->whereIn('status', ['pending_payment', 'booked', 'paid', 'completed'])
            ->whereHas('sessionEvent', fn ($query) => $query->whereDate('event_date', '>=', today('America/Los_Angeles')))
            ->exists();

        if ($hasUpcoming) {
            return back()->with('error', 'This player has an active upcoming booking and cannot be removed yet.');
        }

        $child->is_active = 0;
        $child->save();

        return back()->with('success', 'Player removed.');
    }

    private function childData(Request $request): array
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'team' => ['nullable', 'string', 'max:150'],
            'spring_team' => ['nullable', 'string', 'max:150'],
            'grade' => ['nullable', 'string', 'max:50'],
            'birthdate' => ['nullable', 'date'],
            'position' => ['nullable', 'string', 'max:255'],
        ]);

        foreach (['first_name', 'last_name', 'team', 'spring_team', 'grade', 'position'] as $key) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $this->nullableTrim($data[$key]);
            }
        }

        return $data;
    }

    private function ensureChildOwnership(EMCustomer $customer, EMCustomerChild $child): void
    {
        abort_unless(
            EMCustomerChildParent::where('customer_id', $customer->id)->where('child_id', $child->id)->exists(),
            403,
            'This player is not linked to your Training account.'
        );
    }

    private function customer(): EMCustomer
    {
        return EMCustomer::query()->whereKey(session('em_customer_id'))->where('active', 1)->firstOrFail();
    }

    private function nullableTrim($value): ?string
    {
        $value = trim((string) ($value ?? ''));
        return $value === '' ? null : $value;
    }
}
