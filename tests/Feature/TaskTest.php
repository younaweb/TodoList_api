<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    private $task;
    public function setUp():void
    {
        parent::setUp();
        $this->task=$this->createTask();
    }
   /** @test */
   public function fetch_all_tasks()
   {    
        
       $response=$this->get(route('task.index'))
       ->assertOk()
       ->json();
      $this->assertEquals(1, count($response));
       $this->assertEquals($this->task->title,$response[0]['title']);
   }

   /** @test */
   public function add_new_task()
   {
    $task=Task::factory()->make();
       $response=$this->postJson(route('task.store'),['title'=>$task->title])
       ->assertCreated()
       ->json();
      $this->assertEquals($task->title,$response['title']);
      $this->assertDatabaseHas('tasks',['title'=>$task->title]);

   }

   /** @test */
   public function title_task_is_required()
   {
       $this->withExceptionHandling();
    $response=$this->postJson(route('task.store'))
    ->assertUnprocessable()
    ->assertJsonValidationErrors(['title']);
   }
   

   /** @test */
   public function fetch_single_task()
   {
       $response=$this->getJson(route('task.show',$this->task->id))
       ->assertOk()
       ->json();
       $this->assertEquals($this->task->title,$response['title']);

   }

   /** @test */
   public function update_task()
   {
    $response=$this->putJson(route('task.update',$this->task->id),['title'=>'updated'])
    ->assertOk()
    ->json();
    $this->assertDatabaseHas('tasks',['id'=>$this->task->id,'title'=>'updated']);
   }
   
   /** @test */
   public function delete_task()
   {
       $response=$this->deleteJson(route('task.destroy',$this->task->id))
       ->assertNoContent();
       $this->assertDatabaseMissing('tasks',['title'=>$this->task->title]);
   }
   
}
