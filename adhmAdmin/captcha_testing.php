<?php
session_start();

$width = 140;
$height = 50;
$img = imagecreate($width, $height);

// Dark background
$bg_color = imagecolorallocate($img, 40, 40, 45);
imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);

$text = generateRandomString();
$_SESSION['captcha_text'] = $text;

// Bright accent colors for dark background
$colors = [
    imagecolorallocate($img, 255, 107, 107),  // Coral
    imagecolorallocate($img, 92, 207, 255),   // Light blue
    imagecolorallocate($img, 120, 255, 143),  // Mint green
    imagecolorallocate($img, 255, 168, 107),  // Orange
    imagecolorallocate($img, 187, 134, 252)   // Light purple
];

$start_x = 15;
for ($i = 0; $i < strlen($text); $i++) {
    $char = $text[$i];
    $x = $start_x + ($i * 22);
    $y = rand(15, 18);
    
    $color = $colors[$i % count($colors)];
    
    // Bold text with multiple layers
    imagestring($img, 5, $x, $y, $char, $color);
    imagestring($img, 5, $x+1, $y, $char, $color);
}

// Subtle grid pattern
for ($i = 0; $i < 10; $i++) {
    $dot_color = imagecolorallocate($img, 70, 70, 75);
    imagesetpixel($img, rand(0, $width), rand(0, $height), $dot_color);
}

// Dark border
imagerectangle($img, 0, 0, $width-1, $height-1, imagecolorallocate($img, 60, 60, 65));

header("Content-Type: image/png");
imagepng($img);
imagedestroy($img);

function generateRandomString($length = 6) {
    $characters = '2346789ABCDEFGHJKLMNPRSTUVWXYZ';
    return substr(str_shuffle($characters), 0, $length);
}
?>