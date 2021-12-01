<?php
    include 'config.php';

    if ($_SERVER['REQUEST_METHOD'] == "POST") 

    {
        
            $full_name= htmlspecialchars($_POST['fullName']);
            $email= htmlspecialchars($_POST['email']);
            $phone= htmlspecialchars($_POST['phone']);
            $password= htmlspecialchars($_POST['password']);
            $confirmPassword= htmlspecialchars($_POST['confirmPassword']);
            
        
            if (empty($full_name) == true || strlen($full_name) < 2)
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
            if (empty($password) == true || strlen($password) < 8)
            {
                header("Content-Type: application/json");
                http_response_code(400);
                $message = json_encode(array("message" => "password cant not be less than 8 characters", "status" => false));	
                echo $message;
                exit();        
            }
            if($password !== $confirmPassword){
                header("Content-Type: application/json");
                http_response_code(400);
                $message = json_encode(array("message" => "Password mismatch", "status" => false));	
                echo $message;
                exit();  
            }

        $user_id = uniqid();
        $HashPassword=password_hash($password, PASSWORD_DEFAULT);
        $isDeleted = 0;
        
        $sql = "INSERT INTO `users`( `id`, `name`,`email`, `phone`, `password`, `is_deleted`)
		    VALUES ('$user_id','$full_name', '$email','$phone', '$HashPassword', $isDeleted)";
       
        try {
            header("Content-Type: application/json");
            http_response_code(201);
            mysqli_query($conn, $sql);
            $message = json_encode(array("message" => "Signup Successfully", "status" => true));	
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