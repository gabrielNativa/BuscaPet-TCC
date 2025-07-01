<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use App\Models\Comentario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DenunciaAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'analise');

        $denuncias = Denuncia::with(['comentario.user', 'usuario'])
            ->where('statusDenuncia', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $counts = [
            'analise' => Denuncia::where('statusDenuncia', 'analise')->count(),
            'resolvida' => Denuncia::where('statusDenuncia', 'resolvida')->count(),
        ];

        return view('admin.denuncias.index', compact('denuncias', 'status', 'counts'));
    }

    public function show($id)
    {
        $denuncia = Denuncia::with(['comentario.user', 'comentario.post', 'usuario'])
            ->findOrFail($id);

        return view('admin.denuncias.show', compact('denuncia'));
    }


    public function aprovar($id)
    {
        DB::beginTransaction();

        try {
            $denuncia = Denuncia::with(['comentario.user', 'usuario'])->findOrFail($id);

            if ($denuncia->comentario) {
                $denuncia->comentario->update(['visivel' => 0]);
            }

            if ($denuncia->comentario && $denuncia->comentario->user) {
                $denuncia->comentario->user->update(['ativo' => 0]);
            }

            $denuncia->update([
                'statusDenuncia' => 'resolvida',
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.denuncias.index', ['status' => 'resolvida'])
                ->with('success', 'Ação concluída: Comentário ocultado e usuário bloqueado');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }


    public function rejeitar($id)
    {
        $denuncia = Denuncia::findOrFail($id);

        $denuncia->update([
            'statusDenuncia' => 'resolvida',
            'updated_at' => now()
        ]);

        return redirect()->route('admin.denuncias.index', ['status' => 'resolvida'])
            ->with('success', 'Denúncia marcada como resolvida (rejeitada)');
    }
}
