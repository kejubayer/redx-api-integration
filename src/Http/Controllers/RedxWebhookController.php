<?php

namespace Kejubayer\RedxApiIntegration\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kejubayer\RedxApiIntegration\Events\RedxWebhookReceived;
use Throwable;

class RedxWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $this->guardWebhookSecret($request);

        $payload = $request->json()->all() ?: $request->all();
        $modelClass = config('redx-api-integration.webhook_model');

        $webhookRequest = $modelClass::query()->create([
            'tracking_number' => $this->valueFromPayload($payload, 'tracking_number'),
            'redx_timestamp' => $this->timestampFromPayload($payload),
            'status' => $this->valueFromPayload($payload, 'status'),
            'message_en' => $this->valueFromPayload($payload, 'message_en'),
            'message_bn' => $this->valueFromPayload($payload, 'message_bn'),
            'invoice_number' => $this->valueFromPayload($payload, 'invoice_number'),
            'delivery_type' => $this->valueFromPayload($payload, 'delivery_type'),
            'payload' => $payload,
            'headers' => $request->headers->all(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'signature' => $request->headers->get('X-Redx-Signature') ?: $request->headers->get('X-Signature'),
        ]);

        event(new RedxWebhookReceived($webhookRequest));

        return response()->json([
            'message' => 'RedX webhook received.',
            'id' => $webhookRequest->getKey(),
        ]);
    }

    private function guardWebhookSecret(Request $request): void
    {
        $secret = config('redx-api-integration.webhook.secret');

        if (! $secret) {
            return;
        }

        $header = config('redx-api-integration.webhook.secret_header', 'X-Redx-Webhook-Secret');

        abort_unless(hash_equals((string) $secret, (string) $request->headers->get($header)), 403);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function valueFromPayload(array $payload, string $key): ?string
    {
        $value = data_get($payload, $key);

        if (is_scalar($value) && $value !== '') {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function timestampFromPayload(array $payload): ?Carbon
    {
        $timestamp = $this->valueFromPayload($payload, 'timestamp');

        if (! $timestamp) {
            return null;
        }

        try {
            return Carbon::parse($timestamp);
        } catch (Throwable) {
            return null;
        }
    }
}
