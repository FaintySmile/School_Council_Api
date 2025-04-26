<?
require_once('../../libs/PHPExcel/PHPExcel.php');
require_once("../objects/record.php");
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
$record = new Record;

$filename = "result.xlsx";

// create php excel object
$doc = new PHPExcel();

// set active sheet 
$doc->setActiveSheetIndex(0);
$result = $record->getTop();
$top = $result['payload']['top'];
// read data to active sheet
$doc->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);

$doc->getActiveSheet()->setCellValue("A1", 'Топ шаныраков');
$doc->getActiveSheet()->setCellValue("A2", 'Место');
$doc->getActiveSheet()->setCellValue("B2", 'Шанырак');
$doc->getActiveSheet()->setCellValue("C2", 'Очков');
$doc->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(
    PHPExcel_Style_Fill::FILL_SOLID);
$doc->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setRGB('00FFFF');
$doc->getActiveSheet()->getStyle('B2')->getFill()->getStartColor()->setRGB('FF99CC');
$doc->getActiveSheet()->getStyle('C2')->getFill()->getStartColor()->setRGB('FFFF99');


$doc->getActiveSheet()->getStyle('A1')->getFill()->setFillType(
    PHPExcel_Style_Fill::FILL_SOLID);
$doc->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

// Объединяем ячейки
$doc->getActiveSheet()->mergeCells('A1:C1');

// Выравнивание текста
$doc->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(
    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$doc->getActiveSheet()->getStyle('A2:C2')->getAlignment()->setHorizontal(
    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

foreach($top as $key=>$value){
    $doc->getActiveSheet()->setCellValue('A'.($key+3), $key+1);
    $doc->getActiveSheet()->setCellValue('B'.($key+3), $value['name']);
    $doc->getActiveSheet()->setCellValue('C'.($key+3), $value['points']);
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0'); //no cache
// clean data
ob_end_clean();
//NEW EXCEL 
$objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel2007');

//force user to download the Excel file without writing it to server's HD
$objWriter->save('php://output');
header('Location: /dashboard')
?>