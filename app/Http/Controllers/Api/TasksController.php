<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TaskRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TasksController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id);

        if ($request->keyword) {
            $tasks->where(function ($query) use ($request) {
                $query->where('task_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        return $tasks->paginate(3);
    }

    public function all(Request $request)
    {
        return Task::where('user_id', $request->user()->id)->get();
    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->storePublicly('tasks', 'public');
        }

        $validated['user_id'] = $request->user()->id;

        $task = Task::create($validated);
        return $task;
    }

    public function show($id)
    {
        try {
            return Task::where('user_id', auth()->id())->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    public function update(TaskRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $task = Task::where('user_id', auth()->id())->findOrFail($id);

            if ($request->hasFile('image_path')) {
                $validated['image_path'] = $request->file('image_path')->storePublicly('tasks', 'public');

                if (!is_null($task->image_path)) {
                    Storage::disk('public')->delete($task->image_path);
                }
            }

            $task->update($validated);
            return $task;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    public function destroy($id)
{
    try {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);

        if (!is_null($task->image_path)) {
            Storage::disk('public')->delete($task->image_path);
        }

        $task->delete();
        
        // Return a custom response with a success message
        return response()->json(['message' => 'Task Deleted'], 200);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Task not found'], 404);
    }
}

}
