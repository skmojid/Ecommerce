<!DOCTYPE html>
<html>
<head>
    <title>Debug Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Authentication Debug</h1>
        
        <!-- Debug Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Current Session State</h2>
            <pre id="debug-info" class="bg-gray-100 p-4 rounded text-sm overflow-x-auto"></pre>
            <button onclick="loadDebugInfo()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Refresh</button>
        </div>

        <!-- Test Login Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Test Login</h2>
            <form id="test-login-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Email:</label>
                    <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password:</label>
                    <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Test Login</button>
            </form>
            <div id="login-result" class="mt-4"></div>
        </div>

        <!-- Quick Test Users -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-semibold mb-4">Quick Test Users</h2>
            <div class="grid grid-cols-2 gap-4">
                <button onclick="testUser('admin@example.com', 'password')" class="bg-gray-200 hover:bg-gray-300 p-3 rounded text-sm">Admin User</button>
                <button onclick="testUser('shopadmin@shophub.com', 'password')" class="bg-gray-200 hover:bg-gray-300 p-3 rounded text-sm">Shop Admin</button>
            </div>
        </div>
    </div>

    <script>
        function loadDebugInfo() {
            fetch('/debug-api')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('debug-info').textContent = JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('debug-info').textContent = 'Error loading debug info';
                });
        }

        function testUser(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('test-login-form').dispatchEvent(new Event('submit'));
        }

        document.getElementById('test-login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const resultDiv = document.getElementById('login-result');
            
            resultDiv.innerHTML = '<div class="text-blue-600">Testing login...</div>';
            
            try {
                const response = await fetch('/debug-login', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <strong>Login Successful!</strong>
                            <pre class="mt-2 text-xs">${JSON.stringify(data, null, 2)}</pre>
                        </div>
                    `;
                    // Refresh debug info after successful login
                    setTimeout(loadDebugInfo, 1000);
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong>Login Failed!</strong>
                            <pre class="mt-2 text-xs">${JSON.stringify(data, null, 2)}</pre>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>Error:</strong> ${error.message}
                    </div>
                `;
            }
        });

        // Load debug info on page load
        loadDebugInfo();
    </script>
</body>
</html>