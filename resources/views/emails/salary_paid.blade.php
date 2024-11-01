<!DOCTYPE html>
<html>

<head>
    <title>Salary Paid Notification</title>
</head>

<body>
    <h1>Hello, {{ $name }}</h1>
    <p>Your salary of {{ $salary }} has been paid for the duration of {{ $duration }}.</p>
    <p>Your ID number is: {{ $idNumber }}</p>
    <p>If you have any questions, feel free to contact us at {{ $email ?? 'no email provided' }}.</p>
</body>

</html>
