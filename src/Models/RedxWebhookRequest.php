<?php

namespace Kejubayer\RedxApiIntegration\Models;

use Illuminate\Database\Eloquent\Model;

class RedxWebhookRequest extends Model
{
    protected $table = 'redx_webhook_requests';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'redx_timestamp' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function markAsProcessed(): bool
    {
        return $this->forceFill([
            'processed_at' => now(),
        ])->save();
    }
}
