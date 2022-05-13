<? 
$inn = $_POST['inn'];
$company = $_POST['company'];
$DATE1 = $_POST['date_start'];
$DATE2 = $_POST['date_end'];
$type = $_POST['type'];
$dep = $_POST['dep'];
$status = $_POST['status'];
$result = $_POST['result'];

 include('GET_REPORT_LK.php');
 include('connect.php');
 include('createXML.php');
     //1 Часть: запись в файл
 include("PHPExcel-1.8/Classes/PHPExcel.php");
 //Создание объекта класса библиотеки
  $objPHPExcel = new PHPExcel();
 //Указываем страницу, с которой работаем
 $objPHPExcel->setActiveSheetIndex(0);
 //Получаем страницу, с которой будем работать
 $active_sheet = $objPHPExcel->getActiveSheet();
 //Создание новой страницы(пример)
 $objPHPExcel->createSheet();
 
 $active_sheet->getPageSetup()
     ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
 $active_sheet->getPageSetup()
     ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
     
 //Имя страницы
 $active_sheet->setTitle("Список пациентов");
 //Стили параметров/шапки
 $style = array(
     'font' => array(
         'name'      => 'Times New Roman',
         'size'      => 16,   
         'bold'      => true,
         'italic'    => true,
     )
 );
 $style1 = array(
    'font' => array(
        'name'      => 'Times New Roman',
        'size'      => 10,   
        'bold'      => true,
        'italic'    => true,
    )
);  
 $active_sheet->getStyle('A1')->applyFromArray($style);
 $active_sheet->getStyle('A2')->applyFromArray($style1);
 
 //Стили колонок
 $active_sheet->getStyle("A6:F6")->getFont()->setBold(true);
 $active_sheet->getStyle("A6:F6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 $active_sheet->getStyle("A6:F6")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
 
 //Ширина стобцов
 $active_sheet->getColumnDimension("A")->setWidth(25);
 $active_sheet->getColumnDimension("B")->setWidth(30);
 $active_sheet->getColumnDimension("C")->setWidth(10);
 $active_sheet->getColumnDimension("D")->setWidth(10);
 $active_sheet->getColumnDimension("E")->setWidth(15);
 $active_sheet->getColumnDimension("F")->setWidth(30);
 
 //Объединение ячеек
 $active_sheet->mergeCells('A1:C1');
 $active_sheet->mergeCells('A2:C2');
 $active_sheet->mergeCells('A4:C4');      
 
 //Высота строки
 $active_sheet->getRowDimension('1')->setRowHeight(25);
 
 //Вставить данные
 $active_sheet->setCellValueByColumnAndRow(0, 1,$company);
 $active_sheet->setCellValueByColumnAndRow(0, 2,'Учет прохождения предварительного/периодического медицинского осмотра');
 $active_sheet->setCellValueByColumnAndRow(0, 4, 'Период прохождения с '.$DATE1.' по '.$DATE2);
 $active_sheet->setCellValue('A6', 'ФИО');
 $active_sheet->setCellValue('B6', 'Подразделение');
 $active_sheet->setCellValue('C6', 'Дата начала осмотра');
 $active_sheet->setCellValue('D6', 'Дата закрытия осмотра');
 $active_sheet->setCellValue('E6', 'Вид осмотра');
 $active_sheet->setCellValue('F6', 'Заключение');

 
    $RowCount = count($tableResult);
    //Рамка таблицы
    $border = array(
      'borders'=>array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array('rgb' => '000000')
        )
      )
    );
     
    $active_sheet->getStyle("A6:F".($RowCount+6))->applyFromArray($border);
    $active_sheet->getStyle("A6:F".($RowCount+6))->getFont()->setSize(9);
    $active_sheet->getStyle("A6:F".($RowCount+6))->getAlignment()->setWrapText(true);
    // заполняем таблицу
    $ColNum = 0;
    $RowNum = 7;
    foreach ($tableResult as $k => $v) {
        foreach($v as $q => $w) {
            $active_sheet->setCellValueByColumnAndRow($ColNum, $RowNum, $w);
            $ColNum = $ColNum + 1;
        }
   $ColNum = 0; 
   $RowNum = $RowNum + 1;

   }
    $xmlInput->close();


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

ob_start();
$objWriter->save("php://output");

$xlsData = ob_get_contents();
ob_end_clean();
$response =  array(
        'op' => 'ok',
        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
        'test'=> base64_encode($xlsData)
    );
die(json_encode($response));
?>