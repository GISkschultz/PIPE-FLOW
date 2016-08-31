<?php
/**
 * Created by PhpStorm.
 * User: kschultz
 * Date: 7/25/2016
 * Time: 8:19 AM
 */








































/*
require('draw.php');
$orginx =4.25;
$originy =5.5;

$pdf = new PDF_Draw('P', 'in', array(11, 8.5));
$pdf->AddPage();
$pdf->SetAutoPageBreak(0, 0);
$pdf->SetMargins(0, 0, 0);
$pdf->SetFont('Helvetica', '', 4);
$pdf->SetXY(4.26,5.6);
//$pdf->Cell(0,0,"ORIGIN(0,0)",0);
$pdf->SetXY(4.26,5.7);
//$pdf->Cell(0,0,"ORIGIN(4.25,5.5)",0);
$pdf->Line(4.25, 0, 4.25, 11);
$pdf->Line(0, 5.5, 11, 5.5);


$xMod1 = $orginx;
$yMod1 = $originy;
$xMod2 = $orginx;
$yMod2 = $originy;
$axis = .1;
for ($i=0; $i < 42; $i++) {

    $xMod1 = $orginx +$axis;
    $pdf->Line($xMod1, 5.45, $xMod1, 5.55);
    $pdf->SetXY($xMod1-.05,5.6);
    $pdf->Cell(0,0,$i+1,0);
    $axis = $axis +.1;
}
$axis = .1;
for ($i=0; $i < 42; $i++) {

    $xMod1 = $orginx -$axis;
    $pdf->Line($xMod1, 5.45, $xMod1, 5.55);
    $pdf->SetXY($xMod1-.05,5.6);
    $pdf->Cell(0,0,$i+1,0);
    $axis = $axis +.1;
}
for ($i=0; $i < 42; $i++) {

    $xMod1 = $orginx -$axis;
    $pdf->Line($xMod1, 5.45, $xMod1, 5.55);
    $pdf->SetXY($xMod1-.05,5.6);
    $pdf->Cell(0,0,$i+1,0);
    $axis = $axis +.1;
}


$pdf->Output();
*/

$mystring = 'abc';
$findme   = 'x';
$pos = strpos($mystring, $findme);

// The !== operator can also be used.  Using != would not work as expected
// because the position of 'a' is 0. The statement (0 != false) evaluates
// to false.
if ($pos !== false) {
    echo "The string '$findme' was found in the string '$mystring'";
    echo " and exists at position $pos";
} else {
    echo "The string '$findme' was not found in the string '$mystring'";
}


function inches2pointX($inches){
    if($inches > 4.25){
        $value = $inches - 4.25;
        $value = $value/.1;
    }else if($inches < 4.25){
        $value = 4.25 - $inches;
        $value = $value/.1;
        $value = $value * -1;
    }
    return $value;
}
function inches2pointY($inches){
    if($inches > 5.5){
        $value = $inches - 5.55;
        $value = $value/.1;
    }else if($inches < 5.5){
        $value = 5.5 - $inches;
        $value = $value/.1;
        $value = $value * -1;
    }
    return $value;
}
