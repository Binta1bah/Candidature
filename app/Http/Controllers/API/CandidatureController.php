<?php

namespace App\Http\Controllers\API;

use App\Models\Candidature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Formation;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidatures = Candidature::all();
        return response()->json([
            'message' => 'Liste des Candidatures',
            'Candidatures' => $candidatures
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
    public function store(Request $request, Formation $formation)
    {
        $user =  auth()->user();

        $candidature = new Candidature();

        $candidature->user_id = $user->id;
        // $candidature->formation_id = $request->formation_id;
        $candidature->formation_id = $formation->id;

        if ($candidature->save()) {
            return response()->json([
                'message' => 'Candidature ajoutée',
                'candidature' => $candidature
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidature $candidature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidature $candidature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidature $candidature)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidature $candidature)
    {
        $candidature->delete();
        return response()->json([
            'message' => 'Candidature supprimée avec succes'
        ]);
    }
}
