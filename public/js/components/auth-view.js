// Auth View Component
const { createApp, ref, reactive, onMounted } = Vue;

const AuthView = {
    template: `
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

                <!-- Login Form -->
                <form v-if="!showRegister" @submit.prevent="login" class="mt-8 space-y-6">
                    <div class="rounded-xl border border-slate-800 bg-slate-900/50 backdrop-blur p-6 space-y-4">
                        <h3 class="text-xl font-semibold text-white">Sign in to your account</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Email address</label>
                            <input 
                                v-model="loginForm.email" 
                                type="email" 
                                required
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                placeholder="you@example.com"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                            <input 
                                v-model="loginForm.password" 
                                type="password" 
                                required
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                placeholder="••••••••"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="w-full rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="loading">Signing in...</span>
                            <span v-else>Sign in</span>
                        </button>
                        
                        <div v-if="error" class="rounded-lg bg-red-500/10 border border-red-500/20 p-3 text-sm text-red-400">
                            {{ error }}
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button 
                            type="button" 
                            @click="showRegister = true"
                            class="text-sm text-slate-400 hover:text-white"
                        >
                            Don't have an account? <span class="font-medium text-indigo-400 hover:text-indigo-300">Sign up</span>
                        </button>
                    </div>
                </form>

                <!-- Register Form -->
                <form v-else @submit.prevent="register" class="mt-8 space-y-6">
                    <div class="rounded-xl border border-slate-800 bg-slate-900/50 backdrop-blur p-6 space-y-4">
                        <h3 class="text-xl font-semibold text-white">Create your account</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Full name</label>
                            <input 
                                v-model="registerForm.name" 
                                type="text" 
                                required
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                placeholder="John Doe"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Email address</label>
                            <input 
                                v-model="registerForm.email" 
                                type="email" 
                                required
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                placeholder="you@example.com"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                            <input 
                                v-model="registerForm.password" 
                                type="password" 
                                required
                                minlength="8"
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                placeholder="••••••••"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="w-full rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="loading">Creating account...</span>
                            <span v-else>Create account</span>
                        </button>
                        
                        <div v-if="error" class="rounded-lg bg-red-500/10 border border-red-500/20 p-3 text-sm text-red-400">
                            {{ error }}
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button 
                            type="button" 
                            @click="showRegister = false"
                            class="text-sm text-slate-400 hover:text-white"
                        >
                            Already have an account? <span class="font-medium text-indigo-400 hover:text-indigo-300">Sign in</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `,
    setup(props, { emit }) {
        const showRegister = ref(false);
        const loading = ref(false);
        const error = ref('');

        const loginForm = reactive({
            email: '',
            password: ''
        });

        const registerForm = reactive({
            name: '',
            email: '',
            password: ''
        });

        const api = axios.create({
            baseURL: '/api/v1'
        });

        const login = async () => {
            loading.value = true;
            error.value = '';
            try {
                const response = await api.post('/login', loginForm);
                localStorage.setItem('auth_token', response.data.token);
                emit('login-success', response.data.user);
            } catch (err) {
                error.value = err.response?.data?.message || 'Login failed';
            } finally {
                loading.value = false;
            }
        };

        const register = async () => {
            loading.value = true;
            error.value = '';
            try {
                const response = await api.post('/register', registerForm);
                localStorage.setItem('auth_token', response.data.token);
                emit('login-success', response.data.user);
            } catch (err) {
                error.value = err.response?.data?.message || 'Registration failed';
            } finally {
                loading.value = false;
            }
        };

        return {
            showRegister,
            loading,
            error,
            loginForm,
            registerForm,
            login,
            register
        };
    }
};

// Register the component
window.AuthView = AuthView;
