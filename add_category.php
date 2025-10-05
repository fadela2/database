<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add'])) {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $error = "يرجى إدخال اسم الفئة.";
    } else {
        $check = $conn->prepare("SELECT * FROM categories WHERE name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "هذه الفئة موجودة بالفعل!";
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                $success = "تمت إضافة الفئة بنجاح ✅";
            } else {
                $error = "حدث خطأ أثناء إضافة الفئة.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إضافة فئة</title>
<style>
body {
    font-family: Tahoma;
    background: rgba(245, 245, 245, 1);
}
.container {
    width: 400px;
    margin: 80px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(204, 204, 204, 1);
}
input {
    width: 100%;
    padding: 10px;
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
button:hover {
    background: rgba(0, 86, 179, 1);
}
a {
    display: inline-block;
    margin-top: 10px;
    color: rgba(0, 123, 255, 1);
    text-decoration: none;
}
a:hover { text-decoration: underline; }
.error { color: red; margin-bottom: 10px; }
.success { color: green; margin-bottom: 10px; }
</style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">➕ إضافة فئة جديدة</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="POST">
        <label>اسم الفئة:</label>
        <input type="text" name="name" placeholder="مثلاً: أخبار رياضية" required>

        <button type="submit" name="add">إضافة</button>
    </form>

    <a href="dashboard.php">← العودة إلى لوحة التحكم</a>
</div>

</body>
</html>
