<?php
session_start();
$codigo = rand(1000, 9999);
$_SESSION["captcha"] = $codigo;

header("Content-type: image/png");
$img = imagecreate(80, 30);
$bg = imagecolorallocate($img, 255, 255, 255);
$text_color = imagecolorallocate($img, 0, 0, 0);
imagestring($img, 5, 10, 5, $codigo, $text_color);
imagepng($img);
imagedestroy($img);
?>
