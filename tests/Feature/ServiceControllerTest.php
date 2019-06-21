<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ServiceControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /*public function testAddServiceValidData()
    {
        // name, description, serviceIcon Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'admin@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('post', 'http://127.0.0.1:8000/api/addService',[
              'name' => 'testAPI1',
              'description' => 'bla bla bla',
              'serviceIcon' => $file
          ]);
        $this->assertEquals(200, $response->status());
    }*/


    public function testAddServiceUnValidData()
    {
        // name, description, serviceIcon Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'admin@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('post', 'http://127.0.0.1:8000/api/addService',[
              'name' => 'testAPI',
              'serviceIcon' => $file
          ]);
        $this->assertEquals(401, $response->status());
    }


    public function testEditServiceValidData()
    {
        // name, description, serviceIcon Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'admin@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('post', 'http://127.0.0.1:8000/api/editService/5',[
              'name' => 'testAPI2',
              'description' => 'bbbbbbbbb'
        ]);
        $this->assertEquals(200, $response->status());
    }


    public function testEditServiceUnValidData()
    {
        // name, description, serviceIcon Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'admin@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('post', 'http://127.0.0.1:8000/api/editService/5',[
              'name' => 'testAPI2',
        ]);
        $this->assertEquals(401, $response->status());
    }

    public function testUploadServiceIconValidData()
    {
        // name, description, serviceIcon Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'admin@gmail.com', 'password' => '123']);
        $token = $response->original['success']['token'];
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('post', 'http://127.0.0.1:8000/api/updateServiceIcon/5',[
              'serviceIcon' => $file
          ]);
        $this->assertEquals(200, $response->status());
    }
}
