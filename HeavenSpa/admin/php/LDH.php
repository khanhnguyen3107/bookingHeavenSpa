<?php
include './config.php';
session_start();
$LD = "active";
$LDH = "active";
$taikhoan = $_SESSION['TenTaiKhoan'];

// Xử lý tìm kiếm
if (isset($_POST["submit"])) {
    if (isset($_POST["search"])) {
        $search = $_POST["search"];
    }
} else {
    $search = "";
}


// Lấy tổng số hàng trong bảng dịch vụ
$sql = "SELECT COUNT(*) AS total_rows 
        FROM datlich 
        WHERE (MaKH IN (SELECT MaKH FROM khachhang WHERE TenKH LIKE '%$search%') 
                OR MaDV IN (SELECT MaDV FROM dichvu WHERE TenDV LIKE '%$search%'))
            AND datlich.TrangThai = 'Đã hủy'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_rows = $row['total_rows'];
$num_entries = 5;

$num_entries = 5;

// Tính tổng số trang
$pages = ceil($total_rows / $num_entries);

// Đảm bảo trang hiện tại nằm trong giới hạn hợp lệ
$current_page = (isset($_POST['page']) && $_POST['page'] > 0 && $_POST['page'] <= $pages) ? $_POST['page'] : 1;

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
                            <h5>Lịch Bị Hủy Của Heaven Spa</h5>
                        </div>
                        <form method="post" id="search">

                            <input type="text" name="search" value="<?php if (isset($_POST["search"])) {
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
                                        <th>Mã Đặt Lịch</th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column descending" style="width: 224.135px;" aria-sort="descending">Tên Dịch Vụ</th>
                                        <th>Họ Khách Hàng</th>
                                        <th>Tên Khách Hàng</th>
                                        <th>Thời Gian Đặt</th>
                                        <th>Ngày Đặt</th>
                                        <th>Thời Lượng</th>
                                        <th>Trạng Thái</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Lấy dữ liệu phân trang
                                    $sql = "SELECT datlich.MaDL, datlich.MaDV, datlich.MaKH, datlich.ThoiGianDat, datlich.NgayDat, datlich.TrangThai, datlich.ThoiLuong
                                        FROM datlich
                                        INNER JOIN khachhang ON datlich.MaKH = khachhang.MaKH
                                        INNER JOIN dichvu ON datlich.MaDV = dichvu.MaDV
                                        WHERE (khachhang.TenKH LIKE '%$search%' OR dichvu.TenDV LIKE '%$search%') AND datlich.TrangThai = 'Đã hủy'
                                        ORDER BY datlich.NgayDat ASC
                                        LIMIT $offset, $num_entries";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$row["MaDL"]}</td>";
                                            $sql = "SELECT * FROM dichvu WHERE MaDV = '{$row["MaDV"]}'";
                                            $tenDV = $conn->query($sql);
                                            if ($tenDV->num_rows > 0) {
                                                $rowDV = $tenDV->fetch_assoc();
                                                $tenDV = $rowDV["TenDV"];
                                                echo "<td>{$tenDV}</td>";
                                            }
                                            $sql = "SELECT HoKH, TenKH FROM khachhang WHERE MaKH = '{$row["MaKH"]}'";
                                            $khachhang = $conn->query($sql);
                                            if ($khachhang->num_rows > 0) {
                                                $rowKH = $khachhang->fetch_assoc();
                                                $hoKH = $rowKH["HoKH"];
                                                echo "<td>{$hoKH}</td>";
                                                $tenKH = $rowKH["TenKH"];
                                                echo "<td>{$tenKH}</td>";
                                            }
                                            $thoigiandat = new DateTime($row["ThoiGianDat"]);
                                            $thoigiantheo24H = $thoigiandat->format("H:i");
                                            echo "<td>{$thoigiantheo24H}</td>";
                                            $ngaydat = new DateTime($row["NgayDat"]);
                                            $dinhdangngay = $ngaydat->format("d/m/Y");
                                            echo "<td>{$dinhdangngay}</td>";
                                            echo "<td>{$row["ThoiLuong"]} phút</td>";
                                            echo "<td>{$row["TrangThai"]}</td>";
                                        }
                                    }

                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Mã Đặt Lịch</th>
                                        <th>Tên Dịch Vụ</th>
                                        <th>Họ Khách Hàng</th>
                                        <th>Tên Khách Hàng</th>
                                        <th>Thời Gian Đặt</th>
                                        <th>Ngày Đặt</th>
                                        <th>Thời Lượng</th>
                                        <th>Trạng Thái</th>
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