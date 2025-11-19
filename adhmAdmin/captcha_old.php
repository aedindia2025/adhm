<?php
session_start();


// Create an image
$img = imagecreate(120, 40);

// Define background color
$bgcolor = imagecolorallocate($img, 2, 2, 2); // Black background

// Define text color
$red = imagecolorallocate($img, 255, 255, 255); // White text

// Text to be displayed
$text = generateRandomString();
$_SESSION['captcha_text'] = $text;

// Add text to the image
imagestring($img, 5, 30, 10, $text, $red);

// Add random lines for noise
// $line_color = imagecolorallocate($img, 100, 100, 100); // Gray lines
// for ($i = 0; $i < 5; $i++) {
//     imageline($img, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
// }

$dot_color = imagecolorallocate($img, 150, 150, 150); // Light gray dots
for ($i = 0; $i < 100; $i++) {
    imagesetpixel($img, rand(0, 120), rand(0, 40), $dot_color);
}

// Output the image
header("Content-Type: image/png");
imagepng($img);

// Free memory
imagedestroy($img);

//random string creator
function generateRandomString($length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>