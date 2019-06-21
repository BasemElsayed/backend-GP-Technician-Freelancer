<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditUser()
    {
        // name, email, password, c_password, mobileNumber Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'ebasem653@gmail.com', 'password' => '123456']);
        $token = $response->original['success']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('get', 'http://127.0.0.1:8000/api/getFreelancersByGategory/Electricity');
        $this->assertEquals(200, $response->status());
    }
    
}
