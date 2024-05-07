<?php
    require './connect.php';
    $MaNhom = $_GET['MaNhom'];
    $sql = "SELECT * FROM `dichvu` WHERE NhomDV = '$MaNhom'";
    $result = $conn->query($sql);
    $data[0]=[
        'id' => null,
        'name'=> 'Chọn một dịch vụ',
    ];
    while($row = mysqli_fetch_assoc($result)){
        $data[]=[
            'id'=> $row['MaDV'],
            'name'=>$row['TenDV'],
        ];
    }
    echo json_encode($data);
?>