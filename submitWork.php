<?php
   include 'config.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        $BookName = htmlspecialchars($_POST['bookName']);
        $BookTitle = htmlspecialchars($_POST['bookTitle']);
       // $Descrription = htmlspecialchars($_POST['description']);
        $AboutAuthor = htmlspecialchars($_POST['AboutAuthor']);
        
       
        $file = $_FILES["uploadBook"];
        if(empty($file)== false){
            $filename = $file["name"];
            $filetype = $file["type"];
            $filesize = $file["size"];
            $tempPath = $file['tmp_name'];

            $allowed = array("docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "pdf" => "application/pdf");

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if(array_key_exists($ext, $allowed) == false || in_array($filetype, $allowed) == false)
            {
                header("Content-Type: application/json");
                http_response_code(400);
                echo "Invalid File Format";
                exit();
            }

            // Verify file size - 10MB maximum
            $maxsize = 2 * 1024 * 1024;
            
            if($filesize > $maxsize) 
            {
                header("Content-Type: application/json");
                http_response_code(400);
                echo "Image must be less than 2MB";
                exit();
            }
            $upload_path = "uploads/";
            
            $newFileName = time().".".$ext;
            
            if(move_uploaded_file($tempPath, $upload_path . $newFileName) == false) {
                header("Content-Type: application/json");
                http_response_code(400);
                echo "Failed to upload blog photo";
                exit();
            }
            }
        
        
    }
?>