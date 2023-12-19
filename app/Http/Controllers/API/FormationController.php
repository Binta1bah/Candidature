<?php

namespace App\Http\Controllers\API;

use App\Models\Formation;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;


class FormationController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/formations",
     * tags={"Formation"}, 
     *     summary="liste de toutes les formations",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function index()
    {
        // Gate::authorize('index', Formation::class);
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
     * @OA\Post(
     *     path="/api/formations",
     *      tags={"Formation"}, 
     *     summary="ajouter une formation par l'admin",
     *     @OA\Response(response="201", description="enregistrer avec succes")
     * )
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
     * @OA\get(
     *     path="/api/formations/{formation}",
     * tags={"Formation"}, 
     *     summary="details d'une formation",
     *  @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID de la formation",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function show(Formation $formation)
    {
        return response()->json([
            'message' => 'Details de la formation',
            'Details' => $formation
        ]);
    }



    /**
     * @OA\Get(
     *     path="/api/formationsCloturees",
     * tags={"Formation"}, 
     *     summary="liste de toutes les formations cloturées",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function formationCloturee()
    {
        $formations = Formation::where('cloturee', 1)->get();
        return response()->json([
            'message' => 'Liste des formation cloturées',
            'Formations' => $formations
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/formationsNonCloturees",
     * tags={"Formation"}, 
     *     summary="liste de toutes les formations non cloturées",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function formationNonCloturee()
    {
        $formations = Formation::where('cloturee', 0)->get();
        return response()->json([
            'message' => 'Liste des formation non cloturées',
            'Formations' => $formations
        ]);
    }

    /**
     * @OA\put(
     *     path="/api/cloturer/{formation}",
     * tags={"Formation"}, 
     *     summary="Cloturer une formation par l'admin",
     *  @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID de la formation",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
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
     * @OA\put(
     *     path="/api/formations/{formation}",
     * tags={"Formation"}, 
     *     summary="modifier une formation par l'admin",
     *  @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID de la formation",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
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
     * @OA\delete(
     *     path="/api/formations{formation}",
     * tags={"Formation"}, 
     *     summary="supprimer une formation par l'admin",
     *  @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID de la",
     *         @OA\Schema(type="integer")
     * ),
     *     @OA\Response(response="200", description="succes")
     * )
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
