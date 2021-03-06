<?php
require_once(__DIR__ . "/../../core/core.php");

if ($sid === NULL) {
    // Test nen sua cho nay
    header("Location: ../login.php");
    die();
}

$gpage = isset($_GET["page"]) ? (int)($_GET["page"]) : 1;

$page = ($gpage - 1) * 10;
$max = 10;

$search = isset($_GET["search"]) ? (string)($_GET["search"]) : "";
$querySearch = "";
if ($search) {
    $querySearch = " WHERE hotennhan LIKE '%" . addslashes($search) . "%'";
}

$dulieu = select("SELECT * FROM HOADON " . $querySearch . " ORDER BY id DESC LIMIT " . $page . ", " . $max);
$soluong = query("SELECT * FROM HOADON " . $querySearch . "")->num_rows;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển</title>
    <link rel="stylesheet" href="../assets/css/index2.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body id="wrap">

    <div class="header">
        <div class="max h-full mx-auto p-10">
            Quản trị hệ thống
        </div>
    </div>

    <div class="nav">
        <div class="max flex justify-between mx-auto h-full p-10">
            <div class="nav-menu">
                <a href="./">Trang chủ</a>
            </div>
            <span><?= $sname ?></span>
            <div class="nav-user">
                <a href="../logout.php">Thoát</a>
            </div>
        </div>
    </div>
    <div class="flex-1">

        <div class="max mx-auto flex flex-row">
            <div class="content-left p-10" style="overflow: auto;">
                <ul>
                    <li><a href="../"><i class="fas fa-lg fa-tachometer-alt"></i> Tổng quát</a></li>
                    <li><a href="../brand/brand.php"><i class="fas fa-lg fa-copyright"></i> Nhà sản xuất</a></li>
                    <li><a href="../product/product.php"><i class="fas fa-lg fa-cookie-bite"></i> Sản phẩm</a></li>
                    <li><a href="../user/user.php"><i class="fas fa-lg fa-user"></i> Khách hàng</a></li>
                    <li><a href="../staff/staff.php"><i class="fas fa-lg fa-user-tie"></i> Nhân viên</a></li>
                    <li class="current"><a href="./cart.php"><i class="fas fa-lg fa-shopping-cart"></i> Đơn hàng</a></li>
                </ul>
            </div>
            <div class="flex-1 p-10">
                <h1><i class="fas fa-lg fa-shopping-cart"></i> Đơn hàng</h1>
                <div class="mt-10 flex justify-between">
                    <!-- <a class="button button-green" href="./category_add.php"><i class="fas fa-plus"></i> Thêm khách hàng</a> -->
                    <form action="" method="get">
                        <input type="text" name="search" value="<?= $search ?>" placeholder="Tìm kiếm dữ liệu..." require>
                        <?php if ($gpage) { ?>
                            <input type="hidden" name="page" value="<?= $gpage ?>">
                        <?php } ?>
                    </form>
                </div>
                <div class="box mt-10">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ tên người nhận</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền(đồng)</th>
                                <th>ID NV duyệt</th>
                                <th>Trạng thái đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($soluong > 0) { ?>
                                <?php foreach ($dulieu as $item) { ?>
                                    <tr>
                                        <td><?= $item["id"] ?></td>
                                        <td><?= $item["hotennhan"] ?></td>
                                        <td><?= $item["sodienthoainhan"] ?></td>
                                        <td><?= $item["diachinhan"] ?></td>
                                        <td><?php if (isset($item["thoigiandat"])) {
                                                echo date("d/m/Y", strtotime($item["thoigiandat"]));
                                            } ?></td>
                                        <td><?= number_format($item["tongtien"], 0, '.', ',') ?></td>
                                        <td><?= $item["id_nhanvien"] ?></td>
                                        <?php if (isset($item["id_nhanvien"])) { ?>
                                            <?php if ($item["trangthaidon"] == 0) { ?>
                                                <td style="color:red;">Đã hủy</td>
                                            <?php } else if ($item["trangthaidon"] == 2) { ?>
                                                <td>
                                                    <form action="./cart_delivery.php" method="POST">
                                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                        <input type="submit" class="button button-green" value="Giao hàng" style="width:93px;" />
                                                    </form>
                                                </td>
                                            <?php } else if ($item["trangthaidon"] == 3) { ?>
                                                <td style="color:green;">Đang giao</td>
                                            <?php } else if ($item["trangthaidon"] == 4) { ?>
                                                <td style="color:green;">Đã nhận</td>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <td>
                                                <form action="./cart_approve.php" method="POST">
                                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                    <input type="submit" class="button button-green" value="Duyệt đơn" style="width:93px;" /> <a href="./cart_cancel.php?id=<?= $item['id'] ?>" class="button button-red" style="width:93px;">Hủy đơn</a>
                                                </form>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" style="text-align: center">Không có dữ liệu nào</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($soluong > 0) { ?>
                    <div class="box page mt-10">
                        <?php
                        for ($i = 1; $i <= ceil($soluong / $max); ++$i) {
                        ?>
                            <a href="./user.php?page=<?= $i ?><?php if ($search) {
                                                                    echo '&search=' . $search;
                                                                } ?>" class="page-item<?php if ($gpage == $i) {
                                                                                            echo ' page-current';
                                                                                        } ?>">
                                <?= $i ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
</body>

</html>