<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table = 'tbnotificacoes';
    protected $primaryKey = 'idNotificacao';
    
    protected $fillable = [
        'idOng',
        'idInteresse',
        'tipoNotificacao',
        'mensagem',
        'lida'
    ];

    protected $casts = [
        'lida' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relacionamento com ONG
    public function ong()
    {
        return $this->belongsTo(Ong::class, 'idOng', 'idOng');
    }

    // Relacionamento com interesse
    public function interesse()
    {
        return $this->belongsTo(Interesse::class, 'idInteresse', 'idInteresse');
    }

    // Scopes para facilitar consultas
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }

    public function scopeLidas($query)
    {
        return $query->where('lida', true);
    }

    public function scopeNovoInteresse($query)
    {
        return $query->where('tipoNotificacao', 'novo_interesse');
    }

    public function scopeCancelamentoInteresse($query)
    {
        return $query->where('tipoNotificacao', 'cancelamento_interesse');
    }

    public function scopeRecentes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Método para marcar como lida
    public function marcarComoLida()
    {
        $this->lida = true;
        $this->save();
    }

    // Método estático para criar notificação de novo interesse
    public static function criarNovoInteresse($idOng, $idInteresse, $nomeUsuario, $nomeAnimal)
    {
        return self::create([
            'idOng' => $idOng,
            'idInteresse' => $idInteresse,
            'tipoNotificacao' => 'novo_interesse',
            'mensagem' => "Novo interesse! O usuário {$nomeUsuario} demonstrou interesse no animal {$nomeAnimal}."
        ]);
    }

    // Método estático para criar notificação de cancelamento
    public static function criarCancelamentoInteresse($idOng, $idInteresse, $nomeUsuario, $nomeAnimal)
    {
        return self::create([
            'idOng' => $idOng,
            'idInteresse' => $idInteresse,
            'tipoNotificacao' => 'cancelamento_interesse',
            'mensagem' => "O usuário {$nomeUsuario} cancelou o interesse no animal {$nomeAnimal}."
        ]);
    }

    // Método para obter contagem de notificações não lidas por ONG
    public static function contarNaoLidasPorOng($idOng)
    {
        return self::where('idOng', $idOng)->naoLidas()->count();
    }

    // Método para obter notificações recentes por ONG
    public static function recentesPorOng($idOng, $limite = 10)
    {
        return self::where('idOng', $idOng)
                   ->with(['interesse.usuario', 'interesse.animal'])
                   ->recentes()
                   ->limit($limite)
                   ->get();
    }
}

