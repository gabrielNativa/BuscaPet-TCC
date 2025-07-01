<?php

namespace App\Http\Controllers;

use App\Models\Raca;
use App\Models\Especie;
use Illuminate\Http\Request;

class EspecieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $especies = Especie::all();
        return response()->json($especies);  // Opcional, caso precise devolver a lista de espécies
    }

    /**
     * Get the list of races based on the species.
     *
     * @param  int  $idEspecie
     * @return \Illuminate\Http\Response
     */
    public function getRacasByEspecie($idEspecie)
    {
        $racas = Raca::where('idEspecie', $idEspecie)->get();
        return response()->json($racas);
    }
}
