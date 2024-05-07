<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu đặt lịch</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
    <link rel="stylesheet" href="../css/tracuudatlich.css">
</head>

<body>
    
    <?php
    include("header.php");
    ?>

    <?php
    // Thực hiện kết nối db
   include("connect.php");
        if (isset($_POST['submit'])) {

            $searchTerm = $_POST['search'];
            if(empty($searchTerm)){
                echo "<script> alert('khong duoc de trong') </script>";
            }
            else{
                if(is_numeric($searchTerm)){

                
                    $query = "SELECT datlich.MaDL, khachhang.HoKH, khachhang.TenKH, dichvu.TenDV, datlich.ThoiGianDat,
                    datlich.NgayDat, datlich.TrangThai, datlich.ThoiLuong , dichvu.MaDV , datlich.MaDV
                    FROM datlich
                    JOIN khachhang ON datlich.MaKH = khachhang.MaKH
                    JOIN dichvu ON dichvu.MaDV = datlich.MaDV
                    WHERE khachhang.SDT LIKE ?";
        
                $stmt = $conn->prepare($query);

                // Add '%' to the search term
                $searchTerm = '%' . $searchTerm . '%';
                $stmt->bind_param("s", $searchTerm);

                $stmt->execute();
                $result = $stmt->get_result();
            }
            else{
                echo "<script> alert('khong dung dinh dang') </script>";
            }
        }
    }
        
    
    ?>

    <form style="margin-bottom: 30px;" method="post" action="#" name="tracuuls">
        <h2 style="text-align: center; color:#ff66b2">TRA CỨU LỊCH SỬ ĐẶT LỊCH CỦA KHÁCH HÀNG</h2>
        Nhập số điện thoại <input type="text" name="search" class="txtsdt" value="<?php  if(isset($_POST['search'])) echo $_POST['search']?>">
        <input type="submit" name="submit" value="Tìm kiếm">
    </form>

    <?php
     
    if (isset($result)) {  
        echo "<table>
            <tr>     
            <th>Mã đặt lịch </th>
                <th>Khách hàng</th>
                <th>Tên dịch vụ </th>
                <th>Thời gian đặt</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Thời lượng</th>
                <th>Phản hồi </th>
               
            </tr>";

        while ($row = $result->fetch_assoc()) {
           
            echo "<tr>";  
           echo "<td>" . $row['MaDL'] . "</td>";
            echo "<td>" . $row['HoKH'] . " " .  $row['TenKH'] . "</td>";
            echo "<td>" . $row['TenDV'] . "</td>";
            $thoigiandat = new DateTime($row["ThoiGianDat"]);
            $thoigiantheo24H = $thoigiandat->format("H:i");
            echo "<td>{$thoigiantheo24H}</td>";
            $ngaydat = new DateTime($row["NgayDat"]);
            $dinhdangngay = $ngaydat->format("d/m/Y");
            echo "<td>{$dinhdangngay}</td>";
            echo "<td>{$row['TrangThai']}</td>";
            echo "<td>" . $row['ThoiLuong'] . " phút". "</td>";
            if ( $row['TrangThai'] == 'Đã đến') {
                echo "<td><a href='phanhoi.php?id=" . $row['MaDL'] . "' style='color: green;'>Phản hồi</a></td>";
            } 
    
           
            
            echo "</tr>";
        }

        echo "</table>";
    }

  
   
    
    ?>
    <div style="margin-top: 30px;"></div>
    <?php
    include("footer.php");
    ?>
</body>

</html>
