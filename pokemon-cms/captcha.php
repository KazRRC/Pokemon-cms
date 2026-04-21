<?php
session_start();

header('Content-Type: image/png');

$image = imagecreate(120, 40);
$bg = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

$captcha = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 6);
$_SESSION['captcha'] = $captcha;

imagestring($image, 5, 15, 10, $captcha, $text_color);

imagepng($image);
imagedestroy($image);