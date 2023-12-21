<?php

namespace App\Http\Controllers\API;

// use auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OpenApi\Annotations as OA;


class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/candidats",
     * tags={"User"},
     *     summary="liste de toutes les candidats",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function index()
    {
        $candidats = User::where('role', 'Candidat')->get();
        return response()->json([
            'message' => 'La liste des candidats',
            'Candidats' => $candidats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function respondWithToken($token)
    {
        $user = auth()->user();

        if ($user->role == 'Admin') {
            return response()->json([
                'message' => 'BRAVO vous êtes connecté en tant que Admin',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => $user
            ]);
        } elseif ($user->role == 'Candidat') {
            return response()->json([
                'message' => 'BRAVO vous êtes connecté en tant que Candidat',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => $user
            ]);
        }
    }

    /**
     * @OA\post(
     *     path="/api/inscription",
     * tags={"User"},
     *     summary="inscription d'un user",
     *     @OA\Response(response="200", description="enregistrer succes")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string',
        ]);

        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->telephone = $request->telephone;
        // $user->role = $request->role;


        if ($user->save()) {
            return response()->json([
                "status" => "ok",
                "message" => "inscription effectuée",
                "data" => $user
            ]);
        }
    }


    /**
     * @OA\post(
     *     path="/api/login",
     * tags={"User"},
     *     summary="connexion d'un user",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Connexion echoué; Entrez des information correctes'
            ], 401);
        }
        return $this->respondWithToken($token);
    }



    /**
     * @OA\get(
     *     path="/api/info",
     * tags={"User"},
     *     summary="information de profil d'un user",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function show()
    {
        return response()->json([
            'message' => 'Vos information',
            'infos' => auth()->user()
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/logout",
     * tags={"User"},
     *     summary="Deconnexion d'un user",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Deconnexion efectuée',
        ]);
    }


    /**
     * @OA\get(
     *     path="/api/refresh",
     * tags={"User"},
     *     summary="Raffrechir le token d'un user",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @OA\put(
     *     path="/api/update",
     * tags={"User"},
     *     summary="Modifier le profil d'un user",
     *     @OA\Response(response="200", description="succes")
     * )
     */
    public function update(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id() . ',id|max:255',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string',

        ]);

        $user = auth()->user();

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->telephone = $request->telephone;

        if ($user->save()) {
            return response()->json([
                "message" => "Modification effectuée",
                "data" => $user
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */






    public function destroy(string $id)
    {
        //
    }
}
