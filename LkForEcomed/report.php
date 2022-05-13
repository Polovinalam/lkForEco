<?
ob_clean();
    $inn = $_POST['inn'];
    $DATE1 = $_POST['date_start'];
    $DATE2 = $_POST['date_end'];
    $type = $_POST['type'];
    $dep = $_POST['dep'];
    $status = $_POST['status'];
    $result = $_POST['result'];
    include('GET_REPORT_LK.php');
    include('connect.php');
    if (!$fp || $errno != 0){
        return FALSE; // Подключение не удалось
    }
    else { 
    include('createXML.php');
    
            // Закрываем документ
            $RowCount = count($tableResult);
            echo '<p class="count-hum" id ="1"><b>Количество сотрудников: </b>'.$RowCount.'</p>';
            if ($RowCount > 0) {
                //////////////////////////////////////////////////////////////////////
                // строим шапку
                echo '<div class=table-block>';
                echo '<table id="sortable" class="table_theme" >';
                foreach ($tableResult as $k => $v) {
                    echo '<thead> ';
                    echo '<tr >';
                    $val = 0;
                    foreach($v as $q => $w) {
                        switch ($q) {
                            case "FIO":
                                $q = 'ФИО';
                                $val = $val + 1;
                                break;
                            case "KAGENT":
                                $q = 'Подразделение';
                                $val = $val + 1;
                                break;
                            case "BGNDATE":
                                $q = 'Дата начала осмотра';
                                $val = $val + 1;
                                $datatype = 'data-type="date"';
                                break;
                            case "CLOSEDATE":
                                $q = 'Дата закрытия осмотра';
                                $val = $val + 1;
                                $datatype = 'data-type="date"';
                                break;
                            case "VID":
                                $q = 'Вид осмотра';
                                $val = $val + 1;
                                break;
                            case "NOTE":
                                $q = 'Заключение';
                                $val = $val + 1;
                                break;
                        } 
                        echo '<th id="'.$val.'" ' .$datatype.'>'.$q.'</th>';
                         $datatype = '';
                    }
                    echo '</thead>';
                    break;
                echo '</tr>';
                }
            
            // заполняем таблицу
            foreach ($tableResult as $k => $v) {
                echo '<tr>';
                foreach($v as $q => $w) {
                    echo '<td>' . $w . '</td>';
                    $ColNum = $ColNum + 1;
                }
            echo '</tr>';
            $RowNum = $RowNum + 1;
                }
            echo '</table>';
            echo '</div>';

        }
        else {
            echo '<p id="Empty" class="count-hum">За выбранный период, работники, проходившие осмотр, отсутствуют!</p>';
        }
        function ch($a){
            if ($a & 1)
            {return ' class="tr_d"';}
            else{return ' class="tr_ld"';}
        }
            $xmlInput->close();
                return TRUE;
    }
    ?>