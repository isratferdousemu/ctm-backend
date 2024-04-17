<!DOCTYPE html>
<html>
<head>
    <title>API Data Receive Credentials</title>
</head>
<body>
    <h1>Hello,</h1>

    Welcome to the CTM application.

    <p>Your API credentials are following:</p>

    <ul>
        <li>Username: {{ $apiDataReceive->username }}</li>
        <li>API Key: {{$apiDataReceive->api_key}}</li>
    </ul>


    <p>Thank you.</p>
</body>
</html>
