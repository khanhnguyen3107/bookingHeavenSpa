<?php
    include '../php/config.php';
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập</title>
  <link rel="icon" href="../img/logo.jpg" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300, 400, 500" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
<link rel="stylesheet" href="../css/Login.css">
</head>
<body>
  <?php
    if(isset($_POST['submit'])){
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        if(empty($user) or empty($pass)){
          echo "<script>alert('không được để trống','Thông báo từ hệ thống');</script>";
        }else{
          $sql = "SELECT *
          FROM tkquanly
          WHERE TaiKhoan = '$user';";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
              $password = $row["MatKhau"];
            if ($pass == $password) {
              session_start();
              $_SESSION["TenTaiKhoan"] = $row["TenTaiKhoan"];
              $_SESSION["PhanQuyen"] = $row["PhanQuyen"];
              header("Location:../php/QLDV.php");
            }else{
              echo "<script>alert('mật khẩu không chính xác','Thông báo từ hệ thống');</script>";
            }
          }else{
            echo "<script>alert('email chưa được đăng ký','Thông báo từ hệ thống');</script>";
          }
        }
    }
  ?>
<section class="user">
  <div class="user_options-container">
    <div class="user_options-text">
      <div class="user_options-unregistered">
        <img src="../img/logo.jpg" alt="logo"/><br>
        <span>*chú ý: trang chỉ dành riêng cho nhân viên</span><br><br>
        <span>Nhập vào tài khoản mật khẩu để đăng nhập</span>
      </div>
    
    <div class="user_options-forms">
      <div class="user_forms-login">
        <h2 class="forms_title">ĐĂNG NHẬP</h2>
        <form method="post" action="#">
          <fieldset>
            <div class="forms_field">
              <input type="text" placeholder="Tài khoản" name="user" class="forms_field-input" required autofocus />
            </div>
            <div class="forms_field">
              <input type="password" placeholder="Mật khẩu" name="pass" class="forms_field-input" required />
            </div>
          </fieldset>
          <div class="forms_buttons">
            <input type="submit" value="Đăng nhập" name="submit" class="forms_buttons-action">
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
</body>
</html>
