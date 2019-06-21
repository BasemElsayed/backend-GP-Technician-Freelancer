<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginValidData()
    {
        $response = $this->post('http://127.0.0.1:8000/api/login', ['email' => 'ebasem653@gmail.com', 'password' => '123456']);
        $this->assertEquals(200, $response->status());
    }


    public function testLoginUnValidData()
    {
        $response = $this->post('http://127.0.0.1:8000/api/login', ['email' => 'ebasem653@gmail.com', 'password' => '123']); 
        $this->assertEquals(401, $response->status());
    }

    public function testLoginSQLInjection()
    {
        $response = $this->post('http://127.0.0.1:8000/api/login', ['email' => ' or ""=', 'password' => ' or ""=']); 
        $this->assertEquals(401, $response->status());
    }

   /*public function testRegisterValidData()
    {
        $response = $this->post('http://127.0.0.1:8000/api/register', [
        'name' => 'besem',
        'email' => 'besem@gmail.com', 
        'password' => '123',
        'c_password' => '123',
        'mobileNumber' => '123123123123123',
        'address' => 'Cairo',
        'typeOfUsers' => '1'
        ]); 
        $this->assertEquals(200, $response->status());
    }*/

    public function testRegisterInjection()
    {
        $response = $this->post('http://127.0.0.1:8000/api/register', [
        'name' => ' or ""=',
        'email' => ' or ""=@gmail.com', 
        'password' => ' or ""=',
        'c_password' => ' or ""=',
        'mobileNumber' => ' or ""=',
        'address' => ' or ""=',
        'typeOfUsers' => '1'
        ]); 
        $this->assertEquals(400, $response->status());
    }
    
    public function testRegisterInValidData()
    {
        $response = $this->post('http://127.0.0.1:8000/api/register', [
        'name' => 'beshbeshy',
        'email' => 'beshbeshy@gmail.com', 
        'password' => '123',
        'c_password' => '12345634',
        'mobileNumber' => '1123123',
        'address' => 'Cairo',
        'typeOfUsers' => '1'
        ]); 
        $this->assertEquals(400, $response->status());
    }


    public function testDetailAPIViewCurrentUser()
    {
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'ebasem653@gmail.com', 'password' => '123456']);
        $token = $response->original['success']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('get', 'http://127.0.0.1:8000/api/details');
        $this->assertEquals(200, $response->status());
    }


    
    public function testLogout()
    {
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'ebasem653@gmail.com', 'password' => '123456']);
        $token = $response->original['success']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('get', 'http://127.0.0.1:8000/api/logoutAPI');
        $this->assertEquals(200, $response->status());
    }

    public function testEditUserLessData()
    {
        // name, email, password, c_password, mobileNumber Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'ebasem653@gmail.com', 'password' => '123456']);
        $token = $response->original['success']['token'];
        $id = $response->original['success']['id'];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('post', 'http://127.0.0.1:8000/api/edit/'.$id, [
              'name' => 'testAPI',
              'typeOfUsers' => 1,
        ]);
        $this->assertEquals(400, $response->status());
    }


    public function testEditUser()
    {
        // name, email, password, c_password, mobileNumber Required
        $response = $this->json('post', 'http://127.0.0.1:8000/api/login', ['email' => 'ebasem653@gmail.com', 'password' => '123456']);
        $token = $response->original['success']['token'];
        $id = $response->original['success']['id'];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
          ])->json('post', 'http://127.0.0.1:8000/api/edit/'.$id, [
              'name' => 'testAPI',
              'typeOfUsers' => 1,
              'email' => 'ebasem653@gmail.com',
              'password' => '123456',
              'c_password' => '123456',
              'mobileNumber' => '123123123123'
        ]);
        $this->assertEquals(200, $response->status());
    }




}
