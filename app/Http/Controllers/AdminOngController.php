<?php

namespace App\Http\Controllers;

use App\Mail\OngRejected;
use App\Models\Ong;
use Illuminate\Http\Request;
use App\Mail\OngRejeitada;
use Illuminate\Support\Facades\Mail;

class AdminOngController extends Controller
{
    
    public function pendente()
    {
        $ongs = Ong::where('status', 'pendente')->get();
        return view('admin.ongs.pendente', compact('ongs'));
    }

    public function aprovar($id)
    {
        $ong = Ong::findOrFail($id);
        $ong->update(['status' => 'aprovado']);

        return back()->with('success', 'ONG aprovada com sucesso!');
    }



    public function rejeitar(Request $request, $id)
    {
        \Log::info('Iniciando processo de rejeição para ONG: '.$id);
        
        try {
            $ong = Ong::findOrFail($id);
            
            // Debug da configuração de email
            \Log::debug('Configuração atual de email:', [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption')
            ]);
    
            // Teste simples de conexão
            try {
                Mail::raw('Teste de conexão', function($message) {
                    $message->to('teste@exemplo.com')->subject('Teste');
                });
                \Log::info('Teste de email enviado com sucesso');
            } catch (\Exception $e) {
                \Log::error('Falha no teste de email: '.$e->getMessage());
                throw $e;
            }
    
            // Processo de rejeição
            $ong->update([
                'status' => 'rejeitado',
                'motivo_rejeicao' => $request->motivo
            ]);
            
            Mail::to($ong->emailOng)->send(new OngRejected($ong, $request->motivo));
            
            return back()->with('success', 'ONG rejeitada com sucesso!');
            
        } catch (\Exception $e) {
            \Log::error('Erro completo: '.$e->getMessage());
            return back()->with('error', 'Erro: '.$e->getMessage());
        }
    }
}