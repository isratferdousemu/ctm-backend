<!DOCTYPE html>
<html>
<head>
    <title>Welcome Mail</title>
</head>
<body>
    <h1>Hello, {{ $name }}!</h1>

{{--        <li>Your login credentials are:</li>--}}
{{--            <ul>--}}
{{--                <li>Username: {{ $name }}</li>--}}
{{--                <li>Password: {{$password}}</li>--}}
{{--            </ul>--}}
{{--    </ul>--}}
    <p>Please follow the link to get your device token.</p>
    {{env('APP_FRONTEND_URL') . '/browser-token'}}



    <p>Thank you.</p>
</body>
</html>
