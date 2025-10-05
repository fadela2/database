<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لوحة التحكم</title>
<style>
body {
    font-family: Tahoma;
    background: rgba(245, 245, 245, 1);
    margin: 0;
}
header {
    background: rgba(0, 123, 255, 1);
    color: white;
    padding: 15px;
    text-align: center;
}
nav {
    background: rgba(51, 51, 51, 1);
    display: flex;
    justify-content: center;
}
nav a {
    color: white;
    padding: 14px 20px;
    text-decoration: none;
    display: block;
}
nav a:hover {
    background: rgba(85, 85, 85, 1);
}
.container {
    padding: 25px;
    text-align: center;
}
.logout {
    position: absolute;
    left: 20px;
    top: 20px;
    background: rgba(220, 53, 69, 1);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
}
.logout:hover { background: rgba(181, 42, 54, 1); }
</style>
</head>
<body>

<header>
    <h2>مرحباً، <?php echo $userName; ?> 👋</h2>
    <a href="logout.php" class="logout">تسجيل الخروج</a>
</header>

<nav>
    <a href="add_category.php">➕ إضافة فئة</a>
    <a href="view_categories.php">📂 عرض الفئات</a>
    <a href="add_news.php">📰 إضافة خبر</a>
    <a href="view_news.php">📋 عرض الأخبار</a>
    <a href="deleted_news.php">🗑️ الأخبار المحذوفة</a>
</nav>

<div class="container">
    <h3>مرحبًا بك في نظام إدارة الأخبار</h3>
    <p>اختر أحد الخيارات من القائمة أعلاه لبدء العمل.</p>
</div>

</body>
</html>
