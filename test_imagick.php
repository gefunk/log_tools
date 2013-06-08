<?php
$im = new Imagick('assets/uploads/balship.pdf[0]');
$im->setImageFormat("png");
$type=$im->getFormat();
header("Content-type: $type");
echo $im;
?>

