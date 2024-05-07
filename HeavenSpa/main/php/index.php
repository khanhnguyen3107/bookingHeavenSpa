<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
     crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/trangchu.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/sliderimage.css">
    <title>Trang chủ</title>
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
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6572d4a770c9f2407f7d6536/1hh48tbpp';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</head>
<body>
    <?php 
    include("header.php");
    include("sliderimage.php");
    include("connect.php");
    $query = "SELECT MaDV, COUNT(*) AS count FROM datlich GROUP BY MaDV ORDER BY count DESC LIMIT 4";
        $result = $conn->query($query);

        $chitietdv = array();

     
        while ($row = $result->fetch_assoc()) {
            $maDV = $row['MaDV'];
          
            $id_anh = $maDV . "01.png";

            $query2 = "SELECT MaDV, TenDV, MoTa FROM dichvu WHERE MaDV = '$maDV'";
            $result2 = $conn->query($query2);
            $xuatdichvu = $result2->fetch_assoc();

          
            $mota = implode(' ', array_slice(explode(' ', $xuatdichvu['MoTa']), 0, 10));

          
            $xuatdichvu['MoTa'] = $mota;
            $xuatdichvu['Anh'] = $id_anh;
            $xuatdichvu['MaDV'] = $maDV;
            $chitietdv[] = $xuatdichvu;
        }
    ?>
    <!-- Services Start -->
    <div class="container-fluid services py-5">
        <div class="container py-5">
            <div class="mx-auto text-center mb-5" style="max-width: 800px;">
                <p class="fs-4 text-uppercase text-center text-primary">Heaven Spa</p>
                <h1 class="display-3">Các Dịch Vụ Tiêu Biểu </h1>
            </div>

            <div class="row g-4">
                <?php
                    foreach ($chitietdv as $dichvu){
                        echo"<div class='col-lg-6'>";
                            echo"<div class='services-item bg-light border-4 border-end border-primary rounded p-4'>";
                                echo"<div class='row align-items-center'>";
                                    echo"<div class='col-8'>";
                                        echo"<div class='services-content text-start'>";
                                            echo "<h3 class='content-nav-title'>{$dichvu['TenDV']}</h3>";
                                            echo "<p class='content-nav-describe'>{$dichvu['MoTa']}...</p>";
                                            echo "<form method='post' action='chitietdichvu.php'>";
                                            echo "<input type='hidden' name='View_id' value='{$dichvu['MaDV']}'>";
                                            echo "<input type='submit' class='btn btn-primary btn-primary-outline-0 rounded-pill py-2 px-4' name='View_button' value='Xem Chi Tiết'>";
                                        echo "</div>";
                                    echo "</div>";
                                    echo "<div class='col-4'>";
                                        echo "<div class='services-img d-flex align-items-center justify-content-center rounded'>";
                                            echo "<img class='img-fluid rounded' src='/admin/img/{$dichvu['Anh']}' alt='Ảnh hỏng'>";
                                        echo "</div>";
                                    echo "</div>";
                                echo "</div>";    
                            echo "</div>";
                        echo "</div>"; 
                    }
                ?>
                
                <div class="col-12">
                    <div class="services-btn text-center">
                        <a href="dichvu.php" class="btn btn-primary btn-primary-outline-0 rounded-pill py-3 px-5">Xem Thêm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->
    
    <!-- About Start -->
    <div class="container-fluid about py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <div class="video">
                        <img src="/main/img/about-1.jpg" class="img-fluid rounded" alt="">
                        <div class="position-absolute rounded border-5 border-top border-start border-white" style="bottom: 0; right: 0;;">
                            <img src="/main/img/about-2.jpg" class="img-fluid rounded" alt="">
                        </div>
                        <button type="button" class="btn btn-play" data-bs-toggle="modal" data-src="https://www.youtube.com/embed/6bxmL1Fiq88?si=YHZcgJHQPNrpZ9e9" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-7">
                    <p class="fs-4 text-uppercase text-primary">About Us</p>
                    <h1 class="display-4 mb-4">Your Best Spa, Beauty & Skin Care Center</h1>
                    <p class="mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled
                    </p>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fab fa-gitkraken fa-3x text-primary"></i>
                                    <div class="ms-4">
                                        <h5 class="mb-2">Special Offers</h5>
                                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-gift fa-3x text-primary"></i>
                                    <div class="ms-4">
                                        <h5 class="mb-2">Special Offers</h5>
                                        <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="my-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                        </p>
                        <p class="mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        </p>
                        <a href="#" class="btn btn-primary btn-primary-outline-0 rounded-pill py-3 px-5">Explore More</a>
                    </div> 
                </div>
            </div>
        </div>
        <!-- Modal Video -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 16:9 aspect ratio -->
                        <div class="ratio ratio-16x9">
                            <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                                allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


    <?php include("footer.php"); ?>

    
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
