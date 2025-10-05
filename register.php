<?php
include 'db_connect.php'; // اول اشي هنتصل بالقاعدة

if (isset($_POST['register'])) {
//هنستلم بيانات النموذج
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error = "يرجى تعبئة جميع الحقول!";
    } elseif ($password !== $confirm) {
        $error = "كلمتا المرور غير متطابقتين!";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        //بدنا نخلي الايميل ما يتكرر 
        $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            $error = "البريد الإلكتروني مستخدم مسبقًا!";
        } else {
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);
            if ($stmt->execute()) {
                $success = "تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.";
            } else {
                $error = "حدث خطأ أثناء التسجيل، حاول مرة أخرى.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تسجيل حساب جديد</title>
<style>
body {
    font-family: Tahoma;
    background: rgba(245, 245, 245, 1);
    display: flex; justify-content: center; align-items: center;
    height: 100vh;
}
form {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(204, 204, 204, 1);
    width: 350px;
}
input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid rgba(221, 221, 221, 1);
    border-radius: 5px;
}
button {
    background: rgba(0, 123, 255, 1);
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.error { color: red; margin-bottom: 10px; }
.success { color: green; margin-bottom: 10px; }
</style>
</head>
<body>

<form method="POST">
    <h2 style="text-align:center;">إنشاء حساب جديد</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

    <label>الاسم الكامل</label>
    <input type="text" name="name" required>

    <label>البريد الإلكتروني</label>
    <input type="email" name="email" required>

    <label>كلمة المرور</label>
    <input type="password" name="password" required>

    <label>تأكيد كلمة المرور</label>
    <input type="password" name="confirm" required>

    <button type="submit" name="register">تسجيل</button>
</form>

</body>
</html>
