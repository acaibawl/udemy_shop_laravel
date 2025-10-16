<?php

namespace App\Jobs;

use App\Mail\TestMail;
use App\Mail\ThanksMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendThanksMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly array $products,
        public readonly User $user,
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)
            ->send(new ThanksMail($this->products, $this->user));
    }
}
