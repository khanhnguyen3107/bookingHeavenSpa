<?php
session_start();
include './config.php';
if (isset($_POST['editdv'])) {
    $_SESSION['madv'] = $_POST['editdv'];
}
$taikhoan = $_SESSION['TenTaiKhoan'];
$madv = $_SESSION['madv'];
$DV = "active";
$QLDV = "active";
// Lấy thông tin chi tiết của dịch vụ
$sql = "SELECT dichvu.MaDV, dichvu.TenDV, dichvu.MoTa, dichvu.NhomDV, banggia.ThoiLuong, banggia.Gia
        FROM dichvu
        LEFT JOIN banggia ON dichvu.MaDV = banggia.MaDV
        WHERE dichvu.MaDV = '$madv'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $thoigian = array();
    $gia = array();
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $tenDV = $row["TenDV"];
        $moTa = $row["MoTa"];
        $nhom = $row["NhomDV"];
        $data[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/logo.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/ViewDV.css">
    <link rel="stylesheet" href="../css/EditDV.css">
    <link rel="stylesheet" href="../css/Account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/ViewDV.js"></script>
    <script src="../css/plugins/toastr/toastr.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet">
</head>

<body>
    <?php
    include("../php/MenuHeader.php");
    if (isset($_POST['upload'])) {
        if(isset($_FILES['image'])) {
            $images = $_FILES['image'];
            for($i = 0; $i < count($images['name']); $i++) {
                // Kiểm tra xem file có lỗi không
                if($images['error'][$i] == 0) {
                    $tt = 1;
                    $images['name'][$i] = $madv."0".$tt.".png";
                    $sql = "SELECT * FROM `anhdichvu`";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if($images['name'][$i] == $row["Anh"]){
                                $tt++;
                                $images['name'][$i] = $madv."0".$tt.".png";
                            }
                            if($tt > 9){
                                $images['name'][$i] = $madv.$tt.".png";
                            }
                        }
                    }
                    $name = $images['name'][$i];
                    $target = "../img/" . basename($name);
                    move_uploaded_file($images['tmp_name'][$i],$target); //Nếu nơi lưu tập tin được tải lên đã tồn tại một tập tin có cùng tên thì tập tin đó sẽ bị ghi đè.
                    $sql = "INSERT INTO anhdichvu (Anh, MaDV) VALUES ('$name', '$madv')";
                    $result = $conn->query($sql);
                }
            }
        }
    }
    ?>
    <form method="post" enctype="multipart/form-data">
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
                            <a href="../php/QLDV.php" class="float-right">
                                <i class="fa fa-arrow-left"></i> Trở lại
                            </a>
                            <div class="ibox-title">
                                <h5 id ='edit_dv'>Chi tiết dịch vụ <?php echo $tenDV ?></h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example">
                                <tr>
                                        <th colspan="2">Nhóm Dịch Vụ</th>
                                        <th width="60%" colspan="2">Mô Tả</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php 
                                                $sql = "SELECT MaNhom,TenNhom FROM nhomdichvu WHERE MaNhom = '$nhom'";
                                                $nhom = $conn->query($sql);
                                                if($nhom->num_rows>0){
                                                    while ($nhom_row = $nhom->fetch_assoc()) {
                                                        echo $nhom_row["TenNhom"];
                                                        $nhomdv = $nhom_row["TenNhom"];
                                                    }
                                                }
                                            ?>
                                        </td>
                                        
                                        <td colspan="2" rowspan="5" style="text-align: left;">
                                            <textarea cols="100" rows="15" name="mota"><?php echo $moTa; ?></textarea>
                                        </td>
                                    <tr>
                                        <th width="20%">Mã Dịch Vụ</th>
                                        <th width="20%">Tên Dịch Vụ</th>

                                    </tr>
                                    <tr>
                                        <td><?php echo $madv; ?></td>
                                        <td><?php echo $tenDV; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Thời Gian (Phút)</th>
                                        <th>Giá (VNĐ)</th>
                                    </tr>

                                    <?php
                                    $a = 0;
                                    foreach ($data as $row) {
                                        echo "<tr>";
                                        echo "<td><input type='text' name='Thoiluong[]' value='{$row['ThoiLuong']}' style='text-align: center;'></td>";
                                        $thoigian[] = $row['ThoiLuong'];
                                        echo "<td><input type='text' name='gia[]' value='{$row['Gia']}' style='text-align: center;'></td>";
                                        $gia[] = $row['Gia'];
                                        echo "</tr>";
                                        $a++;
                                    }
                                    ?>
                                    
                                    
                                </table>
                                <div class="input_fields_wrap"></div>
                                <input type="hidden" name="bienphp" id="bienphp" value="-1" />
                                <button type="button" class="btn btn-primary" id="addMore">Thêm</button>
                                <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <tr>
                                        <th colspan="4">
                                            Hình Ảnh
                                        </th>
                                    </tr>
                                    <tr>
                                        <?php
                                        $anh = array();
                                        $anhxoa = array();
                                        $sql = "SELECT Anh
                                        FROM anhdichvu
                                        WHERE MaDV = '$madv'";
                                        $sub_anh = $conn->query($sql);
                                        if ($sub_anh->num_rows > 0) {
                                            $count = 0; // Biến đếm số ảnh đã hiển thị
                                            echo "<tr>";
                                            while ($sub_anh_row = $sub_anh->fetch_array()) {
                                                $anh[] = $sub_anh_row['Anh']; // Thêm tên ảnh vào mảng
                                                echo "<td><div class='image-container' data-image='{$sub_anh_row['Anh']}>
                                                <a title='{$tenDV}' target='blank' href='..\img/{$sub_anh_row['Anh']}'>
                                                    <img src='..//img/{$sub_anh_row['Anh']}' alt='Lỗi Hình ảnh' width='390px'>
                                                </a>
                                                <span class='close'>×</span>
                                            </div></td>"; // Thêm nút xóa với tên ảnh làm dữ liệu

                                                $count++;
                                                // Nếu đã hiển thị 2 ảnh, tạo một hàng mới
                                                if ($count % 3 == 0) {
                                                    echo "</tr><tr>";
                                                }
                                            }
                                            echo "</tr>";
                                        } else {
                                            echo "<tr><td colspan='2'>Chưa có ảnh.</td></tr>";
                                        }
                                        ?>
                                    </tr>

                                </table>
                                <?php
                                if (isset($_POST['submit'])) {
                                    $mota = $_POST['mota'];
                                    $thoiluong = $_POST['Thoiluong'];
                                    $Gia = $_POST['gia'];
                                    for($i = 0; $i < $a; $i++){
                                        if(($thoiluong[$i] < 0 || $Gia[$i] < 0 || !is_numeric($thoiluong[$i]) || !is_numeric($Gia[$i]))){
                                            echo "<script>alert('Thời lượng hoặc giá phải là số và lớn hơn 0','Thông báo từ hệ thống');</script>";
                                            echo "<script>window.location.href = './QLDV.php';</script>";
                                        }
                                    }
                                    $removedImages = explode(',', $_POST['removedImages']);
                                    $fileNames = array_map('basename', $removedImages);
                                    $x = $_POST['bienphp'];
                                    $dem = 0;
                                    if($x>=-1){
                                        for ($i = 0; $i < $a; $i++) {
                                            $sql = "UPDATE `banggia` 
                                            SET `ThoiLuong` = '$thoiluong[$i]', `Gia` = '$Gia[$i]'
                                            WHERE `banggia`.`ThoiLuong` = '$thoigian[$i]' AND `MaDV` = '$madv'";
                                            $sql1 = "UPDATE `dichvu` 
                                            SET `MoTa` = '$mota'
                                            WHERE `dichvu`.`MaDV` = '$madv'";
                                            $result = $conn->query($sql);
                                            $result = $conn->query($sql1);
                                            $dem++;
                                        }
                                        for($i = 0; $i < $x; $i++){
                                            $sql = "INSERT INTO `banggia` (`MaDV`, `ThoiLuong`, `Gia`) 
                                            VALUES ('$madv', '$thoiluong[$dem]', '$Gia[$dem]');";
                                            $result = $conn->query($sql);
                                            $dem++;
                                        }
    
                                        for ($i = 0; $i < $count; $i++) {
                                            $sql = "DELETE FROM anhdichvu
                                            WHERE MaDV = '$madv'
                                            AND Anh = '$fileNames[$i]';";
                                            $result = $conn->query($sql);
                                        }
                                        if ($result) {
                                            echo "<script>alert('cập nhật thông tin dịch vụ thành công','Thông báo từ hệ thống');</script>";
                                            echo "<script>window.location.href = './QLDV.php';</script>";
                                        } else {
                                            echo "<script>alert('cập nhật thông tin dịch vụ thất bại','Thông báo từ hệ thống');</script>";
                                        }
                                        $conn->close();
                                    } else{
                                        for ($i = 0; $i < $a; $i++) {
                                            $sql = "UPDATE `banggia` 
                                            SET `ThoiLuong` = '$thoiluong[$i]', `Gia` = '$Gia[$i]'
                                            WHERE `banggia`.`ThoiLuong` = '$thoigian[$i]' AND `MaDV` = '$madv'";
                                            $sql1 = "UPDATE `dichvu` 
                                            SET `MoTa` = '$mota'
                                            WHERE `dichvu`.`MaDV` = '$madv'";
                                            $result = $conn->query($sql);
                                            $result = $conn->query($sql1);
                                            $dem++;
                                        }
                                        for ($i = 0; $i < $count; $i++) {
                                            $sql = "DELETE FROM anhdichvu
                                            WHERE MaDV = '$madv'
                                            AND Anh = '$fileNames[$i]';";
                                            $result = $conn->query($sql);
                                        }
                                        if ($result) {
                                            echo "<script>alert('cập nhật thông tin dịch vụ thành công','Thông báo từ hệ thống');</script>";
                                            echo "<script>window.location.href = './QLDV.php';</script>";
                                        } else {
                                            echo "<script>alert('cập nhật thông tin dịch vụ thất bại','Thông báo từ hệ thống');</script>";
                                        }
                                        $conn->close();
                                    }
                                    
                                }
                                if(isset($_POST['edit'])){
                                    $tendv = $_POST['TenDV'];
                                    $nhomdv = $_POST['NhomDV'];
                                    $stt = 1;
                                    $madvs = $nhomdv."0".$stt;
                                    $sql = "SELECT * FROM `dichvu`";
                                    $result = $conn->query($sql);
                                    if($result->num_rows>0){
                                        while($row = $result->fetch_assoc()){
                                            if($madvs == $row['MaDV']){
                                                $stt++;
                                                $madvs = $nhomdv."0".$stt;
                                            }
                                            if($stt>9){
                                                $madvs = $nhomdv.$stt;
                                            }
                                        }
                                    }
                                    $sql = "UPDATE `dichvu` 
                                    SET `MaDV` = '$madvs', `TenDV` = '$tendv', `NhomDV` = '$nhomdv' 
                                    WHERE `dichvu`.`MaDV` = '$madv';";
                                    $result = $conn->query($sql);
                                    if ($result) {
                                        echo "<script>alert('cập nhật thông tin dịch vụ thành công','Thông báo từ hệ thống');</script>";
                                        echo "<script>window.location.href = './QLDV.php';</script>";
                                    } else {
                                        echo "<script>alert('cập nhật thông tin dịch vụ thất bại','Thông báo từ hệ thống');</script>";
                                    }
                                }
                                ?>
                                <div class="submit_button">
                                    <input type="hidden" name="removedImages" id="removedImages">
                                    <input type="file" name="image[]" id="image" style="display: none;" accept="image/*" multiple onchange="updateFileList()">
                                    <label for="image" style="left: 84.5%;"><i class="fa fa-image"></i> Chọn ảnh</label>
                                    <button class="btn btn-primary" name="upload">Tải ảnh lên</button>
                                    <button class="btn btn-primary" name="submit">Sửa thông tin</button>
                                    <p id="fileList" style="position: relative; right: 48%;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
<div class="modalBox hidden">
    <div class="inner">
        <div class="header">
            <p>Chỉnh sửa thông tin dịch vụ</p>
            <i class="fas fa-times" id = "close" style="font-size: 25px;"></i>
        </div>
        <div class="body">
            <h2 style="margin-bottom: 20px;">Thông tin dịch vụ</h2>
            <div style="display: flex;">
                <div style="width: 50%;">
                    Tên dịch vụ:<br><br>
                    Mã dịch vụ:<br><br>
                    Nhóm dịch vụ:<br><br>
                </div>
                <div style="width: 50%;">
                    <input type="text" name = "TenDV" value="<?php if(isset($tenDV)) echo $tenDV; ?>" style="width: 75%;" ><br><br>
                    <input type="text" name = "MaDV" value="<?php if(isset($madv)) echo $madv; ?>" readonly style="width: 75%;"><br><br>
                    <select name="NhomDV" id="NhomDV">
                        <?php
                        $sql = "SELECT * FROM `nhomdichvu`";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['MaNhom']}'>{$row['TenNhom']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="footer">
            <button id ="edit" name="edit">Sửa thông tin</button>
        </div>
    </div>
</div>
    </form>
</body>
<script src="../js/EditDV.js"></script>
</html>
<?php
} else {
echo "Dịch vụ không tồn tại.";
}
?>