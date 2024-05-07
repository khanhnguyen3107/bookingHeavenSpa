<?php
session_start();
include './config.php';
$taikhoan = $_SESSION['TenTaiKhoan'];
$DV = "active";
$QLDV = "active";

if (isset($_POST["search"])) {
    $search = $_POST["search"];
} else {
    $search = "";
}

// Lấy tổng số hàng trong bảng dịch vụ
$sql = "SELECT COUNT(*) AS total_rows FROM dichvu WHERE TenDV LIKE '%$search%'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_rows = $row['total_rows'];
$num_entries = 3;
// Tính tổng số trang

$pages = ceil($total_rows / $num_entries);
// Đảm bảo trang hiện tại nằm trong giới hạn hợp lệ
$current_page = (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pages) ? $_GET['page'] : 1;

// Tính offset cho truy vấn SQL
$offset = ($current_page - 1) * $num_entries;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="icon" href="../img/logo.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../css/style.css">

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
    if(isset($_POST['delete_button'])){
        $madv = $_POST['delete_id'];
        $sql = "DELETE FROM dichvu
        WHERE MaDV = '$madv';";
        $result = $conn->query($sql);
        if($result){
            echo "<script>alert('Đã xóa dịch vụ','Thông báo từ hệ thống');</script>";
        }else{
            echo "<script>alert('Không thể xóa dịch vụ','Thông báo từ hệ thống');</script>";
        }
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
                        <div class="ibox-title">
                            <h5>Bảng Dịch Vụ Của Heaven Spa</h5>
                        </div>
                        <form method="post" id="search">

                            <input size="25px" placeholder="Nhập dịch vụ cần tìm..." type="text" name="search" value="<?php if (isset($_POST["search"])) {
                                                                                                                            echo $_POST["search"];
                                                                                                                        } ?>">
                            <button type='submit' name='submit'>
                                <i class='fa fa-search'></i> Tìm Kiếm
                            </button>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã Dịch Vụ</th>
                                        <th>Tên Dịch Vụ</th>
                                        <th>Nhóm Dịch Vụ</th>
                                        <th>Mô Tả</th>
                                        <th style="text-align: center;">Thời Gian</th>
                                        <th style="text-align: center;">Giá</th>
                                        <th style="text-align: center;">Chi Tiết</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stt = ($current_page - 1) * $num_entries + 1;
                                    $sql = "SELECT dichvu.MaDV, dichvu.TenDV, dichvu.MoTa, dichvu.NhomDV
                                    FROM dichvu
                                    WHERE TenDV LIKE '%$search%'  OR MaDV LIKE '%$search%' LIMIT $offset, $num_entries";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        $result->data_seek(0);
                                        while ($row = $result->fetch_assoc()) {

                                            $dotPosition = strpos($row['MoTa'], '.');

                                            $substr = $dotPosition !== false ? substr($row['MoTa'], 0, $dotPosition) . '...' : $row['MoTa'];
                                            $madv = $row["MaDV"];
                                            $manhom = $row["NhomDV"];
                                            $sql = "SELECT MaDV,ThoiLuong,Gia
                                            FROM banggia
                                            WHERE MaDV = '$madv'
                                            ORDER BY CAST(ThoiLuong AS SIGNED) ASC";
                                            $sub = $conn->query($sql);
                                            $sql = "SELECT MaNhom,TenNhom FROM nhomdichvu WHERE MaNhom = '$manhom'";
                                            $nhom = $conn->query($sql);
                                            echo "<tr>";
                                            echo "<td>$stt</td>";
                                            echo "<td>{$madv}</td>";
                                            echo "<td>{$row["TenDV"]}</td>";
                                            if($nhom->num_rows>0){
                                                while ($nhom_row = $nhom->fetch_assoc()) {
                                                    echo "<td>{$nhom_row["TenNhom"]}</td>";
                                                }
                                            }
                                            echo "<td style='max-width: 300px; word-wrap: break-word;'>{$substr}</td>";
                                            echo "<td>
                                            <table width='100%'>";
                                            if ($sub->num_rows > 0) {
                                                while ($sub_row = $sub->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td class='inner-table'>{$sub_row["ThoiLuong"]} phút</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            echo "</table>";
                                            echo "</td>";
                                            echo "<td>
                                            <table width='100%'>";
                                            //Đặt con trỏ về đầu mảng
                                            $sub->data_seek(0);
                                            if ($sub->num_rows > 0) {
                                                while ($sub_row = $sub->fetch_assoc()) {
                                                    echo "<tr>";
                                                    // Định dạng giá tiền theo định dạng Việt Nam
                                                    $gia_tien = number_format($sub_row["Gia"], 0, ',', '.') . ' VNĐ';
                                                    // In giá tiền đã định dạng
                                                    echo "<td class='inner-table'>" . $gia_tien . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            echo "</table>";
                                            echo "</td>";
                                            echo "<td style='text-align: center;'>";
                                            echo "<form action='ViewDV.php' method='post'>";
                                            echo "<input type='hidden' name='viewdv' value='{$madv}'>";
                                            echo "<button type='submit' class='btn btn-sm btn-primary btn-equal fa fa-eye'> Xem</button>";
                                            echo "</form>";
                                            if ($_SESSION["PhanQuyen"] == 1) {
                                                echo "<form action='EditDV.php' method='post'>";
                                                echo "<input type='hidden' name='editdv' value='{$madv}'>";
                                                echo "<button class='btn btn-primary btn-sm btn-equal edit-button fa fa fa-wrench' data-madv='{$madv}'> Sửa</button><br>";
                                                echo "</form>";
                                                echo "<form  method='post'>";
                                                echo "<input type='hidden' name='delete_id' value='$madv'>";
                                                echo "<button type='submit' name='delete_button' class='btn btn-sm btn-primary btn-equal' onclick='return confirm(\"Bạn có chắc chắn muốn xóa dịch vụ này?\")'> ";
                                                echo "<i class='fa fa-trash-o'></i> Xóa";
                                                echo "</button>";
                                                echo "</form>";
                                            } else {
                                                echo "<form>";
                                                echo "<button class='btn btn-primary edit-button btn-sm btn-equal fa fa-lock' name='lock_edit_button' onclick='showAlert()'> Sửa</button><br>";
                                                echo "<button class='btn btn-primary delete-button btn-sm btn-equal fa fa-lock' name='lock_delete_button' onclick='showAlert()'> Xóa</button><br>";
                                                echo "</form>";
                                            }

                                            echo "<script>";
                                            echo "function showAlert() {";
                                            echo "  alert('Bạn không có quyền thực hiện thao tác này. Chỉ admin mới có quyền.');";
                                            echo "}";
                                            echo "</script>";
                                            echo "</td>";
                                            echo "</tr>";
                                            $stt = $stt + 1;
                                        }
                                    }


                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã Dịch Vụ</th>
                                        <th>Tên Dịch Vụ</th>
                                        <th>Nhóm Dịch Vụ</th>
                                        <th>Mô Tả</th>
                                        <th style="text-align: center;">Thời Gian</th>
                                        <th style="text-align: center;">Giá</th>
                                        <th style="text-align: center;">Chi Tiết</th>

                                    </tr>
                                </tfoot>
                            </table>
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                <ul class="pagination">
                                    <?php
                                    // Nút Lùi
                                    if ($current_page > 1) {
                                        echo "<li class='paginate_button'>";
                                        echo "<a href='?page=" . ($current_page - 1) . "' aria-controls='DataTables_Table_0'><</a>";
                                        echo "</li>";
                                    }

                                    // Các nút trang
                                    for ($i = 1; $i <= $pages; $i++) {
                                        echo "<li class='paginate_button'>";
                                        echo "<a href='?page=$i' aria-controls='DataTables_Table_0'>$i</a>";
                                        echo "</li>";
                                    }

                                    // Nút Tiến
                                    if ($current_page < $pages) {
                                        echo "<li class='paginate_button'>";
                                        echo "<a href='?page=" . ($current_page + 1) . "' aria-controls='DataTables_Table_0'>></a>";
                                        echo "</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
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