<?php
if (isset($_GET['file'])) {
    $filePath = $_GET['file'];

    // Sanitize the file path
    $filePath = basename($filePath);
    $fullPath = __DIR__ . "/uploads/" . $filePath;

    if (file_exists($fullPath)) {
        // Set headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid request.";
}
?>
