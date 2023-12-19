<?php

namespace App\Http\Controllers\API;

use App\Models\Formation;
use App\Models\Candidature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\MailAccepter;
use App\Mail\MailRefuser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use OpenApi\Annotations as OA;



class CandidatureController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/candidatures",
     * tags={"Candidature"},
     *     summary="liste de tout les candidatures par l'admin",
     *     @OA\Response(response="200", description="succes")
     * )
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

    /**
     * @OA\Get(
     *     path="/api/mesCandidatures",
     * tags={"Candidature"},
     *     summary="lists des candidatures d'un candidats données",
     *     @OA\Response(response="200", description="succes")
     * )
     */


    public function mesCandidatures()
    {
        $user = auth()->user();
        $candidatures = Candidature::where('user_id', $user->id)->get();

        return response()->json([
            'message' => "Mes candidatures",
            'Candidatures' => $candidatures
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/candidaturesFormation/{formation}",
     * tags={"Candidature"},
     *     summary="lists des candidatures d'une formation données avec les listes des candidature acceptées, 
     *     refusées et en attentes pour la formation",
     * @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID de a formation",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
     */
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
     * @OA\Post(
     *     path="/api/candidatures",
     * tags={"Candidature"},
     *     summary="ajouter d'une candidature",
     *     @OA\Response(response="201", description="enregistrer avec succes")
     * )
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
     * @OA\get(
     *     path="/api/candudatures/{candidature}",
     * tags={"Candidature"},
     *     summary="details d'une candidature",
     *  @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="ID de la candidature",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
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
     * @OA\put(
     *     path="/api/accepter/{candidature}",
     * tags={"Candidature"},
     *     summary="Accepter une candidature par l'admin",
     *  @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="ID de la candidature",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function accepter(Candidature $candidature)
    {
        $user = $candidature->user->email;
        $candidature->etat = 'Accepter';
        if ($candidature->save()) {
            Mail::to($user)->send(new MailAccepter);
            return response()->json([
                'message' => 'Candidature acceptée'
            ]);
        }
    }

    /**
     * @OA\put(
     *     path="/api/refuser/{candidature}",
     * tags={"Candidature"},
     *     summary="refuser une candidature par l'admin",
     *  @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="ID de la candidature",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function refuser(Candidature $candidature)
    {
        $user = $candidature->user->email;
        $candidature->etat = 'Refuser';
        if ($candidature->save()) {
            Mail::to($user)->send(new MailRefuser);
            return response()->json([
                'message' => 'Candidature refusée'
            ]);
        }
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
