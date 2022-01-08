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
            isset($data->description) == false || empty($data->description) == true
        ) 
        {
            
            $fields = ['fields' => ['fullName','email','phone','Description']];
            http_response_code(400);    
            echo GenerateResponseMessage(false,400,'Fields are required',$fields);
            exit();
        }   

            $fullName = $data->fullName;
            $email= $data->email;
            $Description = $data->description;
            $phone= $data->phone;
            echo "$fullName $Description";
            exit();
        
            if (strlen($phone) < 10)
            {
                http_response_code(400);
                echo GenerateResponseMessage(false,400, 'Phone number can not be less than 10 characters"');
                exit();        
            }

            try {

                $consultID = GenerateUUID();
                $isAssisted = 0;

                $sql = "INSERT INTO `bookconsults`( `id`, `client_name`,`email`, `phone`, `description`, `is_assited`)
                    VALUES ('$consultID','$fullName', '$email','$phone', '$Description', '$isAssisted')";

                mysqli_query($conn, $sql);
                                        
                http_response_code(201);
                echo GenerateResponseMessage(true,201,'Signup Successfully');
                exit();  
        
                
            } catch (\Exception $e) {
                http_response_code(500);	
                echo GenerateResponseMessage(false, 500, 'Something went wrong');
                exit();  
            }

    }



?>