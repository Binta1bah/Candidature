<?php

namespace App\Http\Controllers\API;

use App\Models\Formation;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('store', Formation::class);
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

    public function formationCloturee()
    {
        $formations = Formation::where('cloturee', 1)->get();
        return response()->json([
            'message' => 'Liste des formation cloturées',
            'Formations' => $formations
        ]);
    }


    public function formationNonCloturee()
    {
        $formations = Formation::where('cloturee', 0)->get();
        return response()->json([
            'message' => 'Liste des formation non cloturées',
            'Formations' => $formations
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        $formation->cloturee = 1;
        $formation->save();
        return response()->json([
            'message' => 'La formation est cloturée'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        Gate::authorize('update', Formation::class);
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
        Gate::authorize('delete', Formation::class);
        $formation->delete();
        return response()->json([
            'message' => 'Formation supprimer avec succes'
        ]);
    }
}
