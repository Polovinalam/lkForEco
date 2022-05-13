<?
    $inn = $_POST['inn'];
    include('GET_STRUCT_UNIT.php');
    include('connect.php');
    include('createXML.php');
    echo '<option value="0" selected="selected">Не выбрано</option>';
        foreach ($tableResult as $k => $v) {
            foreach($v as $q => $w) {
                echo '<option>' . $w . "</option>\n";
            }
            }
    
        $xmlInput->close();
            return TRUE;

?>

