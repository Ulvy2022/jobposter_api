<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h5>Welcome to job portal System</h5>
    <p>Congratulation! You have been register successfully</p>
    <p>Your name: <b>{{$username}}</b></p>
    <p>Your email: <b>{{$email}}</b></p>
    <p>Your Id: {{$email}}</p>
    <a href="http://localhost:8080/verifyEmail/{{$id}}">Email Verify</a>
</body>
</html>