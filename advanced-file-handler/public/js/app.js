(function() {
    'use strict';

    // 1. DOM Element Constants
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const browseBtn = document.getElementById('browse-btn');
    const statusContainer = document.getElementById('status-container');
    const fileList = document.getElementById('file-list');

    // 2. Event Listeners

    // Browse button triggers hidden file input
    browseBtn.addEventListener('click', () => {
        fileInput.click();
    });

    // Handle file selection from input
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleFile(e.target.files[0]);
        }
    });

    // Drag and Drop listeners
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500');
        if (e.dataTransfer.files.length) {
            handleFile(e.dataTransfer.files[0]);
        }
    });

    // Prevent default drag-and-drop behavior for the whole window
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        document.body.addEventListener(eventName, e => e.preventDefault());
    });


    // 3. Core Functions

    /**
     * Handles the selected file.
     * @param {File} file The file selected by the user.
     */
    function handleFile(file) {
        // Basic validation (can be expanded)
        if (!file) {
            updateStatus('error', 'No file selected.');
            return;
        }
        // You could add client-side size/type validation here if desired
        // const maxSize = 5 * 1024 * 1024; // 5MB
        // if (file.size > maxSize) {
        //     updateStatus('error', 'File is too large.');
        //     return;
        // }

        previewAndUpload(file);
    }

    /**
     * Creates a file preview and initiates the upload.
     * @param {File} file The file to upload.
     */
    function previewAndUpload(file) {
        // For this version, we'll show a simple "uploading" message
        // instead of a full image preview to keep it simple.
        updateStatus('uploading', `Uploading ${file.name}...`);
        uploadFile(file);
    }

    /**
     * Uploads the file to the server using the Fetch API.
     * @param {File} file The file to upload.
     */
    function uploadFile(file) {
        const formData = new FormData();
        formData.append('userfile', file);

        fetch('/upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateStatus('success', data.message);
                addToFileList(data.filename);
            } else {
                updateStatus('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            updateStatus('error', 'An unexpected network error occurred.');
        })
        .finally(() => {
            // Reset the UI after a short delay
            setTimeout(resetUI, 5000);
        });
    }

    /**
     * Updates the status container with a message.
     * @param {string} status 'success', 'error', or 'uploading'
     * @param {string} message The message to display.
     */
    function updateStatus(status, message) {
        statusContainer.innerHTML = ''; // Clear previous status
        const statusDiv = document.createElement('div');
        statusDiv.className = 'status-message';
        statusDiv.textContent = message;

        if (status === 'success') {
            statusDiv.classList.add('status-success');
        } else if (status === 'error') {
            statusDiv.classList.add('status-error', 'shake-animation');
        } else if (status === 'uploading') {
            statusDiv.classList.add('status-uploading', 'pulse-animation'); // A placeholder class
        }

        statusContainer.appendChild(statusDiv);
    }

    /**
     * Adds a new file to the displayed list of files.
     * @param {string} filename The name of the new file.
     */
    function addToFileList(filename) {
        // Remove the "No files" message if it exists
        const noFilesLi = fileList.querySelector('li');
        if (noFilesLi && noFilesLi.textContent.includes('No files')) {
            noFilesLi.remove();
        }

        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = `/download?file=${encodeURIComponent(filename)}`;
        a.className = 'text-blue-600 hover:underline';
        a.textContent = filename;
        li.appendChild(a);
        fileList.appendChild(li);
    }


    /**
     * Resets the UI after an upload attempt.
     */
    function resetUI() {
        statusContainer.innerHTML = '';
        fileInput.value = ''; // Reset the file input
    }

})();
