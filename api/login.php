<?php
include 'config.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once './config/JwtHandler.php';
require_once './config/functions.php';


header("Content-Type: application/json");
if($_SERVER['REQUEST_METHOD'] == "POST"){

    $data = json_decode(file_get_contents("php://input"));

    if(
        isset($data->email) == false || empty($data->email) == true ||
        isset($data->password) == false || empty($data->password) == true
    ) 
    {
        
        $fields = ['fields' => ['email','password']];
        http_response_code(400);    
        echo GenerateResponseMessage(false,400,'Fields are required',$fields);
        exit();
    }

    $email=stripcslashes($data->email);
    $email = mysqli_real_escape_string($conn, $email);
    
    $password=$data->password;
        
    try{

        $sql = "SELECT * FROM users WHERE email = '$email' ";  
        $result = mysqli_query($conn, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);

    
        if($count != 0){
        
            if (password_verify($password, $row['password'])) {
                // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
                $jwt = new JwtHandler();
                $token = $jwt->_jwt_encode_data(
                'avocad0/',
                array("id"=> $row['id'])
                );
            
                $returnData = [
                    // 'message' => 'You have successfully logged in.',
                    'token' => "Bearer $token"
                ];
                http_response_code(200);
                echo GenerateResponseMessage(true,200,'You have successfully logged in.',$returnData);
                exit();
            } else {
                http_response_code(401);
                echo GenerateResponseMessage(false,401,'Invalid login credentials');
                exit();
            }
        }
        else{
            http_response_code(401);
            echo GenerateResponseMessage(false,401,'Invalid login credentials');
            exit();
        }       
    }
    catch(Exception $e) {
        http_response_code(500);	
        echo GenerateResponseMessage(false,500,'Something went wrong. Try agains');
        exit();
    }
}