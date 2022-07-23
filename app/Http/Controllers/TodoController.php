<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function todos()
    {
        return Inertia::render('Todo', [
            'todos' => Todo::oldest('status')->latest()->get()
        ]);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|string|max:500'
        ]);

        if ($validator->fails()) return response()->json($validator->errors()->first(), 400);

        Todo::create(request()->all());
        return response()->json("Todo added successfully");
    }

    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|integer',
            'title' => 'required|string|max:500'
        ]);

        if ($validator->fails()) return response()->json($validator->errors()->first(), 400);

        Todo::where('id', request('id'))->update([
            'title' => request('title')
        ]);
        return response()->json("Todo update successfully");
    }

    public function change_status()
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|integer',
            'status' => 'required|bool|max:1'
        ]);

        if ($validator->fails()) return response()->json($validator->errors()->first(), 400);

        Todo::where('id', request('id'))->update(request()->all());
        return response()->json("Todo status changed successfully");
    }

    public function delete($id)
    {
        $todo = Todo::find($id);
        if($todo) {
            $todo->delete();
            return response()->json("Todo deleted successfully");
        }
        else{
            return response()->json("Todo not found", 404);
        }
    }
}
