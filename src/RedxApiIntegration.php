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

    public function createParcel(array $payload): Response
    {
        return $this->post($this->endpoint('create_parcel'), $payload);
    }

    public function parcels(array $query = []): Response
    {
        return $this->get($this->endpoint('list_parcels'), $query);
    }

    public function parcelDetails(string|int $parcelId): Response
    {
        return $this->get($this->endpoint('parcel_details', ['parcel_id' => (string) $parcelId]));
    }

    public function trackParcel(string $trackingId): Response
    {
        return $this->get($this->endpoint('track_parcel', ['tracking_id' => $trackingId]));
    }

    public function cancelParcel(string|int $parcelId, array $payload = []): Response
    {
        return $this->post($this->endpoint('cancel_parcel', ['parcel_id' => (string) $parcelId]), $payload);
    }

    public function areas(array $query = []): Response
    {
        return $this->get($this->endpoint('areas'), $query);
    }

    public function stores(array $query = []): Response
    {
        return $this->get($this->endpoint('stores'), $query);
    }

    public function getEndpoint(string $name, array $replacements = [], array $query = []): Response
    {
        return $this->get($this->endpoint($name, $replacements), $query);
    }

    public function postEndpoint(string $name, array $payload = [], array $replacements = []): Response
    {
        return $this->post($this->endpoint($name, $replacements), $payload);
    }

    public function putEndpoint(string $name, array $payload = [], array $replacements = []): Response
    {
        return $this->put($this->endpoint($name, $replacements), $payload);
    }

    public function patchEndpoint(string $name, array $payload = [], array $replacements = []): Response
    {
        return $this->patch($this->endpoint($name, $replacements), $payload);
    }

    public function deleteEndpoint(string $name, array $payload = [], array $replacements = []): Response
    {
        return $this->delete($this->endpoint($name, $replacements), $payload);
    }

    public function callEndpoint(string $method, string $name, array $payload = [], array $replacements = []): Response
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

    public function get(string $uri, array $query = []): Response
    {
        return $this->request()->get($uri, $query);
    }

    public function post(string $uri, array $payload = []): Response
    {
        return $this->request()->post($uri, $payload);
    }

    public function put(string $uri, array $payload = []): Response
    {
        return $this->request()->put($uri, $payload);
    }

    public function patch(string $uri, array $payload = []): Response
    {
        return $this->request()->patch($uri, $payload);
    }

    public function delete(string $uri, array $payload = []): Response
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
}
