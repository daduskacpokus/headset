<html>
    <head>
        <meta charset="UTF-8">
        <!-- защита от XSS -->
        <meta http-equiv="X-XSS-Protection" content="0">        
        <!-- <meta http-equiv="Content-Security-Policy" content="default-src 'self';"> -->
        <!-- X-Frame -->
        <meta http-equiv="X-Frame-Options: DENY">
        <link href="css/style.css" rel="stylesheet" type='text/css'>
        <link href='css/print.css' rel='stylesheet' type='text/css' media='print'>
        <!-- <script type="text/javascript" src="lib/js/jquery-2.2.4.min.js"></script>   -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script>
            function getCookie(name) {
              var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
              ));
              return matches ? decodeURIComponent(matches[1]) : undefined;
            }
            $(function() {
                $( "#datepicker1" ).datepicker();
                $( "#datepicker1" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
                $( "#datepicker1" ).datepicker( "setDate", getCookie("start_date"));
            });
        </script>
        <script>
            $(function() {
                $( "#datepicker2" ).datepicker();
                $( "#datepicker2" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
                $( "#datepicker2" ).datepicker( "setDate", getCookie("end_date") );                
            });
        </script>
    </head>
    <body>
        <div id="header">
            <ul class="header no_print">
                <li class="menu"><a href="/?rotate=true">Перемещение</a></li>
                <li class="menu"><a href="/?supply=true">Приём</a></li>
                <li class="menu"><a href="/?writeoff=true">Выдача</a></li>            
                <li class="menu"><a href="/?stat=true">Статистика</a></li>            
                <li class="menu"><a href="/?search=true">Поиск</a></li>            
                <li class="menu"><a href="/?barcode=true">Печать ШК</a></li>            
                <li class="menu"><a href="/?settings=true">Настройки</a></li>            
                <li class="menu"><a class="help" href="/help.php">Помощь</a></li>            
            </ul>