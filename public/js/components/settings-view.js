// Settings View Component
const { createApp, ref } = Vue;

const SettingsView = {
    template: `
        <div class="flex-1 px-4 sm:px-6 py-5 sm:py-6 space-y-6 overflow-y-auto">
            <h2 class="text-xl font-semibold text-white">Settings</h2>
            
            <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Profile Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Name</label>
                        <input type="text" value="Current User" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                        <input type="email" value="user@example.com" class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-white" />
                    </div>
                    <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    `,
    setup() {
        return {};
    }
};

// Register the component
window.SettingsView = SettingsView;
