<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>Social Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top : 70px;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
</body>
</html>
