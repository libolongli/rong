<?php
require __DIR__ . '/../vendor/autoload.php';

use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Common\BCGDrawing;
use BarcodeBakery\Common\BCGFontFile;
use BarcodeBakery\Barcode\BCGcode128;

// Loading Font
$font = new BCGFontFile(__DIR__ . '/../font/Arial.ttf', 18);

// Don't forget to sanitize user inputs
$text = $merchantNo;

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);

$drawException = null;
try {
    $code = new BCGcode128();
    $code->setScale(2); // Resolution
    $code->setThickness(30); // Thickness
    $code->setForegroundColor($color_black); // Color of bars
    $code->setBackgroundColor($color_white); // Color of spaces
    $code->setFont($font); // Font (or 0)
    $code->setStart(null);
    $code->setTilde(true);
    $code->parse($text); // Text
} catch (Exception $exception) {
    $drawException = $exception;
}

/* Here is the list of the arguments
1 - Filename (empty : display on screen)
2 - Background color */
$drawing = new BCGDrawing('qrcode/bar.png', $color_white);
if ($drawException) {
    $drawing->drawException($drawException);
} else {
    $drawing->setBarcode($code);
    $drawing->draw();
}


// Draw (or save) the image into PNG format.
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
