<?php
require_once 'config/function.php';

if (empty($_POST['action'])) {
    die(json_encode(array(
        'status' => 'Error',
        'result' => 'Undefined value.'
    )));
} else {
    if (! empty($_POST['password'])) {
        $passwd = $_POST['password'];
    }
    
    if (! empty($_POST['traffic'])) {
        $traffic = $_POST['traffic'];
    }
    
    if (! empty($_POST['pid'])) {
        $pid = $_POST['pid'];
    }
    // 接收传入的套餐信息
    if (! empty($_POST['package'])) {
        $package = $_POST['package'];
    }
    $action = $_POST['action'];
    
    if ($action == "create") {
        $create = @shadowsocks_create($pid, $passwd, $traffic, $package);
        echo json_encode($create);
    } elseif ($action == "terminate") {
        $terminate = @shadowsocks_terminate($pid, $package);
        echo json_encode($terminate);
    } elseif ($action == "suspend") {
        $suspend = @shadowsocks_suspend($pid, $package);
        echo json_encode($suspend);
    } elseif ($action == "unsuspend") {
        $unsuspend = @shadowsocks_unsuspend($pid, $passwd, $package);
        echo json_encode($unsuspend);
    } elseif ($action == "changepassword") {
        $changepassword = @shadowsocks_changepassword($pid, $passwd, $package);
        echo json_encode($changepassword);
    } elseif ($action == "changepackage") {
        $changepackage = @shadowsocks_changepackage($pid, $traffic, $package);
        echo json_encode($changepackage);
    } elseif ($action == "reset") {
        $reset = @shadowsocks_reset($pid, $package);
    } elseif ($action == "query") {
        $query = @shadowsocks_query($pid, $package);
        echo json_encode($query);
    } else {
        die(json_encode(array(
            'status' => 'Error',
            'result' => 'Undefined value.'
        )));
    }
}
