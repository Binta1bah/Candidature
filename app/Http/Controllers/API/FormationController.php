<?php

namespace App\Http\Controllers\API;

use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\Create;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formations = Formation::all();
        return response()->json([
            'message' => 'Liste des formations',
            'Formations' => $formations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'libelle' => 'required|string',
            'duree' => 'required|string',
            'description' => 'required|string',

        ]);

        $formation = Formation::create($validation);

        if ($formation) {
            return response()->json([
                'message' => 'Formation ajouter avec succes',
                'Formation' => $formation
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
        return response()->json([
            'message' => 'Details de la formation',
            'Details' => $formation
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        $validation = $request->validate([
            'libelle' => 'required|string',
            'duree' => 'required|string',
            'description' => 'required|string',

        ]);
        // dd($formation);

        $formation->update($validation);
        if ($formation) {
            return response()->json([
                'message' => 'Formation modifiée avec succès',
                'Formation' => $formation
            ]);
        } else {
            return response()->json([
                'message' => 'Modification non effectuée'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation)
    {
        $formation->delete();
        return response()->json([
            'message' => 'Formation supprimer avec succes'
        ]);
    }
}
