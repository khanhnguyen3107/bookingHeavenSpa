<?php
session_start();
include './config.php';
$taikhoan = $_SESSION['TenTaiKhoan'];
$TK = "active";
$TKDVT = "active";

// Lấy danh sách tất cả các dịch vụ
$sqlDichVu = "SELECT * FROM dichvu";
$resultDichVu = $conn->query($sqlDichVu);

$selectedDate = isset($_POST['selectedDate']) ? $_POST['selectedDate'] : date("Y-m");

// Thực hiện thống kê theo tháng
$startOfMonth = date("Y-m-01", strtotime($selectedDate));
$endOfMonth = date("Y-m-t", strtotime($selectedDate));

// Lấy dữ liệu số lần đặt cho tất cả các dịch vụ trong tháng được chọn
$sql = "SELECT dichvu.NhomDV, dichvu.TenDV, COUNT(datlich.MaDV) AS SoLanDat, nhomdichvu.TenNhom
        FROM dichvu
        LEFT JOIN datlich ON dichvu.MaDV = datlich.MaDV AND datlich.NgayDat BETWEEN '$startOfMonth' AND '$endOfMonth'
        LEFT JOIN nhomdichvu ON dichvu.NhomDV = nhomdichvu.MaNhom
        GROUP BY dichvu.NhomDV, dichvu.MaDV
        ORDER BY dichvu.NhomDV, SoLanDat DESC";

$result = $conn->query($sql);

// Khởi tạo mảng để lưu trữ dữ liệu cho biểu đồ
$nhomDVArray = [];
$tenDVArrays = [];
$soLanDatArrays = [];

// Lưu số lần đặt vào mảng theo từng nhóm
while ($row = $result->fetch_assoc()) {
    $nhomDV = $row['TenNhom'];
    $tenDV = $row['TenDV'];
    $soLanDat = $row['SoLanDat'];

    if (!in_array($nhomDV, $nhomDVArray)) {
        $nhomDVArray[] = $nhomDV;
    }

    // Lưu tên dịch vụ và số lần đặt theo từng nhóm
    $tenDVArrays[$nhomDV][] = $tenDV;
    $soLanDatArrays[$nhomDV][] = $soLanDat;
}

// Tạo dữ liệu cho biểu đồ
$chartData = [
    'nhomDV' => $nhomDVArray,
    'tenDV' => $tenDVArrays,
    'soLanDat' => $soLanDatArrays
];

// Chuyển đổi dữ liệu thành JSON
$chartDataJSON = json_encode($chartData);
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

    <!-- Include necessary libraries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="../js/ViewDV.js"></script>
    <script src="../css/plugins/toastr/toastr.min.js"></script>

    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet">
</head>

<body>
    <?php include("./MenuHeader.php"); ?>
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
        <form method="post" action="" class="date-selection-form form-inline">
    <div class="form-group">
        <input type="month" id="selectedDate" name="selectedDate" class="form-control" value="<?php echo isset($_POST['selectedDate']) ? $_POST['selectedDate'] : $selectedDate; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Xem thống kê</button>
</form>


            <div class="row">
                <?php foreach ($nhomDVArray as $index => $nhomDV) : ?>
                    <div class="col-md-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Số lần đặt các dịch vụ trong nhóm <?php echo $nhomDV; ?> tháng <?php echo $selectedDate ?></h5>
                            </div>
                            <div class="ibox-content">
                                <!-- Điều chỉnh kích thước khung chứa biểu đồ bằng cách thay đổi class 'chart-container' -->
                                <div id="plotly-bar-chart-<?php echo $index; ?>" class="chart-container"></div>
                                <script>
                                    var chartData<?php echo $index; ?> = <?php echo $chartDataJSON; ?>;
                                    var nhomDV<?php echo $index; ?> = chartData<?php echo $index; ?>['nhomDV'][<?php echo $index; ?>];
                                    var tenDV<?php echo $index; ?> = chartData<?php echo $index; ?>['tenDV'][nhomDV<?php echo $index; ?>];
                                    var soLanDat<?php echo $index; ?> = chartData<?php echo $index; ?>['soLanDat'][nhomDV<?php echo $index; ?>];

                                    var trace<?php echo $index; ?> = {
                                        x: tenDV<?php echo $index; ?>,
                                        y: soLanDat<?php echo $index; ?>,
                                        type: 'bar',
                                        marker: {
                                            color: <?php echo json_encode(generateColors(count($tenDVArrays[$nhomDV]))); ?>
                                        }
                                    };

                                    var data<?php echo $index; ?> = [trace<?php echo $index; ?>];

                                    var layout<?php echo $index; ?> = {
                                        title: 'Số lần đặt các dịch vụ trong nhóm <?php echo $nhomDV; ?> ngày hôm nay',
                                        xaxis: {
                                            fixedrange: true // Ngăn chặn phóng to trục x
                                        },
                                        yaxis: {
                                            title: 'Số lần đặt',
                                            tickformat: 'd', // Định dạng số nguyên
                                            tick0: 0, // Giá trị bắt đầu của trục y
                                            dtick: 1, // Bước giữa các giá trị trên trục y
                                            fixedrange: true // Ngăn chặn phóng to trục y
                                        }
                                    };

                                    var config<?php echo $index; ?> = {
                                        displayModeBar: false, // Ẩn thanh công cụ điều khiển
                                        responsive: true // Biểu đồ co dãn theo kích thước cửa sổ
                                    };

                                    Plotly.newPlot('plotly-bar-chart-<?php echo $index; ?>', data<?php echo $index; ?>, layout<?php echo $index; ?>, config<?php echo $index; ?>);
                                </script>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>

<?php
// Hàm tạo mảng màu sắc tương ứng với số lượng
function generateColors($count)
{
    $colors = ['#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f', '#bcbd22', '#17becf'];
    return array_slice($colors, 0, $count);
}
?>