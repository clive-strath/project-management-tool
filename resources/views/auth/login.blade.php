<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Real-Time PM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Floating Background Shapes -->
    <div class="floating-shape w-20 h-20 top-10 left-10" style="animation-delay: 0s;"></div>
    <div class="floating-shape w-32 h-32 top-20 right-20" style="animation-delay: 2s;"></div>
    <div class="floating-shape w-16 h-16 bottom-20 left-20" style="animation-delay: 4s;"></div>
    <div class="floating-shape w-24 h-24 bottom-10 right-10" style="animation-delay: 1s;"></div>
    
    <!-- Main Container -->
    <div class="w-full max-w-6xl flex items-center justify-center gap-12 relative z-10">
        <!-- Left Side - Brand & Info -->
        <div class="hidden lg:flex flex-1 flex-col justify-center text-white">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-14 w-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        PM
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">Real-Time PM</h1>
                        <p class="text-lg text-white/80">Collaborative Project Management</p>
                    </div>
                </div>
                <p class="text-xl text-white/90 leading-relaxed mb-8">
                    Streamline your projects with real-time collaboration, intelligent task management, and powerful analytics.
                </p>
            </div>
            
            <!-- Features -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">Real-time Updates</h3>
                        <p class="text-sm text-white/70">Instant collaboration with your team</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">Advanced Analytics</h3>
                        <p class="text-sm text-white/70">Track progress and performance</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white">Team Collaboration</h3>
                        <p class="text-sm text-white/70">Work together seamlessly</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="w-full max-w-md">
            <div class="glass-effect rounded-3xl shadow-2xl p-8">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                        PM
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Real-Time PM</h2>
                        <p class="text-sm text-gray-600">Project Management</p>
                    </div>
                </div>
                
                <!-- Welcome Text -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to your account to continue</p>
                </div>
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input 
                                id="email"
                                name="email" 
                                type="email" 
                                required
                                value="{{ old('email') }}"
                                class="input-focus w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Enter your email"
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input 
                                id="password"
                                name="password" 
                                type="password" 
                                required
                                class="input-focus w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Enter your password"
                            >
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" name="remember">
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Sign In
                    </button>
                    
                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-600">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif
                </form>
                
                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">New to our platform?</span>
                    </div>
                </div>
                
                <!-- Sign Up Link -->
                <div class="text-center">
                    <a 
                        href="{{ route('register') }}"
                        class="inline-flex items-center justify-center w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-3 px-4 rounded-xl border border-gray-200 transition-colors"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Create Account
                    </a>
                </div>
                
                <!-- Test Accounts -->
                <div class="mt-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Test Accounts
                    </h4>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between items-center py-1">
                            <span class="font-mono text-gray-600">john@example.com</span>
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-mono">password</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="font-mono text-gray-600">jane@example.com</span>
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-mono">password</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="font-mono text-gray-600">admin@example.com</span>
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-mono">password</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
