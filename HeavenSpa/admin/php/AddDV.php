<?php
include("./config.php");
session_start();
$taikhoan = $_SESSION['TenTaiKhoan'];
$DV = "active";
$TDV = "active";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="../css/plugins/toastr/toastr.min.js"></script>
    <link rel="stylesheet" href="../css/AddDV.css">
    <link rel="icon" href="../img/logo.jpg" type="image/x-icon" />


    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <title>Thêm Dịch Vụ</title>
</head>

<body>
    <?php
    include("./MenuHeader.php");
    if (isset($_POST["submit"])) {
        $tendv = $_POST['TenDV'];
        $mota = $_POST['MoTa'];
        $thoigian = $_POST['thoiGian'];
        $giatien = $_POST['giaTien'];
        $nhomdv = $_POST['NhomDV'];
        if ($nhomdv == "nhomkhac") {
            $nhomdv = $_POST['MaNhomKhac'];
            $tennhom = $_POST['TenNhomKhac'];
            $motanhom = $_POST['MoTaNhomKhac'];
            $sql = "INSERT INTO `nhomdichvu` (`MaNhom`, `TenNhom`, `MoTaNhom`) VALUES ('$nhomdv','$tennhom','$motanhom');";
            $result = $conn->query($sql);
        }
        $stt = 1;
        $madv = $nhomdv . "0" . $stt;
        $sql = "SELECT * FROM `dichvu`";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($madv == $row['MaDV']) {
                    $stt++;
                    $madv = $nhomdv . "0" . $stt;
                }
                if ($stt > 9) {
                    $madv = $nhomdv . $stt;
                }
            }
        }
        $length = count($thoigian);
        for($i = 0; $i < $length; $i++){
            if(($thoigian[$i] < 0 || $giatien[$i] < 0 || !is_numeric($thoigian[$i]) || !is_numeric($giatien[$i]))){
                echo "<script>alert('Thời lượng hoặc giá phải là số và lớn hơn 0','Thông báo từ hệ thống');</script>";
                echo "<script>window.location.href = './QLDV.php';</script>";
            }
        }
        $sql = "INSERT INTO `dichvu` (`MaDV`, `TenDV`, `MoTa`, `NhomDV`) VALUES ('$madv', '$tendv', '$mota', '$nhomdv');";
        $result = $conn->query($sql);
        for ($i = 0; $i < $length; $i++) {
            $sql = "INSERT INTO `banggia` (`MaDV`, `ThoiLuong`, `Gia`) VALUES ('$madv', '$thoigian[$i]', '$giatien[$i]');";
            $result = $conn->query($sql);
        }
        if (isset($_FILES['image'])) {
            $images = $_FILES['image'];

            // Duyệt qua từng file
            for ($i = 0; $i < count($images['name']); $i++) {
                // Kiểm tra xem file có lỗi không
                if ($images['error'][$i] == 0) {
                    $tt = 1;
                    $images['name'][$i] = $madv . "0" . $tt . ".png";
                    $sql = "SELECT * FROM `anhdichvu`";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if ($images['name'][$i] == $row["Anh"]) {
                                $tt++;
                                $images['name'][$i] = $madv . "0" . $tt . ".png";
                            }
                            if ($tt > 9) {
                                $images['name'][$i] = $madv . $tt . ".png";
                            }
                        }
                    }
                    $name = $images['name'][$i];
                    $target = "../img/" . basename($name);
                    move_uploaded_file($images['tmp_name'][$i], $target);
                    $sql = "INSERT INTO anhdichvu (Anh, MaDV) VALUES ('$name', '$madv')";
                    $result = $conn->query($sql);
                }
            }
        }
        if ($result) {
            echo "<script>alert('Thêm dịch vụ thành công','Thông báo từ hệ thống');</script>";
            echo "<script>window.location.href = './QLDV.php';</script>";
        } else {
            echo "<script>alert('Thêm dịch vụ thất bại','Thông báo từ hệ thống');</script>";
        }
        $conn->close();
    }
    ?>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">HEAVEN SPA</span>
                    </li>
                    <li>
                        <a href="../php/login.php">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-container">
                                <div class="form-row">
                                    <label for="TenDV">Tên dịch vụ:</label>
                                    <input type="text" id="TenDV" name="TenDV" required>
                                </div>
                                <div class="form-row" style="margin-left: 150px;">
                                    <label for="NhomDV">Nhóm dịch vụ:</label>
                                    <select name="NhomDV" id="NhomDV" style="position: absolute; top:8px;">
                                        <?php
                                        $sql = "SELECT * FROM `nhomdichvu`";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='{$row['MaNhom']}'>{$row['TenNhom']}</option>";
                                            }
                                            echo "<option value='nhomkhac'>nhóm khác</option>";
                                        }
                                        ?>
                                    </select>
                                    <div id="nhomkhacdiv" style="display: none; position: relative; top: 25px;">
                                        <br>
                                        <label for="MaNhomKhac">Mã nhóm:</label>
                                        <input type="text" id="nhomKhacInput" name="MaNhomKhac">
                                        <label for="TenNhomKhac">Tên nhóm:</label>
                                        <input type="text" id="nhomKhacInput" name="TenNhomKhac">
                                        <label for="MoTaNhomKhac">Mô tả nhóm:</label>
                                        <textarea id="nhomKhacInput" name="MoTaNhomKhac" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label for="MoTa">Mô tả dịch vụ:</label>
                                    <textarea id="MoTa" name="MoTa" rows="4" required></textarea>
                                </div>
                                <div class="form-row">
                                    <label for="Anh" style="left: 65%;">Hình ảnh:</label>
                                    <input type="file" id="image" name="image[]" style="display: none;" multiple accept="image/*" onchange="updateFileList()">
                                    <label for="image" style="left: 73.5%;"><i class="fa fa-image"></i> Chọn ảnh</label>
                                    <p id="fileList"></p>
                                </div>
                                <div class="form-row">
                                    <label for="thoiGianGiaTien">Thời gian và<br> Giá tiền:</label>
                                    <div class="time-price-container">
                                        <div class="time-price-row">
                                            <input type="text" name="thoiGian[]" placeholder="Thời gian" required>
                                            <input type="text" name="giaTien[]" placeholder="Giá tiền" required>
                                            <button type="button" onclick="removeTimePrice(this)">Xóa</button>
                                        </div>
                                    </div>
                                    <button type="button" onclick="addTimePrice()">Thêm</button>
                                </div>

                                <div class="form-row">
                                    <input type="submit" name="submit" value="Thêm Dịch Vụ">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="../js/AddDV.js"></script>
</body>

</html>