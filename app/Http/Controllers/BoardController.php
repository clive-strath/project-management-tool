<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            $response = Http::withToken($token)
                ->get(config('app.url') . '/api/v1/tasks');
            
            $tasks = $response->successful() ? collect($response->json('data', [])) : collect();
            
            return view('board.index', ['tasks' => $tasks]);
        } catch (\Exception $e) {
            return view('board.index', ['tasks' => collect()]);
        }
    }

    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high'
        ]);

        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            $response = Http::withToken($token)
                ->post(config('app.url') . '/api/v1/tasks', [
                    'title' => $request->title,
                    'description' => $request->description ?? 'New task description',
                    'status' => 'backlog',
                    'priority' => $request->priority
                ]);
            
            if ($response->successful()) {
                return response()->json(['success' => true, 'task' => $response->json()]);
            }
            
            return response()->json(['success' => false, 'message' => 'Failed to create task'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create task'], 500);
        }
    }

    public function updateTaskStatus(Request $request, $taskId)
    {
        $request->validate([
            'status' => 'required|in:backlog,in_progress,completed'
        ]);

        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            $response = Http::withToken($token)
                ->put(config('app.url') . "/api/v1/tasks/{$taskId}", [
                    'status' => $request->status
                ]);
            
            if ($response->successful()) {
                return response()->json(['success' => true, 'task' => $response->json()]);
            }
            
            return response()->json(['success' => false, 'message' => 'Failed to update task'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update task'], 500);
        }
    }

    public function getTaskDetails($taskId)
    {
        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            $taskResponse = Http::withToken($token)
                ->get(config('app.url') . "/api/v1/tasks/{$taskId}");
            
            $commentsResponse = Http::withToken($token)
                ->get(config('app.url') . "/api/v1/tasks/{$taskId}/comments");
            
            $attachmentsResponse = Http::withToken($token)
                ->get(config('app.url') . "/api/v1/tasks/{$taskId}/attachments");

            $task = $taskResponse->successful() ? $taskResponse->json() : null;
            $comments = $commentsResponse->successful() ? $commentsResponse->json('data', []) : [];
            $attachments = $attachmentsResponse->successful() ? $attachmentsResponse->json('data', []) : [];
            
            if (!$task) {
                return response()->json(['success' => false, 'message' => 'Task not found'], 404);
            }
            
            return response()->json([
                'success' => true,
                'task' => $task,
                'comments' => $comments,
                'attachments' => $attachments
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load task details'], 500);
        }
    }
}
