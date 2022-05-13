<?
$XMLCmd = new XMLWriter();
$XMLCmd->openMemory();
$XMLCmd->setIndent(false);
$XMLCmd->startDocument('1.0', 'utf-8');
$XMLCmd->startElement("CMD");
$XMLCmd->writeAttribute("name","GET_STRUCT_UNIT");

function xmlParam($XMLCmd,$item,$name){
    $XMLCmd->startElement("PARAM");
    $XMLCmd->writeAttribute("name","$name");
    $XMLCmd->text("$item");
    $XMLCmd->EndElement();  
}
xmlParam($XMLCmd,$inn,'INN');
$XMLCmd->fullEndElement();
$xmlData = $XMLCmd->outputMemory(true);
$XMLCmd->endDocument();
?>