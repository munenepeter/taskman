<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller {
    /**
     *  Get api/v1/tasks - filtered task list
     */
    public function index(Request $request) {
        try {
            $query = Task::query();
            //handle search, paginaton & sorting here
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->has('due_date')) {
                $query->where('due_date', $request->input('due_date'));
            }

            if ($request->has('title')) {
                $query->where('title', 'like', '%' . $request->input('title') . '%');
            }
            if ($request->has('items')) {
                $tasks = $query->simplePaginate((int)$request->input('items'));
            } else {
                $tasks = $query->simplePaginate(10);
            }
            return $this->success($tasks);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "Something happened");
        }
    }
    /**
     * Get api/v1/tasks/{id} - get a task by id
     * 
     * we are using a string or a int here cause lumen does not natively handle route model binding
     */
    public function show(string|int $task) {
        try {
            return $this->success(Task::findOrFail($task));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "Could not search the provided task");
        }
    }
    //create a new task
    public function store(Request $request) {
        try {
            $validated = $this->validate($request, [
                'title' => 'required|unique:tasks,title',
                'description' => 'required|min:20|max:255',
                'status' => ['required', Rule::in(['pending', 'completed', 'archived'])],
                'due_date' => 'required|after:today',
            ]);

            $task = Task::create($validated);

            return $this->success($task, 201);
        } catch (ValidationException $e) {
            return $this->error("Validation Errors", $e->errors());
        } catch (\Exception $e) {
            return $this->error("Something went wrong", $e->getMessage());
        }
    }
    // * we are using a string or a int here cause lumen does not natively handle route model binding
    public function update(Request $request, string|int $task_id) {
        try {
            $validated = $this->validate($request, [
                'title' => 'unique:tasks,title',
                'description' => 'min:20|max:255',
                'status' => [Rule::in(['pending', 'completed', 'archived'])],
                'due_date' => 'after:today',
            ]);

            $task = Task::findOrFail($task_id);

            if (!$task->update($validated)) {
                return $this->error("Could not update!", 500);
            }
            return $this->success($task, 200);
        } catch (ValidationException $e) {
            return $this->error("Validation Errors", $e->errors());
        } catch (\Exception $e) {
            return $this->error("Something went wrong", $e->getMessage());
        }
    }

    //  * we are using a string or a int here cause lumen does not natively handle route model binding
    // and didn't want to install additional libs
    public function delete(string|int $task_id) {

        $task = Task::findOrFail($task_id);

        if (!$task->delete()) {
            return $this->error("Could not DELETE!", 500);
        }
        return $this->success(null, 204);
    }
    //helper for a fail response
    protected function error(string $message, $errors = null, int $code = 422) {
        if ($message == null && is_string($errors)) {
            $message = $errors;
        }
        return response()->json([
            'errors' => $errors,
            'data' => null,
            'message' => $message,
            'status' => 'error'
        ], $code);
    }
    //helper for a success response
    protected function success(mixed $data = null, int $code = 200) {
        return response()->json([
            'errors' => null,
            'data' => $data,
            'status' => 'success'
        ], $code);
    }
}
