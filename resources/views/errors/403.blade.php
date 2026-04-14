<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <style>
        body { text-align: center; padding-top: 100px; font-family: sans-serif; }
        .back-btn {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>403</h1>
    <h2>You can't access this page.</h2>
    <br><br>
    <a href="{{ url()->previous() }}" class="back-btn">Back</a>
</body>
</html>
