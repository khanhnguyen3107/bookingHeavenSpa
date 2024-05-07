<?php
session_start();
include './config.php';
$taikhoan = $_SESSION['TenTaiKhoan'];
$KH = "active";
$BL = "active";

if (isset($_POST["search"])) {
    $search = $_POST["search"];
} else {
    $search = "";
}

// Lấy tổng số hàng trong bảng dịch vụ
$sql = "SELECT COUNT(*) AS total_rows FROM binhluan";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_rows = $row['total_rows'];
$num_entries = 6;
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
                        <div class="ibox-title">
                            <h5>Bảng Phản Hồi Khách Hàng</h5>
                        </div>
                        <form method="post" id="search">

                            <input size="25px" placeholder="Nhập tên mã bình luận cần tìm..." type="text" name="search" value="<?php if (isset($_POST["search"])) {
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
                                        <th>Mã Bình Luận</th>
                                        <th>Mã Dịch Vụ</th>
                                        <th>Họ Tên</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Email</th>
                                        <th>Giới Tính</th>
                                        <th>Nội Dung</th>
                                        <th>Thời Gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stt = ($current_page - 1) * $num_entries + 1;
                                    $sql = "SELECT * 
                                    FROM `binhluan`
                                    WHERE MaBL LIKE '%$search%'  OR NoiDung LIKE '%$search%' LIMIT $offset, $num_entries";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        $result->data_seek(0);
                                        while ($row = $result->fetch_assoc()) {
                                            if($row["GioiTinh"] == 1){
                                                $row["GioiTinh"] = "nữ";
                                            } else{
                                                $row["GioiTinh"] = "nam";
                                            }
                                            echo "<tr>";
                                            echo "<td>$stt</td>";
                                            echo "<td>{$row["MaBL"]}</td>";
                                            echo "<td>{$row["MaDV"]}</td>";
                                            echo "<td>{$row["HoTen"]}</td>";
                                            echo "<td>{$row["SDT"]}</td>";
                                            echo "<td>{$row["Email"]}</td>";
                                            echo "<td>{$row["GioiTinh"]}</td>";
                                            echo "<td>" . substr($row["NoiDung"], 0, 3) . "..." . "<a href='' onclick='return confirm(\"" . $row["NoiDung"] . "\")'>Chi Tiết</a></td>";
                                            echo "<td>{$row["ThoiGian"]}</td>";
                                            $stt++;
                                    }
                                }

                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã Bình Luận</th>
                                        <th>Mã Dịch Vụ</th>
                                        <th>Họ Tên</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Email</th>
                                        <th>Giới Tính</th>
                                        <th>Nội Dung</th>
                                        <th>Thời Gian</th>
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