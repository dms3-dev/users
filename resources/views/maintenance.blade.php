<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <style>
        @vite('resources/css/app.scss')
    </style>

    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>
<body class="antialiased">
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
            <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                503
            </div>

            <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                {{app(\Mediamouse\Users\Settings\GlobalSettings::class)->maintenance_text}}
            </div>
        </div>
    </div>
</div>
</body>
</html>
