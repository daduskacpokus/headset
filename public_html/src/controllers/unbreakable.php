<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model_bookkeeper
 *
 * @author x64386
 */
class unbreakable {

    private $available;
    private $dbhandler;
    private $toogle;
    private static $NOT_FOUND = 0; //"Переместить то чего нет?";
    private static $ACCEPTED = 1;
    private static $USED = 2; //"Уже на рядах?";    
    private static $BUSY = 3; //"Передан в ремонт?";
    private static $UNACCEPTABLE = 4; //"Запчасти
    private static $STORED = 5; //Принять еще раз?


    function __construct($dbhandler) {
        $this->dbhandler = $dbhandler;
    }

    /**
     * Индикатор блокировки приёма/выдачи
     * @return type
     */
    public function _available() {
        return $this->available;
    }

    /**
     * 
     * @param type $needle
     * @param type $toogle чтобы понимать какая страница вызает проверку
     * @return int
     */
    public function _gettrace($needle, $toogle) {
        $this->available = 0;        
        $this->toogle = $toogle;        
        $request = "select * from `trace` where `device`='" . $needle . "';";
        $result = $this->dbhandler->_response($request);

        while ($trace_row = mysqli_fetch_array($result)) {
//            $trace_array[0] = $trace_row['device'];
            $trace_array[1] = $trace_row['storage'];
            $trace_array[2] = $trace_row['state'];
            $trace_array[3] = $trace_row['motion'];
        }
        if (count($trace_array) > 0) {
            $this->available = $this->_sniffit($trace_array[1], $trace_array[2], $trace_array[3]);
        } else {
            switch ($this->toogle) {
                case 'rotate':
                    $this->available = self::$NOT_FOUND;
                    break;
                case 'supply':
                    $this->available = self::$ACCEPTED;
                    break;
                case 'writeoff':
                    $this->available = self::$NOT_FOUND;
                    break;
            }
        }
    }

    /**
     * 
     * @param type $storage
     * @param type $state
     * @param type $motion
     * @return type
     */
    private function _sniffit($storage, $state, $motion) {
        switch ($motion) {
            case 'supply':
                switch ($state) {
                    case 'Новое': return $this->_isIT($storage);
                    case 'Неисправное': 
                        if ($this->toogle == 'writeoff') {
                            return self::$ACCEPTED;
                        }else{
                            return self::$BUSY;
                        }
                    case 'Восстановленное':  
                        if ($this->toogle == 'supply') {
                            return self::$STORED;
                        }else{
                            return self::$ACCEPTED;
                        }
                    case 'Некондиционное': return self::$UNACCEPTABLE;
                }
            case 'writeoff':
                switch ($state) {
                    case 'Новое': return self::$USED;
                    case 'Неисправное':                         
                        if ($this->toogle == 'supply') {
                            return self::$ACCEPTED;
                        }else{
                            return self::$BUSY;
                        }
                    case 'Восстановленное': return self::$USED;
                    case 'Некондиционное': return self::$UNACCEPTABLE;
                }
        }
    }

    private function _isIT($storage){
        // if($storage != 'it'){
            return self::$ACCEPTED;
        // }else{
        //     return self::$NO_ROTABLE;
        // }
    }
}
