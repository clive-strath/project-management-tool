// Dashboard View Component
const { createApp, ref, computed, onMounted } = Vue;

const DashboardView = {
    template: `
        <div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-6 overflow-y-auto">
            <!-- Welcome Section -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <h2 class="text-2xl font-bold text-white mb-2">Welcome back, {{ user?.name || 'User' }}!</h2>
                <p class="text-slate-400">Here's what's happening with your projects today.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-slate-400">Active Projects</p>
                        <span class="text-emerald-400 text-sm">+12%</span>
                    </div>
                    <p class="text-2xl font-bold text-white">{{ activeProjectsCount }}</p>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-slate-400">Total Tasks</p>
                        <span class="text-blue-400 text-sm">+8%</span>
                    </div>
                    <p class="text-2xl font-bold text-white">{{ totalTasksCount }}</p>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-slate-400">Completed</p>
                        <span class="text-purple-400 text-sm">{{ completionPercentage }}%</span>
                    </div>
                    <p class="text-2xl font-bold text-white">{{ completedTasksCount }}</p>
                </div>
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-slate-400">Team Members</p>
                        <span class="text-amber-400 text-sm">+2</span>
                    </div>
                    <p class="text-2xl font-bold text-white">12</p>
                </div>
            </div>

            <!-- Recent Projects -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Recent Projects</h3>
                    <button @click="$emit('navigate-to', 'projects')" class="text-indigo-400 hover:text-indigo-300 text-sm">View all</button>
                </div>
                <div class="space-y-3">
                    <div v-if="recentProjects.length === 0" class="text-slate-400">
                        No projects found. Create your first project!
                    </div>
                    <div 
                        v-for="project in recentProjects" 
                        :key="project.id"
                        class="flex items-center gap-3 p-3 rounded-lg bg-slate-800/50 hover:bg-slate-800/70 cursor-pointer"
                        @click="selectProject(project.id)"
                    >
                        <div class="h-10 w-10 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-semibold">
                            {{ project.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">{{ project.name }}</p>
                            <p class="text-xs text-slate-400">{{ project.description || 'No description' }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-400">
                            {{ project.status || 'active' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    `,
    setup(props, { emit }) {
        const user = ref(null);
        const projects = ref([]);
        const tasks = ref([]);
        const loading = ref(false);

        const api = axios.create({
            baseURL: '/api/v1'
        });

        // Add auth token to requests
        api.interceptors.request.use(config => {
            const token = localStorage.getItem('auth_token');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        });

        // Computed properties
        const activeProjectsCount = computed(() => {
            return projects.value?.filter(p => p.status === 'active').length || 0;
        });

        const totalTasksCount = computed(() => {
            return tasks.value?.length || 0;
        });

        const completedTasksCount = computed(() => {
            return tasks.value?.filter(t => t.status === 'completed').length || 0;
        });

        const completionPercentage = computed(() => {
            if (!tasks.value?.length) return 0;
            return Math.round((completedTasksCount.value / totalTasksCount.value) * 100);
        });

        const recentProjects = computed(() => {
            return projects.value?.slice(0, 3) || [];
        });

        const loadInitialData = async () => {
            loading.value = true;
            try {
                const [projectsRes, tasksRes, userRes] = await Promise.all([
                    api.get('/projects'),
                    api.get('/tasks'),
                    api.get('/me')
                ]);
                projects.value = projectsRes.data.data;
                tasks.value = tasksRes.data.data;
                user.value = userRes.data;
            } catch (err) {
                console.error('Failed to load initial data:', err);
            } finally {
                loading.value = false;
            }
        };

        const selectProject = (projectId) => {
            emit('navigate-to', 'board');
            emit('select-project', projectId);
        };

        onMounted(() => {
            loadInitialData();
        });

        return {
            user,
            projects,
            tasks,
            loading,
            activeProjectsCount,
            totalTasksCount,
            completedTasksCount,
            completionPercentage,
            recentProjects,
            selectProject,
            // Add emit function for template
            $emit: emit
        };
    }
};

// Register the component
window.DashboardView = DashboardView;
