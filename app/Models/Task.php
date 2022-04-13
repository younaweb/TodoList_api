<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable=['title','todo_list_id'];

    public function todo_list()
    {
        return $this->belongsTo(TodoList::class);
    }
}
