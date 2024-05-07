<?php
$TENTK = $_SESSION["TenTaiKhoan"];
if (isset($_POST['logout'])) {
    $_SESSION["TenTaiKhoan"] = null;
    header("Location:../php/Login.php");
}
if (!isset($_SESSION["TenTaiKhoan"])) {
    header("Location:../php/Login.php");
}
$sql = "SELECT * 
    FROM `tkquanly`
    WHERE TenTaiKhoan = '$TENTK';";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tentaikhoan = $row['TenTaiKhoan'];
        $taikhoan = $row['TaiKhoan'];
        $taikhoan2 = $row['TaiKhoan'];
        $matkhau = $row['MatKhau'];
        $phanquyen = $row['PhanQuyen'];
        $email = $row['Email'];
        $sdt = $row['SDT'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/Account.css">
    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs">
                                    <img alt="image" class="img-circle" width="45%" src="../img/logo.jpg" /><br>
                                    <strong class="font-bold"><?php echo $tentaikhoan ?></strong><br>
                                    <strong class="font-bold"><?php if ($phanquyen == 1) {
                                                                    echo "Chức vụ: Quản Lý";
                                                                } else {
                                                                    echo "Chức vụ: Nhân Viên";
                                                                } ?></strong>
                                </span> <span class="text-muted text-xs block">Thông Tin<b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li id="userInfo">Thông tin tài khoản</li>
                            <li class="divider"></li>
                            <li><a href="../php/login.php">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        HEAVEN SPA
                    </div>
                </li>
                <li class="<?php echo $DV ?>">
                    <a href=""><i class="fa fa-th-large"></i> <span class="nav-label">Dịch Vụ</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="<?php echo $QLDV ?>"><a href="../php/QLDV.php">Danh Sách Dịch Vụ</a></li>
                        <li class="<?php echo $TDV ?>"><a href="../php/AddDV.php">Thêm Dịch Vụ</a></li>
                    </ul>
                </li>
                <li class="<?php echo $LD ?>">
                    <a href="#"><i class="fa fa-calendar"></i> <span class="nav-label">Lịch Đặt</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="<?php echo $LDCK ?>">
                            <a  href="#"><i class="fa fa-calendar-o"></i>Lịch Đặt Của Khách <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li class="<?php echo $LDCD ?>">
                                    <a href="./LCD.php" ><i class="fa fa-spinner"></i>Lịch chờ duyệt</a>
                                </li>
                                <li class="<?php echo $LDD ?>">
                                    <a href="./LDD.php" ><i class="fa fa-check"></i>Lịch đã duyệt</a>
                                </li>
                            </ul>
                        </li>        
                        <li class="<?php echo $LHN ?>">
                            <a href="#"><i class="fa fa-calendar-o"></i>Lịch Hôm Nay<span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li class="<?php echo $LCDHN ?>">
                                    <a href="../php/LCDHN.php"><i class="fa fa-spinner"></i>Lịch Chưa Duyệt</a>
                                </li>
                                <li class="<?php echo $LDHN ?>">
                                    <a href="../php/LDHN.php"><i class="fa fa-check"></i>Lịch Đã Duyệt</a>
                                </li>
                            </ul>
                        </li>
                        <li class="<?php echo $LHT ?>"><a href="../php/LHT.php"><i class="fa fa-calendar-check-o"></i>Lịch Hoàn Thành</a></li>
                        <li class="<?php echo $LDH ?>"><a href="../php/LDH.php"><i class="fa fa-calendar-times-o"></i>Lịch Đã Hủy</a></li>
                        <li class="<?php echo $TH ?>"><a href="../php/QLLD.php"><i class="fa fa-calendar"></i>Tổng Hợp</a></li>
                    </ul>
                </li>

                <li class="<?php echo $KH ?>">
                    <a href=""><i class="fa fa-user"></i> <span class="nav-label">Khách Hàng</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="<?php echo $TTKH ?>"><a href="../php/TTKH.php">Thông Tin</a></li>
                        <li class="<?php echo $PH ?>"><a href="../php/QLPH.php">Phản Hồi</a></li>
                        <li class="<?php echo $BL ?>"><a href="../php/QLBL.php">Bình Luận</a></li>
                    </ul>
                </li>
                <li class="<?php echo $TK ?>">
                    <a href="../php/QLTK.php"><i class="fa fa-bar-chart"></i> <span class="nav-label">Thống Kê Dịch Vụ</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="<?php echo $TKDV ?>"><a href="../php/TKDV.php">Theo Ngày</a></li>
                        <li class="<?php echo $TKDVT ?>"><a href="../php/TKDVT.php">Theo Tháng</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </nav>
    <?php
        if(isset($_POST['edit2'])){
            $tentaikhoan = $_POST['TenTaiKhoan'];
            $taikhoan = $_POST['TaiKhoan'];
            $matkhau = $_POST['MatKhau'];
            $email = $_POST['Email'];
            $sdt = $_POST['SDT'];
            $sql = "UPDATE `tkquanly`
            SET `TaiKhoan` = '$taikhoan', `MatKhau` = '$matkhau', `TenTaiKhoan` = '$tentaikhoan', `Email` = '$email', `SDT` = '$sdt'
            WHERE `tkquanly`.`TaiKhoan` = '$taikhoan2';";
            $result = $conn->query($sql);
            if($result){
                echo "<script>alert('Cập nhật thông tin thành công','Thông báo từ hệ thống');</script>";
            } else{
                echo "<script>alert('Cập nhật thông tin thất bại','Thông báo từ hệ thống');</script>";
            }
        }
    ?>
    <form method="post">
    <div class="modalBox hidden">
    <div class="inner" style="height: 440px; width: 420px;">
        <div class="header">
            <p>Chỉnh sửa thông tin tài khoản</p>
            <i class="fa fa-times" id = "close" style="font-size: 25px;"></i>
        </div>
        <div class="body">
            <h2 style="margin-bottom: 20px;">Thông tin tài khoản</h2>
            <div style="display: flex;">
                <div style="width: 50%;">
                    Tên tài khoản:<br><br>
                    Tài khoản:<br><br>
                    Mật Khẩu:<br><br>
                    Phân quyền:<br><br>
                    Email:<br><br>
                    SĐT:<br><br>
                </div>
                <div style="width: 50%;">
                    <input type="text" name="TenTaiKhoan" value="<?php if(isset($tentaikhoan)) echo $tentaikhoan ?>" style="margin-bottom: 12px;"><br>
                    <input type="text" name="TaiKhoan" value="<?php if(isset($taikhoan)) echo $taikhoan ?>" style="margin-bottom: 12px;"><br>
                    <input type="text" name="MatKhau" value="<?php if(isset($matkhau)) echo $matkhau ?>" style="margin-bottom: 12px;"><br>
                    <input type="text" name="PhanQuyen" value="<?php if(isset($phanquyen)){ if($phanquyen == 1){ echo "Quản lý";} else {echo "Nhân viên";} }?>" style="margin-bottom: 12px;" readonly><br>
                    <input type="text" name="Email" value="<?php if(isset($email)) echo $email ?>" style="margin-bottom: 12px;"><br>
                    <input type="text" name="SDT" value="<?php if(isset($sdt)) echo $sdt ?>" style="margin-bottom: 12px;"><br>
                </div>
            </div>
        </div>
        <div class="footer">
            <button id ="edit" name="edit2">Sửa thông tin</button>
        </div>
    </div>
</div>
    </form>
</body>
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<script src="../js/MenuHeader.js"></script>
<script src="../js/inspinia.js"></script>
<script src="../js/plugins/pace/pace.min.js"></script>

</html>