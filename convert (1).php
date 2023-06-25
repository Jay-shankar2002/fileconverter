<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $file = $_FILES['file'];
  $format = $_POST['format'];

  // Validate the file
  if ($file['error'] !== UPLOAD_ERR_OK) {
    echo 'Error uploading file. Please try again.';
    exit;
  }

  // Set the destination directory and filename
  $uploadDir = 'uploads/';
  $originalFilename = basename($file['name']);
  $convertedFilename = 'converted_file.' . $format;
  $uploadPath = $uploadDir . $originalFilename;
  $convertedPath = $uploadDir . $convertedFilename;

  // Create the destination directory if it doesn't exist
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  // Move the uploaded file to the destination directory
  if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    echo 'Error moving file. Please try again.';
    exit;
  }

  // Perform conversion based on selected format
  if ($format === 'pdf') {
    // Perform PDF conversion logic using an external tool like Unoconv
    // Make sure Unoconv is installed on your server
    exec("unoconv -f pdf $uploadPath -o $convertedPath");

    // Check if the conversion was successful
    if (file_exists($convertedPath)) {
      // Send the converted file to the user for download
      header('Content-Type: application/pdf');
      header('Content-Disposition: attachment; filename="' . $convertedFilename . '"');
      readfile($convertedPath);
      exit;
    } else {
      echo 'Error converting file to PDF. Please try again.';
      exit;
    }
  } elseif ($format === 'txt') {
    // Perform text conversion logic using file_get_contents() and file_put_contents()
    $content = file_get_contents($uploadPath);
    file_put_contents($convertedPath, $content);

    // Check if the conversion was successful
    if (file_exists($convertedPath)) {
      // Send the converted file to the user for download
      header('Content-Type: text/plain');
      header('Content-Disposition: attachment; filename="' . $convertedFilename . '"');
      readfile($convertedPath);
      exit;
    } else {
      echo 'Error converting file to text. Please try again.';
      exit;
    }
  } elseif ($format === 'csv') {
    // Perform CSV conversion logic using file_get_contents() and file_put_contents()
    $content = file_get_contents($uploadPath);
    file_put_contents($convertedPath, $content);

    // Check if the conversion was successful
    if (file_exists($convertedPath)) {
      // Send the converted file to the user for download
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename="' . $convertedFilename . '"');
      readfile($convertedPath);
      exit;
    } else {
      echo 'Error converting file to CSV. Please try again.';
      exit;
    }
  } else {
    // Invalid format selected
    echo 'Invalid format selected. Please try again.';
    exit;
  }
}
?>
