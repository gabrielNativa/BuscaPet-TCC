<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Animal;
use App\Models\User;
use App\Models\Ong;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Totais gerais
        $totalAdocao = Animal::where('idStatusAnimal', 3)->count();
        $totalUsuarios = User::count();
        $totalAnimaisPerdidos = Animal::where('idStatusAnimal', 1)->count();
        $totalAnimaisEncontrados = Animal::where('idStatusAnimal', 2)->count();
        $totalCachorros = Animal::where('idEspecie', 1)->count();
        $totalGatos = Animal::where('idEspecie', 2)->count();

        // Dados semanais corretos (últimas 4 semanas)
        $startDate = Carbon::now()->subWeeks(3)->startOfWeek();
        $dadosSemanais = [];

        for ($i = 0; $i < 4; $i++) {
            $semanaInicio = $startDate->copy()->addWeeks($i);
            $semanaFim = $semanaInicio->copy()->endOfWeek();

            $adocoes = Animal::whereBetween('created_at', [$semanaInicio, $semanaFim])
                ->where('idStatusAnimal', 3)
                ->count();

            $perdidos = Animal::whereBetween('created_at', [$semanaInicio, $semanaFim])
                ->where('idStatusAnimal', 1)
                ->count();

            $encontrados = Animal::whereBetween('created_at', [$semanaInicio, $semanaFim])
                ->where('idStatusAnimal', 4)
                ->count();

            $dadosSemanais[] = [
                'semana' => 'Semana ' . ($i + 1) . ' (' . $semanaInicio->format('d/m') . ' - ' . $semanaFim->format('d/m') . ')',
                'adocoes' => $adocoes,
                'perdidos' => $perdidos,
                'encontrados' => $encontrados,
            ];
        }

        try {
            // Últimas ONGs cadastradas
            $recentOngs = Ong::orderBy('idOng', 'desc')
                ->take(5)
                ->get();

            // Retornar para a view 'home' os dados
            return view('home')->with([
                'recentOngs' => $recentOngs,
                'totalAdocao' => $totalAdocao,
                'totalUsuarios' => $totalUsuarios,
                'totalAnimaisPerdidos' => $totalAnimaisPerdidos,
                'totalAnimaisEncontrados' => $totalAnimaisEncontrados,
                'totalCachorros' => $totalCachorros,
                'totalGatos' => $totalGatos,
                'dadosSemanais' => $dadosSemanais,
            ]);
        } catch (\Exception $e) {
            Log::error("Erro no HomeController: " . $e->getMessage());
            return view('home')->with('recentOngs', collect([]));
        }
    }

    // Métodos não utilizados no momento
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
