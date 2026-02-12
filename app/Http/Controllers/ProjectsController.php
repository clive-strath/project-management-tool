<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            $response = Http::withToken($token)
                ->get(config('app.url') . '/api/v1/projects');
            
            $projects = $response->successful() ? $response->json('data', []) : [];
            
            return view('projects.index', ['projects' => $projects]);
        } catch (\Exception $e) {
            return view('projects.index', ['projects' => []]);
        }
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            $response = Http::withToken($token)
                ->post(config('app.url') . '/api/v1/projects', [
                    'name' => $request->name,
                    'description' => $request->description
                ]);
            
            if ($response->successful()) {
                return redirect()->route('projects.index')
                    ->with('success', 'Project created successfully!');
            }
            
            return redirect()->back()
                ->with('error', 'Failed to create project')
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create project')
                ->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $token = $user->createToken('app-token')->plainTextToken;
        
        try {
            $response = Http::withToken($token)
                ->get(config('app.url') . "/api/v1/projects/{$id}");
            
            $project = $response->successful() ? $response->json() : null;
            
            if (!$project) {
                return redirect()->route('projects.index')
                    ->with('error', 'Project not found');
            }
            
            return view('projects.show', ['project' => $project]);
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Failed to load project');
        }
    }
}
