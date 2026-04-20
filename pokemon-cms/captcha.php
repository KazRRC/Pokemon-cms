<?php
session_start();

$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$captcha = '';
for ($i = 0; $i < 5; $i++) {
    $captcha .= $chars[rand(0, strlen($chars) - 1)];
}
$_SESSION['captcha'] = $captcha;

$image = imagecreatetruecolor(120, 40);
$bg = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);

imagefill($image, 0, 0, $bg);

for ($i = 0; $i < 5; $i++) {
    $lineColor = imagecolorallocate($image, rand(150,255), rand(150,255), rand(150,255));
    imageline($image, rand(0,120), rand(0,40), rand(0,120), rand(0,40), $lineColor);
}

imagestring($image, 5, 20, 10, $captcha, $textColor);
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);