<?php 
	class vsettings {
		function __construct(){
			echo "<h1>Настройки</h1>";
			// setcookie('settings');
			$form = '<form method="post" action=./submit.php?settings=true><p>Период отображения:</p>Начало <input name="start_date" type="text" id="datepicker1"> Конец <input name="end_date" type="text" id="datepicker2">';
			$form .= ' <input type="submit" value="Сохранить"></form>';
			echo $form;
		}
	}
?>