<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $random_six_digits;
    public $user;

    /**
     * Create a new message instance.
     *
     * @var $random_six_digits
     * @return void
     */
    public function __construct(User $user, $random_six_digits)
    {
        $this->random_six_digits = $random_six_digits;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.confirm_registration');
    }
}
