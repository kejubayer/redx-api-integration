<?php

namespace Kejubayer\RedxApiIntegration\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array createParcel(array $payload)
 * @method static array parcels(array $query = [])
 * @method static array parcelDetails(string|int $parcelId)
 * @method static array trackParcel(string $trackingId)
 * @method static array cancelParcel(string|int $parcelId, array $payload = [])
 * @method static array areas(array $query = [])
 * @method static array stores(array $query = [])
 * @method static array getEndpoint(string $name, array $replacements = [], array $query = [])
 * @method static array postEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static array putEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static array patchEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static array deleteEndpoint(string $name, array $payload = [], array $replacements = [])
 * @method static array callEndpoint(string $method, string $name, array $payload = [], array $replacements = [])
 * @method static array get(string $uri, array $query = [])
 * @method static array post(string $uri, array $payload = [])
 * @method static array put(string $uri, array $payload = [])
 * @method static array patch(string $uri, array $payload = [])
 * @method static array delete(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\Response getResponse(string $uri, array $query = [])
 * @method static \Illuminate\Http\Client\Response postResponse(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\Response putResponse(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\Response patchResponse(string $uri, array $payload = [])
 * @method static \Illuminate\Http\Client\Response deleteResponse(string $uri, array $payload = [])
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
