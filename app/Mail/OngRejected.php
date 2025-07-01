<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OngRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $ong;
    public $motivo;

    public function __construct($ong, $motivo)
    {
        $this->ong = $ong;
        $this->motivo = $motivo;
    }
    public function build()
    {
        return $this->markdown('emails.ong_rejected')
                    ->subject('Seu cadastro foi rejeitado - Busca Pet')
                    ->with([
                        'ong' => $this->ong,
                        'motivo' => $this->motivo,
                        'logoUrl' => asset('img/logo 3.png') // Imagem embedada

                    ]);
    }
}