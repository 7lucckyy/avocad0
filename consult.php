<?php
    include 'config.php';

    if ($_SERVER['REQUEST_METHOD'] == "POST") 

    {
                

            $fullName = $_POST['fullName'];
            $email= $_POST['email'];
            $Description = $_POST['Description'];
            $phone= $_POST['phone'];
        
            if (empty($fullName) == true || strlen($fullName) < 6)
            {
                header("Content-Type: application/json");
                http_response_code(400);
                $message = json_encode(array("message" => "Name is required", "status" => false));	
                echo $message;
                exit();        
            }
            if (empty($email) == true || strlen($email) < 10)
            {
                header("Content-Type: application/json");
                http_response_code(400);
                $message = json_encode(array("message" => "Email Address is required", "status" => false));	
                echo $message;
                exit();        
            }
            if (empty($phone) == true || strlen($phone) < 8)
            {
                header("Content-Type: application/json");
                http_response_code(400);
                $message = json_encode(array("message" => "Phone number is required", "status" => false));	
                echo $message;
                exit();        
            }

            $consultID = uniqid();
            $isAssisted = 0;

            $sql = "INSERT INTO `bookconsults`( `id`, `client_name`,`email`, `phone`, `description`, `is_assited`)
                VALUES ('$consultID','$fullName', '$email','$phone', '$Description', '$isAssisted')";
        
            try {
                header("Content-Type: application/json");
                http_response_code(201);
                mysqli_query($conn, $sql);
                $message = json_encode(array("message" => "Consult Successfully", "status" => true));	
                echo $message;
                exit();  
        
                
            } catch (\Exception $e) {
                http_response_code(500);
                $message = json_encode(array("message" => "Something went wrong", "status" => false));	
                echo $message .$e->getMessage();
                exit();  
            }

    }



?>