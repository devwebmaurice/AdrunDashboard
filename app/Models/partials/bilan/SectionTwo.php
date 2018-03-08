<?php

$next_row     = $FICC['number'] + 5 ;
$banner       = $FICC['number'] + 4 ;
$new_next_row = $FICC['number'] + 3 ;

//SECTION TWO SETTING START 
$sheet->mergeCells( 'B'.$new_next_row.':E'.$new_next_row );
$sheet->setHeight(array( $banner =>  3 ));

//DISPLAY/STYLE Impression/Clic par flight Row
$sheet->cell('B'.$new_next_row, function($cell) {
    // manipulate the cell
    $cell->setValue('Impression/Clic Par Website');
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
$sheet->cell('B' . $banner, function($cell) { $cell->setBackground('#00538C'); });
$sheet->cell('C' . $banner, function($cell) { $cell->setBackground('#3953A4'); });
$sheet->cell('D' . $banner, function($cell) { $cell->setBackground('#4C86C6'); });
$sheet->cell('E' . $banner, function($cell) { $cell->setBackground('#AED8E6'); });
//BANNER SEPERATOR

//WEBSITE BANNER
$sheet->cells("B{$next_row}:E{$next_row}", function($cells) {

    $cells->setValignment('center');
    $cells->setFontColor('#00538C');
    $cells->setBackground('#F0F8FF');
    $cells->setFont(array( 'bold' => true ));

});

$sheet->row($next_row, array('','WEBSITE', 'IMPRESSIONS','CLICS', 'TAUX DE CLICS') );
//WEBSITE BANNER

$WICC      = $this->getWeImpCliTDC($next_row, $FICC['xxx'],$FICC['i_total'],$FICC['c_total'],$FICC['tx_percentage']);
$total_row = $WICC['number'] + 1;

$sheet->rows( $WICC['data'], NULL, "A{$WICC['next']}",FALSE,FALSE );

$sheet->row($total_row, $WICC['total'] );

$sheet->cells("B{$total_row}:E{$total_row}", function($cells) {

    $cells->setBorder('solid', 'none', 'solid', 'none');
    $cells->setFontColor('#00538C');
    // Set alignment to center
    $cells->setAlignment('right');
    $cells->setFont(array(
        'bold'       =>  true
    ));

});

$sheet->cells("A{$WICC['next']}:D{$WICC['number']}", function($cells) {

    $cells->setAlignment('left');

});

//SECTION TWO SETTING END
