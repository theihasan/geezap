<!DOCTYPE html>
<html>
<head>
    <title>Exception Report</title>
</head>
<body>
<h2>Exception Detected</h2>

<p><strong>Error Message:</strong><br>
    {{ $exception->getMessage() }}</p>

<p><strong>File:</strong><br>
    {{ $exception->getFile() }}</p>

<p><strong>Line:</strong><br>
    {{ $exception->getLine() }}</p>

<hr>
<p>This is an automated message from Geezap</p>
</body>
</html>
