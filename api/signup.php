<?php
    include 'config.php';
    require_once './config/functions.php';

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] == "POST") 
    {
        $data = json_decode(file_get_contents("php://input"));

        if(
            isset($data->fullName) == false || empty($data->fullName) == true ||
            isset($data->email) == false || empty($data->email) == true || 
            isset($data->phone) == false || empty($data->phone) == true ||
            isset($data->password) == false || empty($data->password) == true ||
            isset($data->confirmPassword) == false || empty($data->confirmPassword) == true
        ) 
        {
            
            $fields = ['fields' => ['fullName','email','phone','password','confirmPassword']];
            http_response_code(400);    
            echo GenerateResponseMessage(false,400,'Fields are required',$fields);
            exit();
        }

        $fullName= htmlspecialchars($data->fullName);
        $email= htmlspecialchars($data->email);
        $phone= htmlspecialchars($data->phone);
        $password= htmlspecialchars($data->password);
        $confirmPassword= htmlspecialchars($data->confirmPassword);           
        
        if (strlen($phone) < 10)
        {
            http_response_code(400);	
            echo GenerateResponseMessage(false,400,'Phone number must be 11 digits');
            exit();        
        }
        if (strlen($password) < 8)
        {
            http_response_code(400);
            echo GenerateResponseMessage(false,400,'password cant not be less than 8 characters');
            exit();        
        }
        if($password !== $confirmPassword){
            http_response_code(400);
            echo GenerateResponseMessage(false,400,'Password mismatch');
            exit();
        }
        

        try {

            $sql = "SELECT * FROM users WHERE email = '$email' ";  
            $result = mysqli_query($conn, $sql);  
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
            $count = mysqli_num_rows($result);

            if($count != 0){
                http_response_code(400);
                echo GenerateResponseMessage(false,400,'Email address already exist');
                exit();                    
            }

            //Check if the user already exist 
            
            $userId = GenerateUUID();
            $fullName = mysqli_real_escape_string($conn,$fullName);
            $email = mysqli_real_escape_string($conn,$email);
            $phone = mysqli_real_escape_string($conn,$phone);

            $HashPassword=password_hash($password, PASSWORD_DEFAULT);

            $sql = "
                    INSERT INTO 
                        `users`( `id`, `name`,`email`, `phone`, `password`, `is_deleted`)
                    VALUES 
                        ('$userId','$full_name', '$email','$phone', '$HashPassword', 0)
            ";

            mysqli_query($conn, $sql);
                        
            http_response_code(201);
            echo GenerateResponseMessage(true,201,'Signup Successfully');
            exit();  
    
            
        } catch (\Exception $e) {
            http_response_code(500);                
            echo GenerateResponseMessage(false,500,'Something went wrong. Try again');
            exit();  
        }

    }