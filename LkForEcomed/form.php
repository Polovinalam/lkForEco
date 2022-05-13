<head>
  <link id='theme-link'rel="stylesheet" type="text/css" href="/page/lk-form/style/style-lk_light.css">
    <script>
        function getdetails(){
            var inn = <?echo LK_USER_LOGIN;?>;
            var date_start = $('[name="date_start"]').val();
            var date_end = $('[name="date_end"]').val();
            var type = $('[name="type"]').val();
            var dep =$('[name="cat"]').val();
            var status = $('[name="status"]').val();
            var result = $('[name="result"]').val();
            var loader = `<div class="loader" >
                                <div id="ld4">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="load_text">Подождите, пожалуйста...</div>
                         </div>`;
             $('#msg').html(loader);
            $.ajax({
                type: "POST",
                url: "/report",
                data: {inn:inn,
                    date_start:date_start,
                    date_end:date_end,
                    type:type,
                    dep:dep,
                    status:status,
                    result:result
                    }
            }).done(function(data) {
                    $('#msg').html(data);
                    const empty = document.getElementById('Empty');
                    if (empty == null){
                            const table = document.getElementById('sortable');
                            const headers = table.querySelectorAll('th');
                            const tableBody = table.querySelector('tbody');
                            const rows = tableBody.querySelectorAll('tr');
                            // Направление сортировки
                            const directions = Array.from(headers).map(function(header) {
                                return '';
                            });
                            // Преобразовать содержимое данной ячейки в заданном столбце
                            const transform = function(index, content) {
                                // Получить тип данных столбца
                                const type = headers[index].getAttribute('data-type');
                                switch (type) {
                                    case 'number':
                                        return parseFloat(content);
                                    case 'string':
                                    default:
                                        return content;
                                }
                            };
                                const sortColumn = function(index) {
                                // Получить текущее направление
                                const direction = directions[index] || 'asc';
                                // Фактор по направлению
                                const multiplier = (direction === 'asc') ? 1 : -1;
                                const newRows = Array.from(rows);
                                newRows.sort(function(rowA, rowB) {
                                    const cellA = rowA.querySelectorAll('td')[index].innerHTML;
                                    const cellB = rowB.querySelectorAll('td')[index].innerHTML;
                                    const a = transform(index, cellA);
                                    const b = transform(index, cellB);    
                                    switch (true) {
                                        case a > b: return 1 * multiplier;
                                        case a < b: return -1 * multiplier;
                                        case a === b: return 0;
                                    }
                                });
                        
                                // Удалить старые строки
                                [].forEach.call(rows, function(row) {
                                    tableBody.removeChild(row);
                                });
                                // Поменять направление
                                directions[index] = direction === 'asc' ? 'desc' : 'asc';
                                // Добавить новую строку
                                newRows.forEach(function(newRow) {
                                    tableBody.appendChild(newRow);
                                });
            
                                const wrapObj = document.querySelector('thead tr');
                                for(let i = 0;i<wrapObj.children.length;i++){
                                                wrapObj.children[i].classList.remove('asc')
                                                wrapObj.children[i].classList.remove('desc');
                                }
                                    wrapObj.children[index].classList.add(direction);
                            };

                            [].forEach.call(headers, function(header, index) {
                                header.addEventListener('click', function() {
                                    sortColumn(index);
                                });
                            });
                        }
                });
        }

        function downloadReport() {
            var inn = <?echo LK_USER_LOGIN;?>;
            var company = <?echo '\''.html_entity_decode(LK_USER_COMPANY).'\'';?>;
            var date_start = $('[name="date_start"]').val();
            var date_end = $('[name="date_end"]').val();
            var type = $('[name="type"]').val();
            var dep =$('[name="cat"]').val();
            var status = $('[name="status"]').val();
            var result = $('[name="result"]').val();
            var page='/download';
            $.ajax({
                type: "POST",
                url: page,
                data: {inn:inn,
                    company:company,
                    date_start:date_start,
                    date_end:date_end,
                    type:type,
                    dep:dep,
                    status:status,
                    result:result                    
                    },
                dataType:'json'
                }).done(function(data) {   
                    var dt = new Date();
                    var day = dt.getDate();
                    var month = (dt.getMonth() + 1) < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
                    var year = dt.getFullYear();
                    var hour = dt.getHours();
                    var mins = dt.getMinutes();
                    var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
                    var a = document.createElement('a');
                    a.href = data.file;
                    a.download = 'report_' + postfix + '.xls';
                    a.click();
                });
        }
    </script> 
    
</head>
<body>
    <div class="center">
        <div class="block-table-theme">
            <div class="btn_table-theme" type="button"></div>
        </div>
                <form id="bildReport" class="lk-form" method="post" >
                    <div id="param" class="lk-col">
                    <div class="lk-col-head lk-col-head1">Период прохождения</div>
                    <div class="lk-input-small">
                        <div class="lk-label">с</div>
                        <input type="text" class="input lk-input datepicker"  value=<?echo dateDefault(1);?>
                        <?if ($_POST['date_start'] != null) {echo 'value=' . $_POST['date_start'];}?>
                        name="date_start" required>
                    </div>
                    
                        <div class="lk-input-small lk-input-small2">
                            <div class="lk-label">до</div>
                                <input type="text" class="input lk-input datepicker" value=<?echo dateDefault(2);?>
                                <?if ($_POST['date_end'] != null) {echo 'value=' . $_POST['date_end'];}?>
                                name="date_end" required>
                            </div>
                            <div class="clear"></div>
                    </div>

                    <div class="lk-col">
                        <div class="lk-col-head">Вид осмотра</div>
                            <select class="input lk-select" name="type" value="предварительный">	
                                <option value="0">Не выбран</option> 
                                <option value="предварительный">Предварительный</option>
                                <option value="Периодический">Периодический</option>
                            </select>
                            <div class="lk-col-desc">Периодическое предварительное псих.осведетельственность</div>
                    </div>
                    <div class="lk-col">
                        <div class="lk-col-head">Подразделение</div>
                        <select class="input lk-select" name="cat">
                            <option value="0" selected="selected">Не выбрано</option>
                        </select>
                        <div class="lk-col-desc">Отдел филиал и т.д.</div>
                    </div>
                    <div class="lk-col">
                        <div class="lk-col-head">Статус прохождения</div>
                        <select class="input lk-select" name="status">
                            <option value="0">Не выбран</option>
                            <option value="1">Прошедшие</option>
                            <option value="2">Должники</option>
                        </select>
                        <div class="lk-col-desc">Прошедшие/не прошедшие/должники</div>
                    </div>
                    <div class="lk-col lk-col-last">
                        <div class="lk-col-head">Результат осмотра</div>
                        <select class="input lk-select" name="result">
                            <option value="0">Не выбран</option>
                            <option value="1">Годен</option>
                            <option value="2">Не годен</option>
                            <option value="3">Осмотр не завершен</option>
                        </select>
                        <div class="lk-col-desc">Допущены<br>не допущены</div>
                    </div>
                    <div class="clear lk-clear"></div>
                    <div class="lk-btn-row">
                            <div class="lk-btn-wrap">
                                <input id="btn_rep" type="button" onClick = "getdetails()" class="lk-btn lk-btn-list" name="button1" value="Сформировать отчет">
                                <input type="reset" class="lk-btn lk-btn-list lk-btn-clear" value="Сбросить параметры">
                            </div>
                                <div class="lk-btn-wrap">
                                    <input id="btn-xls" type="button" onClick= "downloadReport()" class="btn-xls" name="button2"  value="Скачать отчёт" >
                            </div>
                        </div>    
                    </form> 
                <div id="msg"></div>
                <div  id="menu">
                    <a id="up" class="scrollup"  href="#param">К началу списка</a>
                </div>
    </div>        
</body>
<script>
    function getParamDep() {
            var inn = <?echo LK_USER_LOGIN;?>;
            var page = '/getStruct';
            $.ajax({
                type: 'POST',
                url: page,
                data: {
                    inn: inn
                }
            }).done(function(result){
               $('[name="cat"]').html(result);
            });
        }
        getParamDep();
</script>
<script>
            const btn = document.querySelector(".btn_table-theme");
            const theme = document.querySelector("#theme-link");
            const currentTheme = localStorage.getItem("theme");
            if (currentTheme == "https://profmedosmotr.ru/page/lk-form/style/style-lk_dark.css"){
                theme.href = "/page/lk-form/style/style-lk_dark.css";
            }
            btn.addEventListener("click", function() {
            if (theme.getAttribute("href") == "/page/lk-form/style/style-lk_light.css") {
                theme.href = "/page/lk-form/style/style-lk_dark.css";
            } else {
                theme.href = "/page/lk-form/style/style-lk_light.css";
            }
                localStorage.setItem("theme", theme.href);
            });
</script>
<script type="text/javascript">
    $(document).ready(function(){ 
    
    $(window).scroll(function(){
    if ($(this).scrollTop() > 100) {
    $('.scrollup').fadeIn();
    } else {
    $('.scrollup').fadeOut();
    }
    }); 
    
    $('.scrollup').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 600);
    return false;
    });
    
    });
</script>
<?
function dateDefault($val){
    $d1 = date('d.m.Y');
if ($val == 1) {
    $d1 = strtotime('-1 days');
    echo date('d.m.Y',$d1);
}
    else  {echo $d1;};               
}
function valueDisplay($name,$value = null){
    if ($value != null){
        if ($_POST[$name] == $value) 
            {echo "selected";};
    }
    else {echo 'value=' . $_POST['name'];}
}
?>