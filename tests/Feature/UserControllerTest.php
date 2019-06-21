<?php

namespace Tests\Feature;

use Tests\TestCase;
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

    public function testRegisterValidData()
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
    }

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


    
        /*
Route::get('details', 'API\UserController@viewCurrentUser');
    Route::get('logoutAPI', 'API\UserController@logoutAPI');
    Route::post('edit/{id}', 'API\UserController@edit');
    Route::post('uploadPhoto/{id}', 'API\UserController@uploadPhoto');
      
$response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/userer', ['name' => 'Sally']);
    */

}
