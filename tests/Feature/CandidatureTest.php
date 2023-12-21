<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CandidatureTest extends TestCase
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

    public function test_cant_see_liste_candidature()
    {
        $response = $this->get('api/candidatures');
        $response->assertStatus(200)->assertJson([
            'message' => 'Liste des candidatures',
            'candidature' => []
        ]);
    }

    public function test_can_see_liste_candidature()
    {
        $user = User::create([
            'nom' => 'Ba',
            'prenom' => 'seydou',
            'telephone' => '774785478',
            'role' => 'Admin',
            'email' => 'seydoubaahaba@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->get('/api/candidatures');

        $response->assertStatus(200)->assertJson([
            'message' => 'Liste des Candidatures',
            'Candidatures' => []
        ]);
    }

    public function test_enregistrer_candidature()
    {
        $user = User::create([
            'nom' => 'Bah',
            'prenom' => 'sey',
            'telephone' => '774785478',
            'role' => 'Candidat',
            'email' => 'sey@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->post('/api/candidater/1');
        $response->assertStatus(200)->assertJson([
            'message' => 'Candidature ajoutée',
            'candidature' => []
        ]);
    }

    public function test_accepter_candidature()
    {
        $user = User::create([
            'nom' => 'Bah',
            'prenom' => 'bin',
            'telephone' => '774785478',
            'role' => 'Admin',
            'email' => 'bint@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->put('/api/accepter/1');

        $response->assertStatus(200)->assertJson([
            'message' => 'Candidature acceptée'
        ]);
    }

    public function test_donnees_liste_candidature()
    {
        $user = User::create([
            'nom' => 'Ba',
            'prenom' => 'seydou',
            'telephone' => '774785478',
            'role' => 'Admin',
            'email' => 'seydoubaa@gmail.com',
            'password' => bcrypt('password'),
        ]);


        $this->actingAs($user, 'api');

        $response = $this->get('/api/candidatures');

        $response->assertStatus(200)->assertJson([
            'message' => 'Liste des Candidatures',
            'Candidatures' => []
        ]);
    }
}
