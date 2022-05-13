<?
    // Открываем TCP-сокет (подключение к XML-Серверу, вместо 192.168.0.1:9999 указать реальный адрес XML-Сервера)
    $fp = @stream_socket_client("192.168.0.1:9999", $errno, $errstr, 10);
    if (!$fp || $errno != 0){
        echo '<p class="srv-connect">Отсутствет связь с сервером<p>';
        echo '<p class="srv-connect_mess">Пожалуйста,обратитесь к администратору.<p>';
        return FALSE; // Подключение не удалось
    }    
    else {
        // Передаем данные
        $xmlData .= "\r\n";
        for ($written = 0; $written < strlen($xmlData); $written += $fwrite) { //если поставить <= то при $line=fgets($fp, 1); перестает выводить единицу
            $fwrite = fwrite($fp, substr($xmlData, $written));
        }
    }          
    while (strpos($XMLResult, "\r\n") === FALSE)
        $XMLResult .= fgets($fp, 512);
    // // // Закрываем подключение
    fclose($fp);

    // // Убираем лишние символы из ответа
    while (strpos($XMLResult, "\r") === 0) $XMLResult = substr($XMLResult, 1);
    while (strpos($XMLResult, "\n") === 0) $XMLResult = substr($XMLResult, 1);
?>