<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management Tool</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div id="app">
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-center mb-6">Project Management Tool</h1>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h2 class="font-semibold text-blue-800 mb-2">API Endpoints Available:</h2>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li><code>POST /api/v1/register</code> - Register user</li>
                            <li><code>POST /api/v1/login</code> - Login user</li>
                            <li><code>GET /api/v1/me</code> - Get current user</li>
                            <li><code>GET /api/v1/projects</code> - List projects</li>
                            <li><code>POST /api/v1/projects</code> - Create project</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h2 class="font-semibold text-green-800 mb-2">Quick Test:</h2>
                        <button onclick="testApi()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Test API Connection
                        </button>
                        <div id="api-result" class="mt-2 text-sm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function testApi() {
            const resultDiv = document.getElementById('api-result');
            try {
                const response = await fetch('/api/v1/projects', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.status === 401) {
                    resultDiv.innerHTML = '<span class="text-green-600">✅ API is working! (Authentication required)</span>';
                } else {
                    const data = await response.json();
                    resultDiv.innerHTML = '<span class="text-green-600">✅ API Response:</span><pre class="mt-1 text-xs bg-gray-100 p-2 rounded">' + JSON.stringify(data, null, 2) + '</pre>';
                }
            } catch (error) {
                resultDiv.innerHTML = '<span class="text-red-600">❌ Error: ' + error.message + '</span>';
            }
        }
    </script>
</body>
</html>
