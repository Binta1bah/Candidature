<?php

namespace App\Http\Controllers\API;

use App\Models\Formation;
use App\Models\Candidature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('store', Candidature::class);

        $candidatures = Candidature::all();
        return response()->json([
            'message' => 'Liste des Candidatures',
            'Candidatures' => $candidatures
        ]);
    }

    public function mesCandidatures()
    {
        $user = auth()->user();
        $candidatures = Candidature::where('user_id', $user->id)->get();

        return response()->json([
            'message' => "Mes candidatures",
            'Candidatures' => $candidatures
        ]);
    }


    public function CandidaturesFormation(Formation $formation)
    {
        $candidatureAccept = [];
        $candidatureRefus = [];
        $candidatureAttente = [];

        $candidatures = Candidature::where('formation_id', $formation->id)->get();
        foreach ($candidatures as $candidature) {
            if ($candidature->etat === 'Accepter') {
                $candidatureAccept[] = $candidature;
            }
            if ($candidature->etat === 'Refuser') {
                $candidatureRefus[] = $candidature;
            }
            if ($candidature->etat === 'Attente') {
                $candidatureAttente[] = $candidature;
            }
        }
        return response()->json([
            'message' => 'Les candidature de la formation ' . $formation->libelle,
            'Candidatures' => $candidatures,
            'Candidatures acceptées' => $candidatureAccept,
            'Candidatures refusées' => $candidatureRefus,
            'Candidatures en attente' => $candidatureAttente


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

        if ($formation->cloturee == 1) {
            return response()->json([
                'message' => 'Cette formation est cloturée'
            ]);
        } else {
            $candidature = new Candidature();
            $candidature->user_id = $user->id;
            $candidature->formation_id = $formation->id;
            if ($candidature->save()) {
                return response()->json([
                    'message' => 'Candidature ajoutée',
                    'candidature' => $candidature
                ]);
            }
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Candidature $candidature)
    {
        $userNom = $candidature->user->nom;
        $userPrenom = $candidature->user->prenom;
        $formation = $candidature->formation->libelle;
        return response()->json([
            'message' => 'Les details de la candidature',
            'Candidat' => $userNom . ' ' . $userPrenom,
            'Formation' => $formation,
            'Date' => $candidature->created_at,
            'Etat' => $candidature->etat
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function accepter(Candidature $candidature)
    {
        $candidature->etat = 'Accepter';
        $candidature->save();
        return response()->json([
            'message' => 'Candidature acceptée'
        ]);
    }


    public function refuser(Candidature $candidature)
    {
        $candidature->etat = 'Refuser';
        $candidature->save();
        return response()->json([
            'message' => 'Candidature refusée'
        ]);
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
    }
}
