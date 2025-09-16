<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Add some simple inline styles for the error page for now */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            font-family: sans-serif;
        }
        .error-container {
            max-width: 500px;
        }
        h1 {
            font-size: 2.5rem;
        }
        p {
            font-size: 1.2rem;
            color: #666;
        }
        a {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="error-container">
        <h1 class="text-4xl font-bold text-gray-800">404</h1>
        <p class="text-lg text-gray-600 mt-2">Page Not Found</p>
        <p class="text-md text-gray-500 mt-4">
            <?php
            // The $message variable is passed from the ErrorController
            echo isset($message) ? htmlspecialchars($message, ENT_QUOTES, 'UTF-8') : 'Sorry, we couldn\'t find the page you were looking for.';
            ?>
        </p>
        <a href="/" class="mt-6 text-blue-500 hover:underline">Go back to Home</a>
    </div>

</body>
</html>
