<?php
session_start();
if (isset($_POST["View_button"])) {
    $_SESSION["MaDV"] = $_POST["View_id"];
}
if (isset($_POST["View_btn"])) {
    $_SESSION["MaDV"] = $_POST["View_btn"];
}
$madv = $_SESSION["MaDV"];

include("connect.php");
// Lấy thông tin dịch vụ
$sql = "SELECT dichvu.MaDV, dichvu.TenDV, dichvu.MoTa, banggia.ThoiLuong, banggia.Gia
        FROM dichvu
        LEFT JOIN banggia ON dichvu.MaDV = banggia.MaDV
        WHERE dichvu.MaDV = '$madv'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tenDV = $row["TenDV"];
    $moTa = $row["MoTa"];
    $sub_moTa = explode("\n", $moTa);
}

// Lấy thông tin bảng giá
$sql = "SELECT ThoiLuong, Gia
        FROM banggia
        WHERE MaDV = '$madv'";
$sub_banggia = $conn->query($sql);
$mucgia = $sub_banggia->num_rows;

// Lấy thông tin ảnh
$sql = "SELECT Anh
        FROM anhdichvu
        WHERE MaDV = '$madv'";
$sub_anh = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/chitietdichvu.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
    <link rel="stylesheet" href="../css/phanhoi.css">
    <link rel="stylesheet" href="../css/tcphanhoi.css">
    <link rel="stylesheet" href="../css/formthembl.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="../js/traloibl.js"></script>
    <title>Dịch Vụ</title>

</head>

<script>
    $(document).ready(function() {
        $('#toggle_detail').click(function() {
            $('nav').slideToggle();
        });
    })
</script>
<style>
    button.button-link {
        border: none;
        padding: 0;
        background: none;
        cursor: pointer;
        color: gray;
    }

    button.button-link:hover {
        color: lightgreen;
    }
</style>

<body>
    <?php include("header.php");
    include("sliderimage.php"); ?>

    <div class="container_detail">
        <div class="container_col_1">
            <div>
                <h4>CÁC DỊCH VỤ CÙNG LOẠI</h4>
            </div>

            <div class="menu_item">
                <div id="toggle_detail">
                    <i class="fas fa-bars"></i>
                </div>
                <nav>
                    <ul class="item-list-detail">
                        <?php
                        $sqlnhomdv = "SELECT dichvu.*, nhomdichvu.MaNhom
                        FROM dichvu
                        INNER JOIN nhomdichvu ON dichvu.NhomDV = nhomdichvu.MaNhom
WHERE nhomdichvu.MaNhom = (SELECT NhomDV FROM dichvu WHERE MaDV = '$madv')"; 
            $resultnhomdv = $conn->query($sqlnhomdv);
            while ($rownhomdv = mysqli_fetch_assoc($resultnhomdv)) {
                echo '<li>';
                echo "<form method='post' action='chitietdichvu.php'>";
                echo "<input type='hidden' name='View_btn' value='{$rownhomdv["MaDV"]}'>";
                echo "<button class='btn btn-link' type='submit'>{$rownhomdv["TenDV"]}</button>";
                echo "</form>";
                echo '</li>';
            }
                        ?>
                    </ul>
                </nav>

            </div>
        </div>
        <div  class="container_col_2">
            <div class="title">
                <h5><?php echo $tenDV; ?></h5>
            </div>
            <div style="width: 70%; margin-left: 15%; margin-top: 30px; "><?php echo $sub_moTa[0]; ?></div>
            <?php
            $count = 0;
            if ($sub_anh->num_rows > 0) {
                $count = 0; // Biến đếm số ảnh đã hiển thị
                echo "<div style='width: 70%; margin-left: 15%; margin-top: 30px;  text-align: center;'>";
                while ($sub_anh_row = $sub_anh->fetch_array()) {
                    echo "<a title='{$tenDV}' target='blank' href='/admin/img/{$sub_anh_row['Anh']}'><img src='/admin/img/{$sub_anh_row['Anh']}' alt='Lỗi Hình ảnh' width='500px'></a>";
                    $count++;
                    if ($count % 2 == 0) {
                        echo "<br>";
                        echo "</div>";
                        echo "<div style='width: 70%; margin-left: 15%; margin-top: 30;  text-align: center;'>";
                    }
                }
                echo "</div>";
            }
            for ($i = 1; $i < count($sub_moTa); $i++) {
                echo "<div style='width: 70%; margin-left: 15%; margin-top: 30px;'>{$sub_moTa[$i]}</div>";
            };
            ?>
            <h3 style="width: 70%; margin-left: 15%; ">Dịch vụ có <?php echo $mucgia; ?> sự lựa chọn để bạn sử dụng</h3>
            <?php

            if ($sub_banggia->num_rows > 0) {
                $sub_banggia_array = array();
                while ($sub_banggia_row = $sub_banggia->fetch_assoc()) {
                    $sub_banggia_array[] = $sub_banggia_row;
                }
                usort($sub_banggia_array, function ($a, $b) {
                    return $a['ThoiLuong'] - $b['ThoiLuong'];
                });
                echo '<table style="width: 70%; margin-left: 15%; text-align: center;">'; // Thêm class "center" để căn giữa bảng
                echo '<tr><th>Thời Gian</th><th>Giá</th></tr>';
                foreach ($sub_banggia_array as $row) {
                    echo "<tr>";
                    echo "<td>{$row["ThoiLuong"]} Phút</td>";
                    echo "<td>{$row["Gia"]} VNĐ</td>";
echo "</tr>";
                }
echo "</table>";
            }
            ?>
        </div>

        <?php
        include("connect.php");
        date_default_timezone_set("Asia/Ho_Chi_Minh");


        // Lấy thông tin phản hồi
        $sql_phanhoi = "SELECT phanhoi.*, datlich.MaDL, khachhang.HoKH, khachhang.TenKH
                FROM phanhoi
                INNER JOIN datlich ON phanhoi.MaDL = datlich.MaDL
                INNER JOIN khachhang ON datlich.MaKH = khachhang.MaKH
                WHERE datlich.MaDV = '$madv'";
        $result_phanhoi = $conn->query($sql_phanhoi);

        // Kiểm tra và hiển thị thông tin phản hồi nếu có
        if ($result_phanhoi->num_rows > 0) {
            echo '<div class="phanhoi-container">';
            echo '<h2 style="text-align: center;">Phản hồi về dịch vụ</h2>';
            while ($row_phanhoi = $result_phanhoi->fetch_assoc()) {
                echo '<div class="comment-container">';
                echo '<div class="comment">';
                echo '<h4>' . $row_phanhoi['HoKH'] . ' ' . $row_phanhoi['TenKH'] . '</h4>';
                $ngaybl = new DateTime($row_phanhoi['ThoiGian']);
                $dinhdangngay = $ngaybl->format("d/m/Y H:i");
                echo "<td>{$dinhdangngay}</td>";
                echo '<p>' . $row_phanhoi['ChiTietPH'] . '</p>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<div class="phanhoi-container">';
            echo '<p>Chưa có phản hồi nào cho dịch vụ này.</p>';
            echo '</div>';
        }
        

        // xử lý cho thêm bình luận
        if (isset($_POST['submitadd'])) {
   
            // Lấy số thứ tự hiện tại
            $sqlGetMaxStt = "SELECT MAX(RIGHT(MaBL, 2)) as MaxStt FROM binhluan WHERE MaBL LIKE 'BL" . date("ym") . "%'";
            $resultMaxStt = $conn->query($sqlGetMaxStt);
        
            $stt = 1; // Giá trị mặc định nếu không có dữ liệu
        
            if ($resultMaxStt->num_rows > 0) {
                $rowMaxStt = $resultMaxStt->fetch_assoc();
                $stt = (int)$rowMaxStt['MaxStt'] + 1;
            }
        
            $sttFormatted = str_pad($stt, 2, '0', STR_PAD_LEFT);
        
            $mabl = "BL" . date("ym") . $sttFormatted;
            $hoten = $_POST['hotenbl'];
            $sdt = $_POST['sdtbl'];
            $email = $_POST['emailbl'];
            $gioitinh = $_POST['sexbl'];
            $noidung = $_POST['noidungbl'];
            $thoigianbl = date('Y-m-d H:i');
        
            // thêm bình luận
            $sqlthembl = "INSERT INTO binhluan (MaBL, MaDV, HoTen, SDT, Email, GioiTinh, NoiDung, ThoiGian) 
                  VALUES ('$mabl', '$madv', '$hoten', '$sdt', '$email', b'$gioitinh', '$noidung', '$thoigianbl')";
            $resultthembl = $conn->query($sqlthembl);
            if($resultthembl){
echo "<script>alert('Thêm bình luận thành công');</script>";
            echo "<script>window.location.href = './index.php';</script>";
            }
        }
       
        ?>

    </div>


    <!-- tạo form bình luận -->
    <div>

        <form class="model-form" id="formthembl" action="chitietdichvu.php" method="post">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h4>Nhập thông tin người gửi</h4>
            <input class="model-hoten" name="hotenbl" type="text" placeholder="Nhập họ tên...">
            <input class="model-sdt" name="sdtbl" type="text" placeholder="Nhập số điện thoại...">
            <input class="model-sex" type="radio" name="sexbl" value="0"> anh <input class="model-sex" type="radio" name="sexbl" value="1">chị <br>
            <input type="email" name="emailbl" placeholder="Nhập email..."><br>
            <textarea style="margin-top: 20px;" class="model-content" name="noidungbl" class="inpbl " rows="3" placeholder="Nhập nội dung bình luận (tiếng Việt có dấu)..."></textarea>
            <input type="submit" name="submitadd" class="btnht" value="HOÀN TẤT">
        </form>




    </div>
    <div>
        <h4>BÌNH LUẬN</h4>
        <div class="overlay" id="overlay"></div>
        <form style="display: flex;" action="#" name="formbl" method="post" onsubmit="return openModalOnSubmit();">
            <textarea name="contentbl" class="inpbl " rows="3" placeholder="Nhập nội dung bình luận (tiếng Việt có dấu)..."></textarea>
            <input type="submit" name="submitbl" class="btnbl" value="GỬI BÌNH LUẬN">
        </form>

        <?php
        $sqlbl = "SELECT binhluan.MaBL, binhluan.MaDV, binhluan.HoTen, binhluan.SDT, binhluan.GioiTinh, binhluan.NoiDung, binhluan.ThoiGian, dichvu.MaDV 
FROM binhluan
INNER JOIN dichvu ON binhluan.MaDV = dichvu.MaDV
WHERE dichvu.MaDV = '$madv'";

        $resultbl = $conn->query($sqlbl);
        if ($resultbl->num_rows > 0) {
            echo '<div class="binhluan-container">';
            while ($row_binhluan = $resultbl->fetch_assoc()) {
                $mabl = $row_binhluan['MaBL'];
                echo '<div class="binhluan-item">';
                echo '<img class="avatar" src="/main/img/avataphanhoi.jpg" alt="Avatar">';
                echo '<div class="binhluan-content">';
                echo '<span class="hoten">' . $row_binhluan['HoTen'] . '</span><br>';

                $ngaybl1 = new DateTime($row_binhluan['ThoiGian']);
                $dinhdangngay1= $ngaybl1->format("d/m/Y H:i");
                echo '<span class="thoigian">' . $dinhdangngay1 . '</span><br>';
                echo '<span class="noidung">' . $row_binhluan['NoiDung'] . '</span>'.'<br>';
               
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
       
    
        ?>


    </div>
  
   
    <div class="return">
<a style="font: 17px;" href="index.php">Quay lại trang chủ</a>
    </div>

    <?php include("footer.php"); ?>

    <script>
        function openModal() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('formthembl').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('formthembl').style.display = 'none';
        }

        function openModalOnSubmit() {
            openModal();
            return false;
        }
    </script>
</body>
 
</html>
