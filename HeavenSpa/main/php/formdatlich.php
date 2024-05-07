<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
    <link rel="stylesheet" href="../css/formdl.css">
    <!-- Bao gồm jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script src="../js/select.js"></script>
    <title>form dat lich</title>
</head>


<body>

    <?php
    include("connect.php");
    include("header.php");
    ?>
    <?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    if (isset($_POST['submit'])) {
        $sdt = $_POST['sdt'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $gioitinh = $_POST['gioitinh'];
        $madichvu = $_POST['chondichvu'];
        $thoiluong = $_POST['chonthoiluong'];
        $gioden = $_POST['gioden'];
        $pattern = '#^?[\d]3?-?[\d]2?-[\d]{2}\.[\d]{3}-[\d]{3}$#';
        if(!is_numeric($sdt) || strlen($sdt)!=10 || !preg_match( '/^[+]?[0-9() -]*$/', $sdt )){
            $msg = "Số điện thoại không hợp lệ";
        }else{
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $msg = "Email không hợp lệ";
            }else{
                if (!empty($gioden)) {
                    $giophut = date($gioden);
                    $array = explode(":", $giophut);
                    
                    if (isset($array[0]) && isset($array[1])) {
                        $hour = intval($array[0]);
                        $minute = intval($array[1]);
                        $int_gioden = $hour * 60 + $minute;
                        $ngayden = $_POST['ngayden'];
                        $parts = explode(" ", $name);
                        $ho = isset($parts[0]) ? $parts[0] : ''; // Lấy phần tử đầu tiên là họ
                        $ten = end($parts);
                        $date = date("d-m-Y", time());
                        $array = explode("-", $date);
                        $two_digits = $array[0] . $array[1];
                        $homnay = $array[0] . $array[1] . $array[2];
                        $homnay = (int)$homnay;
                        $ten_dau_chu = substr($ten, 0, 1);
                        $ba_so_cuoi = substr($sdt, -3);
                        $makh = $two_digits . $ten_dau_chu . $ba_so_cuoi;
                        $solan = 1;
                        $maDL = "HEV".$two_digits."0".$solan;
                        $sql = "SELECT * FROM `datlich`";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                if($maDL == $row['MaDL']){
                                    $solan++;
                                    $maDL = "HEV".$two_digits."0".$solan;
                                }
                                if($solan>9){
                                    $solan++;
                                    $maDL = "HEV".$two_digits.$solan;
                                }
                                if($maDL == $row['MaDL'] && $solan > 9){
                                    $solan++;
                                    $maDL = "HEV".$two_digits.$solan;
                                }
                            }
                        }
                        $sql = "SELECT * FROM `khachhang`
                        WHERE SDT = $sdt";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                $makhOld = $row['MaKH'];
                                if($ten == $row['TenKH'] && $ho == $row['HoKH']){
                                    $sql = "INSERT INTO `datlich`(`MaDL`, `MaKH`, `MaDV`, `ThoiGianDat`, `NgayDat`, `TrangThai`, `ThoiLuong`) 
                                    VALUES ('$maDL','$makhOld','$madichvu','$gioden','$ngayden','Đã đặt','$thoiluong')";
                                    $result = $conn->query($sql);
                                    if($result){
                                        echo "<script>alert('Cập nhật thông tin thành công','Thông báo từ hệ thống');</script>";
                                        echo "<script>window.location.href = './index.php';</script>";
                                    } else{
                                        echo "<script>alert('Cập nhật thông tin thất bại','Thông báo từ hệ thống');</script>";
                                    }
                                } else{
                                    $sql = "UPDATE `khachhang` 
                                    SET `HoKH`='$ho',`TenKH`='$ten',`Email`='$email',`SDT`='$sdt',`GioiTinh`='$gioitinh'
                                    WHERE SDT = $sdt";
                                    $result = $conn->query($sql);
                                    $sql = "INSERT INTO `datlich`(`MaDL`, `MaKH`, `MaDV`, `ThoiGianDat`, `NgayDat`, `TrangThai`, `ThoiLuong`) 
                                    VALUES ('$maDL','$makhOld','$madichvu','$gioden','$ngayden','Đã đặt','$thoiluong')";
                                    $result = $conn->query($sql);
                                    if($result){
                                        echo "<script>alert('Cập nhật thông tin thành công','Thông báo từ hệ thống');</script>";
                                        echo "<script>window.location.href = './index.php';</script>";
                                    } else{
                                        echo "<script>alert('Cập nhật thông tin thất bại','Thông báo từ hệ thống');</script>";
                                    }
                                }
                            }
                        } else{
                            $sql = "INSERT INTO `khachhang` (`MaKH`, `HoKH`, `TenKH`, `Email`, `SDT`, `GioiTinh`) 
                                    VALUES ('$makh', '$ho', '$ten', '$email', '$sdt', '$gioitinh');";
                                    $result = $conn->query($sql);
                            $sql = "INSERT INTO `datlich`(`MaDL`, `MaKH`, `MaDV`, `ThoiGianDat`, `NgayDat`, `TrangThai`, `ThoiLuong`) 
                                    VALUES ('$maDL','$makh','$madichvu','$gioden','$ngayden','Đã đặt','$thoiluong')";
                                    $result = $conn->query($sql);
                                    if($result){
                                        echo "<script>alert('Cập nhật thông tin thành công','Thông báo từ hệ thống');</script>";
                                        echo "<script>window.location.href = './index.php';</script>";
                                    } else{
                                        echo "<script>alert('Cập nhật thông tin thất bại','Thông báo từ hệ thống');</script>";
                                    }
                        }

                    } else {
                        // Xử lý khi không có đúng định dạng giờ
                        $msg = "Giờ đến không đúng định dạng";
                    }
                    
                } else {
                    // Xử lý khi không có giá trị
                    $msg = "Vui lòng nhập giờ đến";
                }
               


            }
        }
    }
    ?>
    <div class="content-item">

        <div class="form-content">
            <img src="../img/bgdl.png" width="400px" height="400px" alt="">
        </div>
        <div class="form">
            <form method="post" name="formdatlich" class="formdl" action="">
                <?php if(isset($msg)) echo $msg ?>
                <h3>ĐẶT LỊCH HẸN VỚI CHÚNG TÔI</h3>
                <input type="text" name="sdt" value="<?php if(isset($sdt)) echo $sdt;?>" class="sdt2" placeholder="Số điện thoại" required><br>
                <input type="text" name="name" value="<?php if(isset($name)) echo $name;?>" class="hoten2" placeholder="Họ và tên" required><br>
                <input type="email" name="email" value="<?php if(isset($email)) echo $email;?>" placeholder="Email" required><br>
                  
                    <div style="margin-left: 150px;" >
                        <input type="radio" id="0" name="gioitinh" class="gtnam" value="0" required checked>Nam
                        <input class="gtnu" type="radio" id="1" name="gioitinh" required value="1">Nữ <br>
                    </div>
                <label style="font-size: 15px; color:black" for="" >Chọn dịch vụ:</label>
                <select  class="form-select form-select-sm mb-3" id="chonnhomdv" name="chonnhomdv" required aria-label=".form-select-sm">
                    <option value="Chọn dịch vụ" selected>Chọn nhóm dịch vụ</option>
                    <?php
                    $sql = "SELECT * FROM nhomdichvu";
                    $result = $conn->query($sql);
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo $row['MaNhom'] ?>"><?php echo $row['TenNhom'] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <label style="font-size: 15px; color:black" for="">Chọn Dịch vụ</label>
                <select class="form-select form-select-sm mb-3" id="chondichvu" name="chondichvu" required aria-label=".form-select-sm">
                    <option value="" selected>Chọn một dịch vụ</option>
                </select>
                <label style="font-size: 15px; color:black" for="">Chọn Gói Thời Lượng</label>
                <select class="form-select form-select-sm mb-3" id="chonthoiluong" required name="chonthoiluong" aria-label=".form-select-sm">
                    <option value="" selected>Chọn thời lượng</option>
                </select>
                <input type="time" class="gio2" name="gioden" required value="" placeholder="Giờ đến"><br>
                <input type="date" name="ngayden" class="ngay2" required value="" placeholder="Ngày đến"><br>
                <div style="display: flex;">
                    <input  class="gui2" style="margin-right: 20px;" type="submit" name="submit" value="Gửi">
                    <input type="reset" class="reset2" value="Nhập lại">
                </div>
            </form>
        </div>
    </div>
    <div class="return">
        <a style="font: 17px;" href="index.php">Quay lại trang chủ</a>
    </div>

    <?php
    include("footer.php");
    ?>
</body>

</html>