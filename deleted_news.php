<?php
session_start();
include 'db_connect.php';

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['restore'])) {
    $id = intval($_GET['restore']);
    $conn->query("UPDATE news SET deleted = 0 WHERE id = $id");
    header("Location: deleted_news.php");
    exit;
}

$sql = "SELECT news.*, categories.name AS category_name, users.name AS user_name
        FROM news
        JOIN categories ON news.category_id = categories.id
        JOIN users ON news.user_id = users.id
        WHERE news.deleted = 1
        ORDER BY news.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>🗑️ الأخبار المحذوفة</title>
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
    background: rgba(220, 53, 69, 1);
    color: white;
}
tr:nth-child(even) { background: hsla(0, 0%, 95%, 1.00); }
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
.btn-restore { background: rgba(40, 167, 69, 1); }
.btn-back { background: rgba(0, 123, 255, 1); display:inline-block; margin-bottom:10px; }
a.btn:hover { opacity: 0.8; }
</style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">🗑️ الأخبار المحذوفة</h2>

    <a href="view_news.php" class="btn btn-back">📋 عرض الأخبار</a>
    <a href="dashboard.php" class="btn btn-back">🏠 لوحة التحكم</a>

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
                    <a href='deleted_news.php?restore={$row['id']}' class='btn btn-restore'>استرجاع 🔁</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>لا توجد أخبار محذوفة حالياً.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
