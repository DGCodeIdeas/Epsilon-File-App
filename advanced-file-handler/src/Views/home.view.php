<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Advanced MVC File Handler</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="bg-gray-100 p-4 md:flex md:items-center md:justify-center md:h-screen">

    <div id="app-container" class="w-full max-w-2xl">

        <!-- Upload Container -->
        <div class="upload-container bg-white rounded-lg shadow-md p-6 md:p-8 mb-8">
            <h1 class="text-xl lg:text-2xl font-bold mb-4 text-center">Upload a File</h1>

            <!-- Drop Zone -->
            <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-500 transition-colors">
                <p class="text-gray-500">Drag & Drop your file here</p>
                <p class="text-gray-400 text-sm my-2">or</p>
                <button id="browse-btn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Browse Files</button>
                <input type="file" id="file-input" class="hidden">
            </div>

            <!-- Status & Preview Container -->
            <div id="status-container" class="mt-6">
                <!-- This will be populated by JavaScript -->
            </div>
        </div>

        <!-- Download Container -->
        <div class="download-container bg-white rounded-lg shadow-md p-6 md:p-8">
            <h2 class="text-xl font-bold mb-4">Available Files</h2>
            <ul id="file-list" class="list-disc pl-5">
                <?php if (empty($files)): ?>
                    <li class="text-gray-500">No files have been uploaded yet.</li>
                <?php else: ?>
                    <?php foreach ($files as $file): ?>
                        <?php
                            // Sanitize output to prevent XSS
                            $safeFile = htmlspecialchars($file, ENT_QUOTES, 'UTF-8');
                        ?>
                        <li>
                            <a href="/download?file=<?= urlencode($safeFile) ?>" class="text-blue-600 hover:underline">
                                <?= $safeFile ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

    </div>

    <script src="/js/app.js" defer></script>
</body>
</html>
