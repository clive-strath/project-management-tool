// Team View Component
const { createApp, ref } = Vue;

const TeamView = {
    template: `
        <div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-6 overflow-y-auto">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Team Members</h2>
                <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Invite Member
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                            U
                        </div>
                        <div>
                            <h3 class="font-medium text-white">Current User</h3>
                            <p class="text-xs text-slate-400">Owner</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    setup() {
        return {};
    }
};

// Register the component
window.TeamView = TeamView;
