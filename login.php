<?php
include 'config.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once 'JwtHandler.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){

        $email=stripcslashes($_POST['email']);
        $email = mysqli_real_escape_string($conn, $email);
        $password=$_POST['password'];
        $password = mysqli_real_escape_string($conn, $password);
}
try{

        $sql = "SELECT * FROM users WHERE email = '$email' ";  
        $result = mysqli_query($conn, $sql);  
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
        $count = mysqli_num_rows($result);

    
        if($email == $row['email']){
        
        if (password_verify($password, $row['password'])) {
            header("Content-Type: application/json");
            http_response_code(200);
            mysqli_query($conn, $sql);
            $message = json_encode(array("message" => "Login Success", "status" => true));	
            
             // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
            // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
            $jwt = new JwtHandler();
            $token = $jwt->_jwt_encode_data(
            'avocad0/',
            array("id"=> $row['id'])
            );
        
            $returnData = [
                'message' => 'You have successfully logged in.',
                'token' => "Bearer $token"
            ];

            echo $token;
            exit();
            
              
        } else {
            header("Content-Type: application/json");
            http_response_code(401);
            mysqli_query($conn, $sql);
            $message = json_encode(array("message" => "Invalid Password ","status" => false));	
            echo $message;
            exit();
        }
    }
            
    }
    catch(Exception $e) {
        http_response_code(401);
        echo('Message: ' .$e->getMessage());
        $message = json_encode(array("message" => $e->getMessage(),"status" => false));	
        echo $message;
      }
    
    

?>