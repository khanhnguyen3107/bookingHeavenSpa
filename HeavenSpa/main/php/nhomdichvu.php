<?php
    include("connect.php");
    if (isset($_POST["MaNhom"])) {
        $_SESSION["MaNhom"] = $_POST["MaNhom"];
    }
    $MaNhom = $_SESSION["MaNhom"];
    $sql = "SELECT * FROM nhomdichvu WHERE MaNhom = '$MaNhom'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tennhom = $row['TenNhom'];
            $mota = $row['MoTaNhom'];
        }
    }
    $sql = "SELECT * FROM dichvu WHERE NhomDV = '$MaNhom'";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dichvu.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=PT+Serif:wght@400;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <title>Dịch Vụ</title>
</head>

<body>
    <?php
    include("header.php");
    include("sliderimage.php");
    ?>

        <!-- Services Start -->
        <div class="container-fluid services py-5">
            <div class="container py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 800px;">
                    <p class="fs-4 text-uppercase text-center text-primary">Our Service</p>
                    <h1 class="display-3"><?php echo $tennhom; ?></h1>
                    <p><?php echo $mota; ?></p>
                </div>
                <div class="row g-4">
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $madv = $row["MaDV"];
                            $id_anh = $madv . "01.png";
                            echo"<div class='col-lg-6'>";
                            echo"<div class='services-item bg-light border-4 border-end border-primary rounded p-4'>";
                                echo"<div class='row align-items-center'>";
                                    echo"<div class='col-8'>";
                                        echo"<div class='services-content text-start'>";
                                            echo "<h3 class='content-nav-title'>{$row["TenDV"]}</h3>";
                                            $substr = substr($row['MoTa'], 0, 50) . "...";
                                            echo "<p class='content-nav-describe'>{$substr}...</p>";
                                            echo "<form method='post' action='chitietdichvu.php'>";
                                            echo "<input type='hidden' name='View_id' value='$madv'>";
                                            echo "<input type='submit' class='btn btn-primary btn-primary-outline-0 rounded-pill py-2 px-4' name='View_button' value='Xem Chi Tiết'>";
                                            echo "</form>";
                                        echo "</div>";
                                    echo "</div>";
                                    echo "<div class='col-4'>";
                                        echo "<div class='services-img d-flex align-items-center justify-content-center rounded'>";
                                            echo "<img class='img-fluid rounded' src='/admin/img/{$id_anh}' alt='Ảnh hỏng'>";
                                        echo "</div>";
                                    echo "</div>";
                                echo "</div>";    
                            echo "</div>";
                        echo "</div>"; 
                            }}
                ?>
                    
                    <div class="col-12">
                        <div class="services-btn text-center">
                            <a href="formdatlich.php" class="btn btn-primary btn-primary-outline-0 rounded-pill py-3 px-5">Đặt Lịch Ngay!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services End -->




    

   <!-- viết chức năng bình luận cho khách hàng -->
  
   <div class="return">
        <a style="font: 17px;" href="index.php">Quay lại trang chủ</a>
    </div>

    <?php
    include("footer.php");
    ?>
    
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/counterup/counterup.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>

</body>

</html>