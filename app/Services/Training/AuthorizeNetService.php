<?php

namespace App\Services\Training;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AuthorizeNetService
{
    public function charge(array $card, float $amount, array $billing, string $invoiceNumber): array
    {
        $loginId = trim((string) config('services.authorize_net.login_id'));
        $transactionKey = trim((string) config('services.authorize_net.transaction_key'));

        if ($loginId === '' || $transactionKey === '') {
            throw new RuntimeException('Authorize.Net credentials are not configured.');
        }

        $environment = strtolower((string) config('services.authorize_net.environment', 'sandbox'));
        $configuredUrl = trim((string) config('services.authorize_net.url'));
        $url = $configuredUrl !== ''
            ? $configuredUrl
            : ($environment === 'production'
                ? 'https://api.authorize.net/xml/v1/request.api'
                : 'https://apitest.authorize.net/xml/v1/request.api');

        $payload = [
            'createTransactionRequest' => [
                'merchantAuthentication' => [
                    'name' => $loginId,
                    'transactionKey' => $transactionKey,
                ],
                'refId' => 'aces-' . substr(hash('sha256', $invoiceNumber . microtime(true)), 0, 16),
                'transactionRequest' => [
                    'transactionType' => 'authCaptureTransaction',
                    'amount' => number_format($amount, 2, '.', ''),
                    'payment' => [
                        'creditCard' => [
                            'cardNumber' => preg_replace('/\D+/', '', (string) ($card['number'] ?? '')),
                            'expirationDate' => sprintf('%04d-%02d', (int) ($card['year'] ?? 0), (int) ($card['month'] ?? 0)),
                            'cardCode' => (string) ($card['cvv'] ?? ''),
                        ],
                    ],
                    'order' => [
                        'invoiceNumber' => substr($invoiceNumber, 0, 20),
                        'description' => 'ACES Lacrosse Training',
                    ],
                    'customer' => [
                        'email' => (string) ($billing['email'] ?? ''),
                    ],
                    'billTo' => [
                        'firstName' => (string) ($billing['first_name'] ?? ''),
                        'lastName' => (string) ($billing['last_name'] ?? ''),
                        'company' => 'ACES Lacrosse',
                        'address' => (string) ($billing['street'] ?? ''),
                        'city' => (string) ($billing['city'] ?? ''),
                        'state' => (string) ($billing['state'] ?? ''),
                        'zip' => (string) ($billing['zip'] ?? ''),
                        'country' => (string) ($billing['country'] ?? 'USA'),
                    ],
                ],
            ],
        ];

        $response = Http::asJson()->timeout(30)->post($url, $payload);
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', (string) $response->body());
        $data = json_decode($raw, true) ?: [];

        if (!$response->successful()) {
            throw new RuntimeException('Payment gateway is unavailable. Please try again.');
        }

        $transaction = data_get($data, 'transactionResponse', []);
        $responseCode = (string) data_get($transaction, 'responseCode', '');
        $transactionId = (string) data_get($transaction, 'transId', '');
        $message = (string) data_get($transaction, 'messages.0.description', '');

        if ($responseCode !== '1' || $transactionId === '') {
            $error = (string) data_get($transaction, 'errors.0.errorText')
                ?: (string) data_get($data, 'messages.message.0.text')
                ?: 'The card was declined.';
            throw new RuntimeException($error);
        }

        return [
            'transaction_id' => $transactionId,
            'auth_code' => (string) data_get($transaction, 'authCode', ''),
            'response_code' => $responseCode,
            'message' => $message ?: 'Approved',
            'ref_id' => (string) data_get($data, 'refId', ''),
            'environment' => $environment,
        ];
    }
}
