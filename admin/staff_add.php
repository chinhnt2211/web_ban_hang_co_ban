<?php
require_once(__DIR__ . "/../core/core.php");

// hiển thị lỗi, nếu có lỗi
$error_message = "";

// kiểm tra thông tin form nhập đủ ko
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    echo $hoten = isset($_POST["hoten"]) ? addslashes($_POST["hoten"]) : NULL;
    echo $sdt = isset($_POST["sdt"]) ? addslashes($_POST["sdt"]) : NULL;
    echo $diachi = isset($_POST["diachi"]) ? addslashes($_POST["diachi"]) : NULL;
    echo $email = isset($_POST["email"]) ? addslashes($_POST["email"]) : NULL;
    echo $matkhau = isset($_POST["matkhau"]) ? addslashes($_POST["matkhau"]) : NULL;
    echo $anh = isset($_POST["anh"]) ? addslashes($_POST["anh"]) : NULL;
    echo $capdo = isset($_POST["capdo"]) ? addslashes($_POST["capdo"]) : NULL;

    if ($hoten && $sdt && $diachi && $email && $matkhau && $anh) {

        // kiểm tra tên này đã được dùng chưa?
        $kiemtra = query("SELECT * FROM NHANVIEN WHERE email = '{$email}'");

        if ($kiemtra->num_rows === 0) {

            $hashmd5 = md5($matkhau);

            insert("NHANVIEN", [
                "hoten" => $hoten,
                "sodienthoai" => $sdt,
                "anh" => $anh,
                "diachi" => $diachi,
                "email" => $email,
                "matkhau" => $hashmd5,
                "capdo" => $capdo
            ]);

            header("Location: ./staff.php");
        } else {
            $error_message = "Tên email này đã được tạo rồi, chọn tên khác đi";
        }
    } else {
        $error_message = "Vui lòng nhập đủ thông tin";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển</title>
    <link rel="stylesheet" href="./assets/css/index.css">
</head>

<body>
    <h1>
        Bảng điều khiển
    </h1>
    <ul>
        <li><a href="./">Danh mục</a></li>
        <li><a href="./product.php">Nhà sản xuất</a></li>
        <li><a href="./product.php">Sản phẩm</a></li>
        <li><a href="./user.php">Người dùng</a></li>
        <li class="active"><a href="./staff.php">Nhân sự</a></li>
        <li><a href="./cart.php">Giỏ hàng</a></li>
    </ul>

    <h1>Thêm nhân sự</h1>
    <div class="form">
        <form action="" method="POST">
            <?php if ($error_message !== "") { ?>
                <div style="border: 2px dashed orange;background: #fff5e2;color: #e99700;padding: 5px 10px;margin: 10px 0px;">
                    <?= $error_message ?>
                </div>
            <?php } ?>
            Họ tên:<br>
            <input name="hoten" type="text" /><br>
            Số điện thoại:<br>
            <input name="sdt" type="text" /><br>
            Địa chỉ:<br>
            <input name="diachi" type="text" /><br>
            Email:<br>
            <input name="email" type="email" /><br>
            Mật khẩu:<br>
            <input name="matkhau" type="text" /><br>
            Ảnh:<br>
            <input name="anh" type="text" /><br>
            Cấp độ:<br>
            <select name="capdo">
                <option value="0">Nhân viên</option>
                <option value="1">Quản lý</option>
            </select><br>
            <input type="submit" value="Thêm người dùng" />
        </form>
        <a href="./staff.php">Quay lại</a>
    </div>
</body>

</html>