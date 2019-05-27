    <head>
    <meta charset="UTF-8">
    <link href="css/style.css" rel="stylesheet" type='text/css'>    
</head>

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




$toogle ='';
foreach ($_GET as $key => $value) {

    switch ($key) {
        case 'rotate':
            $toogle = 'rotate';
            _rotate_submit($toogle);
            break;
        case 'supply':
            $toogle = 'supply';
            _supply_submit($toogle);
            break;
        case 'writeoff':
            $toogle = 'writeoff';
            _writeoff_submit($toogle);
            break;
        case 'barcode':
            $toogle = 'barcode';
            _barcode_submit();
            break;
        case 'search':
            $toogle = 'search';
            _search_submit();
            break;
        case 'reverse':
            _reverse_submit($value);
            break;
        case 'settings':
            _settings_submit();
    }
}

/**
 * Сколько нужно проводок отменить?
 * Что делать с записью(-ями) в `trace`?
 */
function _reverse_submit($row_id){
    require ('./src/controllers/database_handler.php');
    $dbhandler = new database_handler('headset');
    $tabname = $_COOKIE['reverse'];
    if($tabname == 'rotate'){
        for ($i= $row_id; $i < $row_id + 5; $i++) { 
            $request = "update `across` set `reverse_date` = '".date('c')."' where `row_id` = '". $i . "';";
            _safeput($dbhandler, $request);
        }
    }else{
        // echo "Aloha!";
        // exit();
        $request = "update `across` set `reverse_date` = '".date('c')."' where `row_id` = '". $row_id . "';";
        _safeput($dbhandler, $request);
    }
    header("Location: /?" . $tabname . '=true');
}





function _rotate_submit($toogle) {
    require ('./src/controllers/database_handler.php');
    $dbhandler = new database_handler('headset');
    if (!$_POST['decrement']) {
        _checkit($toogle, $dbhandler, $_POST['increment']);
    } else {
        if(_checkit($toogle, $dbhandler, $_POST['decrement'], TRUE)){
            foreach ($_POST as $key => $value) {
                switch ($key) {
                    case 'increment':
                        $increment = strtoupper($value);
                        setcookie('increment', '', time() - 60);
                        break;
                    case 'decrement':
                        $decrement = strtoupper($value);
                        break;
                }
            }
            $inc_responce = "select * from `across` where `increment_id` ='"
                    . $increment . "' and `decrement_id` is null ORDER BY `row_date` DESC;";
            $inc_result = $dbhandler->_response($inc_responce);
            while ($incf_row = mysqli_fetch_array($inc_result)) {
                $inc_row[0] = $incf_row['increment_label'];
                $inc_row[1] = 'Неисправное';
                $inc_row[2] = $incf_row['increment_storage'];
                break;
            }
            $dec_responce = "select * from `across` where `increment_id` ='"
                    . $decrement . "' and `decrement_id` is null ORDER BY `row_date` DESC;";
            $dec_result = $dbhandler->_response($dec_responce);
            while ($decf_row = mysqli_fetch_array($dec_result)) {
                $dec_row[0] = $decf_row['increment_label'];
                $dec_row[1] = $decf_row['increment_condition'];
                $dec_row[2] = $decf_row['increment_storage'];
            }
            $ins_rotate = "insert into `across` values ('','" . date('c') . "','"
                    . $increment . "','" . $inc_row[0] . "','" . $inc_row[1] . "','"
                    . $inc_row[2] . "','" . $decrement . "','" . $dec_row[0]
                    . "','" . $dec_row[1] . "','" . $dec_row[2] . "',NULL,TRUE);";
            _safeput($dbhandler, $ins_rotate);
            $ins_dec1 = "insert into `across` values ('','" . date('c')
                    . "',NULL,NULL,NULL,NULL,'" . $increment . "','"
                    . $inc_row[0] . "','" . $inc_row[1] . "','" . $inc_row[2]
                    . "',NULL,TRUE);";
            _safeput($dbhandler, $ins_dec1);
            $ins_dec2 = "insert into `across` values ('','" . date('c')
                    . "',NULL,NULL,NULL,NULL,'" . $decrement . "','"
                    . $dec_row[0] . "','" . $dec_row[1] . "','" . $dec_row[2]
                    . "',NULL,TRUE);";
            _safeput($dbhandler, $ins_dec2);
            $ins_inc1 = "insert into `across` values ('','" . date('c') . "','"
                    . $increment . "','" . $inc_row[0] . "','" . $inc_row[1]
                    . "','" . $dec_row[2] . "',NULL,NULL,NULL,NULL,NULL,TRUE);";
            _safeput($dbhandler, $ins_inc1);
            _traceit($dbhandler, $increment, $dec_row[2], $inc_row[1], 'supply');
            $ins_inc2 = "insert into `across` values ('','" . date('c') . "','"
                    . $decrement . "','" . $dec_row[0] . "','" . $dec_row[1]
                    . "','" . $inc_row[2] . "',NULL,NULL,NULL,NULL,NULL,TRUE);";
            _safeput($dbhandler, $ins_inc2, TRUE);
            _traceit($dbhandler, $decrement, $inc_row[2], $dec_row[1], 'supply');
        }
    }
}

function _checkit($tooggle, $dbhandler, $device_id, $secondary = FALSE) {
    include './src/controllers/unbreakable.php';
    $unbreakable = new unbreakable($dbhandler);
    $unbreakable->_gettrace($device_id, $tooggle);
    $url = "/?$tooggle=true";
    if($secondary){
        if ($tooggle == 'rotate' & $_POST['increment'] == $_POST['decrement']) {
            $url = "/?rotate=true&increment=" . $_POST['increment'];
            $mess[0] = 'Значения не должны совпадать'; //"Передан в ремонт?";
            $mess[1] = 'Ошибочный ввод';
            echo _baloon($mess, 'caution');
            $pause = 3500;
            _redirect($url, $pause);            
        }
    }
    switch ($unbreakable->_available()) {
        case 0:
            $mess[0] = '"' . $device_id . '" сначала нужно принять'; //"Переместить то чего нет?";
            $mess[1] = 'Не найден в журнале приёма';
            echo _baloon($mess, 'caution');
            $pause = 3500;
            if($tooggle == 'rotate' & $secondary)$url .= '&increment=' . $_POST['increment'];
            _redirect($url, $pause);
            break;
        case 1:
            if ($secondary) {
                return TRUE;
            }else{
                if (!isset($_COOKIE['increment']) ) {
                    setcookie('increment', $device_id, time() + 60);
                    header("Location: " . "/?rotate=true&increment=" . $_POST['increment']);
                }
            }
            break;
        case 2:
            $mess[0] = '"' . $device_id . '" нужно сдать в ремонт'; //Уже на рядах?
            $mess[1] = 'Уже принят';
            echo _baloon($mess, 'caution');
            $pause = 3500;
            if($tooggle == 'rotate')$url .= '&increment=' . $_POST['increment'];
            _redirect($url, $pause);
            break;
        case 3:
            $mess[0] = '"' . $device_id . '" числится переданным в ремонт'; //"Передан в ремонт?";
            $mess[1] = 'Найден в журнале выдачи';
            echo _baloon($mess, 'caution');
            $pause = 3500;
            _redirect($url, $pause);
            break;
        case 4:
            $mess[0] = '"' . $device_id . '" числится некондиционным'; //"Выдать запчасти?";
            $mess[1] = 'Не подлежит восстановлению';
            echo _baloon($mess, 'error');
            $pause = 3500;
            if($tooggle == 'rotate')$url .= '&increment=' . $_POST['increment'];
            _redirect($url, $pause);
            break;
        case 5:
            $mess[0] = '"' . $device_id . '" уже принят и не выдавался'; //Принять второй раз?
            $mess[1] = 'Уже принят';
            echo _baloon($mess, 'caution');
            $pause = 3500;
            if($tooggle == 'rotate')$url .= '&increment=' . $_POST['increment'];
            _redirect($url, $pause);
            break;
    }
}

/**
 * 
 * @param type $dbhandler
 * @param type $request
 * @param type $redirect
 */
function _safeput($dbhandler, $request, $redirect = FALSE, $url = FALSE) {
    if (!$dbhandler->_response($request)) {
        header('charset=uft-8', true);
        echo $dbhandler->_err_code() . ' ' . $dbhandler->_err_mess();
    }
    if ($redirect) {
        if (!$url) {
            header("Location: /?rotate=true");
        } else {
            header("Location: /?" . $url);
        }
    }
}

function _baloon($message, $type) {
    $div = '<table class="baloon">';
    $div .= '<tr><td width=80% rowspan="2" style="text-align:center;"><h1>' . $message[0] . '</h1></td>';
    switch ($type) {
        case 'caution':
            $div .= '<td><p style="text-align:center;"><img class="caution" src="img/caution.png"></p></td></tr>';
            $div .= '<tr><td style="text-align:center;">' . $message[1] . '</td></tr>';
            break;
    }
    return $div . '</table>';
}

/**
 * 
 * @param type $url
 * @param type $pause
 */
function _redirect($url, $pause = 0) {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function(){window.location.href="' . $url . '"},' . $pause . ');';
    echo '</script>';
//    exit();
}

function _supply_submit($toogle) {
    include './src/controllers/database_handler.php';
    $dbhandler = new database_handler('headset');
    if (!isset($_COOKIE['device'])) {
        if(_checkit($toogle, $dbhandler, $_POST['device'], TRUE)){
            setcookie('device', $_POST['device'],time() + 60);
            header("Location: /?supply=true&device=" . $_POST['device']);
        }
    } else {
        foreach ($_POST as $key => $value) {
            switch ($key) {
                case 'device':
                    $device = $value;
                    setcookie('device', '');
                    break;
                case 'workstation':
                    $workstation = $value;
                    break;
                case 'used':
                    switch ($value) {
                        case 'new':
                            $used = 'Новое';
                            break;
                        case 'true':
                            $used = 'Неисправное';
                            break;
                        case 'false':
                            $used = 'Восстановленное';
                            break;
                        case 'null':
                            $used = 'Некондиционное';
                            break;
                    }
                    break;
                case 'vendor':
                    switch ($value) {
                        case 'jabra':
                            $vendor = 'Jabra';
                            break;
                        case 'logitech':
                            $vendor = 'Logitech';
                            break;
                    }
                    break;
            }
        }
        $ins_responce = "insert into `across` values ('','" . date('c') . "','"
                . $device . "','" . $vendor . "','" . $used . "','"
                . $workstation . "',NULL,NULL,NULL,NULL,NULL,FALSE);";
        _safeput($dbhandler, $ins_responce);
        _traceit($dbhandler, $device, $workstation, $used, 'supply', 'supply=true');
    }
}

/**
 * 
 * @param type $dbhandler
 * @param type $device
 * @param type $storage
 * @param type $state
 * @param type $motion
 * @param type $url_postfix
 */
function _traceit($dbhandler, $device, $storage, $state, $motion, $url_postfix) {
    $sel_responce = "select * from `trace` where `device` = '" . $device . "';";
    $sel_request = $dbhandler->_response($sel_responce);
    if (mysqli_num_rows($sel_request) == 1) {
        $request = "update `trace` set `storage` = '" . $storage
                . "', `state` = '" . $state . "', `motion` = '" . $motion
                . "' where `device` = '" . $device . "';";
    } else {
        $request = "insert into `trace` values('" . $device . "','"
                . $storage . "','" . $state . "','supply');";
    }
    _safeput($dbhandler, $request, TRUE, $url_postfix);
}

function _writeoff_submit($toogle) {
    include './src/controllers/database_handler.php';
    $dbhandler = new database_handler('headset');
    if (!isset($_COOKIE['device'])) {
        if(_checkit($toogle, $dbhandler, $_POST['device'], TRUE)){
            setcookie('device', $_POST['device'],time() + 60);
            $faulty_responce = "select * from `across` where `increment_id` ='"
                    . $_POST['device'] . "' and `decrement_id` is null ORDER BY `row_date` DESC;";
            $faulty_result = $dbhandler->_response($faulty_responce);
            while ($decf_row = mysqli_fetch_array($faulty_result)) {
                $faulty_row[0] = $decf_row['increment_label'];
                $faulty_row[1] = $decf_row['increment_condition'];
                $faulty_row[2] = $decf_row['increment_storage'];
                break;
            }
            switch ($faulty_row[1]) {
                case 'Новое':
                    $condition = 'new';
                    break;
                case 'Неисправное':
                    $condition = 'faulty';
                    break;
                case 'Восстановленное':
                    $condition = 'refurbished';
                    break;
                case 'Некондиционное':
                    $condition = 'inadequate';
                    break;
            }
            header("Location: /?writeoff=true&device=" . $_POST['device']
                    . '&storage=' . $faulty_row[2] . '&label=' . $faulty_row[0]
                    . '&condition=' . $condition);
        }
    } else {
        echo 'Aloha!';
        foreach ($_POST as $key => $value) {
            switch ($key) {
                case 'device':
                    $device = $value;
                    setcookie('device', '');
                    break;
                case 'workstation':
                    $workstation = $value;
                    break;
                case 'used':
                    switch ($value) {
                        case 'new':
                            $used = 'Новое';
                            break;
                        case 'true':
                            $used = 'Неисправное';
                            break;
                        case 'false':
                            $used = 'Восстановленное';
                            break;
                        case 'null':
                            $used = 'Некондиционное';
                            break;
                    }
                    break;
                case 'vendor':
                    switch ($value) {
                        case 'jabra':
                            $vendor = 'Jabra';
                            break;
                        case 'logitech':
                            $vendor = 'Logitech';
                            break;
                    }
                    break;
            }
        }
        $request = "insert into `across` values ('','" . date('c')
                . "',NULL,NULL,NULL,NULL,'" . $device . "','" . $vendor . "','"
                . $used . "','" . $workstation . "',NULL,FALSE);";
        _safeput($dbhandler, $request);
        _traceit($dbhandler, $device, $workstation, $used, 'writeoff', 'writeoff=true');
    }
}

function _barcode_submit() {
    include './src/models/codeprint.php';
    foreach ($_POST as $key => $value) {
        switch ($key) {
            case 'barcode_from':
                $from = $value;
                break;
            case 'barcode_to':
                $to = $value;
                break;
        }
    }
    $code_gen = new codeprint();
    $code_arr = $code_gen->_code_arr();
    session_start();
    $_SESSION['run'] = true;
    $_SESSION['codes'] = $code_arr;
    header("Location: /?barcode=true");
}

function _search_submit() {
    if (strlen($_POST['needle']) > 0) {
        header("Location: /?search=true&needle=" . $_POST['needle']);
    } else {
        header("Location: /?search=true");
    }
}

function _settings_submit(){
    foreach ($_POST as $key => $value) {
            switch ($key) {
                case 'start_date':
                    setcookie('start_date', $value);
                    break;
                case 'end_date':
                    setcookie('end_date', $value);
                    break;
            }
        }
    _redirect('/?settings=true');
}
