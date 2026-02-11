<!DOCTYPE html>
<html>
<head>
    <title>Image Test</title>
</head>
<body>
    <h1>Image Test</h1>
    <h2>Direct Path Test:</h2>
    <img src="/storage/products/product_10_6972132d58649_kjXUP9S9FmQB.png" alt="Direct Test" style="border: 1px solid red; width: 200px;">
    <h2>Storage Directory Contents:</h2>
    <pre>{{ print_r(scandir(public_path('storage/products/')), true) }}</pre>
    <h2>File Info:</h2>
    @if(file_exists(public_path('storage/products/product_10_6972132d58649_kjXUP9S9FmQB.png')))
        File exists! Size: {{ filesize(public_path('storage/products/product_10_6972132d58649_kjXUP9S9FmQB.png')) }} bytes
    @else
        File does NOT exist in public/storage
    @endif
    <h2>Storage Link Check:</h2>
    @if(is_link(public_path('storage')))
        Storage link exists
        Target: {{ readlink(public_path('storage')) }}
    @else
        Storage link does NOT exist
    @endif
</body>
</html>