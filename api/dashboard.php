<?php
    include_once 'config.php';
    require_once './config/functions.php';

    header('Content-Type: application/json');
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        $authorizationHeader = GetAuthorizationHeader(getallheaders());

        if($authorizationHeader == false){
            http_response_code(401);
            echo GenerateResponseMessage(false,401,'Access denied');
            exit();
        }

        $user = DecodeAuthenticationToken($authorizationHeader);
        if($user == false){
            http_response_code(401);
            echo GenerateResponseMessage(false,401,'Token has been tampered. Login again');
            exit();
        }
        
        try {
            
            $sql = "SELECT * FROM users WHERE id = '$user->id' ";  
            $result = mysqli_query($conn, $sql);  
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
            
            $data = array(
                'data' => array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'earnings' => 0
                )
            );

            http_response_code(200);
            echo GenerateResponseMessage(true,200,'Retrieved user data',$data);
            exit();

        } catch (\Throwable $th) {
            http_response_code(500);
            echo GenerateResponseMessage(false,500,'Something went wrong. Try again');
        }

    }