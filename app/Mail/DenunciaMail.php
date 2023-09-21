<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DenunciaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Hay una nueva denuncia desde la app móvil.";
    public $denuncia;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($denuncia)
    {
        $this->denuncia = $denuncia;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.denuncia');
    }
}
