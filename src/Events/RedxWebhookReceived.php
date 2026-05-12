<?php

namespace Kejubayer\RedxApiIntegration\Events;

use Kejubayer\RedxApiIntegration\Models\RedxWebhookRequest;

class RedxWebhookReceived
{
    public function __construct(
        public readonly RedxWebhookRequest $webhookRequest,
    ) {
    }
}
