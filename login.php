<?php
session_start(); 
include 'db_connect.php'; 

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (empty($email) || empty($password)) {
        $error = "يرجى إدخال البريد الإلكتروني وكلمة المرور.";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "كلمة المرور غير صحيحة.";
            }
        } else {
            $error = "البريد الإلكتروني غير موجود.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تسجيل الدخول</title>
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
    background: rgba(40, 167, 69, 1);
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.error { color: red; margin-bottom: 10px; }
</style>
</head>
<body>

<form method="POST">
    <h2 style="text-align:center;">تسجيل الدخول</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <label>البريد الإلكتروني</label>
    <input type="email" name="email" required>

    <label>كلمة المرور</label>
    <input type="password" name="password" required>

    <button type="submit" name="login">دخول</button>
</form>

</body>
</html>
