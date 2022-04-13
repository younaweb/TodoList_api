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
    private $list;
    public function setUp():void
    {
        parent::setUp();
        $this->list=$this->createTodoList();
        $this->task=$this->createTask();
    }
   /** @test */
   public function fetch_all_tasks()
   {    
        
        $task=$this->createTask(['todo_list_id'=>$this->list->id]);
       $response=$this->get(route('todo-list.task.index',$this->list->id))
       ->assertOk()
       ->json();

    //    dd($response);
      $this->assertEquals(1, count($response));
       $this->assertEquals($task->title,$response[0]['title']);
       $this->assertEquals($this->list->id, $task->todo_list_id);
   }

   /** @test */
   public function add_new_task()
   {
    $task=Task::factory()->make(['todo_list_id'=>$this->list->id]);
       $response=$this->postJson(route('todo-list.task.store',$this->list->id),[
           'title'=>$task->title])
       ->assertCreated()
       ->json();
      $this->assertEquals($task->title,$response['title']);
      $this->assertDatabaseHas('tasks',[
          'title'=>$response['title'],
          'todo_list_id'=>$response['todo_list_id'],
        ]);

   }

   /** @test */
   public function title_and_todolistid_are_required()
   {
       $this->withExceptionHandling();
    $response=$this->postJson(route('todo-list.task.store',$this->list->id))
    ->assertUnprocessable()
    ->assertJsonValidationErrors(['title']);
   }
   

   /** @test */
//    public function fetch_single_task()
//    {
//        $response=$this->getJson(route('task.show',$this->task->id))
//        ->assertOk()
//        ->json();
//        $this->assertEquals($this->task->title,$response['title']);

//    }

   /** @test */
   public function update_task()
   {
    $task=$this->createTask(['todo_list_id'=>$this->list->id]);

    $response=$this->patchJson(route('task.update',$task->id),['title'=>'updated'])
    ->assertOk()
    ->json();
    $this->assertDatabaseHas('tasks',['id'=>$task->id,'title'=>'updated']);
   }
   
   /** @test */
   public function delete_task()
   {
       $response=$this->deleteJson(route('task.destroy',$this->task->id))
       ->assertNoContent();
       $this->assertDatabaseMissing('tasks',['title'=>$this->task->title]);
   }
   
}
