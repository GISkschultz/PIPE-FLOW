<?php

// PROGRAM GENERATES PDF BASED ON CSV FILE FOR PIPE FLOW
//AUTOMATICALLY CREATES PDF READS CSV INPUTS DATA
//USES ARRAY 1-12 FOR FLOW OF PIPE TO PLOT DIRECTION AND FLOW
$target_dir = "//ENTER//DIRECTORY//HERE";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {

        $uploadOk = 0;
    } else {

        $uploadOk = 1;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    //echo "Sorry, file already exists.";
    // $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "csv" ) {
    echo "Sorry, only CSV files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        list($header,$content,$row) = readCSV($target_file);
        // printContent($header,$content,$row);
        pdf($header,$content,$row);


    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}


//GENERATES PDF $HEADER IS NOT CURRENTLY USED LEFT IN CASE NEEDED
//
function pdf($header,$content,$row)
{
    if (ob_get_level() == 0) ob_start();

    //fpdf script that requires rotations, which links to draw.php which links to fpdf
    //fpdf.php draw.php rotation.php all requried in its current form.
    require('rotation.php');

    //gets today's date
    $today = date("F j, Y, g i a");
    //folder directory path
    $filename= '\\\\10.32.51.200\\imagestore\\PIPE_FLOW\\'.$today.'\\';
    if (file_exists($filename)) {
        echo "The file $filename exists";//sends out a message saying the folder already exists and will not execute script
    } else {
        mkdir($filename, 0777, true); //creates folder

    }
    $errorTxt = fopen($filename."errorLog.txt", "w") or die("Unable to open file!");//error text file that is created within the folder that is created
    for ($postionContent = 3; $postionContent < $row; $postionContent++) {//loops through each row and generates a pdf
//echo "POSITION: ".$postionContent."<br>";
        $pdf = new PDF('P', 'in', array(11, 8.5));
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(0, 0);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Rect(0.4, 0.4, 7.7, 10.2);
        $pdf->SetXY(6.4, 3.7);
        $pdf->Cell(0, 0, 'CATCH BASINS ONLY:');
        $pdf->SetXY(6.4, 3.85);
        $pdf->SetFont('Helvetica', 'U', 8);
        $pdf->Cell(0, 0, 'GPS REFFERENCE POINT IS');
        $pdf->SetXY(6.4, 4);
        $pdf->Cell(0, 0, 'CENTER IF ROUND TOP');
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetXY(6.4, 4.9);
        $pdf->Cell(0, 0, 'N =');
        $pdf->SetXY(6.6, 4.9);
        $pdf->Cell(0, 0, $content[$postionContent]["POINT_Y"]); //N =
        $pdf->SetXY(6.4, 5.1);
        $pdf->Cell(0, 0, 'E =');
        $pdf->SetXY(6.6, 5.1);
        $pdf->Cell(0, 0, $content[$postionContent]["POINT_X"]); // E =
        $pdf->SetXY(6.4, 5.3);
        $pdf->Cell(0, 0, 'Z =');
        $pdf->SetXY(6.6, 5.3);
        $pdf->Cell(0, 0, $content[$postionContent]["POINT_Z"]); //Z =
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetXY(6.4, 4.25);
        $pdf->Cell(0, 0, 'MANHOLES ONLY:');
        $pdf->SetXY(6.4, 4.4);
        $pdf->SetFont('Helvetica', 'U', 8);
        $pdf->Cell(0, 0, 'GPS REFFERENCE POINT IS');
        $pdf->SetXY(6.4, 4.55);
        $pdf->Cell(0, 0, 'OPPOSITE OF LADDER AT');
        $pdf->SetXY(6.4, 4.7);
        $pdf->Cell(0, 0, 'CENTER');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetXY(1.3, .95);
        $pdf->Cell(0, 0, $content[$postionContent]["OBJNAME"], 0);// STRUCTURE NUMBER
        $pdf->SetXY(.6, 1.08);
        $pdf->Cell(0, 0, "STRUCTURE NUMBER", 0);
        $pdf->Line(0.6, 1.0, 2.6, 1.0);
        $pdf->Line(0.6, 1.45, 2.6, 1.45);
        $pdf->SetXY(.6, 1.53);
        $pdf->Cell(0, 0, "STRUCTURE LOCATION", 0);
        $pdf->Line(0.6, 1.9, 2.6, 1.9);
        $pdf->SetXY(.6, 1.4);
        $pdf->Cell(0, 0, $content[$postionContent]["LOCATION"], 0); // STRUCTURE LOCATION
        $pdf->SetXY(.6, 1.98);
        $pdf->Cell(0, 0, "CENTERLINE DEPTH", 0);
        $pdf->SetXY(1.3, 1.85);
        $pdf->Cell(0, 0, $content[$postionContent]["CL_INV"], 0); //CENTER LINE DEPTH
        $pdf->SetXY(5.95, .7);
        $pdf->Cell(0, 0, "DATE", 0);
        $pdf->Line(6.4, .73, 7.05, .73);
        $pdf->SetXY(7.05, .7);
        $pdf->Cell(0, 0, "MAP", 0);
        $pdf->Line(7.35, .73, 8.05, .73);
        $pdf->SetXY(6.6, 1);
        $pdf->Cell(0, 0, "STRUCTURE", 0);
        $pdf->SetXY(6.6, 1.15);
        $pdf->Cell(0, 0, "MATERIAL", 0);
        $pdf->SetXY(6.64, 1.25);
        //STRUCTURE MATERIAL IF STATEMENT, CHECKS WHICH MATERIAL IS WITHIN VARIABLE AND HIGHLIGHTS THE BOX
        if ($content[$postionContent]["MH_MAT"] == "CONCRETE" || $content[$postionContent]["CB_MAT"] == "CONCRETE") { //CONCRETE
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(0.16, .16, "C", 1, 0, 'C', true);//CONCRETE
            $pdf->SetXY(6.82, 1.25);
            $pdf->Cell(0.16, .16, "S", 1, 0, 'C');//STONE
            $pdf->SetXY(7, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PRECAST
            $pdf->SetXY(7.18, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PVC
            $pdf->SetXY(7.36, 1.25);
            $pdf->Cell(0.16, .16, "B", 1, 0, 'C');//BRICK
            $pdf->SetXY(7.54, 1.25);
            $pdf->Cell(0.16, .16, "L", 1, 0, 'C');//LINED
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["MH_MAT"] == "STONE" || $content[$postionContent]["CB_MAT"] == "STONE") {//STONE
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(0.16, .16, "C", 1, 0, 'C');//CONCRETE
            $pdf->SetXY(6.82, 1.25);
            $pdf->Cell(0.16, .16, "S", 1, 0, 'C', true);//STONE
            $pdf->SetXY(7, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PRECAST
            $pdf->SetXY(7.18, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PVC
            $pdf->SetXY(7.36, 1.25);
            $pdf->Cell(0.16, .16, "B", 1, 0, 'C');//BRICK
            $pdf->SetXY(7.54, 1.25);
            $pdf->Cell(0.16, .16, "L", 1, 0, 'C');//LINED
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["MH_MAT"] == "PRECAST" || $content[$postionContent]["CB_MAT"] == "PRECAST") {//PRECAST
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(0.16, .16, "C", 1, 0, 'C');//CONCRETE
            $pdf->SetXY(6.82, 1.25);
            $pdf->Cell(0.16, .16, "S", 1, 0, 'C');//STONE
            $pdf->SetXY(7, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C', true);//PRECAST
            $pdf->SetXY(7.18, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PVC
            $pdf->SetXY(7.36, 1.25);
            $pdf->Cell(0.16, .16, "B", 1, 0, 'C');//BRICK
            $pdf->SetXY(7.54, 1.25);
            $pdf->Cell(0.16, .16, "L", 1, 0, 'C');//LINED
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["MH_MAT"] == "PVC" || $content[$postionContent]["CB_MAT"] == "PVC") {//PVC
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(0.16, .16, "C", 1, 0, 'C');//CONCRETE
            $pdf->SetXY(6.82, 1.25);
            $pdf->Cell(0.16, .16, "S", 1, 0, 'C');//STONE
            $pdf->SetXY(7, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PRECAST
            $pdf->SetXY(7.18, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C', true);//PVC
            $pdf->SetXY(7.36, 1.25);
            $pdf->Cell(0.16, .16, "B", 1, 0, 'C');//BRICK
            $pdf->SetXY(7.54, 1.25);
            $pdf->Cell(0.16, .16, "L", 1, 0, 'C');//LINED
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["MH_MAT"] == "BRICK" || $content[$postionContent]["CB_MAT"] == "BRICK") {//BRICK
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(0.16, .16, "C", 1, 0, 'C');//CONCRETE
            $pdf->SetXY(6.82, 1.25);
            $pdf->Cell(0.16, .16, "S", 1, 0, 'C');//STONE
            $pdf->SetXY(7, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PRECAST
            $pdf->SetXY(7.18, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PVC
            $pdf->SetXY(7.36, 1.25);
            $pdf->Cell(0.16, .16, "B", 1, 0, 'C', true);//BRICK
            $pdf->SetXY(7.54, 1.25);
            $pdf->Cell(0.16, .16, "L", 1, 0, 'C');//LINED
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["MH_MAT"] == "LINED" || $content[$postionContent]["CB_MAT"] == "LINED") {//LINED
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Cell(0.16, .16, "C", 1, 0, 'C');//CONCRETE
            $pdf->SetXY(6.82, 1.25);
            $pdf->Cell(0.16, .16, "S", 1, 0, 'C');//STONE
            $pdf->SetXY(7, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PRECAST
            $pdf->SetXY(7.18, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PVC
            $pdf->SetXY(7.36, 1.25);
            $pdf->Cell(0.16, .16, "B", 1, 0, 'C');//BRICK
            $pdf->SetXY(7.54, 1.25);
            $pdf->Cell(0.16, .16, "L", 1, 0, 'C', true);//LINED
            $pdf->SetFillColor(255, 255, 55);
        } else {//IF THE VARIABLE IS UNPOPULATED
            $pdf->Cell(0.16, .16, "C", 1, 0, 'C');//CONCRETE
            $pdf->SetXY(6.82, 1.25);
            $pdf->Cell(0.16, .16, "S", 1, 0, 'C');//STONE
            $pdf->SetXY(7, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PRECAST
            $pdf->SetXY(7.18, 1.25);
            $pdf->Cell(0.16, .16, "P", 1, 0, 'C');//PVC
            $pdf->SetXY(7.36, 1.25);
            $pdf->Cell(0.16, .16, "B", 1, 0, 'C');//BRICK
            $pdf->SetXY(7.54, 1.25);
            $pdf->Cell(0.16, .16, "L", 1, 0, 'C');//LINED
        }
        $pdf->SetXY(6.63, 1.44);
        $pdf->MultiCell(.1, .12, 'ONCRETE');
        $pdf->SetXY(6.81, 1.44);
        $pdf->MultiCell(.1, .12, 'TONE');
        $pdf->SetXY(6.99, 1.44);
        $pdf->MultiCell(.1, .12, 'RECAST');
        $pdf->SetXY(7.17, 1.44);
        $pdf->MultiCell(.1, .12, 'VC');
        $pdf->SetXY(7.35, 1.44);
        $pdf->MultiCell(.1, .12, 'RICK');
        $pdf->SetXY(7.53, 1.44);
        $pdf->MultiCell(.1, .12, 'INED');
        //END FOR STRUCTURE MATERIAL

        // INFLITRATION PAN STATEMENT
        if ($content[$postionContent]["INFILPAN"] == "YES") { //IF YES HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 2.78, .15, .15, 'DF');
            $pdf->SetFillColor(255, 255, 55);
            $pdf->Rect(1.9, 2.78, .15, .15);
        } else if ($content[$postionContent]["INFILPAN"] == "NO") { //IF NO HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->Rect(0.9, 2.78, .15, .15);
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(1.9, 2.78, .15, .15, 'DF');
            $pdf->SetFillColor(255, 255, 55);
        } else {//IF EMPTY
            $pdf->Rect(0.9, 2.78, .15, .15);
            $pdf->Rect(1.9, 2.78, .15, .15);
        }
        $pdf->Rect(0.65, 2.05, 1.7, .5);//main box for inflitration pan
        $pdf->SetXY(.8, 2.15);
        $pdf->Cell(0, 0, "INFILTRATION PAN", 0);
        $pdf->SetXY(.8, 2.45);
        $pdf->Cell(0, 0, "YES", 0);
        $pdf->SetXY(1.85, 2.45);
        $pdf->Cell(0, 0, "NO", 0);
        //END FOR INFLITRATION PAN

        //IF STATEMENT FOR INFILTRATION
        if ($content[$postionContent]["INFIL"] == "YES") {//IF YES HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 2.23, .15, .15, 'DF');
            $pdf->Rect(1.9, 2.23, .15, .15);//no
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["INFIL"] == "NO") {//IF NO HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->Rect(0.9, 2.23, .15, .15);
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(1.9, 2.23, .15, .15, 'DF');//no
            $pdf->SetFillColor(255, 255, 55);
        } else {//IF EMPTY
            $pdf->Rect(0.9, 2.23, .15, .15);
            $pdf->Rect(1.9, 2.23, .15, .15);//no
        }
        $pdf->Rect(0.65, 2.6, 1.7, .5); //main box for inflitration
        $pdf->SetXY(1, 2.7);
        $pdf->Cell(0, 0, "INFILTRATION", 0);
        $pdf->SetXY(.8, 3);
        $pdf->Cell(0, 0, "YES", 0);
        $pdf->SetXY(1.85, 3);
        $pdf->Cell(0, 0, "NO", 0);
        //END FOR INFLITRATION

        //IF STATEMENT FOR SUMP
        if ($content[$postionContent]["SUMP"] == "YES" || $content[$postionContent]["SUMP"] == "Y") {//IF YES HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 3.33, .15, .15, 'DF');//yes
            $pdf->SetFillColor(255, 255, 55);
            $pdf->Rect(1.9, 3.33, .15, .15);//no
        } else if ($content[$postionContent]["SUMP"] == "NO" || $content[$postionContent]["SUMP"] == "N") {//IF NO HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->Rect(0.9, 3.33, .15, .15);//yes
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(1.9, 3.33, .15, .15, 'DF');//no
            $pdf->SetFillColor(255, 255, 55);
        } else {//IF EMPTY
            $pdf->Rect(0.9, 3.33, .15, .15);//yes
            $pdf->Rect(1.9, 3.33, .15, .15);//no
        }
        $pdf->Rect(0.65, 3.15, 1.7, .5);
        $pdf->SetXY(1.2, 3.25);
        $pdf->Cell(0, 0, "SUMP", 0);
        $pdf->SetXY(.8, 3.55);
        $pdf->Cell(0, 0, "YES", 0);
        $pdf->SetXY(1.85, 3.55);
        $pdf->Cell(0, 0, "NO", 0);
        //END FOR SUMP

//TOP SHAPE IF STATEMENT
        if ($content[$postionContent]["TOP_SHAPE"] == "ROUND") {//IF ROUND HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 3.88, .15, .15, 'DF');//round
            $pdf->Rect(1.4, 3.88, .15, .15);//mound
            $pdf->Rect(1.9, 3.88, .15, .15);//square
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["TOP_SHAPE"] == "MOUND") {//IF MOUND HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->Rect(0.9, 3.88, .15, .15);//round
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(1.4, 3.88, .15, .15, 'DF');//mound
            $pdf->SetFillColor(255, 255, 55);
            $pdf->Rect(1.9, 3.88, .15, .15);//square
        } else if ($content[$postionContent]["TOP_SHAPE"] == "SQUARE") {//IF SQUARE HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->Rect(0.9, 3.88, .15, .15);//round
            $pdf->Rect(1.4, 3.88, .15, .15);//mound
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(1.9, 3.88, .15, .15, 'DF');//square
            $pdf->SetFillColor(255, 255, 55);
        } else {
            $pdf->Rect(0.9, 3.88, .15, .15);//round
            $pdf->Rect(1.4, 3.88, .15, .15);//mound
            $pdf->Rect(1.9, 3.88, .15, .15);//square

        }
        $pdf->Rect(0.65, 3.7, 1.7, .5);
        $pdf->SetXY(1.1, 3.8);
        $pdf->Cell(0, 0, "TOP SHAPE", 0);
        $pdf->SetXY(.7, 4.1);
        $pdf->Cell(0, 0, "ROUND", 0);
        $pdf->SetXY(1.74, 4.1);
        $pdf->Cell(0, 0, "SQUARE", 0);
        $pdf->SetXY(1.22, 4.1);
        $pdf->Cell(0, 0, "MOUND", 0);

//END FOR TOP SHAPE

//START OF CONDITION
        if ($content[$postionContent]["CONDITION"] == "GOOD") {//IF SQUARE HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 4.43, .15, .15, 'DF');//GOOD
            $pdf->Rect(1.4, 4.43, .15, .15);//FAIR
            $pdf->Rect(1.9, 4.43, .15, .15);//POOR
            $pdf->SetFillColor(255, 255, 55);
        } else if ($content[$postionContent]["CONDITION"] == "FAIR") {//IF SQUARE HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 4.43, .15, .15);//GOOD
            $pdf->Rect(1.4, 4.43, .15, .15, 'DF');//FAIR
            $pdf->Rect(1.9, 4.43, .15, .15);//POOR
            $pdf->SetFillColor(255, 255, 55);

        } else if ($content[$postionContent]["CONDITION"] == "POOR") {//IF SQUARE HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 4.43, .15, .15);//GOOD
            $pdf->Rect(1.4, 4.43, .15, .15);//FAIR
            $pdf->Rect(1.9, 4.43, .15, .15, 'DF');//POOR
            $pdf->SetFillColor(255, 255, 55);
        } else {
            $pdf->Rect(0.9, 4.43, .15, .15);//GOOD
            $pdf->Rect(1.4, 4.43, .15, .15);//FAIR
            $pdf->Rect(1.9, 4.43, .15, .15);//POOR

        }
        $pdf->Rect(0.65, 4.25, 1.7, .5);
        $pdf->SetXY(1.1, 4.35);
        $pdf->Cell(0, 0, "CONDITION", 0);
        $pdf->SetXY(.7, 4.65);
        $pdf->Cell(0, 0, "GOOD", 0);
        $pdf->SetXY(1.76, 4.65);
        $pdf->Cell(0, 0, "POOR", 0);
        $pdf->SetXY(1.26, 4.65);
        $pdf->Cell(0, 0, "FAIR", 0);
        //END FOR CONDITION

        //START FOR STRUCTURE SHAPE

        if ($content[$postionContent]["STR_SHAPE"] == "ROUND") {//IF ROUND HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 4.98, .15, .15, 'DF');//ROUND
            $pdf->Rect(1.9, 4.98, .15, .15);//SQUARE
            $pdf->SetFillColor(255, 255, 55);
            $pdf->Rect(0.65, 4.8, 1.7, .5);
            $pdf->SetXY(.85, 4.9);
            $pdf->Cell(0, 0, "STRUCTURE SHAPE", 0);
            $pdf->SetXY(.7, 5.2);
            $pdf->Cell(0, 0, "ROUND", 0);

            $pdf->SetXY(1.76, 5.2);
            $pdf->Cell(0, 0, "SQUARE", 0);
        } else if ($content[$postionContent]["STR_SHAPE"] == "SQUARE") {//IF SQUARE HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(0.9, 4.98, .15, .15);//ROUND
            $pdf->Rect(1.9, 4.98, .15, .15, 'DF');//SQUARE
            $pdf->SetFillColor(255, 255, 55);
            $pdf->Rect(0.65, 4.8, 1.7, .5);
            $pdf->SetXY(.85, 4.9);
            $pdf->Cell(0, 0, "STRUCTURE SHAPE", 0);
            $pdf->SetXY(.7, 5.2);
            $pdf->Cell(0, 0, "ROUND", 0);
            $pdf->SetXY(1.76, 5.2);
            $pdf->Cell(0, 0, "SQUARE", 0);
        } else if ($content[$postionContent]["STR_SHAPE"] != " " && $content[$postionContent]["STR_SHAPE"] != "SQUARE" && $content[$postionContent]["STR_SHAPE"] != "ROUND" && $content[$postionContent]["STR_SHAPE"] != "") {//IF BOX IS POPULATED BUT NOT EMPTY OR OTHER VALUES HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            //$pdf->Rect(0.9, 4.98, .15, .15);//ROUND
            //$pdf->Rect(1.9, 4.98, .15, .15);//SQUARE
            $pdf->Rect(1.4, 4.98, .15, .15, 'DF');//OTHER
            $pdf->SetFillColor(255, 255, 55);
            $pdf->SetXY(1.26, 5.2);
            $pdf->Cell(0, 0, $content[$postionContent]["STR_SHAPE"], 0);
            $pdf->Rect(0.65, 4.8, 1.7, .5);
            $pdf->SetXY(.85, 4.9);
            $pdf->Cell(0, 0, "STRUCTURE SHAPE", 0);
        } else {//IF EMPTY
            $pdf->Rect(0.9, 4.98, .15, .15);//ROUND
            $pdf->Rect(1.9, 4.98, .15, .15);//SQUARE
            $pdf->Rect(0.65, 4.8, 1.7, .5);
            $pdf->SetXY(.85, 4.9);
            $pdf->Cell(0, 0, "STRUCTURE SHAPE", 0);
            $pdf->SetXY(.7, 5.2);
            $pdf->Cell(0, 0, "ROUND", 0);
            $pdf->SetXY(1.76, 5.2);
            $pdf->Cell(0, 0, "SQUARE", 0);
        }
//END STRUCTURE SHAPE

        ///START FOR MEASUREMENT ON PIPE
        $pdf->SetFont('Helvetica', '', 7);
        if ($content[$postionContent]["MEAS_LOC"] == "TOP") {//IF TOP HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(1.8, 5.4, .1, .1, 'DF');//TOP
            $pdf->Rect(2.1, 5.4, .1, .1);//BOTTOM
            $pdf->SetFillColor(255, 255, 55);

        } else if ($content[$postionContent]["MEAS_LOC"] == "BOTTOM") {//IF BOTTOM HIGHLIGHTS CORRESPONDING BOX 'DF"
            $pdf->SetFillColor(255, 255, 0);
            $pdf->Rect(1.8, 5.4, .1, .1);//TOP
            $pdf->Rect(2.1, 5.4, .1, .1, 'DF');//BOTTOM
            $pdf->SetFillColor(255, 255, 55);
        } else {//IF EMPTY
            $pdf->Rect(1.8, 5.4, .1, .1);//TOP
            $pdf->Rect(2.1, 5.4, .1, .1);//BOTTOM
        }
        $pdf->Rect(.5, 5.35, 1.9, .25);
        $pdf->SetXY(.5, 5.5);
        $pdf->Cell(0, 0, "MEASUREMENT ON PIPE", 0);
        $pdf->SetXY(1.71, 5.55);
        $pdf->SetFont('Helvetica', '', 5);
        $pdf->Cell(0, 0, "TOP", 0);
        $pdf->SetXY(2.01, 5.55);
        $pdf->Cell(0, 0, "BOTTOM", 0);
//END FOR MEASUREMENT ON PIPE

//SETS UP VARIABLES FOR PIPES 1-12

        $xMod = 1;
        $yMod = 0;
        $pipe = 1;
        $keys1=array("1","2","3","4","5","6","7","8","9","10","11","12");//KEYS FOR ARRAY FOR PIPE DIRECTION VARIABLE
        $flowPipe=array_fill_keys($keys1,"");//FILLS ARRAY WITH VARIABLES EACH KEY IS A DIRECTION

        $flow = array(12);

        for ($i = 1; $i < 13; $i++) {//LOOPS THROUGH ALL 12 PIPES
            $pdf->SetFont('Helvetica', '', 7);
            //IF STATEMENTS CHANGES POSITION OF PIPES 1-12
            if ($i % 5 == 1) {
                $xMod = 0;
            } else if ($i % 5 == 2) {
                $xMod = 2 - .5;

            } else if ($i % 5 == 3) {
                $xMod = 3;
            } else if ($i % 5 == 4) {
                $xMod = 4 + .5;
            } else if ($i % 5 == 0) {
                $xMod = 5 + 1;
            }

            if ($i == 6) {
                $yMod = 1.7;
            } else if ($i == 11) {
                $yMod = 3.3;
            }
            $flowtemp = $content[$postionContent]["FLOW" . $i];//TEMP VAR FOR FLOW
            $directtemp = $content[$postionContent]["DIRECT" . $i];//TEMP VAR FOR DIRECTION
            if($directtemp==" "||$directtemp==""){
            }else{
                if(empty($flowPipe[$directtemp])){//SETS UP ARRAY FOR PIPE FLOW ERROR DETECTION AND PIPEFLOW IMAGE PROCESSING
                    $flowPipe[$directtemp] = $flowtemp;//IF ARRAY IS NOT POPULATED ARRAY WILL BEGIN TO BE POPUKLATED
                }else{
                    $flowPipe[$directtemp].= ",".$flowtemp;//IF ARRAY IS POPULATED COMA IS ADDED AND EXISTING VALUE IS KEPT THIS IS SO ANY SINGLE VARIBLE WITHIN THE ARRAY 1-12(PIPE DIRECTION) WILL CONTAIN AN IN OR OUT OR BOTH DEPENDING
                }

            }
            //EXAMPLE
            //

            $flow[$i] = $flowtemp; //REPLACES FLOWTEMP
            $directPerm[$i] = (string)$directtemp;//CONVERTS VAR FROM FLOAT TO STRING
            $pdf->SetXY(.6 + $xMod, 5.8 + $yMod);
            $pdf->Cell(0, 0, "PIPE #" . $pipe, 0);
            $pdf->SetXY(.6 + $xMod, 5.95 + $yMod);
            $pdf->Cell(0, 0, "SIZE", 0);
            $pdf->Line(1.4 + $xMod, 6 + $yMod, 1.95 + $xMod, 6 + $yMod);
            $pdf->SetXY(1.6 + $xMod, 5.95 + $yMod);
            $pdf->Cell(0, 0, $content[$postionContent]["PIPESIZE" . $i], 0);//PIPE SIZE
            $pdf->SetXY(.6 + $xMod, 6.1 + $yMod);
            $pdf->Cell(0, 0, "FLOW", 0);
            $pdf->Line(1.4 + $xMod, 6.15 + $yMod, 1.95 + $xMod, 6.15 + $yMod);
            $pdf->SetXY(1.6 + $xMod, 6.1 + $yMod);
            $pdf->Cell(0, 0, $content[$postionContent]["FLOW" . $i], 0);
            $pdf->SetXY(.6 + $xMod, 6.25 + $yMod);
            $pdf->Cell(0, 0, "INV. ELEV", 0);
            $pdf->Line(1.4 + $xMod, 6.3 + $yMod, 1.95 + $xMod, 6.3 + $yMod);
            if ($content[$postionContent]["INVELEV" . $i] != 0) {
                $pdf->SetXY(1.6 + $xMod, 6.25 + $yMod);
                $pdf->Cell(0, 0, $content[$postionContent]["INVELEV" . $i], 0);//INV ELEV.
            }
            $pdf->SetXY(.6 + $xMod, 6.4 + $yMod);
            $pdf->Cell(0, 0, "DIRECTION", 0);
            $pdf->Line(1.4 + $xMod, 6.45 + $yMod, 1.95 + $xMod, 6.45 + $yMod);
            $pdf->SetXY(1.6 + $xMod, 6.4 + $yMod);
            $pdf->Cell(0, 0, $content[$postionContent]["DIRECT" . $i], 0);//DIRECTION
            $findme = '?';
            $pos = strpos($content[$postionContent]["PIPEMAT" . $i], "$findme");
// The !== operator can also be used.  Using != would not work as expected
// because the position of 'a' is 0. The statement (0 != false) evaluates
// to false.
            if ($pos !== false) {
                $content[$postionContent]["PIPEMAT" . $i] = str_replace($findme, "", $content[$postionContent]["PIPEMAT" . $i]);//PIPE MAT
                $pdf->SetXY(.6 + $xMod, 6.65 + $yMod);
                $pdf->Cell(0, 0, "?", 0);

            }
            $boxSize = .08;
            $pdf->SetXY(.6 + $xMod, 6.55 + $yMod);
            $pdf->Cell(0, 0, "MAT.", 0);
            $pdf->SetFont('Helvetica', '', 5);
            if ($content[$postionContent]["PIPEMAT" . $i] == "CONCRETE") {//IF PIPE MAT IS CONCRETE

                $pdf->SetFillColor(255, 255, 0);
                $pdf->SetXY(.6 + $xMod, 6.65 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "C", 1, 0, 'C', true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetXY(.65 + $xMod, 6.70 + $yMod);
                $pdf->Cell(0, 0, 'ONCRETE');
            } else {//IF EMPTY
                $pdf->SetXY(.6 + $xMod, 6.65 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "C", 1, 0, 'C');
                $pdf->SetXY(.65 + $xMod, 6.70 + $yMod);
                $pdf->Cell(0, 0, 'ONCRETE');
            }

            if ($content[$postionContent]["PIPEMAT" . $i] == "CLAY") {//IF PIPE MAT IS CLAY
                $pdf->SetFillColor(255, 255, 0);
                $pdf->SetXY(.6 + $xMod, 6.75 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "V", 1, 0, 'C', true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetXY(.65 + $xMod, 6.80 + $yMod);
                $pdf->Cell(0, 0, 'IT');
            } else {//IF EMPTY
                $pdf->SetXY(.6 + $xMod, 6.75 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "V", 1, 0, 'C');
                $pdf->SetXY(.65 + $xMod, 6.80 + $yMod);
                $pdf->Cell(0, 0, 'IT');
            }
            if ($content[$postionContent]["PIPEMAT" . $i] == "PVC") {//IF PIPE MAT IS PVC
                $pdf->SetFillColor(255, 255, 0);
                $pdf->SetXY(.6 + $xMod, 6.85 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "P", 1, 0, 'C', true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetXY(.65 + $xMod, 6.90 + $yMod);
                $pdf->Cell(0, 0, 'VC');
            } else {//IF EMPTY
                $pdf->SetXY(.6 + $xMod, 6.85 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "P", 1, 0, 'C');
                $pdf->SetXY(.65 + $xMod, 6.90 + $yMod);
                $pdf->Cell(0, 0, 'VC');
            }
            if ($content[$postionContent]["PIPEMAT" . $i] == "ELLIPTICAL") {//IF PIPE MAT IS ELLIPTICAL
                $pdf->SetFillColor(255, 255, 0);
                $pdf->SetXY(.6 + $xMod, 6.95 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "L", 1, 0, 'C', true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetXY(.65 + $xMod, 7 + $yMod);
                $pdf->Cell(0, 0, 'LLIPTICAL');
            } else {//IF EMPTY
                $pdf->SetXY(.6 + $xMod, 6.95 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "E", 1, 0, 'C');
                $pdf->SetXY(.65 + $xMod, 7 + $yMod);
                $pdf->Cell(0, 0, 'LLIPTICAL');
            }

            if ($content[$postionContent]["PIPEMAT" . $i] == "LINED") {//IF PIPE MAT IS LINED
                $pdf->SetFillColor(255, 255, 0);
                $pdf->SetXY(.6 + $xMod, 7.05 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "L", 1, 0, 'C', true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetXY(.65 + $xMod, 7.1 + $yMod);
                $pdf->Cell(0, 0, 'INED');
            } else {//IF EMPTY
                $pdf->SetXY(.6 + $xMod, 7.05 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "L", 1, 0, 'C');
                $pdf->SetXY(.65 + $xMod, 7.1 + $yMod);
                $pdf->Cell(0, 0, 'INED');
            }
//IF PIPE MAT IS NOT ANY OPTIONS AND NOT EMTPY
            if ($content[$postionContent]["PIPEMAT" . $i] != "PVC" && $content[$postionContent]["PIPEMAT" . $i] != "CONCRETE" && $content[$postionContent]["PIPEMAT" . $i] != "CLAY" && $content[$postionContent]["PIPEMAT" . $i] != " "&& $content[$postionContent]["PIPEMAT" . $i] != "") {
                $firstLetter = substr($content[$postionContent]["PIPEMAT" . $i], 0, 1);
                $pdf->SetFillColor(255, 255, 0);
                $pdf->SetXY(.6 + $xMod, 7.15 + $yMod);
                $pdf->Cell($boxSize, $boxSize, $firstLetter, 1, 0, 'C', true);
                $pdf->SetXY(.65 + $xMod, 7.2 + $yMod);
                $other = substr($content[$postionContent]["PIPEMAT" . $i], 1);
                $pdf->Cell(0, 0, $other);
                $pdf->SetFillColor(255, 255, 255);
            } else {//IF EMPTY
                $pdf->SetXY(.6 + $xMod, 7.15 + $yMod);
                $pdf->Cell($boxSize, $boxSize, "O", 1, 0, 'C');
                $pdf->SetXY(.68 + $xMod, 7.2 + $yMod);
                $pdf->Cell(0, 0, '_ _ _ _ _ _ _ _ _');
                $pdf->SetFont('Helvetica', '', 7);
            }
            $pipe++;
        }
        // VARIABLES FOR CALCULATING PIPE FLOW AND DIRECTION
        $directionCount=count($flowPipe);//COUNTS HOW MANY VALUES ARE INSIDE AN ARRAY
        $totalOut=0;//SETS TO 0 TRACKS HOW MANY PIPES FLOW OUT
        $totalError=0;//TRACKS HOW MANY PIPES FLOW IN TO OUT FROM THE SAME PIPE
        $totalIn=0; //TRACKS  TOTAL PIPES THAT FLOW IN
        $lineWidth=.1;//SETS LINE WIDTH TO ROTATION OF IMAGE
        $lineHeight =.8;//SETS LINE HEIGHT TO ROTATION OF IMAGE

        //FOLLOWING 4 ARRAYS EACH USE A SINGLE IMAGE TO ROTATE AN ARRAY AROUND THE COMPASS GENERATED ON PAGE
        //ARRAY GOES FROM 0-11

        $out = array('arrow2.png', 4.66, 2.05, $lineWidth, $lineHeight, 150, 'arrow2.png', 4.63, 2.06, $lineWidth, $lineHeight, 120, 'arrow2.png', 4.6, 2.08, $lineWidth, $lineHeight, 90, 'arrow2.png', 4.57, 2.07, $lineWidth, $lineHeight, 55, 'arrow2.png', 4.57, 2.04, $lineWidth, $lineHeight, 30, 'arrow2.png', 4.565, 2.02, $lineWidth, $lineHeight, 0, 'arrow2.png', 4.57, 2.01, $lineWidth, $lineHeight, -30, 'arrow2.png', 4.59, 2.0, $lineWidth, $lineHeight, -55, 'arrow2.png', 4.6, 1.99, $lineWidth, $lineHeight, -90, 'arrow2.png', 4.62, 1.99, $lineWidth, $lineHeight, 235, 'arrow2.png', 4.64, 2, $lineWidth, $lineHeight, 210, 'arrow2.png', 4.66, 2.05, $lineWidth, $lineHeight, 180);
        $in = array('arrow1.png', 4.66, 2.05, $lineWidth, $lineHeight, 150, 'arrow1.png', 4.63, 2.06, $lineWidth, $lineHeight, 120, 'arrow1.png', 4.6, 2.08, $lineWidth, $lineHeight, 90, 'arrow1.png', 4.57, 2.05, $lineWidth, $lineHeight, 55, 'arrow1.png', 4.57, 2.04, $lineWidth, $lineHeight, 30, 'arrow1.png', 4.565, 2.02, $lineWidth, $lineHeight, 0, 'arrow1.png', 4.57, 2.01, $lineWidth, $lineHeight, -30, 'arrow1.png', 4.59, 2, $lineWidth, $lineHeight, -55, 'arrow1.png', 4.6, 1.99, $lineWidth, $lineHeight, -90, 'arrow1.png', 4.62, 1.99, $lineWidth, $lineHeight, 235, 'arrow1.png', 4.64, 2, $lineWidth, $lineHeight, 210, 'arrow1.png', 4.66, 2.05, $lineWidth, $lineHeight, 180);
        $doubleout = array('double2.png', 4.66, 2.05, $lineWidth, $lineHeight, 150, 'double2.png', 4.63, 2.06, $lineWidth, $lineHeight, 120, 'double2.png', 4.6, 2.08, $lineWidth, $lineHeight, 90, 'double2.png', 4.57, 2.07, $lineWidth, $lineHeight, 55, 'double2.png', 4.57, 2.04, $lineWidth, $lineHeight, 30, 'double2.png', 4.565, 2.02, $lineWidth, $lineHeight, 0, 'double2.png', 4.57, 2.01, $lineWidth, $lineHeight, -30, 'double2.png', 4.59, 2, $lineWidth, $lineHeight, -55, 'double2.png', 4.6, 1.99, $lineWidth, $lineHeight, -90, 'double2.png', 4.62, 1.99, $lineWidth, $lineHeight, 235, 'double2.png', 4.64, 2.0, $lineWidth, $lineHeight, 210, 'double2.png', 4.66, 2.05, $lineWidth, $lineHeight, 180);
        $doublein = array('double1.png', 4.66, 2.05, $lineWidth, $lineHeight, 150, 'double1.png', 4.63, 2.06, $lineWidth, $lineHeight, 120, 'double1.png', 4.6, 2.08, $lineWidth, $lineHeight, 90, 'double1.png', 4.57, 2.07, $lineWidth, $lineHeight, 55, 'double1.png', 4.57, 2.04, $lineWidth, $lineHeight, 30, 'double1.png', 4.565, 2.02, $lineWidth, $lineHeight, 0, 'double1.png', 4.57, 2.01, $lineWidth, $lineHeight, -30, 'double1.png', 4.59, 2, $lineWidth, $lineHeight, -55, 'double1.png', 4.6, 1.99, $lineWidth, $lineHeight, -90, 'double1.png', 4.62, 1.99, $lineWidth, $lineHeight, 235, 'double1.png', 4.64, 2.0, $lineWidth, $lineHeight, 210, 'double1.png', 4.66, 2.05, $lineWidth, $lineHeight, 180);
        $tripleout = array('triple2.png', 4.66, 2.05, $lineWidth, $lineHeight, 150, 'triple2.png', 4.63, 2.06, $lineWidth, $lineHeight, 120, 'triple2.png', 4.6, 2.08, $lineWidth, $lineHeight, 90, 'triple2.png', 4.57, 2.07, $lineWidth, $lineHeight, 55, 'triple2.png', 4.57, 2.04, $lineWidth, $lineHeight, 30, 'triple2.png', 4.565, 2.02, $lineWidth, $lineHeight, 0, 'triple2.png', 4.57, 2.01, $lineWidth, $lineHeight, -30, 'triple2.png', 4.59, 2, $lineWidth, $lineHeight, -55, 'triple2.png', 4.6, 1.99, $lineWidth, $lineHeight, -90, 'triple2.png', 4.62, 1.99, $lineWidth, $lineHeight, 235, 'triple2.png', 4.64, 2.0, $lineWidth, $lineHeight, 210, 'triple2.png', 4.66, 2.05, $lineWidth, $lineHeight, 180);
        $triplein = array('triple1.png', 4.66, 2.05, $lineWidth, $lineHeight, 150, 'triple1.png', 4.63, 2.06, $lineWidth, $lineHeight, 120, 'triple1.png', 4.6, 2.08, $lineWidth, $lineHeight, 90, 'triple1.png', 4.57, 2.07, $lineWidth, $lineHeight, 55, 'triple1.png', 4.57, 2.04, $lineWidth, $lineHeight, 30, 'triple1.png', 4.565, 2.02, $lineWidth, $lineHeight, 0, 'triple1.png', 4.57, 2.01, $lineWidth, $lineHeight, -30, 'triple1.png', 4.59, 2, $lineWidth, $lineHeight, -55, 'triple1.png', 4.6, 1.99, $lineWidth, $lineHeight, -90, 'triple1.png', 4.62, 1.99, $lineWidth, $lineHeight, 235, 'triple1.png', 4.64, 2.0, $lineWidth, $lineHeight, 210, 'triple1.png', 4.66, 2.05, $lineWidth, $lineHeight, 180);


//LOOP FLOWS THROUGH ARRAY SET UP IN EARILIER
        for ($x = 1; $x < $directionCount+1; $x++) {

            $explodeData =  explode( ',', $flowPipe[$x] );//EXPLODES POPULATED DATA ITS OWN ARRAY
            $outCount[$x] = 0;
            $inCount[$x] = 0;
            foreach($explodeData as $data){//LOOPS THROUGH AND INCREMENTS A COUNTER FOR IN OR OUT
                if($data=="OUT"){
                    $outCount[$x]++;
                }else if($data=="IN"){
                    $inCount[$x]++;
                }


            }
            if($inCount[$x]>0&&$outCount[$x]>>0){//IF  SINGLE PIPE HAS BOTH AN IN OR OUT FLOW $totalError is INCREMENTED
                $totalError++;
            }
            if($outCount[$x]>>0){//IF THERE IS AN OUT FLOW DIRECTION OF PIPE  $totalOut WILL BE INCREMENTED TO TRACK HOW MANY PIPES SHOULD FLOW OUT
                $totalOut++;
            }

            if($inCount[$x]>>0){//IF THERE IS AN IN FLOW DIRECTION OF PIPE  $totalIn WILL BE INCREMENTED TO TRACK HOW MANY PIPES SHOULD FLOW IN
                $totalIn++;
            }
        }
        $outputError1 = false; //OUT GREATER THAN 2
        $outputError2 = false; //SAME PIPE DIFFERENT DIRECTION
        $outputError3 = false; //NO OUT WITH AN IN

        if($totalOut>1 ){//PRODUCES AN ERROR IF THERE ARE MORE THAN ONE OUT FLOWING PIPES
            $pdf->Circle(4.6, 2.02, .8);//main
            $pdf->SetXY(4.2, 2.02);
            $pdf->Cell(0, 0, "PIPEFLOW ERROR", 0);
            $pdf->SetXY(4.2, 2.12);
            $pdf->Cell(0, 0, "CHECK DATA FOR", 0);
            $pdf->SetXY(4.2, 2.22);
            $pdf->Cell(0, 0, "INCONSISTENCY", 0);
            $outputError1 = true;
        }else if($totalError>0) {//{PRODUCES AN ERROR IF A SINGLE PIPE FLOWS BOTH IN AND OUT

            $pdf->Circle(4.6, 2.02, .8);//main
            $pdf->SetXY(4.2, 2.02);
            $pdf->Cell(0, 0, "PIPEFLOW ERROR", 0);
            $pdf->SetXY(4.2, 2.12);
            $pdf->Cell(0, 0, "CHECK DATA FOR", 0);
            $pdf->SetXY(4.2, 2.22);
            $pdf->Cell(0, 0, "INCONSISTENCY", 0);
            $outputError2 = true;
        }else if($totalOut==0 && $totalIn>=1){ //PRODUCES AN ERROR IF  THERE IS NO PIPES FLOWING OUT BUT 1 OR MORE PIPES FLOWING IN
            $pdf->Circle(4.6, 2.02, .8);//main
            $pdf->SetXY(4.2, 2.02);
            $pdf->Cell(0, 0, "PIPEFLOW ERROR", 0);
            $pdf->SetXY(4.2, 2.12);
            $pdf->Cell(0, 0, "CHECK DATA FOR", 0);
            $pdf->SetXY(4.2, 2.22);
            $pdf->Cell(0, 0, "INCONSISTENCY", 0);
            $outputError3 =true;
        } else{// IF THERE ARE NO ERRORS LOOP WILL START TO BEGIN PLOTTING AND ROTATING LINES WITH ARROWHEADS
            for ($x = 1; $x < $directionCount+1; $x++) {
                $direction = $x * 6 - 6;//TAKES PIPE DIRECTION $x AND FINDS THE POSITION OF THE ARRAY TO BE PLOTTED FOR THE IMAGE
                if($x>=1&&$x<=5){//FOR DOUBLE OR TRIPLE PIPES  CHNAGES LOCATION SO 2X OR 3X DOES NOT HANG IN THE MIDDLE.
                    $widthSub =.5;
                    $heightSub=.1;
                }
                if($x>=7&&$x<=11){
                    $widthSub =-.5;
                    $heightSub=-.1;
                }
                if($x==12){
                    //$widthSub =-.5;
                    $heightSub=-.1;
                }
                if($x==6){
                    //$widthSub =-.5;
                    $heightSub=-.1;
                }
                $pdf->SetTextColor(255,0,0);//CHANGES COLOR TO RED FOR 2X or 3X
                if ($outCount[$x] == 1 && $inCount[$x] == 0) {//IF ONE PIPE FLOWS OUT AND IS ONLY 1
                    $pdf->RotatedImage($out[$direction], $out[$direction + 1], $out[$direction + 2], $out[$direction + 3], $out[$direction + 4], $out[$direction + 5]);
                } else if ($outCount[$x] == 2 && $inCount[$x] == 0) {//IF ONE PIPE FLOWS OUT AND IS ONLY 2
                    $pdf->RotatedImage($doubleout[$direction], $doubleout[$direction + 1], $doubleout[$direction + 2], $doubleout[$direction + 3], $doubleout[$direction + 4], $doubleout[$direction + 5]);
                    $pdf->SetXY($doubleout[$direction + 1]+$widthSub, $doubleout[$direction + 2]+$heightSub);
                    $pdf->Cell(0, 0, "2X", 0);
                } else if ($outCount[$x] == 3 && $inCount[$x] == 0) {//IF ONE PIPE FLOWS OUT AND IS ONLY 3
                    $pdf->RotatedImage($tripleout[$direction], $tripleout[$direction + 1], $tripleout[$direction + 2], $tripleout[$direction + 3], $tripleout[$direction + 4], $tripleout[$direction + 5]);
                    $pdf->SetXY($doubleout[$direction + 1]+$widthSub, $doubleout[$direction + 2]+$heightSub);
                    $pdf->Cell(0, 0, "3X", 0);
                }
                if ($outCount[$x] == 0 && $inCount[$x] == 1) {//IF ONE PIPE FLOWS IN AND IS ONLY 1
                    $pdf->RotatedImage($in[$direction], $in[$direction + 1], $in[$direction + 2], $in[$direction + 3], $in[$direction + 4], $in[$direction + 5]);
                } else if ($outCount[$x] == 0 && $inCount[$x] == 2) {//IF ONE PIPE FLOWS IN AND IS ONLY 2
                    $pdf->RotatedImage($doublein[$direction], $doublein[$direction + 1], $doublein[$direction + 2], $doublein[$direction + 3], $doublein[$direction + 4], $doublein[$direction + 5]);
                    $pdf->SetXY($doubleout[$direction + 1]+$widthSub, $doubleout[$direction + 2]+$heightSub);
                    $pdf->Cell(0, 0, "2X", 0);
                } else if ($outCount[$x] == 0 && $inCount[$x] == 3) {//IF ONE PIPE FLOWS IN AND IS ONLY 3
                    $pdf->RotatedImage($triplein[$direction], $triplein[$direction + 1], $triplein[$direction + 2], $triplein[$direction + 3], $in[$direction + 4], $triplein[$direction + 5]);
                    $pdf->SetXY($doubleout[$direction + 1]+$widthSub, $doubleout[$direction + 2]+$heightSub);
                    $pdf->Cell(0, 0, "3X", 0);
                }
            }
        }
        //FOR BOTTOM RIGHT BOX
        $pdf->SetTextColor(0,0,0);
        $pdf->Rect(3.8, 9.1, 4.2, 1.4);
        $pdf->SetXY(3.85, 9.25);
        $pdf->Cell(0, 0, "PHOTO", 0);
        $pdf->Rect(4.3, 9.15, .15, .15);
        $pdf->SetXY(6.3, 9.25);
        $pdf->Cell(0, 0, "NOTES:", 0);
        $pdf->SetXY(3.85, 9.55);
        $pdf->Cell(0, 0, "TYPES", 0);
        $pdf->SetXY(3.85, 9.7);
        $pdf->Cell(0, 0, "SANITARY = SMH", 0);
        $pdf->SetXY(3.85, 9.85);
        $pdf->Cell(0, 0, "COMBINATION = CMH", 0);
        $pdf->SetXY(3.85, 10);
        $pdf->Cell(0, 0, "STORM = STM", 0);
        $pdf->SetXY(3.85, 10.15);
        $pdf->Cell(0, 0, "CATCH BASIN = CB", 0);
        $pdf->SetXY(3.85, 10.3);
        $pdf->Cell(0, 0, "COMBINATION CATCH BASIN = CCB", 0);


        //STARTS MIDDLE GRADING PLAN
        $style5 = array('width' => 0.01, 'cap' => 'butt', 'join' => 'bevel', 'dash' => 10, 'color' => array(0, 0, 0));
        $pdf->Line(5.6, 4, 6.1, 3.15);
        $pdf->Line(5.6, 4, 5.6 + .05, 4 - .05);
        $pdf->Line(5.6, 4, 5.6 - .005, 4 - .05);
        $pdf->SetXY(6.4, 3.4);
        $pdf->SetXY(6.4, 3.5);
        $pdf->Line(6.1, 3.15, 6.2, 3.15);
        $pdf->Line(5.2, 4, 5.6, 4);
        $pdf->Line(5.2, 4, 5.2, 5);
        $pdf->Line(5.6, 4, 5.6, 5);
        $pdf->Line(5.2, 5, 3.5, 4.9);
        $pdf->Line(3.5, 5, 3.5, 4.9);
        $pdf->Line(3.5, 5, 5.2, 5.1);
        $pdf->Line(5, 4.98, 4.95, 4.5);//arrow
        $pdf->Line(4, 4, 3.6, 4);//arrow
        $pdf->Line(4, 4, 3.6, 4);//arrow
        $pdf->Line(4, 4, 4, 3.65);//arrow
        $pdf->Line(4, 4.92, 4, 5.2);//arrow
        $pdf->Line(5.6, 4, 6, 4);
        $pdf->Line(4.85, 4.5, 4.95, 4.5);
        $pdf->SetLineStyle($style5);
        $pdf->Line(5.2, 4, 4, 4);//dashed
        $pdf->Line(4, 4, 4, 4.92);//dashed
        $style5 = array('width' => 0.01, 'cap' => 'butt', 'join' => 'bevel', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->SetLineStyle($style5);
        $pdf->SetXY(4.3, 4.5);
        $pdf->Cell(0, 0, "GPS REF", 0);
        $pdf->SetXY(4.3, 4.6);
        $pdf->SetFont('Helvetica', '', 4);
        $pdf->Cell(0, 0, "YES OR NO", 0);
        $pdf->SetFont('Helvetica', '', 4);
        $pdf->SetXY(5.35, 4.1);
        $pdf->MultiCell(.1, .05, 'BACK OF CURB');
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetXY(6.25, 3.15);
        $pdf->Cell(0, 0, "GPS REF", 0);
        $pdf->SetXY(6.28, 3.25);
        $pdf->SetFont('Helvetica', '', 4);
        $pdf->Cell(0, 0, "YES OR NO", 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetXY(4.2, 3.75);
        $pdf->Cell(0, 0, "LENGTH =", 0);
        $pdf->SetXY(4.9, 3.75);
        $pdf->Cell(0, 0, $content[$postionContent]["LENGTH"], 0);//LENGTH
        $pdf->Line(4.75, 3.8, 5.3, 3.8);
        $pdf->SetXY(4.2, 3.9);
        $pdf->Cell(0, 0, "(BC TO CL STRUCTURE)", 0);
        $pdf->SetXY(2.8, 4.5);
        $pdf->Cell(0, 0, "HEIGHT = ", 0);
        $pdf->SetXY(3.4, 4.5);
        $pdf->Cell(0, 0, $content[$postionContent]["HEIGHT"], 0);//HEIGHT
        $pdf->Line(3.3, 4.55, 3.9, 4.55);
        $pdf->SetXY(4.3, 5.2);
        $pdf->Cell(0, 0, "CASTING", 0);
        $pdf->SetXY(3.7, 5.35);
        $pdf->Cell(0, 0, "WIDTH = ", 0);
        $pdf->SetXY(4.3, 5.35);
        $pdf->Cell(0, 0, $content[$postionContent]["WIDTH"], 0);//WIDTH
        $pdf->Line(4.2, 5.39, 4.7, 5.39);
        $pdf->SetXY(4.8, 5.35);
        $pdf->Cell(0, 0, "LEN = ", 0);
        $pdf->Line(5.15, 5.39, 5.6, 5.39);
        $pdf->SetXY(4.1, 5.45);
        $pdf->Cell(0, 0, "dimension of grate", 0);
        $pdf->SetFont('Helvetica', 'B', 8);

        //PLOTS COMPASS
        $pdf->Circle(4.6, 2.02, .8);//main
        $pdf->SetXY(4.53, .6);
        $pdf->Cell(0, 0, 'N');
        $pdf->SetXY(4.5, .85);
        $pdf->Cell(0, 0, '12');
        $pdf->Circle(4.6, .85, .15);//12
        $pdf->SetXY(4.53, 3.2);
        $pdf->Cell(0, 0, '6');
        $pdf->Circle(4.6, 3.2, .15);//6
        $pdf->SetXY(3.43, 2);
        $pdf->Cell(0, 0, '9');
        $pdf->Circle(3.5, 2, .15);//9
        $pdf->SetXY(5.73, 2);
        $pdf->Cell(0, 0, '3');
        $pdf->Circle(5.8, 2, .15);//3
        $pdf->SetXY(3.55, 1.45);
        $pdf->Cell(0, 0, '10');
        $pdf->Circle(3.65, 1.45, .15);//10
        $pdf->SetXY(5.58, 1.45);
        $pdf->Cell(0, 0, '2');
        $pdf->Circle(5.65, 1.45, .15);//2
        $pdf->SetXY(3.95, 1.05);
        $pdf->Cell(0, 0, '11');
        $pdf->Circle(4.05, 1.05, .15);//11
        $pdf->SetXY(5.13, 1.05);
        $pdf->Cell(0, 0, '1');
        $pdf->Circle(5.2, 1.05, .15);//1
        $pdf->SetXY(3.58, 2.6);
        $pdf->Cell(0, 0, '8');
        $pdf->Circle(3.65, 2.6, .15);//8
        $pdf->SetXY(5.58, 2.6);
        $pdf->Cell(0, 0, '4');
        $pdf->Circle(5.65, 2.6, .15);//4
        $pdf->SetXY(3.98, 3.05);
        $pdf->Cell(0, 0, '7');
        $pdf->Circle(4.05, 3.05, .15);//11
        $pdf->SetXY(5.13, 3.05);
        $pdf->Cell(0, 0, '5');
        $pdf->Circle(5.2, 3.05, .15);//1


        if($outputError1==true ||$outputError2==true ||$outputError3==true){//ERROR CHECKING AND OUTPUTTING
            if($outputError1==true){
                $errorMessaage="MULITPLE PIPES FLOW OUT";
            }
            if($outputError2==true){
                $errorMessaage="SINGLE PIPE FLOWS IN BOTH DIRECTIONS";
            }
            if($outputError3==true){
                $errorMessaage="PIPE FLOWS IN WITHOUT AN OUT";
            }
            echo '<div class="alert alert-danger">
  <strong>Error Found Within Data!</strong> Error Message: '.$errorMessaage.'<br> Please Check File: '.$content[$postionContent]["OBJNAME"].".pdf".'
</div>';

            $txt = 'Error Found Within Data! Error Message: '.$errorMessaage.' Please Check File: '.$content[$postionContent]["OBJNAME"].".pdf\n\n\n";
            fwrite($errorTxt, $txt);
            ob_flush();
            flush();


        }
        $file=$filename.$content[$postionContent]["OBJNAME"]."_CALC.pdf";
        $pdf->Output($file,'F');
    }
    fclose($errorTxt);
    ob_end_flush();
}




function readCSV($target_file){//READS CSV FILE AND POPULATES VARIABLES
    $row = 1;
    $loop = false;
    $header ="";

    if (($handle = fopen($target_file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 65536, ",")) !== FALSE) {
            $num = count($data);

            $row++;
            for ($c=0; $c < $num; $c++) {

                if($loop == false){
                    $header[$c]= $data[$c];
                }else{
                    if(is_numeric($data[$c])){

                        $data[$c] = (float)$data[$c];
                    }
                    $content[$row][$header[$c]]=$data[$c];

                }
            }

            $loop = true;
        }
        fclose($handle);

    }
    return array($header,$content,$row);
}
