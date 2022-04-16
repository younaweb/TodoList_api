<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    private $user;
    public function setUp():void
    {
        parent::setUp();
        $this->user=User::factory()->make();
    }
  /** @test */
  public function user_can_register()
  { 
      $response=$this->postJson(route('user.register'),[
          'name'=>$this->user->name,
          'email'=>$this->user->email,
          'password'=>'12345678',
          'password_confirmation'=>'12345678',
      ])->assertCreated()
      ->json();
    //   dd($response);
      $this->assertDatabaseHas('users',['email'=>$this->user->email]);
  }
  /** @test */
  public function a_user_can_logged_in()
  { $user=User::factory()->create();
      $response=$this->postJson(route('user.login'),[
          'email'=>$user->email,
          'password'=>$user->password,
      ])->assertOk();
    //   dd($response->json());
      $this->assertArrayHasKey('token',$response->json());
    //   dd($response);
  }
  
}
