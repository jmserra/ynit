<!DOCTYPE html>
<html>
<head>
    <title>Blade Page Example</title>
</head>
<body>
    <h1>BladeOne Page</h1>
    This is a Page rendered with <a href="https://github.com/EFTEC/BladeOne">BladeOne</a>.<br>
    You can use variables with @{{ }} (use @ to escape it) <br>
    <br>
    Example:<br>
    @{{$_SERVER['HTTP_HOST']}}: {{$_SERVER['HTTP_HOST']}}
</body>
</html>
