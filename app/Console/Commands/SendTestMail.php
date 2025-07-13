<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test';
    protected $description = 'Enviar un correo de prueba a MailHog';

    public function handle()
    {
        $email = 'test@lightit.com'; 

        Mail::to($email)->send(new TestMail());

        $this->info('Correo de prueba enviado a MailHog correctamente.');
    }
}
