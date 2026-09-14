<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcesTrainingPortalController extends FamilyTrainingPortalController
{
    public function updateCart(Request $request, $id)
    {
        $response = parent::updateCart($request, $id);
        $this->cleanTrainingFlashMessages();

        return $response;
    }

    public function removeCart($id)
    {
        $response = parent::removeCart($id);
        $this->cleanTrainingFlashMessages();

        return $response;
    }

    public function checkout()
    {
        $response = parent::checkout();
        $this->cleanTrainingFlashMessages();

        return $response;
    }

    private function cleanTrainingFlashMessages(): void
    {
        foreach (['success', 'error'] as $key) {
            if (!session()->has($key)) {
                continue;
            }

            $message = (string) session($key);
            $message = str_ireplace([
                'shared family Training cart',
                'shared family cart',
                'family Training cart',
            ], [
                'Training cart',
                'cart',
                'Training cart',
            ], $message);

            session()->flash($key, $message);
        }
    }
}
