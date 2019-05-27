<html>
	<head>
		<meta charset="UTF-8">
		<link href="css/style.css" rel="stylesheet" type='text/css'>
	</head>
	<body>		
		<div id="error">
<?php

switch (TRUE) {
    case $_POST['denied'] !=NULL:
        echo '<h1>Нехорошо</h1>'.
            '<p>Параметры в командную строку зачем-то ввёл ты...</p>';
        break;

    default:
        echo '</div>';
        break;
}
?>
	</body>
</html>