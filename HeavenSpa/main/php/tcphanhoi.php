<!DOCTYPE html>
<html lang="en">

<head>
<link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
    <link rel="stylesheet" href="../css/tcphanhoi.css">
</head>

<body>
    <!-- form giao diện -->
    <?php
    include("header.php");
    include("sliderimage.php");

    // Thực hiện kết nối db
    include("connect.php");
        $query2 = "SELECT khachhang.HoKH, khachhang.TenKH, dichvu.TenDV , phanhoi.ChiTietPH ,phanhoi.ThoiGian
                  FROM phanhoi
                  JOIN datlich ON phanhoi.MaDL = datlich.MaDL
                  JOIN khachhang ON datlich.MaKH = khachhang.MaKH
                  JOIN dichvu ON datlich.MaDV = dichvu.MaDV";

        $stmt2 = $conn->prepare($query2);

        $stmt2->execute();
        $result2 = $stmt2->get_result();
    
    ?>

    <?php
    if (isset($result2)) {
        echo"<h4>PHẢN HỒI </h4>";
        while ($row = $result2->fetch_assoc()) {
            
            echo '<div class="comment-container">';
          
            echo '<div class="comment">';
          
            echo '<h4>' . $row['HoKH'] . ' ' . $row['TenKH'] . '</h4>';
            echo '<p>' . $row['TenDV'] . '</p>';
            $ngaybl = new DateTime($row['ThoiGian']);
            $dinhdangngay = $ngaybl ->format("d/m/Y H:i");
            echo "<td>{$dinhdangngay}</td>";
            
            echo '<p>' . $row['ChiTietPH'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
    }

    include("footer.php");
    ?>

</body>

</html>
