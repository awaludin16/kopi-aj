<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\XenditWebhookController;

Route::post('/webhooks/xendit', [XenditWebhookController::class, 'handle'])
    ->name('webhook.xendit');
