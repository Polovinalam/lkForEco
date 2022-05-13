<?
        // // Создаем XML-документ ответа
        $xmlInput = new XMLReader();
        $xmlInput->XML($XMLResult, 'utf-8');

        // // Парсим документ, достаем необходимые данные и т.д.
        $command = "";
        $userid = "";
        $isTable = FALSE;
        $tableResult;
        $dataResult;
        $errorText = "";
        $errorCode = "";
        $name = "";
        while ($xmlInput->read())
        {
            if (strtoupper($xmlInput->name) == "ERROR" && $xmlInput->nodeType == XMLReader::ELEMENT) // ошибка обработки
            {
                $errorCode = $xmlInput->getAttribute("code");
                $xmlInput->read();
                $errorText = $xmlInput->value; 
                return FALSE;
            }
            
            if (strtoupper($xmlInput->name) == "RESULT" && $xmlInput->nodeType == XMLReader::ELEMENT) // определим что за команда
            {
                $command = $xmlInput->getAttribute("name");
                $userid =  $xmlInput->getAttribute("userid");
            }
            
            if (strtoupper($xmlInput->name) == "ROW" && $xmlInput->nodeType == XMLReader::ELEMENT) // таблица
            {
                $isTable = TRUE;
                $rownum = $xmlInput->getAttribute("num");
                while (!(($xmlInput->nodeType == XMLReader::END_ELEMENT) && ($xmlInput->name == "ROW")))
                {
                    $xmlInput->read();
                    if ($xmlInput->nodeType == XMLReader::ELEMENT)
                        $name = $xmlInput->name;
                    
                    if ($xmlInput->nodeType == XMLReader::TEXT)
                    {
                        $value = $xmlInput->value;
                        $params[$name] = $value;
                    }
                    
                }
                $tableResult[$rownum] = $params;
            }
            else
            {
                if ($xmlInput->nodeType == XMLReader::ELEMENT)
                    $name = $xmlInput->name;
                
                if ($xmlInput->nodeType == XMLReader::TEXT)
                {
                    $value = $xmlInput->value;
                    $dataResult[$name] = $value;
                }
            }
        }

        if ($isTable == FALSE && $errorText != "")
        {
            return FALSE;
        }
        if ($isTable)
            $result = $tableData;
        else
            $result = $valueData;
?>