<?php
require 'PDF2/PDF.php';                    // Require the class.
$pdf = &PDF::factory('p', 'a4');      // Set up the pdf object.
$pdf->open();                         // Start the document.
$pdf->setCompression(true);           // Activate compression.
$pdf->addPage();                      // Start a page.
$pdf->setFont('Courier', 'B', 12);      // Set font to courier 8 pt.
$pdf->setDrawColor('rgb', 0, 0, 0);   // Set draw color to Black.
$pdf->rect(20, 20, 560, 800, 'd'); // Draw a filled rectangle.
$pdf->image('cabecera_minfra.jpg', 50, 35,500,50); 
$pdf->text(250, 100, 'ACTA DE INICIO');
$pdf->setLineWidth(2); 
$pdf->line(40, 125,557,125); 
$pdf->line(40, 300,557,300);
$pdf->setLineWidth(1);
$pdf->text(45, 158, 'Obra:');
$pdf->line(40, 160,557,160);
$pdf->text(45, 193, 'Ubicación:');
$pdf->setLineWidth(2); 
$pdf->line(40, 195,557,195); 
$pdf->text(45, 228, 'Contratista:'); 
$pdf->setLineWidth(1); 
$pdf->line(40, 230,557,230); 
$pdf->line(400, 195,400,230); 
$pdf->text(405, 228, 'Nº Contrato:'); 
$pdf->output('pruebaacta.pdf');              

/*$pdf->text(100, 100, 'First page');   // Text at x=100 and y=100.
$pdf->setFontSize(20);                // Set font size to 20 pt.
$pdf->setFillColor('rgb', 1, 0, 0);   // Set text color to red.
$pdf->text(100, 200, 'HELLO WORLD!'); // Text at x=100 and y=200.

$pdf->setDrawColor('rgb', 0, 0, 1);   // Set draw color to blue.
$pdf->line(100, 202, 240, 202);       // Draw a line.
$pdf->setFillColor('rgb', 1, 1, 0);   // Set fill/text to yellow.
$pdf->rect(200, 300, 100, 100, 'fd'); // Draw a filled rectangle.
$pdf->addPage();                      // Add a new page.

$pdf->setFont('Arial', 'BI', 12);     // Set font to arial bold
                                      // italic 12 pt.
$pdf->text(100, 100, 'Second page');  // Text at x=100 and y=100.
$pdf->image('sample.jpg', 50, 200);   // Image at x=50 and y=200.
$pdf->setLineWidth(4);                // Set line width to 4 pt.
$pdf->circle(200, 300, 150, 'd');     // Draw a non-filled
                                      // circle.
$pdf->output('foo.pdf');              // Output the file named foo.pdf*/
?> 
