<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testViewFreelancers()
    {
        // name, email, password, c_password, mobileNumber Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'admin@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('get', 'http://127.0.0.1:8000/api/viewFreelancers');
        $this->assertEquals(200, $response->status());
    }


    public function testViewStatistics()
    {
        // name, email, password, c_password, mobileNumber Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'admin@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('get', 'http://127.0.0.1:8000/api/viewStatistics');
        $this->assertEquals(200, $response->status());
    }


    public function testBlockFreelancer()
    {
        // name, email, password, c_password, mobileNumber Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'worker@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];
        $id = $response->original['success']['id'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('get', 'http://127.0.0.1:8000/api/blockFreelancer/'.$id);
        $this->assertEquals(200, $response->status());
    }

    public function testApproveFreelancer()
    {
        // name, email, password, c_password, mobileNumber Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'worker@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];
        $id = $response->original['success']['id'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('get', 'http://127.0.0.1:8000/api/approveFreelancer/'.$id);
        $this->assertEquals(200, $response->status());
    }
}
