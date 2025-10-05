<?php
session_start();
include 'db_connect.php';

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

if (isset($_POST['add'])) {
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $details = trim($_POST['details']);
    $user_id = $_SESSION['user_id'];
    $imageName = "";

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    }

    if (empty($title) || empty($category_id) || empty($details)) {
        $error = "يرجى تعبئة جميع الحقول المطلوبة.";
    } else {
        $stmt = $conn->prepare("INSERT INTO news (title, category_id, details, image, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sissi", $title, $category_id, $details, $imageName, $user_id);
        if ($stmt->execute()) {
            $success = "تمت إضافة الخبر بنجاح ✅";
        } else {
            $error = "حدث خطأ أثناء إضافة الخبر.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إضافة خبر جديد</title>
<style>
body {
    font-family: Tahoma;
    background: rgba(245, 245, 245, 1);
}
.container {
    width: 600px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(204, 204, 204, 1);
}
input, select, textarea {
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
button:hover { background: hsla(211, 100%, 35%, 1.00); }
.error { color: red; margin-bottom: 10px; }
.success { color: green; margin-bottom: 10px; }
a {
    color: rgba(0, 123, 255, 1);
    text-decoration: none;
}
a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">📰 إضافة خبر جديد</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>عنوان الخبر:</label>
        <input type="text" name="title" required>

        <label>الفئة:</label>
        <select name="category_id" required>
            <option value="">اختر الفئة</option>
            <?php
            if ($categories->num_rows > 0) {
                while ($cat = $categories->fetch_assoc()) {
                    echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['name']) . "</option>";
                }
            } else {
                echo "<option disabled>لا توجد فئات بعد</option>";
            }
            ?>
        </select>

        <label>تفاصيل الخبر:</label>
        <textarea name="details" rows="5" required></textarea>

        <label>صورة الخبر (اختياري):</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="add">إضافة الخبر</button>
    </form>

    <a href="dashboard.php">← العودة إلى لوحة التحكم</a>
</div>

</body>
</html>
