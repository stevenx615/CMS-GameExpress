<?php
require_once 'utilities.php';
$upload_folder = 'uploads';

/**
 * Main function that saves the uploaded image
 * Return first element in the array is the path for original image, 
 * and the second element is the path for thumbnail image.
 */
function handle_image_upload($files, $keep_original_name)
{
  global $upload_folder;
  if (isset($files) && $files['error'] === 0) {
    $temporary_path = $files['tmp_name'];
    $file_name = $files['name']; // with extension
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

    $new_file_name = $file_name;
    $new_name = pathinfo($file_name)['filename'];

    // want a random file name
    if (!$keep_original_name) {
      $new_name = create_image_name();
      $new_file_name = $new_name . '.' . $file_extension;
    }

    $new_path = file_upload_path($new_file_name, $upload_folder);

    if (file_is_an_image($temporary_path, $new_path)) {
      // Save the uploaded file
      if (move_uploaded_file($temporary_path, $new_path)) {
        // Resize the image to different sizes
        resizeImage($new_path, 500, 300, 'thumbnail');
        $original_relative_path = $upload_folder . '/' . $new_file_name;
        $thumbnail_relative_path = $upload_folder . '/' . $new_name . '_thumbnail.' . $file_extension;
        return [$original_relative_path, $thumbnail_relative_path];
      }
    } else {
      // not a valid image file
      $error_msgs = [];
      $error_msgs[] = 'The upload file should be a valid image .';
      $_SESSION['error_msgs'] = $error_msgs;
      redirect('admin-posts-process.php?action=error-messages');
    }
  }
  return ['', ''];
}

// generate the file upload path
function file_upload_path($original_filename, $upload_subfolder_name = '')
{
  $current_folder = dirname(__FILE__);
  $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
  return join(DIRECTORY_SEPARATOR, $path_segments);
}

// Check if the uploaded file is an image
function file_is_an_image($temporary_path, $new_path)
{
  $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
  $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

  $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
  $actual_mime_type        = mime_content_type($temporary_path);

  $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
  $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

  return $file_extension_is_valid && $mime_type_is_valid;
}

// Function to resize and save the image
function resizeImage($originalImagePath, $width, $height, $suffix)
{
  list($originalWidth, $originalHeight) = getimagesize($originalImagePath);
  $ratio = $originalWidth / $originalHeight;
  if ($width / $height > $ratio) {
    $width = $height * $ratio;
  } else {
    $height = $width / $ratio;
  }

  $image_p = imagecreatetruecolor($width, $height);
  $image = imagecreatefromstring(file_get_contents($originalImagePath));
  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

  $pathInfo = pathinfo($originalImagePath);
  $fileName = $pathInfo['filename'] . '.' . $pathInfo['extension'];
  $newImagePath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '_' . $suffix . '.' . $pathInfo['extension'];

  switch ($pathInfo['extension']) {
    case 'jpg':
    case 'jpeg':
      imagejpeg($image_p, $newImagePath);
      break;
    case 'png':
      imagepng($image_p, $newImagePath);
      break;
    case 'gif':
      imagegif($image_p, $newImagePath);
      break;
  }

  imagedestroy($image_p);
  imagedestroy($image);
}

// create a image name based on date, time, and random numbers
function create_image_name()
{
  $date_time = date("YmdHis");
  $random_number = rand(1000, 9999);
  $date_time_with_random = $date_time . $random_number;
  return $date_time_with_random;
}

// delete an image from the file system
function delete_image($path)
{
  global $upload_folder;
  $success = false;

  $file_name_thumbnail = pathinfo($path, PATHINFO_BASENAME);
  $file_name_original = str_replace('_thumbnail', '', $file_name_thumbnail);
  $path_thumbnail = file_upload_path($file_name_thumbnail, $upload_folder);
  $path_original = file_upload_path($file_name_original, $upload_folder);

  if (file_exists($path_thumbnail) && file_exists($path_original)) {
    $success = unlink($path_thumbnail) && unlink($path_original);
  }

  return $success;
}
