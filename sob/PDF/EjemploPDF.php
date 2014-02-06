<?php
require 'PDF1/PDF.php';                    // Require the lib.
$pdf = &PDF::factory('p', 'a4');      // Set up the pdf object.
$pdf->open();                         // Start the document.
$pdf->setCompression(true);           // Activate compression.
$pdf->addPage();                      // Start a page.
$pdf->setFont('Courier', '', 8);      // Set font to arial 8 pt.
$pdf->text(100, 100, 'First page');   // Text at x=100 and y=100.
$pdf->setFontSize(20);                // Set font size to 20 pt.
$pdf->text(100, 200, 'HELLO WORLD!'); // Text at x=100 and y=200.
$pdf->addPage();                      // Add a new page.
$pdf->setFont('Arial', 'BI', 12);     // Set font to arial bold italic 12 pt.
$pdf->text(100, 100, 'Second page');  // Text at x=100 and y=200.
$pdf->output('foo.pdf');              // Output the file named foo.pdf
?>
