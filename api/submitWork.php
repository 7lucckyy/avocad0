<?php
   include 'config.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        $BookName = $_POST['bookName'];
        $BookTitle =$_POST['bookTitle'];
        $Descrription = $_POST['description'];
        $AboutAuthor = $_POST['aboutAuthor'];
        $AuthorName = $_POST['authorName'];
        
        $file = $_FILES["uploadBook"];
        
        
        
        
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
            echo "Document must be less than 2MB";
            exit();
        }
        $upload_path = './uploads';
        
        $newFileName1 = time().".".$ext;
        
        if(move_uploaded_file($tempPath, $upload_path . $newFileName1) == false) {
            header("Content-Type: application/json");
            http_response_code(400);
            echo "Failed to upload Book";
            exit();
        }
        }

        //CoverPage
        
        $bookCover = $_FILES["uploadCover"];
        $filename = $bookCover["name"];
        $filetype = $bookCover["type"];
        $filesize = $bookCover["size"];
        $tempPath = $bookCover['tmp_name'];

        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "pdf" => "application/pdf");
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
        $maxsize = 3 * 1024 * 1024;
        
        if($filesize > $maxsize) 
        {
            header("Content-Type: application/json");
            http_response_code(400);
            echo "Image must be less than 2MB";
            exit();
        }
        $upload_path = './uploads';
        
        $newFileName = time().".".$ext;
        
        if(move_uploaded_file($tempPath, $upload_path . $newFileName) == false) {
            header("Content-Type: application/json");
            http_response_code(400);
            echo "Failed to upload photo";
            exit();
        }
        
        $bookID = uniqid();
        $isPublised = 0;
        $isDeleted = 0;
        $sql = "INSERT INTO `books` (`id`, `name`, `title`, `description`, `author_name`, `about_author`, `book_src`, `cover_src`, `is_published`, `is_deleted`) 
                VALUES ('$bookID', '$BookName', '$BookTitle', '$Descrription', '$AuthorName', '$AboutAuthor', '$newFileName1', '$newFileName', '$isPublised', '$isDeleted' )";
        
        if(mysqli_query($conn, $sql)){
            header("Content-Type: application/json");
            http_response_code(200);
            $message = json_encode(array("message" => "Submitted Successfully", "status" => true));	
            echo $message;
            exit();  
        }else{
            header("Content-Type: application/json");
            http_response_code(401);
            mysqli_query($conn, $sql);
            $message = json_encode(array("message" => "Invalid Request ","status" => false));	
            echo $message;
            exit();
        }
        
        
        
    
?>