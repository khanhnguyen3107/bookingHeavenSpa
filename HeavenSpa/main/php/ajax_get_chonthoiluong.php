<?php
    require './connect.php';
    $MaDV = $_GET['MaDV'];
    $sql = "SELECT * FROM banggia WHERE MaDV = '$MaDV'";
    $result = $conn->query($sql);
    $data[0]=[
        'id' => null,
        'name'=> 'Chọn thời lượng',
    ];
    while($row = mysqli_fetch_assoc($result)){
        $data[]=[
            'id'=> $row['ThoiLuong'],
            'name'=>$row['ThoiLuong'].' Phút',
        ];
    }
    echo json_encode($data);
?>