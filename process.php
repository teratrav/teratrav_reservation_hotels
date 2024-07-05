<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $nationality = htmlspecialchars($_POST['nationality']);
    $phone = htmlspecialchars($_POST['phone']);
    
    // Process passport image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["passport"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["passport"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "الملف ليس صورة.";
            $uploadOk = 0;
        }
    }
    
    // Check file size
    if ($_FILES["passport"]["size"] > 500000) {
        echo "عذرًا، حجم الملف كبير جدًا.";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "عذرًا، يسمح فقط بملفات JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "عذرًا، لم يتم رفع الملف.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["passport"]["tmp_name"], $target_file)) {
            // إرسال البيانات إلى واتساب
            $whatsapp_number = "+201201666688";
            $message = "تم استلام حجز جديد!\n";
            $message .= "الاسم: $name\n";
            $message .= "البريد الإلكتروني: $email\n";
            $message .= "الجنسية: $nationality\n";
            $message .= "رقم الهاتف: $phone\n";
            $message .= "صورة جواز السفر: $target_file\n";
            
            // Encode message for WhatsApp API
            $message = urlencode($message);
            $api_url = "https://api.whatsapp.com/send?phone=$whatsapp_number&text=$message";
            
            // Redirect to WhatsApp API
            header("Location: $api_url");
            exit();
        } else {
            echo "عذرًا، حدث خطأ أثناء رفع الملف.";
        }
    }
}
?>
