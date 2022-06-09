<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * ProductTest
 */
class ProductTest extends TestCase
{

    /**
     * token
     *
     * @var string
     */
    public $token = '';

    /**
     * uuid
     *
     * @var string
     */
    public $uuid = '';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function authentication()
    {
        //$user = \App\Models\User::factory()->create();
        $data = ['email' => 'prueba@gmail.com', 'password' => 'password'];
        $response = $this->post('/api/login', $data);
        $data_response = json_decode($response->baseResponse->content());
        $this->token = $data_response->token;
        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'success', "message" => "Bienvenido al sistema"]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function get_products()
    {
        $header = ['Authorization' => $this->token];
        $response = $this->get('/products', $header);
        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'success', "message" => "success"]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function create_product()
    {
        $header = ['Authorization' => $this->token];
        $response = $this->post('/products', ['name' => 'name_prueba', 'description' => ''], $header);
        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'success', "message" => "Guardado correctamente"]);
        $data_response = json_decode($response->baseResponse->content());
        $this->uuid = $data_response->data;
    }

    /**
     * update_product
     *
     * @return void
     */
    public function update_product()
    {
        $header = ['Authorization' => $this->token];
        $response = $this->put('/' . 'products/' . $this->uuid, ['name' => 'name_prueba_editar', 'description' => ''], $header);
        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'success', "message" => "Actualizado correctamente."]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function logout()
    {
        $header = ['Authorization' => $this->token];
        $response = $this->post('/api/logout', [], $header);
        $data_response = json_decode($response->baseResponse->content());
        $this->token = $data_response->token;
        $response->assertStatus(200);
    }
}
