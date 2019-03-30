<?php
    header("Access-Control-Allow-Origin: *");
    $json = file_get_contents('php://input');
    $array = json_decode($json, true);

    $username = $array['username'];

    if(isset($username) && $array['password']){
        require('../../function/const.php');
        $res = [];

        $con = mysqli_connect($GLOBALS['DATABASE_HOST'], $GLOBALS['DATABASE_USER'], $GLOBALS['DATABASE_PASS'], $GLOBALS['DATABASE_NAME']);
        if ( mysqli_connect_errno() ) {
            $res = [
                'status' =>  'KO',
                'message' => 'System internal error'
            ];

            echo json_encode($res);
            return;
        }

        if ($stmt = $con->prepare("SELECT username, password FROM users WHERE username = ?")) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password);
                $stmt->fetch();
                if (password_verify($array['password'], $password)) {
                    $res = [
                        'status' =>  'ok',
                        'message' => 'Welcome back.'
                    ];
                
                    echo json_encode($res);
                } else {
                    $res = [
                        'status' =>  'error',
                        'message' => 'Invalid login data.'
                    ];
                
                    echo json_encode($res);
                }
            } else {
                $res = [
                    'status' =>  'error',
                    'message' => 'Invalid login data.'
                ];
    
                echo json_encode($res);
            }
            $stmt->close();
        }

    } else {
        $res = [
            'status' =>  'No data.',
            'message' => 'No data found in the request.'
        ];

        echo json_encode($res);
        //No data on the request
    }
?>