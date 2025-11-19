<?php
session_start();

$width = 140; // Reduced from 160
$height = 50;
$img = imagecreate($width, $height);

// Clean white background
$bg_color = imagecolorallocate($img, 255, 255, 255);
imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);

$text = generateRandomString();
$_SESSION['captcha_text'] = $text;

// Modern color scheme
$colors = [
    imagecolorallocate($img, 231, 76, 60),   // Red
    imagecolorallocate($img, 52, 152, 219),  // Blue
    imagecolorallocate($img, 46, 204, 113),  // Green
    imagecolorallocate($img, 155, 89, 182),  // Purple
    imagecolorallocate($img, 241, 196, 15)   // Yellow
];

$start_x = 15; // Adjusted for smaller width
for ($i = 0; $i < strlen($text); $i++) {
    $char = $text[$i];
    $x = $start_x + ($i * 22); // Slightly tighter spacing
    $y = rand(15, 18); // Adjusted for larger text
    
    // Use the largest built-in font (5) and draw multiple times for boldness
    $color = $colors[$i % count($colors)];
    
    // Draw multiple layers to make text appear larger and bolder
    imagestring($img, 5, $x, $y, $char, $color);
    imagestring($img, 5, $x+1, $y, $char, $color);
    imagestring($img, 5, $x, $y+1, $char, $color);
}

// Add very subtle pattern
for ($i = 0; $i < 2; $i++) {
    $line_color = imagecolorallocate($img, 240, 240, 240);
    imageline($img, rand(0, $width), rand(0, $height), 
              rand(0, $width), rand(0, $height), $line_color);
}

// Thin border
imagerectangle($img, 0, 0, $width-1, $height-1, imagecolorallocate($img, 220, 220, 220));

header("Content-Type: image/png");
imagepng($img);
imagedestroy($img);

function generateRandomString($length = 6) {
    $characters = '23479ABCDEFGHJKLMNPRTVXYZ';
    return substr(str_shuffle($characters), 0, $length);
}
?>