<?php

$next_row2     = $WICC['number'] + 5 ;
$banner2       = $WICC['number'] + 4 ;
$new_next_row2 = $WICC['number'] + 3 ;

//SECTION TWO SETTING START 
$sheet->mergeCells( 'B'.$new_next_row2.':E'.$new_next_row2 );
$sheet->setHeight(array( $banner2 =>  3 ));

//DISPLAY/STYLE Impression/Clic par flight Row
$sheet->cell('B'.$new_next_row2, function($cell) {
    // manipulate the cell
    $cell->setValue('Impression/Clic Par Date');
    // Set font
    $cell->setFont(array(
        'family'     => 'Calibri',
        'size'       => '17',
        'bold'       =>  true
    ));

    $cell->setFontColor('#4B88C7');
    $cell->setAlignment('left');

});

//BANNER SEPERATOR                    
$sheet->cell('B' . $banner2, function($cell) { $cell->setBackground('#00538C'); });
$sheet->cell('C' . $banner2, function($cell) { $cell->setBackground('#3953A4'); });
$sheet->cell('D' . $banner2, function($cell) { $cell->setBackground('#4C86C6'); });
$sheet->cell('E' . $banner2, function($cell) { $cell->setBackground('#AED8E6'); });
//BANNER SEPERATOR

//WEBSITE BANNER
$sheet->cells("B{$next_row2}:E{$next_row2}", function($cells) {

    $cells->setValignment('center');
    $cells->setFontColor('#00538C');
    $cells->setBackground('#F0F8FF');
    $cells->setFont(array( 'bold' => true ));

});

$sheet->row($next_row2, array('','DATE', 'IMPRESSIONS','CLICS', 'TAUX DE CLICS') );

$DICC       = $this->getDateImpCliTDC($next_row2,$FICC['i_total'],$FICC['c_total'],$FICC['tx_percentage']);
$total_rowx = $DICC['number'] + 1;
$sheet->rows( $DICC['data'], NULL, "A{$DICC['next']}",FALSE,FALSE );
$sheet->row($total_rowx, $DICC['total'] );

$sheet->cells("b{$total_rowx}:e{$total_rowx}", function($cells) {
    
    $cells->setFontColor('#00538C');
    $cells->setBorder('solid', 'none', 'solid', 'none');
    // Set alignment to center
    $cells->setAlignment('right');
    $cells->setFont(array(
        'bold'       =>  true
    ));

});

$sheet->cells("A{$DICC['next']}:D{$DICC['number']}", function($cells) {

    $cells->setAlignment('left');

});

// Set height for multiple rows
$sheet->setHeight(array(
    $next_row2  => 40,
    $next_row   => 40,
));