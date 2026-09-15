<?php

namespace App\Http\Controllers\Admin;

use App\Models\EMCustomer;
use App\Models\EMCustomerChild;
use Illuminate\Http\Request;

class AcesParentsBookingController extends ParentsBookingController
{
    public function storeChild(Request $request)
    {
        if ($response = $this->existingParentConfirmationError($request)) {
            return $response;
        }

        return parent::storeChild($request);
    }

    public function updateChild(Request $request, EMCustomerChild $child)
    {
        if ($response = $this->existingParentConfirmationError($request)) {
            return $response;
        }

        return parent::updateChild($request, $child);
    }

    private function existingParentConfirmationError(Request $request)
    {
        $resolved = [];

        foreach (['parent_one' => 'Parent 1', 'parent_two' => 'Parent 2'] as $prefix => $label) {
            $email = strtolower(trim((string) $request->input($prefix . '_email')));
            $selectedId = (int) $request->input($prefix . '_id');

            if ($email === '') {
                continue;
            }

            $existing = EMCustomer::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            if (!$existing) {
                continue;
            }

            if ($selectedId !== (int) $existing->id) {
                return back()
                    ->withInput()
                    ->with('error', $label . ' already exists with this email. Please confirm "Use Existing Parent" before saving the member.');
            }

            $resolved[$prefix] = (int) $existing->id;
        }

        if (!empty($resolved['parent_one']) && !empty($resolved['parent_two']) && $resolved['parent_one'] === $resolved['parent_two']) {
            return back()
                ->withInput()
                ->with('error', 'Parent 1 and Parent 2 cannot be the same customer account.');
        }

        return null;
    }
}
