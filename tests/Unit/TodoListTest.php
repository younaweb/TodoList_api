<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;
  /** @test */
  public function todolist_has_many_tasks()
  {
      $list=$this->createTodoList();
      $task=$this->createTask(['todo_list_id'=>$list->id]);

      $this->assertInstanceOf(Collection::class,$list->tasks);
      $this->assertInstanceOf(Task::class,$list->tasks->first());

  }
  
}
