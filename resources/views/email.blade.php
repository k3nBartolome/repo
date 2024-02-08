<!DOCTYPE html>
<html>
<head>
    <title>Email</title>
    <style>
        .stripe {
            background: repeating-linear-gradient(
                45deg,
                #606dbc,
                #606dbc 10px,
                #465298 10px,
                #465298 20px
            );
            color: white;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="stripe">
        {!! $html !!}
    </div>
</body>
</html>
