<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our System</title>
</head>
    <body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Your account has been created successfully.</p>
    <p>Email: {{ $user->email }}</p>
    <p>Please log in using the credentials provided by your administrator.</p>
    <br>
    <p>Best Regards,<br> The Team</p>
    </body>
</html>
