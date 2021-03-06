<?php
require_once "../core/core.user.php";
try {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $id = $_GET["id-product"];
        $quantity = $_GET["quantity"];
        if (empty($_SESSION['cart'][$id])) {
            $sql = "SELECT * FROM SANPHAM
        WHERE id = '$id'";
            $result = select($sql)[0];
            $_SESSION['cart'][$id]['name'] = $result['ten'];
            $_SESSION['cart'][$id]['image'] = $result['anh'];
            $_SESSION['cart'][$id]['price'] = $result['gia'];
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        } else {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        }
    }
    // Gui binh luan
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = $_POST["id-product"];
        $id_review = $_POST['id-review'];
        $comment = addslashes($_POST['comment']);
        $rating = addslashes($_POST['rating-star']);
        update("DANHGIA", [
            "nhanxet" => "$comment",
            "chatluong" => "$rating",
            "thoigian" => date("Y-m-d")
        ], "`id` = '$id_review'");
        header("Location: ./product.php?id=$id");
    }
} catch (\Throwable $th) {
}

