<?php
session_start();
include './config.php';
$taikhoan = $_SESSION['TenTaiKhoan'];
if (isset($_POST['viewdv'])) {
    $_SESSION['madv'] = $_POST['viewdv'];
}
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
    $row = $result->fetch_assoc();
    $tenDV = $row["TenDV"];
    $moTa = $row["MoTa"];
    $nhom = $row["NhomDV"];
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

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/ViewDV.js"></script>
        <script src="../css/plugins/toastr/toastr.min.js"></script>


        <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet">
    </head>

    <body>
        <?php
        include("../php/MenuHeader.php");
        ?>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message"><a href="/main/php/index.php" style="color: inherit; text-decoration: none;">HEAVEN SPA</a></span>
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
                                <h5>Chi tiết dịch vụ <?php echo $tenDV ?></h5>
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
                                            if ($nhom->num_rows > 0) {
                                                while ($nhom_row = $nhom->fetch_assoc()) {
                                                    echo $nhom_row["TenNhom"];
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td colspan="2" rowspan="7" style="text-align: left;">
                                            <?php
                                            $sub_moTa = explode("\n", $moTa);
                                            foreach ($sub_moTa as $value) {
                                                echo "- ";
                                                echo $value;
                                                echo "<br>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
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
                                    $sql = "SELECT ThoiLuong, Gia
                                    FROM banggia
                                    WHERE MaDV = '$madv'
                                    ORDER BY CAST(ThoiLuong AS SIGNED) ASC";
                                    $sub_banggia = $conn->query($sql);

                                    if ($sub_banggia->num_rows > 0) {
                                        while ($sub_banggia_row = $sub_banggia->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$sub_banggia_row["ThoiLuong"]}</td>";
                                            echo "<td>{$sub_banggia_row["Gia"]}</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>Chưa có thông tin giá và thời lượng.</td></tr>";
                                    }
                                    ?>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <tr>
                                        <th colspan="4">
                                            Hình Ảnh
                                        </th>
                                    </tr>
                                    <tr>
                                        <?php
                                        $sql = "SELECT Anh
                                        FROM anhdichvu
                                        WHERE MaDV = '$madv'";
                                        $sub_anh = $conn->query($sql);

                                        if ($sub_anh->num_rows > 0) {
                                            $totalImages = $sub_anh->num_rows;
                                            $count = 0; // Biến đếm số ảnh đã hiển thị
                                            $lastRow = false; // Biến kiểm tra xem có phải hàng cuối không đủ

                                            while ($sub_anh_row = $sub_anh->fetch_array()) {
                                                $count++;

                                                if ($totalImages % 2 == 0) {
                                                    // Nếu tổng số ảnh là chẵn
                                                    echo "<td colspan='2' width='50%'><a title='{$tenDV}' target='_blank' href='../img/{$sub_anh_row['Anh']}'><img src='..//img/{$sub_anh_row['Anh']}' alt='Lỗi Hình ảnh' width='500px'></a></td>";
                                                } else {
                                                    // Nếu tổng số ảnh là lẻ
                                                    if ($count == $totalImages && $totalImages % 2 == 1) {
                                                        // Ảnh cuối cùng nằm giữa
                                                        echo "<td colspan='2' width='50%' style='text-align: center;'><a title='{$tenDV}' target='_blank' href='../img/{$sub_anh_row['Anh']}'><img src='..//img/{$sub_anh_row['Anh']}' alt='Lỗi Hình ảnh' width='500px'></a></td>";
                                                    } else {
                                                        echo "<td width='50%'><a title='{$tenDV}' target='_blank' href='../img/{$sub_anh_row['Anh']}'><img src='..//img/{$sub_anh_row['Anh']}' alt='Lỗi Hình ảnh' width='500px'></a></td>";
                                                    }
                                                }

                                                // Nếu đã hiển thị 2 ảnh, tạo một hàng mới
                                                if ($count % 2 == 0) {
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
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </form>

    </body>

    </html>

<?php

} else {
    echo "Dịch vụ không tồn tại.";
}

?>