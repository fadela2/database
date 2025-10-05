<?php
session_start();
include 'db_connect.php';

// بدنا نتأكد إن المستخدم مسجل دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("UPDATE news SET deleted = 1 WHERE id = $id");
    header("Location: view_news.php");
    exit;
}

// بدنا نجيب الأخبار الغير محذوفة
$sql = "SELECT news.*, categories.name AS category_name, users.name AS user_name
        FROM news
        JOIN categories ON news.category_id = categories.id
        JOIN users ON news.user_id = users.id
        WHERE news.deleted = 0
        ORDER BY news.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>عرض الأخبار</title>
<style>
body {
    font-family: Tahoma;
    background: rgba(245, 245, 245, 1);
    margin: 0;
}
.container {
    width: 90%;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(204, 204, 204, 1);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
th, td {
    border: 1px solid rgba(221, 221, 221, 1);
    padding: 8px;
    text-align: center;
    vertical-align: middle;
}
th {
    background: rgba(0, 123, 255, 1);
    color: white;
}
tr:nth-child(even) { background: rgba(242, 242, 242, 1); }
img {
    width: 100px;
    height: 70px;
    object-fit: cover;
    border-radius: 5px;
}
a.btn {
    padding: 6px 10px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
}
.btn-edit { background: hsla(134, 61%, 41%, 1.00); }
.btn-delete { background: #dc3545; }
.btn-back { background: rgba(0, 123, 255, 1); display:inline-block; margin-bottom:10px; }
a.btn:hover { opacity: 0.8; }
</style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">📋 عرض الأخبار</h2>

    <a href="add_news.php" class="btn btn-back">➕ إضافة خبر جديد</a>
    <a href="dashboard.php" class="btn btn-back">🏠 العودة للوحة التحكم</a>

    <table>
        <tr>
            <th>رقم</th>
            <th>العنوان</th>
            <th>الفئة</th>
            <th>التفاصيل</th>
            <th>الصورة</th>
            <th>الناشر</th>
            <th>تاريخ الإضافة</th>
            <th>الخيارات</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                echo "<td>" . nl2br(htmlspecialchars(substr($row['details'], 0, 100))) . "...</td>";
                echo "<td>";
                if (!empty($row['image'])) {
                    echo "<img src='uploads/{$row['image']}' alt='صورة الخبر'>";
                } else {
                    echo "بدون صورة";
                }
                echo "</td>";
                echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td>
                    <a href='edit_news.php?id={$row['id']}' class='btn btn-edit'>تعديل ✏️</a> 
                    <a href='view_news.php?delete={$row['id']}' class='btn btn-delete' onclick='return confirm(\"هل أنت متأكد من حذف هذا الخبر؟\")'>حذف 🗑️</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>لا توجد أخبار حالياً.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
