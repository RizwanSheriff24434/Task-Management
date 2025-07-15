<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        return redirect()->back()->with('message', 'Task created successfully.');
    }
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }
    public function update(Request $request, Task $task)
    {
        // Optional: validate input
       $validated=  $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Pending,In Progress,Completed',
            'due_date' => 'nullable|date',
        ]);

        // Optional: check ownership
        $user = Auth::user();
        if ($task->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the task
        $task->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Task updated successfully');
    }


    public function destroy(Task $task)
    {
        $user = Auth::user();
        if ($task->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
