<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('css')
    <title>@yield('title')</title>
</head>
<body>
    <div>
        <ul>
            <li>{{ route('adminHome') }}</li>
            <li>{{ route('admin') }}</li>
        </ul>
    </div>
    @yield('mainAdmin')
</body>
</html>