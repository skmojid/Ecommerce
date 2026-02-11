<!DOCTYPE html>
<html>
<head>
    <title>CSRF Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">CSRF Token Test</h1>
        
        <!-- CSRF Token Display -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">CSRF Token Information</h2>
            <div class="space-y-2">
                <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
                <p><strong>Session Started:</strong> {{ session()->isStarted() ? 'Yes' : 'No' }}</p>
                <p><strong>CSRF Token:</strong> <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ csrf_token() }}</code></p>
                <p><strong>CSRF Token in Session:</strong> {{ session()->has('_token') ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <!-- Test Forms -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Test Forms</h2>
            
            <!-- Simple POST Form -->
            <div class="mb-6">
                <h3 class="font-medium mb-2">Simple POST Form</h3>
                <form method="POST" action="/test-csrf-post" class="border p-4 rounded">
                    @csrf
                    <input type="hidden" name="test_field" value="simple_test">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit Simple POST</button>
                </form>
            </div>

            <!-- AJAX POST Form -->
            <div class="mb-6">
                <h3 class="font-medium mb-2">AJAX POST Form</h3>
                <form id="ajax-form" class="border p-4 rounded">
                    @csrf
                    <input type="hidden" name="test_field" value="ajax_test">
                    <button type="button" onclick="submitAjaxForm()" class="bg-green-500 text-white px-4 py-2 rounded">Submit AJAX POST</button>
                </form>
            </div>

            <!-- Result Display -->
            <div id="result" class="mt-4"></div>
        </div>

        <!-- Test User Credentials -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-semibold mb-4">Test User</h2>
            <p><strong>Email:</strong> admin@example.com</p>
            <p><strong>Password:</strong> password</p>
            <div class="mt-4">
                <a href="/minimal-login" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 inline-block">Test Minimal Login</a>
            </div>
        </div>
    </div>

    <script>
        function submitAjaxForm() {
            const form = document.getElementById('ajax-form');
            const formData = new FormData(form);
            const resultDiv = document.getElementById('result');
            
            resultDiv.innerHTML = '<div class="text-blue-600">Sending AJAX request...</div>';
            
            fetch('/test-csrf-ajax', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <strong>AJAX Success!</strong>
                            <pre class="mt-2 text-xs">${JSON.stringify(data, null, 2)}</pre>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong>AJAX Failed!</strong>
                            <pre class="mt-2 text-xs">${JSON.stringify(data, null, 2)}</pre>
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>Error:</strong> ${error.message}
                    </div>
                `;
            });
        }
    </script>
</body>
</html>