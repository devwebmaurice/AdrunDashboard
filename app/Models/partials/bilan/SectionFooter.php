<?php

$footer_row = $DICC['number'] + 4 ;
$footer_row2 = $DICC['number'] + 5 ;
                    
$objDrawing = new PHPExcel_Worksheet_Drawing;
$objDrawing->setPath( public_path('img/footer.png') );
$objDrawing->setCoordinates( 'A'.$footer_row );
$objDrawing->setName( "ADRUN" );
$objDrawing->setDescription( "ADRUN" );  
$objDrawing->setWorksheet( $sheet );

$sheet->mergeCells("A{$footer_row}:F{$footer_row}");
$sheet->mergeCells("B{$footer_row2}:E{$footer_row2}");

$sheet->cell("B{$footer_row2}", function($cell) {
    // manipulate the cell
    $cell->setValue('© 2018 by ADRUN [ Inteligence Dashboard ]');

    // Set font
    $cell->setFont(array(
        'family'     => 'Calibri',
        'size'       => '7'
    ));

});


$sheet->setHeight($footer_row, 170); 
