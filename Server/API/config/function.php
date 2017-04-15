<?php
/*
 * 重写了最为核心的数据库公共函数,使用了mysqli函数库,使得其支持PHP较新版本
 * @Author ACGunion(fssbbskefu@gmail.com)
 */
// 加载依赖
require_once 'configuration.php';
// 获取端口号
function shadowsocks_port($package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    // 获取一波userTable
    if ($package == 1) {
        $userTable = 'user';
    } else {
        $userTable = 'user' . $package;
    }
    $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (! $mysql) {
        $result = array(
            'status' => 'Error',
            'result' => 'Unable to connect to database.'
        );
    } else {
        $query = "SELECT port FROM " . $userTable . " order by port desc limit 1";
        $port = mysqli_query($mysql, $query);
        $port = mysqli_fetch_assoc($port);
        // $port = mysql_fetch_assoc($port);
        $port = $port['port'] + 1;
        if ($port > 65535) {
            $result = "Error reach port limited";
        } else {
            $result = $port;
        }
    }
    mysqli_close($mysql);
    return $result;
}
// 新建账户，传入密码和流量值(MB)
function shadowsocks_create($pid, $passwd, $traffic, $package)
{
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } elseif (empty($passwd)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined password value.'
        );
    } elseif (empty($traffic)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined traffic value.'
        );
    } elseif (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    } else {
        if (shadowsocks_port() != "Error") {
            $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (! $mysql) {
                $result = array(
                    'status' => 'Error',
                    'result' => 'Unable to connect to database.'
                );
            } else {
                $port = shadowsocks_port();
                // 获取一波userTable
                if ($package == 1) {
                    $userTable = 'user';
                } else {
                    $userTable = 'user' . $package;
                }
                // 连接用
                // $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $query = "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'";
                $check = mysqli_query($mysql, $query);
                $check = mysqli_fetch_assoc($check);
                if ($check != "") {
                    $result = array(
                        'status' => 'Error',
                        'result' => 'Account already exists.'
                    );
                } else {
                    $traffic = $traffic * 1048576; // 按照 GB 为流量单位
                    $insert = "INSERT INTO " . $userTable . "(pid,passwd,port,transfer_enable) VALUES ('" . $pid . "','" . $passwd . "','" . $port . "','" . $traffic . "')";
                    $create = mysqli_query($mysql, $insert);
                    // $create = mysql_query("INSERT INTO user(pid,passwd,port,transfer_enable) VALUES ('".$pid."','".$passwd."','".$port."','".$traffic."')");
                    if (! $create) {
                        $result = array(
                            'status' => 'Error',
                            'result' => 'MySQL query failed.'
                        );
                    } else {
                        $result = array(
                            'status' => 'Success',
                            'result' => $port
                        );
                    }
                }
            }
        } else {
            $result = array(
                'status' => 'Error',
                'result' => 'Port exceeds the maximum value.'
            );
        }
    }
    mysqli_close($mysql);
    return $result;
}
// 暂停账户，传入端口号
function shadowsocks_suspend($pid, $package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } else {
        $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (! $mysql) {
            $result = array(
                'status' => 'Error',
                'result' => 'Unable to connect to database.'
            );
        } else {
            // 获取一波userTable
            if ($package == 1) {
                $userTable = 'user';
            } else {
                $userTable = 'user' . $package;
            }
            $query = "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'";
            $check = mysqli_query($mysql, $query);
            $check = mysqli_fetch_assoc($check);
            if ($check == "") {
                $result = array(
                    'status' => 'Error',
                    'result' => 'No data found.'
                );
            } else {
                $passwd = md5(time() . rand(0, 100));
                $suspend = mysqli_query($mysql, "UPDATE " . $userTable . " SET passwd='" . $passwd . "' WHERE pid='" . $pid . "'");
                // $suspend = mysql_query("UPDATE user SET passwd='".$passwd."' WHERE pid='".$pid."'");
                if (! $suspend) {
                    $result = array(
                        'status' => 'Error',
                        'result' => 'MySQL query failed.'
                    );
                } else {
                    $result = array(
                        'status' => 'Success',
                        'result' => 'Account successfully suspend.'
                    );
                }
            }
        }
    }
    mysqli_close($mysql);
    return $result;
}
// 解除暂停，传入密码和端口号
function shadowsocks_unsuspend($pid, $passwd, $package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } elseif (empty($passwd)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined password value.'
        );
    } else {
        // 获取一波userTable
        if ($package == 1) {
            $userTable = 'user';
        } else {
            $userTable = 'user' . $package;
        }
        $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (! $mysql) {
            $result = array(
                'status' => 'Error',
                'result' => 'Unable to connect to database.'
            );
        } else {
            $query = "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'";
            $check = mysqli_query($mysql, $query);
            $check = mysqli_fetch_assoc($check);
            if ($check == "") {
                $result = array(
                    'status' => 'Error',
                    'result' => 'No data found.'
                );
            } else {
                $unsuspend = mysqli_query($mysql, "UPDATE " . $userTable . " SET passwd='" . $passwd . "' WHERE pid='" . $pid . "'");
                if (! $unsuspend) {
                    $result = array(
                        'status' => 'Error',
                        'result' => 'MySQL query failed.'
                    );
                } else {
                    $result = array(
                        'status' => 'Success',
                        'result' => 'Account successfully unsuspend.'
                    );
                }
            }
        }
    }
    mysqli_close($mysql);
    return $result;
}
// 终止账户，传入端口号
function shadowsocks_terminate($pid, $package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } else {
        if ($package == 1) {
            $userTable = 'user';
        } else {
            $userTable = 'user' . $package;
        }
        $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (! $mysql) {
            $result = array(
                'status' => 'Error',
                'result' => 'Unable to connect to database.'
            );
        } else {
            $query = "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'";
            $check = mysqli_query($mysql, $query);
            $check = mysqli_fetch_assoc($check);
            if ($check == "") {
                $result = array(
                    'status' => 'Error',
                    'result' => 'No data found.'
                );
            } else {
                $terminate = mysqli_query($mysql, "DELETE FROM " . $userTable . " WHERE pid='" . $pid . "'");
                if (! $terminate) {
                    $result = array(
                        'status' => 'Error',
                        'result' => 'MySQL query failed.'
                    );
                } else {
                    $result = array(
                        'status' => 'Success',
                        'result' => 'Account successfully terminated.'
                    );
                }
            }
        }
    }
    return $result;
}
// 修改套餐，传入流量值和端口号(MB)
function shadowsocks_changepackage($pid, $traffic, $package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } elseif (empty($traffic)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined traffic value.'
        );
    } else {
        if ($package == 1) {
            $userTable = 'user';
        } else {
            $userTable = 'user' . $package;
        }
        $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (! $mysql) {
            $result = array(
                'status' => 'Error',
                'result' => 'Unable to connect to database.'
            );
        } else {
            $traffic = $traffic * 1048576;
            
            $query = "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'";
            $check = mysqli_query($mysql, $query);
            $check = mysqli_fetch_assoc($check);
            if ($check == "") {
                $result = array(
                    'status' => 'Error',
                    'result' => 'No data found.'
                );
            } else {
                $changepackage = mysqli_query($mysql, "UPDATE " . $userTable . " SET transfer_enable='" . $traffic . "' WHERE pid='" . $pid . "'");
                if (! $changepackage) {
                    $result = array(
                        'status' => 'Error',
                        'result' => 'MySQL query failed.'
                    );
                } else {
                    $result = array(
                        'status' => 'Success',
                        'result' => 'Account successfully modified.'
                    );
                }
            }
        }
    }
    mysqli_close($mysql);
    return $result;
}
// 更改密码，传入密码和端口号
function shadowsocks_changepassword($pid, $passwd, $package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } elseif (empty($passwd)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined password value.'
        );
    } else {
        if ($package == 1) {
            $userTable = 'user';
        } else {
            $userTable = 'user' . $package;
        }
        $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (! $mysql) {
            $result = array(
                'status' => 'Error',
                'result' => 'Unable to connect to database.'
            );
        } else {
            $query = "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'";
            $check = mysqli_query($mysql, $query);
            $check = mysqli_fetch_assoc($check);
            if ($check == "") {
                $result = array(
                    'status' => 'Error',
                    'result' => 'No data found.'
                );
            } else {
                $changepassword = mysqli_query($mysql, "UPDATE " . $userTable . " SET passwd='" . $passwd . "' WHERE pid='" . $pid . "'");
                if (! $changepassword) {
                    $result = array(
                        'status' => 'Error',
                        'result' => 'MySQL query failed.'
                    );
                } else {
                    $result = array(
                        'status' => 'Success',
                        'result' => 'Password reset complete.'
                    );
                }
            }
        }
    }
    mysqli_close($mysql);
    return $result;
}
// 重置流量，传入端口号
function shadowsocks_reset($pid, $package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } else {
        if ($package == 1) {
            $userTable = 'user';
        } else {
            $userTable = 'user' . $package;
        }
        $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (! $mysql) {
            $result = array(
                'status' => 'Error',
                'result' => 'Unable to connect to database.'
            );
        } else {
            $query = "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'";
            $check = mysqli_query($mysql, $query);
            $check = mysqli_fetch_assoc($check);
            if ($check == "") {
                $result = array(
                    'status' => 'Error',
                    'result' => 'No data found.'
                );
            } else {
                $reset = mysqli_query($mysql, "UPDATE " . $userTable . " SET u='0',d='0' WHERE pid='" . $pid . "'");
                if (! $reset) {
                    $result = array(
                        'status' => 'Error',
                        'result' => 'MySQL query failed.'
                    );
                } else {
                    $result = array(
                        'status' => 'Success',
                        'result' => 'Account Reset success.'
                    );
                }
            }
        }
    }
    mysqli_close($mysql);
    return $result;
}
// 查询账户，传入端口号
function shadowsocks_query($pid, $package)
{
    if (empty($package)) {
        // 设置在未传入套餐名时的信息
        $userTable = 'user';
    }
    if (empty($pid)) {
        $result = array(
            'status' => 'Error',
            'result' => 'Undefined product id value.'
        );
    } else {
        if ($package == 1) {
            $userTable = 'user';
        } else {
            $userTable = 'user' . $package;
        }
        $mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (! $mysql) {
            $result = array(
                'status' => 'Error',
                'result' => 'Unable to connect to database.'
            );
        } else {
            $query = mysqli_query($mysql, "SELECT * FROM " . $userTable . " WHERE pid='" . $pid . "'");
            $query = mysqli_fetch_assoc($query);
            if ($query == "") {
                $result = array(
                    'status' => 'Error',
                    'result' => 'No data found.'
                );
            } else {
                $result = array(
                    'port' => $query['port'],
                    'upload' => $query['u'],
                    'download' => $query['d'],
                    'last_time' => $query['t']
                );
            }
        }
    }
    mysqli_close($mysql);
    return $result;
}
