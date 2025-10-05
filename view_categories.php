<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>عرض الفئات</title>
<style>
body {
    font-family: Tahoma;
    background: rgba(245, 245, 245, 1);
    margin: 0;
}
.container {
    width: 70%;
    margin: 50px auto;
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
    padding: 10px;
    text-align: center;
}
th {
    background: hsla(211, 100%, 50%, 1.00);
    color: white;
}
tr:nth-child(even) { background: rgba(242, 242, 242, 1); }
a.btn {
    display: inline-block;
    padding: 6px 10px;
    background: rgba(0, 123, 255, 1);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 10px;
}
a.btn:hover { background: rgba(0, 86, 179, 1); }
</style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">📂 عرض الفئات</h2>

    <a href="add_category.php" class="btn">➕ إضافة فئة جديدة</a>
    <a href="dashboard.php" class="btn">🏠 العودة للوحة التحكم</a>

    <table>
        <tr>
            <th>رقم الفئة</th>
            <th>اسم الفئة</th>
            <th>تاريخ الإضافة</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>لا توجد فئات بعد.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
