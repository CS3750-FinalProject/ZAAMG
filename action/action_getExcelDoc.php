<?php


require_once '../Classes/PHPExcel.php';
require_once '../Database.php';
require_once '../Professor.php';
require_once '../Section.php';


session_start();

$db = new Database();
$dbh = $db->getdbh();

$styleArray_HEADER = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'size'  => 12,
        'name'  => 'Arial'
));


$styleArray_BORDER_OUTLINE = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);

$styleArray_BORDER_TOP = array(
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);

$styleArray_BORDER_INSIDE = array(
    'borders' => array(
        'inside' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF99a3a4 '),
        ),
    ),
);

$styleArray_BORDER_TOP_THIN = array(
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF99a3a4 '),
        ),
    ),
);

$styleArray_BORDER_RIGHT_THIN = array(
    'borders' => array(
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF99a3a4 '),
        ),
    ),
);

$styleArray_BORDER_LEFT_THIN = array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF99a3a4 '),
        ),
    ),
);

$styleArray_SMALLER = array(
    'font'  => array(
        'color' => array('rgb' => '000000'),
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'size'  => 10,
        'name'  => 'Arial'
    ));

$styleArray_REGULAR = array(
    'font'  => array(
        'color' => array('rgb' => '000000'),
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'size'  => 12,
        'name'  => 'Arial'
    ));


$objPHPExcel = new PHPExcel();
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getRowDimension()->setRowHeight(16.5);

$sheet->getPageMargins()->setTop(0.75);
$sheet->getPageMargins()->setRight(0.25);
$sheet->getPageMargins()->setLeft(0.25);
$sheet->getPageMargins()->setBottom(0.75);


$sheet->setTitle("CS FTF Sp 2017");
$sheet->getStyle('A1:N1')->applyFromArray($styleArray_HEADER);
$sheet->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('N1')->getFont()->setSize('9');

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);


setColumnWidths($sheet);
mergeCells($sheet);


$sheet->setCellValue('A1', 'INSTRUCTOR');
$sheet->setCellValue('B1', 'COURSE');
$sheet->setCellValue('D1', 'CRN');
$sheet->setCellValue('E1', 'HOURS');
$sheet->setCellValue('G1', 'DAYS');
$sheet->setCellValue('H1', 'ROOM');
$sheet->setCellValue('I1', 'MAX');
$sheet->setCellValue('J1', 'HRS');
$sheet->setCellValue('K1', 'CAMP');
$sheet->setCellValue('L1', 'Pay');
$sheet->setCellValue('M1', 'Load/Ovld');
$sheet->setCellValue('N1', 'Hrs Req');

$sheet->getStyle('A1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('B1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('D1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('E1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('G1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('H1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('I1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('J1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('K1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('L1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('M1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);
$sheet->getStyle('N1')->applyFromArray($styleArray_BORDER_RIGHT_THIN);



$col = 0;
$row = 2;

$profs = $db->getAllProfessors('prof_last');

foreach($profs as $prof){
    $first = $prof->getProfFirst();
    $last = $prof->getProfLast();
    $req = $prof->getProfRequiredHours();
    $secs = $db->getProfSections($prof, $_SESSION['mainSemesterId']);

    $startRow = $row;

    $sheet->freezePane('N2');                                               //freeze top row
    $sheet->setCellValueByColumnAndRow($col++, $row, $last.', '.$first);    //put Professor's name
    $sheet->getStyleByColumnAndRow($col-1, $row)                            //format Professor's name
        ->getAlignment()->setWrapText(true);
    $sheet->getStyleByColumnAndRow($col-1, $row)
        ->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
    $sheet->setCellValueByColumnAndRow(13, $row, $req);                     //put required hours
    $sheet->getStyleByColumnAndRow(13, $row)->applyFromArray($styleArray_REGULAR);  //format required hours
    $sheet->getStyleByColumnAndRow($col-1,$row,$col+12,$row)                //put top border on Professor line
        ->applyFromArray($styleArray_BORDER_TOP);
    $sheet->getStyleByColumnAndRow($col-1, $row)
        ->applyFromArray($styleArray_HEADER);

    if (count($secs) > 0){
        foreach($secs as $index=>$sec){
            $prefix = $sec->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID');
            $number = $sec->getSectionProperty('course_number', 'Course', 'course_id', 'courseID');
            $days = $sec->getDayString_toUpper();
            $days = $days == 'O' ? 'ONL' : $days;
            $start = $sec->getStartTime();
            $end = $sec->getEndTime();
            $cap = $sec->getCapacity();
            $credits = $sec->getSectionProperty('course_credits', 'Course', 'course_id', 'courseID');
            $room = $sec->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID');
            $campus = $sec->getSectionProperty_Join_4('campus_name', 'Classroom', 'Building', 'Campus',
                'classroom_id', 'building_id', 'campus_id', 'classroomID');
            $campus = substr($campus,0,3);


            if ($index > 0){
                $sheet->setCellValueByColumnAndRow($col++, $row, '');       //blank space under professor name
            }

            $sheet->setCellValueByColumnAndRow($col++, $row, $prefix);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValueByColumnAndRow($col++, $row, $number);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValueByColumnAndRow($col++, $row, '');
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValueByColumnAndRow($col++, $row, $start);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValueByColumnAndRow($col++, $row, $end);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValueByColumnAndRow($col++, $row, $days);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValueByColumnAndRow($col++, $row, $room);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $sheet->setCellValueByColumnAndRow($col++, $row, $cap);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValueByColumnAndRow($col++, $row, $credits);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValueByColumnAndRow($col++, $row, $campus);
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValueByColumnAndRow($col++, $row, '');
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValueByColumnAndRow($col++, $row, '');
            $sheet->getStyleByColumnAndRow($col-1, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            //formatting:
            $sheet->getStyleByColumnAndRow(1,$row,3,$row)->applyFromArray($styleArray_REGULAR);
            $sheet->getStyleByColumnAndRow(4,$row,7,$row)->applyFromArray($styleArray_SMALLER);
            $sheet->getStyleByColumnAndRow(8,$row,12,$row)->applyFromArray($styleArray_REGULAR);

            $col=0;
            if ($index+1 == count($secs)){
                $row+=2;
            }
            else{
                $row++;
            }
        }
    }else{   //if there are no sections for this professor
        $row += 2;
        $col = 0;
    }
    $sheet->mergeCellsByColumnAndRow($col, $startRow, $col, $row-1);
    $sheet->getStyleByColumnAndRow($col, $startRow, $col, $row-1)->applyFromArray($styleArray_BORDER_RIGHT_THIN);
    $sheet->mergeCellsByColumnAndRow(13, $startRow, 13, $row-1);
    $sheet->getStyleByColumnAndRow(13, $startRow, 13, $row-1)->applyFromArray($styleArray_BORDER_LEFT_THIN);
    $sheet->getStyleByColumnAndRow(13, $startRow)
        ->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
    $sheet->getStyleByColumnAndRow(13, $startRow)
        ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyleByColumnAndRow(1,$startRow, 12, $row-2)->applyFromArray($styleArray_BORDER_INSIDE);

    $sheet->getStyleByColumnAndRow(1,$row-1,12,$row-1)->applyFromArray($styleArray_BORDER_TOP_THIN);
    $sheet->getStyleByColumnAndRow(1,$row-1,12,$row-1)->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFFF');
}

$highestRow = $sheet->getHighestDataRow();
$sheet->getStyle('A1:A'.$highestRow)->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFD9D9D9');
//$sheet->getStyleByColumnAndRow(0,$highestRow+1,13,$highestRow+2)
  //  ->applyFromArray($styleArray_BORDER_TOP);
$sheet->getStyleByColumnAndRow(0,1,13,$highestRow)
    ->applyFromArray($styleArray_BORDER_OUTLINE);
//$sheet->getPageSetup()->setPrintAreaByColumnAndRow(0,1,13,$highestRow);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="FinalFormatZAAMG.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');








/**
 * @param $sheet
 */
function setColumnWidths($sheet)
{
    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('B')->setWidth(5.67);
    $sheet->getColumnDimension('C')->setWidth(6.83);
    $sheet->getColumnDimension('D')->setWidth(8.5);
    $sheet->getColumnDimension('E')->setWidth(9.5);
    $sheet->getColumnDimension('F')->setWidth(9.5);
    $sheet->getColumnDimension('G')->setWidth(6.67);
    $sheet->getColumnDimension('H')->setWidth(11.83);
    $sheet->getColumnDimension('I')->setWidth(6);
    $sheet->getColumnDimension('J')->setWidth(6);
    $sheet->getColumnDimension('K')->setWidth(8.5);
    $sheet->getColumnDimension('L')->setWidth(9);
    $sheet->getColumnDimension('M')->setWidth(15);
    $sheet->getColumnDimension('N')->setWidth(8);
}


/**
 * @param $sheet
 */
function mergeCells($sheet)
{
    $sheet->mergeCells('B1:C1');
    $sheet->mergeCells('E1:F1');
}