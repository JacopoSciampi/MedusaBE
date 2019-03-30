<?php
    header("Access-Control-Allow-Origin: *");
    require('../../function/const.php');
    $res = [];

    $query = "Select * FROM server_list";
    $con = mysqli_connect($GLOBALS['DATABASE_HOST'], $GLOBALS['DATABASE_USER'], $GLOBALS['DATABASE_PASS'], $GLOBALS['DATABASE_NAME']);
        if ( mysqli_connect_errno() ) {
            $res = [
                'status' =>  'KO',
                'message' => 'System internal error'
            ];

            echo json_encode($res);
            return;
        }

    if ($result = $con->query($query)) {
        while ($obj = $result->fetch_object()) {
            $res[]=$obj;
        }
    }

    echo json_encode($res);
?>