<?php
require __DIR__ . '/../vendor/autoload.php';

use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Common\BCGDrawing;
use BarcodeBakery\Barcode\BCGcode128;

$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);

// Barcode Part
$code = new BCGcode128();
$code->setScale(2);
$code->setColor($colorFront, $colorBack);
$code->parse($merchantNo);

// Drawing Part
$drawing = new BCGDrawing('qrcode/bar.png', $colorBack);
$drawing->setBarcode($code);
$drawing->draw();

$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
