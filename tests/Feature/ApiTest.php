<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSignUp()
    {
        $response = $this->postJson('/api/signup', [
            'first_name' => '' ,
            'surname' => '',
            'phone' => '01234567891',
            'password' => 'qwe',
        ])->dump() ;
        $response->assertStatus(422);

        $response1 = $this->postJson('/api/login', [
            'phone' => '01234567812' ,
            'password' => 'asdf',
        ]);

//        echo $response1->content();
        echo $response1->baseResponse;

    }


}
