<?php
require_once(__DIR__ . "/../core/core.php");

// hiển thị lỗi, nếu có lỗi
$error_message = "";
// kiểm tra thông tin đăng nhập
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST["email"]) ? addslashes($_POST["email"]) : NULL;
    $password = isset($_POST["password"]) ? addslashes($_POST["password"]) : NULL;

    if ($email && $password) {
        $md5_password = md5($password);
        $kiemtra = query("SELECT * FROM NHANVIEN WHERE email = '{$email}' and matkhau = '{$md5_password}'");

        if ($kiemtra->num_rows > 0) {
            $result = $kiemtra->fetch_assoc();
            $_SESSION["staff_id"] = $result["id"];
            $_SESSION["staff_level"] = $result["capdo"];
            $_SESSION["staff_name"] = $result["hoten"];
            header("Location: ../admin/");
        } else {
            $error_message = "Tài khoản và mật khẩu vừa nhập không chính xác";
        }
    } else {
        $error_message = "Vui lòng nhập đầy đủ thông tin tài khoản và mật khẩu";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="./assets/css/login.css">
</head>

<body>

    <div class="left-login">
        <div class="container-login">
            <h1>Đăng nhập</h1>
            <?php if ($error_message !== "") { ?>
                <div style="border: 2px dashed orange;background: #fff5e2;color: #e99700;padding: 5px 10px;margin: 10px 0px;">
                    <?= $error_message ?>
                </div>
            <?php } ?>
            <form method="post" action="login.php">
                <div class="label">
                    <input type="email" name="email" placeholder="Nhập tên tài khoản" required>
                </div>
                <div class="label">
                    <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
    </div>

    <div class="right-login">
        Quản trị hệ thống
    </div>
</body>

</html>