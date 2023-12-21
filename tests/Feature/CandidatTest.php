<?php

namespace Tests\Feature;

use Carbon\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Auth\User;
//use App\Models\User;
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
        // Simuler l'authentification (à ajuster selon votre logique d'authentification)
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        // Effectuer une requête HTTP GET vers l'endpoint API
        $response = $this->get('/api/candidats');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Liste des candidats',
                // Ajouter d'autres assertions si nécessaire
            ]);
    }
}
