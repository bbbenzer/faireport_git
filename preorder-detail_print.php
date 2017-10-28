<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];

$DocNo = $_REQUEST["DocNo"];
$FName = $_REQUEST["FName"];
$DueDate = $_REQUEST["DueDate"];
$xUrl = $_REQUEST["xUrl"];
$CusCode = $_REQUEST["CusCode"];
$Objective = $_REQUEST["Objective"];
$totalqty = 0;
$totalprice = (float) 0;

if($Objective=='ทั่วไป'){
  $obj = 1;
}elseif ($Objective=='เบรค') {
  $obj = 2;
}elseif ($Objective=='น้ำ') {
  $obj = 3;
}elseif ($Objective=='อาหาร') {
  $obj = 4;
}else {
  $obj = 7;
}
 ?>
<html>
<header>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

</header>
<body onload="window.print()">
<center><b style="font-size: 24">ใบบันทึกสั่งออร์เดอร์ล่วงหน้า</b>
<br>389/2 หมู 5 ต.ยางเนิ้ง อ.สารภี เชียงใหม 50140
<br>โทร. 0-5322-2828
<br>Email : faibakerylanna@hotmail.com
<br><br>
<div style="font-size: 20">ชื่อลูกค้า : <b><?=$FName?></b> วันส่ง : <b><?=date(('d/m/'.(date('Y')+543)),strtotime($DueDate))?></b></div><br>
<table class="table table-sm" data-role="table" style="font-size: 14" border="1">
  <thead>
    <tr>
       <th>ลำดับ</th>
       <th>รายการขนม</th>
       <th>ราคา</th>
       <th>จำนวน</th>
    </tr>
  </thead>
  <tbody>

    <?
      $Sql = "SELECT item.NameTH,
      item.SalePrice,
      saleorder_detail.Qty,
      (item.SalePrice*saleorder_detail.Qty),
      saleorder.DocNo,
      saleorder.DueDate
      FROM item
      INNER JOIN saleorder_detail ON saleorder_detail.Item_Code = item.Item_Code
      INNER JOIN saleorder ON saleorder.DocNo = saleorder_detail.DocNo
      WHERE saleorder.Objective = '$obj'
      AND date(saleorder.DueDate) = date('$DueDate')
      AND saleorder.DocNo = '$DocNo'
      AND saleorder.Cus_Code = '$CusCode'
      AND saleorder.IsCancel = 0
      AND saleorder.IsFinish = 1
      ORDER BY item.NameTH ASC";

      $row = 1;
      $meQuery = mysql_query( $Sql );
        while ($Result = mysql_fetch_assoc($meQuery)){
    ?>
      <tr>
        <th width="50px"><?=$row?></th>
        <td width="170px"><?=$Result["NameTH"]?></td>
        <td width="100px"><?=$Result["SalePrice"]?></td>
        <td width="100px"><?=$Result["Qty"]?></td>
      </tr>
    <?
      $totalprice += ($Result["SalePrice"]*$Result["Qty"]);
      $totalqty += $Result["Qty"];
      $row++;

      }
    ?>
    <tr>
      <th width="50px"></th>
      <td width="170px"><center><b>รวม</b></center></td>
      <td width="100px"><?=$totalprice?></td>
      <td width="100px"><?=$totalqty?></td>
    </tr>

    </tbody>
</table></center>
</body>
</html>
