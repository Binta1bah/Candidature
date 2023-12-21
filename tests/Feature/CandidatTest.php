<?php

namespace Tests\Feature;

use Carbon\Factory;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CandidatTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_cant_see_listeCandidat()
    {
        $response = $this->get('api/candidats');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Liste des candidats'
        ]);
    }

    public function test_can_see_listeCandidat()
    {
        // Créer un utilisateur 
        $user = User::create([
            'nom' => 'Bah',
            'prenom' => 'oumou',
            'telephone' => '123456789',
            'role' => 'Admin',
            'email' => 'oumou@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // Simuler l'authentification 
        $this->actingAs($user, 'api');

        // Effectuer une requête 
        $response = $this->get('/api/candidats');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'La liste des candidats', 
                'Candidats' => [],
            ]);
    }
}
