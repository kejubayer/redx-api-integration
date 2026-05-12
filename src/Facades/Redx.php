<?php

namespace Kejubayer\RedxApiIntegration\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\Response createParcel(array $payload)
 * @method static \Illuminate\Http\Client\Response parcels(array $query = [])
 * @method static \Illuminate\Http\Client\Response parcelDetails(string|int $parcelId)
 * @method static \Illuminate\Http\Client\Response trackParcel(string $trackingId)
 * @method static \Illuminate\Http\Client\Response cancelParcel(string|int $parcelId, array $payload = [])
 * @method static \Illuminate\Http\Client\Response areas(array $query = [])
 * @method static \Illuminate\Http\Client\Response stores(array $query = [])
 * @method static \Illuminate\Http\Client\Response getEndpoint(string $name, array $replacements = [], array $query = [])
 * @method static \Illuminate\Http\Client\Response postEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static \Illuminate\Http\Client\Response putEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static \Illuminate\Http\Client\Response patchEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static \Illuminate\Http\Client\Response deleteEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static \Illuminate\Http\Client\Response callEndpoint(string $method, string $name, array $payload = [], array $replacements = [])
 * @method static \Illuminate\Http\Client\Response get(string $uri, array $query = [])
 * @method static \Illuminate\Http\Client\Response post(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\Response put(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\Response patch(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\Response delete(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\PendingRequest request()
 * @method static string endpoint(string $name, array $replacements = [])
 *
 * @see \Kejubayer\RedxApiIntegration\RedxApiIntegration
 */
class Redx extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'redx';
    }
}
