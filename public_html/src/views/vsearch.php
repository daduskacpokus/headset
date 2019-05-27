<?php

/**
 * 
 */
class vsearch extends selfprint {

    private $search_model;
    private $haystack = array('rotation', 'supply', 'writeoff');
    private $colunm_title = array(
        0 => array('№ записи', 'Дата', 'Модель', 'Неисправное', 'РМ', 'Модель',
            'Восстановленое', 'РМ'),
        1 => array('№ записи', 'Дата', 'Артикул', 'Модель', 'Категория', 'РМ')
    );

    function __construct() {
        if(session_id()==NULL){
            session_start();//строки результатов
        }

        $header = '</div><div id="content">';
        $header .= '<form class="search" method="post" action="./submit.php?search=true">';
        $header .= 'Артикул: <input autofocus  onfocus="this.select()" type="text" name="needle" placeholder="например, 72F4E" value="' .
                $_GET['needle'] . '" checked>';
        $header .= '<input tabindex="3" type="submit" value="Запрос"></form>';
        echo "<h1>Поиск</h1>";
        echo $header;
        $getlenth = strlen($_GET['needle']);
        if ($getlenth > 0) {
            $this->search_model = new search();
            $matches = $this->search_model->_search_through($_GET['needle']);
            if ($matches > 0) {
                foreach ($this->haystack as $value) {
                    $this->search_model->_set_haystack($value);
                    $search_results .= $this->_result_assembler($value);
                }
            }else{
                echo "<p>Нет результатов</p>";
            }
            echo $search_results;
        }
        echo '</div></body></html>';
        session_destroy();
        // echo "model_search " . " session_id() " . session_id() ;
    }

    /**
     * 
     * @param type $tabname
     * @return type
     */
    private function _result_assembler($tabname) {
        switch ($tabname) {
            case $this->haystack[0]:
                if ($this->search_model->_rows($this->haystack[0]) > 0) {
                    $found .= '<p>Найдено в журнале перемещений</p>';
                    return $found . $this->_painttab($this->haystack[0],$this->colunm_title[0]);
                }
            case $this->haystack[1]:
                if ($this->search_model->_rows($this->haystack[1]) > 0) {
                    $found .= '<p>Найдено в журнале приёма</p>';
                    return $found . $this->_painttab($this->haystack[1],$this->colunm_title[1]);
                }
            case $this->haystack[2]:
                if ($this->search_model->_rows($this->haystack[2]) > 0) {
                    $found .= '<p>Найдено в журнале выдачи</p>';
                    return $found . $this->_painttab($this->haystack[2],$this->colunm_title[1]);
                }
        }
    }

    /**
     * 
     * @param type $colunm_title
     * @return type
     */
    private function _painttab($tabname, $colunm_title) {
        $table = '';
        $table .= parent::_thead($colunm_title);
        $rows = $this->search_model->_rows($tabname);
        $columns = $this->search_model->_columns();
        for ($row = 0; $row < $rows ; $row++) {
            $table .= '<tr>';
            for ($column = 0; $column < $columns; $column++) {
                $tabcell = $this->search_model->_push($row, $column);
                if($tabcell == $_GET['needle']){
                    $tabcell = '<span style="color: red;">'.$tabcell.'</span>';
                }
                $table .= '<td>' . $tabcell . '</td>';
            }
            $table .= '</tr>';
        }
        return $table . '</table>';
    }

}
