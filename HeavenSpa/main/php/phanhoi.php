<head>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/dichvu.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
</head>

<?php
ob_start();
date_default_timezone_set("Asia/Ho_Chi_Minh");
include("header.php");
include("sliderimage.php");

$MaDL = $_GET['id'];
include("connect.php");

echo "<div style='display:flex ; margin-left:50px' >";
$sql = "SELECT datlich.MaDL, khachhang.HoKH, khachhang.TenKH, dichvu.TenDV, datlich.ThoiGianDat, datlich.NgayDat, datlich.TrangThai, datlich.ThoiLuong
        FROM datlich
        JOIN khachhang ON datlich.MaKH = khachhang.MaKH
        JOIN dichvu ON datlich.MaDV = dichvu.MaDV
        WHERE datlich.MaDL = '$MaDL'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    echo "<div style='margin-left:50px'>";
    echo "<h4 style='font-size:20px'>PHẢN HỒI </h4>";
    echo "<div style='display:flex ; font-size:15px'>";
    echo " <img style='width:50px ; height:50px ;  border-radius:50%; margin-right:20px' src ='/main/img/avataphanhoi.jpg' > ";

    echo "<div style=''>";
    echo $row['HoKH'] . " " . $row['TenKH'] . "<br>";
    echo " Tên dịch vụ:";
    echo $row['TenDV'] . " . <br>";
    echo "Thời gian đặt:";
    $thoigiandat = new DateTime($row["ThoiGianDat"]);
    $thoigiantheo24H = $thoigiandat->format("H:i");
    echo "{$thoigiantheo24H} .<br>";
    echo "Ngày đặt:";
    $ngaydat = new DateTime($row["NgayDat"]);
    $dinhdangngay = $ngaydat->format("d/m/Y");
    echo "{$dinhdangngay} <br>";
    echo "</div>";
    echo "</div>";

    echo "<form style='margin-top:20px' action='' method='post' >";
    echo " <input type='hidden' name='MaDL' value='{$row['MaDL']}' />";
    
    echo " <textarea style='margin-top:20px' rows='3' cols='60' name='chiTiet' placeholder ='Viết phản hồi của bạn...'></textarea> . <br>";
    echo " <input style='margin-left:150px  ; margin-top:20px ; background:#cb1c22 ; color: #fff ; width:150px ; outline:none; height:30px ' type='submit' name='submit' value='GỬI PHẢN HỒI' >";
    echo " </form>";
    echo "</div>";
} else {
    echo "No results found";
}

echo "<div>";
echo " <img style='width:500px ; height:400px ; margin-left:50px ; margin-botom:30px' src ='/main/img/phanhoibg1.jpg' > ";
echo "</div>";
echo "</div>";
$thoiGianPhanHoi = date('Y-m-d H:i:s');
if (isset($_POST['submit'])) {
    
    $thoiGianPhanHoi = date('Y-m-d H:i:s');
    $maDL = $_POST['MaDL'];
    $chiTiet = $_POST['chiTiet'];

    // lấy  MaDL từ hoKH
    $queryMaDL = "SELECT MaDL FROM datlich WHERE MaDL = '$maDL'";
    $resultMaDL = $conn->query($queryMaDL);
    $rowMaDL = $resultMaDL->fetch_assoc();
    $maDL = $rowMaDL['MaDL'];

   

   

    $sqlthem = "INSERT INTO phanhoi (MaDL, ThoiGian, ChiTietPH) VALUES ('$maDL',  '$thoiGianPhanHoi', '$chiTiet')";
    $result2 = $conn->query($sqlthem);
    
    if ($result2) {
        echo " <script> alert ('phản hồi thành công.') </script>";
        header("Location: tcphanhoi.php");
        exit();
    
        
    } else {
        echo "<script> alert ('phản hồi thất bại.') </script> " . $conn->error;
    }

   
}

$conn->close();
ob_end_flush();
?>

<?php
include("footer.php");
?>
