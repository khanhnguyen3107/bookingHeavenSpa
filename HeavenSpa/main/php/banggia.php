<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
    <link rel="stylesheet" href="../css/banggia.css">
    <title>Bảng giá</title>
    
</head>
<body>
    <!-- Header -->
    <?php include("header.php");
    include("sliderimage.php"); ?>
 
    <!-- Content -->
    <?php
    // Thực hiện kết nối db
    include("connect.php");
        $sql = "SELECT dichvu.TenDV, banggia.ThoiLuong, banggia.Gia
                FROM banggia 
                INNER JOIN dichvu ON banggia.MaDV = dichvu.MaDV";
        $result  = $conn ->query($sql);
        if(!$result){
            echo "Failed database";
        } else {
            echo "<h2 style='color:pink'>THÔNG TIN BẢNG GIÁ HEAVEN SPA</h2>";
            echo "<div class='container_price'>";
            $chitietdv = array(); // lưu thông tin chi tiết của dịch vụ đó nhé
            foreach($result as $item){

                $tendv = $item['TenDV'];
                if (!isset($chitietdv[$tendv])) {
                    $chitietdv[$tendv] = array();
                }
                $chitietdv[$tendv][] = array(
                    'ThoiLuong' => $item['ThoiLuong'],  
                    'Gia' => $item['Gia']
                );
            }
          
            // Hiển thị thông tin chi tiết của dịch vụ đó
            foreach ($chitietdv as $tendv => $chitiet) {
               
               
                echo "<div class='service-item'>
                    <h3>".$tendv."</h3>";  
                    // Sắp xếp tăng dần
                usort($chitiet, function($a, $b) {
                    return $a['ThoiLuong'] - $b['ThoiLuong'];
                });
                foreach ($chitiet as $chitiet) {
                    echo "<br>";
                    echo "<p>Thời Lượng: ".$chitiet['ThoiLuong']. ' phút'."</p>
                          <p>Giá: ".$chitiet['Gia']. ' VNĐ' ."</p>"; 
                }
                echo "</div>";
            }
            echo "</div>";
        }
    
    ?>
    <div class="return">
    <a style="font-size: 15px;" href="index.php">Quay lại trang chủ</a>
    </div>
    <!-- Footer -->
    <?php include("footer.php"); ?>
</body>
</html>
