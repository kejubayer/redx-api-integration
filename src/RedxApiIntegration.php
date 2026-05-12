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

    public function trackParcel(string $trackingId): Response
    {
        return $this->get($this->endpoint('track_parcel', ['tracking_id' => $trackingId]));
    }

    public function cancelParcel(string|int $parcelId, array $payload = []): Response
    {
        return $this->post($this->endpoint('cancel_parcel', ['parcel_id' => (string) $parcelId]), $payload);
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

    private function endpoint(string $name, array $replacements = []): string
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
