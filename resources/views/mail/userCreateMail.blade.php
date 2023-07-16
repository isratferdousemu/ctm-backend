<!DOCTYPE html>
<html>
<head>
    <title>Welcome Mail</title>
</head>
<body>
    <h1>Hello, {{ $name }}!</h1>

        <li>Your login credentials are:</li>
            <ul>
                <li>Username: {{ $email }}</li>
                <li>Password: {{$password}}</li>
            </ul>
    </ul>
    <p>If you have any questions, please don't hesitate to reach out to your supervisor.</p>
    <p>Thank you and welcome again!</p>
</body>
</html>
