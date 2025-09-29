<!DOCTYPE html>
<html>
<head>
    <title>Upload Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>File Upload Test</h1>
    
    <form action="{{ route('test.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Test Upload</button>
    </form>
    
    <div id="result"></div>
    
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("test.upload") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('result').innerHTML = '<p style="color: red;">Error: ' + error.message + '</p>';
            });
        });
    </script>
</body>
</html> 