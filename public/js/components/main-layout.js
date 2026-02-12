// Main Layout Component
const { createApp, ref, onMounted, onUnmounted } = Vue;

const MainLayout = {
    template: `
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="hidden md:flex w-64 flex-col bg-slate-900 border-r border-slate-800">
                <div class="px-6 py-5 flex items-center gap-3 border-b border-slate-800">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-sm font-semibold text-white">
                        PM
                    </div>
                    <div>
                        <p class="text-sm font-semibold leading-tight text-white">Real-Time PM</p>
                        <p class="text-xs text-slate-400">Project Management</p>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Overview</p>
                    <button 
                        @click="currentView = 'dashboard'"
                        :class="currentView === 'dashboard' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-700/70">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 1.293a1 1 0 0 0-1.414 0l-8 8A1 1 0 0 0 2 11h1v6a2 2 0 0 0 2 2h3.5a.5.5 0 0 0 .5-.5V13h2v5.5a.5.5 0 0 0 .5.5H15a2 2 0 0 0 2-2v-6h1a1 1 0 0 0 .707-1.707l-8-8z"/>
                            </svg>
                        </span>
                        <span class="font-medium">Dashboard</span>
                    </button>

                    <button 
                        @click="currentView = 'projects'"
                        :class="currentView === 'projects' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 3h5v5H3V3zm9 0h5v5h-5V3zM3 12h5v5H3v-5zm9 0h5v5h-5v-5z"/>
                            </svg>
                        </span>
                        <span class="font-medium">Projects</span>
                    </button>

                    <button 
                        @click="currentView = 'board'"
                        :class="currentView === 'board' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 4a2 2 0 0 1 2-2h1.5a.5.5 0 0 1 .5.5V16a.5.5 0 0 1-.5.5H5a2 2 0 0 1-2-2V4zm6 0a2 2 0 0 1 2-2h1.5a.5.5 0 0 1 .5.5V11a.5.5 0 0 1-.5.5H11a2 2 0 0 1-2-2V4zm6 0a2 2 0 0 1 2-2h.5a.5.5 0 0 1 .5.5V13a.5.5 0 0 1-.5.5H17a2 2 0 0 1-2-2V4z"/>
                            </svg>
                        </span>
                        <span class="font-medium">Board</span>
                    </button>

                    <button 
                        @click="currentView = 'time'"
                        :class="currentView === 'time' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm.75 4a.75.75 0 0 0-1.5 0v4.25c0 .199.079.39.22.53l2.5 2.5a.75.75 0 1 0 1.06-1.06l-2.28-2.28V6z"/>
                            </svg>
                        </span>
                        <span class="font-medium">Time Tracking</span>
                    </button>

                    <button 
                        @click="currentView = 'reports'"
                        :class="currentView === 'reports' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-5zM8 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V7zM14 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V4z"/>
                            </svg>
                        </span>
                        <span class="font-medium">Reports</span>
                    </button>

                    <p class="px-3 pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Team</p>
                    <button 
                        @click="currentView = 'team'"
                        :class="currentView === 'team' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a3 3 0 1 1-2.995 3.176A3 3 0 0 1 10 2zm-5 9a3 3 0 1 1 2.65-1.588A5.977 5.977 0 0 0 5 11zm10 0a3 3 0 1 1 2.65-1.588A5.977 5.977 0 0 1 15 11zM10 9a5 5 0 0 0-4.546 2.916A4 4 0 0 0 9 17h2a4 4 0 0 0 3.546-5.084A5 5 0 0 0 10 9z"/>
                            </svg>
                        </span>
                        <span class="font-medium">Team</span>
                    </button>

                    <button 
                        @click="currentView = 'settings'"
                        :class="currentView === 'settings' ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/60'"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors"
                    >
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.983 2.163a1 1 0 0 0-1.966 0l-.19 1.148a5.977 5.977 0 0 0-1.492.864L7.29 3.58a1 1 0 0 0-1.414 1.415l.595 1.045a5.977 5.977 0 0 0-.864 1.492l-1.148.19a1 1 0 0 0 0 1.966l1.148.19a5.977 5.977 0 0 0 .864 1.492L5.876 13.7a1 1 0 1 0 1.414 1.414l1.045-.595a5.977 5.977 0 0 0 1.492.864l.19 1.148a1 1 0 0 0 1.966 0l.19-1.148a5.977 5.977 0 0 0 1.492-.864l1.045.595a1 1 0 1 0 1.414-1.414l-.595-1.045a5.977 5.977 0 0 0 .864-1.492l1.148-.19a1 1 0 0 0 0-1.966l-1.148-.19a5.977 5.977 0 0 0-.864-1.492l.595-1.045A1 1 0 0 0 16.71 3.58l-1.045.595a5.977 5.977 0 0 0-1.492-.864l-.19-1.148zM10 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        <span class="font-medium">Settings</span>
                    </button>
                </nav>

                <div class="px-4 py-4 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                    <div>
                        <p class="font-medium text-slate-200">{{ user.name }}</p>
                        <p>{{ user.role?.name || 'Member' }}</p>
                    </div>
                    <button 
                        @click="handleLogout"
                        class="inline-flex items-center gap-1 rounded-full bg-rose-500/10 px-2 py-1 text-[10px] font-medium text-rose-400 border border-rose-500/30 hover:bg-rose-500/20"
                    >
                        Logout
                    </button>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col">
                <!-- Top Bar -->
                <header class="h-16 border-b border-slate-800 flex items-center justify-between px-4 sm:px-6 gap-4 bg-slate-950/60 backdrop-blur">
                    <div class="flex items-center gap-3 min-w-0">
                        <button class="md:hidden inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-200">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 5.75A.75.75 0 0 1 3.75 5h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.75zM3 10a.75.75 0 0 1 .75-.75h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 10zm0 4.25A.75.75 0 0 1 3.75 13.5h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 14.25z"/>
                            </svg>
                        </button>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">{{ currentView.charAt(0).toUpperCase() + currentView.slice(1) }}</p>
                            <h1 class="text-base sm:text-lg font-semibold text-slate-50 truncate">
                                {{ getViewTitle() }}
                            </h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input
                                v-model="searchQuery"
                                type="search"
                                placeholder="Search projects, tasks…"
                                class="w-52 lg:w-64 rounded-lg border border-slate-800 bg-slate-900/80 px-8 py-1.5 text-xs text-slate-100 placeholder:text-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500/80 focus:border-indigo-500/80"
                            >
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 3.473 9.8l3.613 3.614a.75.75 0 1 0 1.06-1.06l-3.613-3.615A5.5 5.5 0 0 0 9 3.5zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <button class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 text-slate-200 hover:bg-slate-800">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a4 4 0 0 0-4 4v1.175c0 .338-.112.667-.32.936L4.2 9.6A1.75 1.75 0 0 0 5.6 12h8.8a1.75 1.75 0 0 0 1.4-2.8l-1.48-1.49A1.75 1.75 0 0 1 13.5 7.175V6a4 4 0 0 0-3.5-3.968V2z"/>
                                <path d="M8 14.75A2.25 2.25 0 0 0 10.25 17h-.5A2.25 2.25 0 0 1 7.5 14.75h.5z"/>
                            </svg>
                            <span class="absolute -top-0.5 -right-0.5 inline-flex h-3 w-3 items-center justify-center rounded-full bg-rose-500 text-[9px] font-semibold leading-none text-white border border-slate-900">
                                {{ notifications.length }}
                            </span>
                        </button>

                        <div class="h-8 w-px bg-slate-800"></div>

                        <button class="flex items-center gap-2 rounded-full border border-slate-800 bg-slate-900/80 px-2.5 py-1.5 text-xs text-slate-100 hover:bg-slate-800/80">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500 text-[11px] font-semibold">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </span>
                            <span class="hidden sm:flex flex-col items-start leading-tight">
                                <span class="text-[11px] font-medium">{{ user.name }}</span>
                                <span class="text-[10px] text-slate-400">{{ user.role?.name || 'Member' }}</span>
                            </span>
                        </button>
                    </div>
                </header>

                <!-- Dynamic Content Area -->
                <component 
                    :is="getCurrentViewComponent()" 
                    @navigate-to="handleNavigateTo"
                    @select-project="handleSelectProject"
                />
            </main>
        </div>
    `,
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    setup(props, { emit }) {
        const currentView = ref('dashboard');
        const searchQuery = ref('');
        const notifications = ref([]);

        const getViewTitle = () => {
            const titles = {
                dashboard: 'Dashboard Overview',
                projects: 'Project Management',
                board: 'Kanban Board',
                time: 'Time Tracking',
                reports: 'Reports & Analytics',
                team: 'Team Management',
                settings: 'Settings'
            };
            return titles[currentView.value] || 'Dashboard';
        };

        const getCurrentViewComponent = () => {
            switch (currentView.value) {
                case 'dashboard':
                    return 'dashboard-view';
                case 'projects':
                    return 'projects-view';
                case 'board':
                    return 'board-view';
                case 'time':
                    return 'time-tracking-view';
                case 'reports':
                    return 'reports-view';
                case 'team':
                    return 'team-view';
                case 'settings':
                    return 'settings-view';
                default:
                    return 'dashboard-view';
            }
        };

        const handleLogout = () => {
            emit('logout');
        };

        const handleNavigateTo = (view) => {
            currentView.value = view;
        };

        const handleSelectProject = (projectId) => {
            // This could be used to set the current project context
            console.log('Selected project:', projectId);
        };

        return {
            currentView,
            searchQuery,
            notifications,
            getViewTitle,
            getCurrentViewComponent,
            handleLogout,
            handleNavigateTo,
            handleSelectProject
        };
    }
};

// Register the component
window.MainLayout = MainLayout;
