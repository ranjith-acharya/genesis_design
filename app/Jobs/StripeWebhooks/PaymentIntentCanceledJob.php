<?php

namespace App\Jobs\StripeWebhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class PaymentIntentCanceledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response=$this->webhookCall->payload['data']['object'];
        $payment_id=$response['charges']['data'][0]['payment_intent'];
        $system_design=SystemDesign::where('stripe_payment_code', $payment_id)->first();
        $system_design->payment_status=Statics::DESIGN_PAYMENT_STATUS_CANCELED;
        $system_design->save();
    }
}
