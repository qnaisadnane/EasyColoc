<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Colocation;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly,monthly',
            'points' => 'required|integer|min:1',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $colocation->tasks()->create($request->all());

        return redirect()->back()->with('success', 'Tâche ajoutée au planning !');
    }

    public function complete(Task $task)
    {
        $task->update(['last_done_at' => now()]);
        return redirect()->back()->with('success', 'Tâche marquée comme terminée !');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tâche supprimée.');
    }
}
