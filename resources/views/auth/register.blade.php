<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo/Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                PM
            </div>
            <h2 class="mt-6 text-3xl font-bold text-white">Real-Time PM</h2>
            <p class="mt-2 text-sm text-slate-400">Collaborative Project Management</p>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
            @csrf
            <div class="rounded-xl border border-slate-800 bg-slate-900/50 backdrop-blur p-6 space-y-4">
                <h3 class="text-xl font-semibold text-white">Create your account</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Full name</label>
                    <input 
                        id="name"
                        name="name" 
                        type="text" 
                        required
                        value="{{ old('name') }}"
                        class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        placeholder="John Doe"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email address</label>
                    <input 
                        id="email"
                        name="email" 
                        type="email" 
                        required
                        value="{{ old('email') }}"
                        class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <input 
                        id="password"
                        name="password" 
                        type="password" 
                        required
                        minlength="8"
                        class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Confirm Password</label>
                    <input 
                        id="password_confirmation"
                        name="password_confirmation" 
                        type="password" 
                        required
                        class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        placeholder="••••••••"
                    >
                </div>
                
                <button 
                    type="submit" 
                    class="w-full rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900"
                >
                    Create account
                </button>
                
                @if ($errors->any())
                    <div class="rounded-lg bg-red-500/10 border border-red-500/20 p-3 text-sm text-red-400">
                        {{ $errors->first() }}
                    </div>
                @endif
            </div>
            
            <div class="text-center">
                <a 
                    href="{{ route('login') }}"
                    class="text-sm text-slate-400 hover:text-white"
                >
                    Already have an account? <span class="font-medium text-indigo-400 hover:text-indigo-300">Sign in</span>
                </a>
            </div>
        </form>

        <!-- Test Accounts -->
        <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-4">
            <h4 class="text-sm font-medium text-slate-300 mb-3">Test Accounts</h4>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400">john@example.com</span>
                    <span class="text-slate-500">password</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">jane@example.com</span>
                    <span class="text-slate-500">password</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">admin@example.com</span>
                    <span class="text-slate-500">password</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">test@example.com</span>
                    <span class="text-slate-500">password</span>
                </div>
            </div>
        </div>
    </div>
</div>
