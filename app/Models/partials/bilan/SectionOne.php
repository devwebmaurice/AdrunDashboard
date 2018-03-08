<?php

   $FICC       = $this->getFlImpCliTDC();
   
   $this->imp  = $FICC['total'][2];
   $this->clk  = $FICC['total'][3];
   $this->rate = $FICC['total'][4];

   $sheet->cell('B9', function($cell) {
       // manipulate the cell
       $cell->setValue('Global');
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '17',
           'bold'       =>  true
       ));

       $cell->setFontColor('#4B88C7');
       $cell->setAlignment('left');

   });


   $sheet->cell('B10', function($cell) {
       // manipulate the cell
       $cell->setValue('IMPRESSIONS');
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#AFD8E6');
       $cell->setBackground('#00538C');
       $cell->setAlignment('left');

   });

   $sheet->cell('C10', function($cell) {
       // manipulate the cell
       $cell->setValue('CLICS');
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#AFD8E6');
       $cell->setBackground('#3953A4');
       $cell->setAlignment('left');

   });

   $sheet->cell('D10', function($cell) {
       // manipulate the cell
       $cell->setValue('VISITEURS UNIQUES');
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#00538C');
       $cell->setBackground('#4C86C6');
       $cell->setAlignment('left');

   });

   $sheet->cell('E10', function($cell) {
       // manipulate the cell
       $cell->setValue('RÉPÉTITION');
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#00538C');
       $cell->setBackground('#AED8E6');
       $cell->setAlignment('left');

   });


   $sheet->cell('B11', function($cell) {
       // manipulate the cell
       $cell->setValue($this->imp);
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#FFFFFF');
       $cell->setBackground('#00538C');
       $cell->setAlignment('center');
       $cell->setValignment('center');

   });

   $sheet->cell('C11', function($cell) {
       // manipulate the cell
       $cell->setValue($this->clk);
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#FFFFFF');
       $cell->setBackground('#3953A4');
       $cell->setAlignment('center');
       $cell->setValignment('center');

   });

   $sheet->cell('D11', function($cell) {

       $vu = preg_replace('/\s+/u', '', $this->extra['vu']);


       // manipulate the cell
       $cell->setValue( number_format($vu, 0 ," "," ") );
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#FFFFFF');
       $cell->setBackground('#4C86C6');
       $cell->setAlignment('center');
       $cell->setValignment('center');

   });

   $sheet->cell('E11', function($cell) {

       $vu        = (int) preg_replace('/\s+/u', '', $this->extra['vu']);
       $this->imp = str_replace(' ','',$this->imp);
       $rep = $this->imp / (int) $vu;

       // manipulate the cell
       $cell->setValue( number_format( $rep , 2 ,"."," ") );
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '15',
           'bold'       =>  true
       ));

       $cell->setFontColor('#FFFFFF');
       $cell->setBackground('#AED8E6');
       $cell->setAlignment('center');
       $cell->setValignment('center');

   });
    
    $sheet->mergeCells( 'B14:E14' );
    
    $sheet->cell('B14', function($cell) {
       // manipulate the cell
       $cell->setValue('Impression/Clic Par Flight');
       // Set font
       $cell->setFont(array(
           'family'     => 'Calibri',
           'size'       => '17',
           'bold'       =>  true
       ));

       $cell->setFontColor('#4B88C7');
       $cell->setAlignment('left');

    });

    //WEBSITE BANNER
    $sheet->cells("B16:E16", function($cells) {

        $cells->setValignment('center');
        $cells->setFontColor('#00538C');
        $cells->setBackground('#F0F8FF');
        $cells->setFont(array( 'bold' => true ));

    });

   $sheet->row(16, array('','FLIGHT', 'IMPRESSIONS','CLICS', 'TAUX DE CLICS') );

   $sheet->fromArray( $FICC['data'], NULL, 'A17',FALSE,FALSE );

   $sheet->cells("B{$FICC['number']}:E{$FICC['number']}", function($cells) {
       
       $cells->setFontColor('#00538C');
       $cells->setBorder('solid', 'none', 'solid', 'none');
       // Set alignment to center
       $cells->setAlignment('right');

       $cells->setFont(array(
           'bold'       =>  true
       ));

   });

   $sheet->row( $FICC['number'], $FICC['total'] );

