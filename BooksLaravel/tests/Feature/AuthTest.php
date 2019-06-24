<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AuthTest extends TestCase {

    /**
     * @test 
     * Test registration
     */
    public function testRegister() {
        //User's data
        $data = [
            'email' => 'testunit1@gmail.com',
            'name' => 'Test',
            'password' => 'secret1234'
        ];
        //Send post request
        $response = $this->json('POST', 'api/signup', $data);
        //Assert it was successful
        $response->assertStatus(201);
        //Delete data
        User::where('email', 'testunit1@gmail.com')->delete();
    }

    /**
     * @test 
     * Test registration
     */
    public function testRegisterExistingUser() {
        //User's data
        $data = [
            'email' => 'testunit1@gmail.com',
            'name' => 'Test',
            'password' => 'secret1234'
        ];
        //Create The User
        $response = $this->json('POST', 'api/signup', $data);
        $response->assertStatus(201);
        //Try Again with same user
        $response = $this->json('POST', 'api/signup', $data);
        $response->assertStatus(422);
        //
        //Delete data
        User::where('email', 'testunit1@gmail.com')->delete();
    }

    /**
     * @test
     * Test login
     */
    public function testLogin() {
        //Create user
        User::create([
            'name' => 'test',
            'email' => 'testunit2@gmail.com',
            'password' => bcrypt('secret1234')
        ]);
        //attempt login
        $response = $this->json('POST', 'api/login', [
            'email' => 'testunit2@gmail.com',
            'password' => 'secret1234',
        ]);
        //Assert it was successful and a token was received
        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());
        //Delete the user
        User::where('email', 'testunit2@gmail.com')->delete();
    }

    /**
     * @test
     * Test login Fail
     */
    public function testLoginFail() {

        //attempt login
        $response = $this->json('POST', 'api/login', [
            'email' => 'testfail2@gmail.com',
            'password' => 'secret1234',
        ]);
        //Assert it was successful and a token was received
        $response->assertStatus(422);
    }

}
