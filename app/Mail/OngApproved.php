<?php

namespace App\Mail;

use App\Models\Ong;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OngApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $ong;

    public function __construct(Ong $ong)
    {
        $this->ong = $ong;
    }

    public function build()
    {
        return $this->markdown('emails.ong_approved')
                   ->subject('Cadastro Aprovado - Busca Pet')
                   ->with([
                    'ong' => $this->ong,
                    'logoUrl' => asset('img/logo 3.png') // Imagem embedada
                ]);
                   
    }
}