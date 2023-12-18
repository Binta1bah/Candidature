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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string|max:255',
            // 'role' => 'required|in:Candidat,Admin',
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


    // public function logout(): Response
    // {
    //     $user = Auth::user();

    //     $user->currentAccessToken()->delete();

    //     return Response([
    //         'message' => 'Deconnexion effectuée'
    //     ], 200);
    // }



    /**
     * Display the specified resource.
     */
    public function show()
    {
        return response()->json([
            'message' => 'Vos information',
            'infos' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Deconnexion efectuée',
        ]);
    }

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
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id() . ',id|max:255',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string|max:255',

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
