<!doctype html>

<title>Website Monitoring</title>
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<style>
    html {
        scroll-behavior: smooth;
    }
</style>

<body {{ $attributes(['class' => 'bg-gray-100 content-center']) }}>
{{ $slot }}
</body>
