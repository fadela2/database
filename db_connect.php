<?php
// معلومات الاتصال بقاعدة البيانات
$host = "localhost";   
$user = "root";        
$pass = "";            
$dbname = "newsofdb";   

// من هنا هنعمل الاتصال
$conn = new mysqli($host, $user, $pass, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// إذا تم الاتصال بنجاح
// echo "تم الاتصال بنجاح";
?>
