<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: view_news.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("⚠️ الخبر غير موجود!");
}

$news = $result->fetch_assoc();

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $details = trim($_POST['details']);
    $imageName = $news['image'];

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    }

    $stmt = $conn->prepare("UPDATE news SET title = ?, category_id = ?, details = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sissi", $title, $category_id, $details, $imageName, $id);

    if ($stmt->execute()) {
        $success = "تم تعديل الخبر بنجاح ✅";
        $news['title'] = $title;
        $news['category_id'] = $category_id;
        $news['details'] = $details;
        $news['image'] = $imageName;
    } else {
        $error = "حدث خطأ أثناء التعديل.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تعديل الخبر</title>
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
    background: rgba(40, 167, 69, 1);
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
button:hover { background: rgba(30, 126, 52, 1); }
.error { color: red; margin-bottom: 10px; }
.success { color: green; margin-bottom: 10px; }
img {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}
a {
    color: rgba(0, 123, 255, 1);
    text-decoration: none;
}
a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">✏️ تعديل الخبر</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>عنوان الخبر:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>

        <label>الفئة:</label>
        <select name="category_id" required>
            <?php
            while ($cat = $categories->fetch_assoc()) {
                $selected = ($cat['id'] == $news['category_id']) ? "selected" : "";
                echo "<option value='{$cat['id']}' $selected>" . htmlspecialchars($cat['name']) . "</option>";
            }
            ?>
        </select>

        <label>تفاصيل الخبر:</label>
        <textarea name="details" rows="5" required><?php echo htmlspecialchars($news['details']); ?></textarea>

        <label>صورة الخبر الحالية:</label><br>
        <?php
        if (!empty($news['image'])) {
            echo "<img src='uploads/{$news['image']}' alt='صورة الخبر'><br><br>";
        } else {
            echo "لا توجد صورة حالياً.<br><br>";
        }
        ?>

        <label>تغيير الصورة (اختياري):</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update">💾 حفظ التعديلات</button>
    </form>

    <a href="view_news.php">← العودة إلى قائمة الأخبار</a>
</div>

</body>
</html>
