<?php

namespace Modules\Order\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\WelcomeEmail;
use Modules\Order\Models\Order;

class ProcessSendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $userMail;
    protected Order $order;
    protected User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(string $userMail, Order $order, User $user)
    {
        $this->userMail = $userMail;
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->userMail)->queue(new WelcomeEmail($this->order, $this->user));
    }
}
