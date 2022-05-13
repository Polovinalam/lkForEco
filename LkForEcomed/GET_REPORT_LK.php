<?
$XMLCmd = new XMLWriter();
$XMLCmd->openMemory();
$XMLCmd->setIndent(false);
$XMLCmd->startDocument('1.0', 'utf-8');
$XMLCmd->startElement("CMD");
$XMLCmd->writeAttribute("name","GET_REPORT_LK");

function xmlParam($XMLCmd,$item,$name){
    $XMLCmd->startElement("PARAM");
    $XMLCmd->writeAttribute("name","$name");
    $XMLCmd->text("$item");
    $XMLCmd->EndElement();  
}
xmlParam($XMLCmd,$inn,'INN');
xmlParam($XMLCmd,$DATE1,'DATE1');
xmlParam($XMLCmd,$DATE2,'DATE2');
xmlParam($XMLCmd,$type,'TYPE');
xmlParam($XMLCmd,$dep,'DEP');
xmlParam($XMLCmd,$status,'STATUS');
xmlParam($XMLCmd,$result,'RESULT');

$XMLCmd->fullEndElement();
$xmlData = $XMLCmd->outputMemory(true);
$XMLCmd->endDocument();
?>