// Projects View Component
const { createApp, ref, onMounted } = Vue;

const ProjectsView = {
    template: `
        <div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-6 overflow-y-auto">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Projects</h2>
                <button @click="createNewProject" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    New Project
                </button>
            </div>

            <div v-if="loading" class="text-center py-8">
                <p class="text-slate-400">Loading projects...</p>
            </div>

            <div v-else-if="projects.length === 0" class="text-center py-8">
                <p class="text-slate-400">No projects found. Create your first project!</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div 
                    v-for="project in projects" 
                    :key="project.id"
                    @click="selectProject(project.id)"
                    class="rounded-xl border border-slate-800 bg-slate-900/70 p-5 hover:border-slate-700 transition-colors cursor-pointer"
                >
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-semibold text-white">{{ project.name }}</h3>
                        <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-400">
                            {{ project.status || 'Active' }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-400 mb-4">{{ project.description || 'No description' }}</p>
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Due: {{ formatDate(project.due_date) }}</span>
                        <div class="flex -space-x-1">
                            <div class="h-6 w-6 rounded-full bg-indigo-500 border-2 border-slate-900"></div>
                            <div class="h-6 w-6 rounded-full bg-purple-500 border-2 border-slate-900"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    setup(props, { emit }) {
        const projects = ref([]);
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

        const loadProjects = async () => {
            loading.value = true;
            try {
                const response = await api.get('/projects');
                projects.value = response.data.data;
            } catch (err) {
                console.error('Failed to load projects:', err);
            } finally {
                loading.value = false;
            }
        };

        const createNewProject = async () => {
            const projectName = prompt('Enter project name:');
            if (projectName) {
                try {
                    const response = await api.post('/projects', {
                        name: projectName,
                        description: 'New project description'
                    });
                    projects.value.push(response.data);
                } catch (err) {
                    console.error('Failed to create project:', err);
                    alert('Failed to create project. Please try again.');
                }
            }
        };

        const selectProject = (projectId) => {
            emit('navigate-to', 'board');
            emit('select-project', projectId);
        };

        const formatDate = (dateString) => {
            if (!dateString) return 'No deadline';
            return new Date(dateString).toLocaleDateString();
        };

        onMounted(() => {
            loadProjects();
        });

        return {
            projects,
            loading,
            createNewProject,
            selectProject,
            formatDate,
            // Add emit function for template
            $emit: emit
        };
    }
};

// Register the component
window.ProjectsView = ProjectsView;
