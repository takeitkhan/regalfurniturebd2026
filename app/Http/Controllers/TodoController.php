<?php

namespace App\Http\Controllers;

use App\Repositories\Todo\TodoInterface;

class TodoController extends Controller
{
    private $todo;


    /**
     * TodoController constructor.
     * @param TodoRepository $todo
     */
    public function __construct(TodoInterface $todo)
    {
        $this->todo = $todo;
    }

    public function getAllTodos()
    {
        $todos = $this->todo->getAll();

        dd($todos);
    }
}
