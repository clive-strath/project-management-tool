// Time Tracking View Component
const { createApp, ref, computed, onMounted, onUnmounted } = Vue;

const TimeTrackingView = {
    template: `
        <div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-6 overflow-y-auto">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Time Tracking</h2>
                <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Start Timer
                </button>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <div class="text-center">
                    <p class="text-4xl font-bold text-white mb-2">{{ formattedTime }}</p>
                    <p class="text-slate-400">Current Session</p>
                </div>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Today's Time Entries</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-800/50">
                        <div>
                            <p class="text-sm font-medium text-white">Project Setup</p>
                            <p class="text-xs text-slate-400">Mobile App Project</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">2h 30m</p>
                            <p class="text-xs text-slate-400">10:00 AM - 12:30 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    setup() {
        const currentTime = ref(0);
        const isRunning = ref(false);
        let interval = null;

        const formattedTime = computed(() => {
            const hours = Math.floor(currentTime.value / 3600);
            const minutes = Math.floor((currentTime.value % 3600) / 60);
            const seconds = currentTime.value % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        });

        const startTimer = () => {
            if (!isRunning.value) {
                isRunning.value = true;
                interval = setInterval(() => {
                    currentTime.value++;
                }, 1000);
            }
        };

        const stopTimer = () => {
            if (isRunning.value) {
                isRunning.value = false;
                clearInterval(interval);
            }
        };

        const resetTimer = () => {
            stopTimer();
            currentTime.value = 0;
        };

        onUnmounted(() => {
            if (interval) {
                clearInterval(interval);
            }
        });

        return {
            currentTime,
            isRunning,
            formattedTime,
            startTimer,
            stopTimer,
            resetTimer
        };
    }
};

// Register the component
window.TimeTrackingView = TimeTrackingView;
