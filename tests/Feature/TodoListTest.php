<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;
    private $list;
    public function setUp():void
    {
        parent::setUp();
        $this->list=$this->createTodoList();
    }
   /** @test */
   public function fetch_all_todo_lists()
   {    
        
       $response=$this->get(route('todo-list.index'))
       ->assertOk()
       ->json();
      $this->assertEquals(1, count($response));
       $this->assertEquals($this->list->name,$response[0]['name']);
   }

   /** @test */
   public function add_new_todo_list()
   {
        $todo=TodoList::factory()->make();
       $response=$this->postJson(route('todo-list.store'),['name'=>$todo->name])
       ->assertCreated()
       ->json();
      $this->assertEquals($todo->name,$response['name']);
      $this->assertDatabaseHas('todo_lists',['name'=>$todo->name]);

   }

   /** @test */
   public function name_todo_list_is_required()
   {
       $this->withExceptionHandling();
    $response=$this->postJson(route('todo-list.store'))
    ->assertUnprocessable()
    ->assertJsonValidationErrors(['name']);
   }
   

   /** @test */
   public function fetch_single_todo_list()
   {
       $response=$this->getJson(route('todo-list.show',$this->list->id))
       ->assertOk()
       ->json();
       $this->assertEquals($this->list->name,$response['name']);

   }

   /** @test */
   public function update_todo_list()
   {
    $response=$this->putJson(route('todo-list.update',$this->list->id),['name'=>'updated'])
    ->assertOk()
    ->json();
    $this->assertDatabaseHas('todo_lists',['id'=>$this->list->id,'name'=>'updated']);
   }
   
   /** @test */
   public function delete_todo_list()
   {
       $response=$this->deleteJson(route('todo-list.destroy',$this->list->id))
       ->assertNoContent();
       $this->assertDatabaseMissing('todo_lists',['name'=>$this->list->name]);
   }
   
   
   
}
