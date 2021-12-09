<?php
include 'config.php';

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
            echo $message;
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