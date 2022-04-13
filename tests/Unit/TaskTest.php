<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
  /** @test */
  public function task_belongs_to_todo_list()
  {
      $list=$this->createTodoList();
      $task=$this->createTask(['todo_list_id'=>$list->id]);

      $this->assertInstanceOf(TodoList::class,$task->todo_list);

  }
  
}
