<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interesse extends Model
{
    protected $table = 'tbinteresses';
    protected $primaryKey = 'idInteresse';
    
    protected $fillable = [
        'idUser',
        'idAnimal',
        'statusInteresse',
        'observacoes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relacionamento com usuário
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    // Relacionamento com animal
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'idAnimal', 'idAnimal');
    }

    // Relacionamento com notificações
    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class, 'idInteresse', 'idInteresse');
    }

    // Scopes para facilitar consultas
    public function scopePendentes($query)
    {
        return $query->where('statusInteresse', 'pendente');
    }

    public function scopeAprovados($query)
    {
        return $query->where('statusInteresse', 'aprovado');
    }

    public function scopeRejeitados($query)
    {
        return $query->where('statusInteresse', 'rejeitado');
    }

    public function scopeCancelados($query)
    {
        return $query->where('statusInteresse', 'cancelado');
    }

    // Método para verificar se o interesse está ativo
    public function isAtivo()
    {
        return in_array($this->statusInteresse, ['pendente', 'aprovado']);
    }

    // Método para cancelar interesse
    public function cancelar()
    {
        $this->statusInteresse = 'cancelado';
        $this->save();
        
        // Criar notificação de cancelamento
        $this->criarNotificacaoCancelamento();
    }

    // Método para criar notificação de cancelamento
    private function criarNotificacaoCancelamento()
    {
        $animal = $this->animal;
        $usuario = $this->usuario;
        
        if ($animal && $animal->idOng && $usuario) {
            Notificacao::create([
                'idOng' => $animal->idOng,
                'idInteresse' => $this->idInteresse,
                'tipoNotificacao' => 'cancelamento_interesse',
                'mensagem' => "O usuário {$usuario->nomeUser} cancelou o interesse no animal {$animal->nomeAnimal}."
            ]);
        }
    }
}

