<?php

namespace App\Mail;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    public function build()
    {
        return $this->subject('Registration Confirmation')
                    ->view('emails.default');
    }
}
