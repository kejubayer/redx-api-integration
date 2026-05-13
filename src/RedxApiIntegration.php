<?php

namespace Kejubayer\RedxApiIntegration;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use InvalidArgumentException;

class RedxApiIntegration
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly array $config = [],
    ) {
    }

    public function createParcel(array $payload): array
    {
        return $this->post($this->endpoint('create_parcel'), $payload);
    }

    public function parcelDetails(string $trackingId): array
    {
        return $this->get($this->endpoint('parcel_details', ['tracking_id' => $trackingId]));
    }

    public function trackParcel(string $trackingId): array
    {
        return $this->get($this->endpoint('track_parcel', ['tracking_id' => $trackingId]));
    }

    public function updateParcel(string $trackingId, string $propertyName, string $newValue, ?string $reason = null): array
    {
        return $this->patch($this->endpoint('update_parcel'), [
            'entity_type' => 'parcel-tracking-id',
            'entity_id' => $trackingId,
            'update_details' => array_filter([
                'property_name' => $propertyName,
                'new_value' => $newValue,
                'reason' => $reason,
            ], static fn ($value): bool => $value !== null),
        ]);
    }

    public function updateParcelRaw(array $payload): array
    {
        return $this->patch($this->endpoint('update_parcel'), $payload);
    }

    public function cancelParcel(string $trackingId, ?string $reason = null): array
    {
        return $this->updateParcel($trackingId, 'status', 'cancelled', $reason);
    }

    public function areas(array $query = []): array
    {
        return $this->get($this->endpoint('areas'), $query);
    }

    public function areasByPostCode(string|int $postCode): array
    {
        return $this->areas(['post_code' => $postCode]);
    }

    public function areasByDistrictName(string $districtName): array
    {
        return $this->areas(['district_name' => $districtName]);
    }

    public function createPickupStore(array $payload): array
    {
        return $this->post($this->endpoint('create_pickup_store'), $payload);
    }

    public function pickupStores(array $query = []): array
    {
        return $this->get($this->endpoint('pickup_stores'), $query);
    }

    public function pickupStoreDetails(string|int $pickupStoreId): array
    {
        return $this->get($this->endpoint('pickup_store_details', ['pickup_store_id' => (string) $pickupStoreId]));
    }

    public function stores(array $query = []): array
    {
        return $this->pickupStores($query);
    }

    public function calculateCharge(array $query): array
    {
        return $this->get($this->endpoint('charge_calculator'), $query);
    }

    public function getEndpoint(string $name, array $replacements = [], array $query = []): array
    {
        return $this->get($this->endpoint($name, $replacements), $query);
    }

    public function postEndpoint(string $name, array $payload = [], array $replacements = []): array
    {
        return $this->post($this->endpoint($name, $replacements), $payload);
    }

    public function putEndpoint(string $name, array $payload = [], array $replacements = []): array
    {
        return $this->put($this->endpoint($name, $replacements), $payload);
    }

    public function patchEndpoint(string $name, array $payload = [], array $replacements = []): array
    {
        return $this->patch($this->endpoint($name, $replacements), $payload);
    }

    public function deleteEndpoint(string $name, array $payload = [], array $replacements = []): array
    {
        return $this->delete($this->endpoint($name, $replacements), $payload);
    }

    public function callEndpoint(string $method, string $name, array $payload = [], array $replacements = []): array
    {
        return match (strtolower($method)) {
            'get' => $this->getEndpoint($name, $replacements, $payload),
            'post' => $this->postEndpoint($name, $payload, $replacements),
            'put' => $this->putEndpoint($name, $payload, $replacements),
            'patch' => $this->patchEndpoint($name, $payload, $replacements),
            'delete' => $this->deleteEndpoint($name, $payload, $replacements),
            default => throw new InvalidArgumentException("HTTP method [{$method}] is not supported."),
        };
    }

    public function get(string $uri, array $query = []): array
    {
        return $this->toArray($this->request()->get($uri, $query));
    }

    public function post(string $uri, array $payload = []): array
    {
        return $this->toArray($this->request()->post($uri, $payload));
    }

    public function put(string $uri, array $payload = []): array
    {
        return $this->toArray($this->request()->put($uri, $payload));
    }

    public function patch(string $uri, array $payload = []): array
    {
        return $this->toArray($this->request()->patch($uri, $payload));
    }

    public function delete(string $uri, array $payload = []): array
    {
        return $this->toArray($this->request()->delete($uri, $payload));
    }

    public function getResponse(string $uri, array $query = []): Response
    {
        return $this->request()->get($uri, $query);
    }

    public function postResponse(string $uri, array $payload = []): Response
    {
        return $this->request()->post($uri, $payload);
    }

    public function putResponse(string $uri, array $payload = []): Response
    {
        return $this->request()->put($uri, $payload);
    }

    public function patchResponse(string $uri, array $payload = []): Response
    {
        return $this->request()->patch($uri, $payload);
    }

    public function deleteResponse(string $uri, array $payload = []): Response
    {
        return $this->request()->delete($uri, $payload);
    }

    public function request(): PendingRequest
    {
        $baseUrl = rtrim((string) ($this->config['base_url'] ?? ''), '/');
        $apiToken = $this->config['api_token'] ?? null;
        $tokenHeader = (string) ($this->config['token_header'] ?? 'API-ACCESS-TOKEN');

        if ($baseUrl === '') {
            throw new InvalidArgumentException('RedX base URL is not configured.');
        }

        $request = $this->http
            ->baseUrl($baseUrl)
            ->acceptJson()
            ->asJson()
            ->timeout((int) ($this->config['timeout'] ?? 30));

        if ($apiToken) {
            $request->withHeaders([$tokenHeader => $apiToken]);
        }

        return $request;
    }

    public function endpoint(string $name, array $replacements = []): string
    {
        $endpoint = $this->config['endpoints'][$name] ?? null;

        if (! is_string($endpoint) || $endpoint === '') {
            throw new InvalidArgumentException("RedX endpoint [{$name}] is not configured.");
        }

        foreach ($replacements as $key => $value) {
            $endpoint = str_replace('{' . $key . '}', rawurlencode((string) $value), $endpoint);
        }

        return $endpoint;
    }

    private function toArray(Response $response): array
    {
        $data = $response->json();

        if (is_array($data)) {
            return $data;
        }

        return [
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }
}
