<?php
    include './config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/ViewDV.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.cdnfonts.com/css/dec-terminal-modern" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>admin</title>
    <link rel="stylesheet" href="../css/Admin_page.css">
    <link rel="icon" href="../img/logo.jpg" type="image/x-icon" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <?php
        session_start();
        if($_SESSION["PhanQuyen"] !=1){
            echo "<script>
                window.history.back();
        </script>";
        };
        $taikhoan = $_SESSION['TenTaiKhoan'];
        $trang = "Quản lý thống kê";
        include './Form_header.php';
    ?>

    <div id="myPieChart" style="width: 600px; height: 600px; margin: auto;"></div>

    <?php
        // Truy vấn để lấy dữ liệu (ví dụ: số lượng đã đến và đã hủy)
        $query = "SELECT COUNT(*) as count, dv.TenDV
                  FROM datlich dl
                  JOIN dichvu dv ON dl.MaDV = dv.MaDV
                  GROUP BY dl.MaDV";
        $result = $conn->query($query);

        // Lưu kết quả vào một mảng để sử dụng trong JavaScript
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $row['count'] = (int)$row['count']; // Chuyển đổi sang kiểu số nguyên
            $data[] = $row;
        }

        // Đóng kết nối
        $conn->close();
    ?>

    <script>
        // Dữ liệu từ PHP
        var dataFromPHP = <?php echo json_encode($data); ?>;
        
        // Đảm bảo DOM đã tải xong trước khi thực hiện các thao tác
        document.addEventListener('DOMContentLoaded', function() {
            // Load thư viện Google Charts
            google.charts.load('current', {'packages':['corechart']});
            
            // Khi thư viện đã tải xong, thực hiện vẽ biểu đồ
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Biểu đồ Pie Chart
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'TenDV');
                data.addColumn('number', 'Count');

                // Đổ dữ liệu từ PHP vào biểu đồ
                data.addRows(dataFromPHP.map(item => [item.TenDV, item.count]));

                // Cấu hình của biểu đồ
                var options = {
                    pieHole: 0.4, // Điều này tạo ra khoảng trắng ở giữa, giá trị từ 0.0 đến 1.0
                    chartArea: { left: 10, top: 10, width: '100%', height: '80%' }, // Điều này làm cho biểu đồ lớn ra và căn giữa
                    backgroundColor: 'transparent', // Điều này làm cho nền trong suốt
                    legend: { position: 'bottom' } // Điều này đặt hình tròn biểu đồ ở giữa và chú thích dưới cùng
                };

                // Vẽ biểu đồ Pie Chart
                var chart = new google.visualization.PieChart(document.getElementById('myPieChart'));
                chart.draw(data, options);
            }
        });
    </script>
</body>
</html>
