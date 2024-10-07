<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Admin Dashboard</title>
    @include('layouts.include.link')
</head>

<body>

    <div class="main-wrapper">
        @include('components.header')
        @include('components.sidebar')

        <div class="page-wrapper">
            @yield('content')
        </div>
    </div>

    @include('layouts.include.script')
</body>

</html>
