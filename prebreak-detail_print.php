<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$DueDate = $_REQUEST["DueDate"];
$xUrl = $_REQUEST["xUrl"];
$NameTH = $_REQUEST["NameTH"];
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
<center><b style="font-size: 24">ใบบันทึกรายละเอียดเบรคก่อนเวลากรุ๊ป(ล่วงหน้า)</b>
<br>389/2 หมู 5 ต.ยางเนิ้ง อ.สารภี เชียงใหม 50140
<br>โทร. 0-5322-2828
<br>Email : faibakerylanna@hotmail.com
<br><br>
<div style="font-size: 20">วันที่จัดส่ง : <b><?=date(('d/m/'.(date('Y')+543)),strtotime($DueDate))?></b></div><br>
<table class="table table-sm" data-role="table" style="font-size: 14" border="1">
  <thead>
    <tr>
       <th>ลำดับ</th>
       <th>ชื่อสินค้า</th>
       <th>ราคา</th>
       <th>Order</th>
    </tr>
  </thead>
  <tbody>

    <?
      $Sql = "SELECT item.NameTH,
      item.SalePrice,
      saleorder.DueDate,
      SUM(saleorder_detail.Qty) AS order_qty
      FROM saleorder
      INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
      INNER JOIN item ON item.Item_Code = saleorder_detail.Item_Code
      WHERE saleorder.Objective = 2
      AND saleorder.IsCancel = 0
      AND saleorder.IsFinish = 1
      AND saleorder.DueDate LIKE '$DueDate%'
      GROUP BY item.NameTH
      ORDER BY item.NameTH,saleorder.DueDate ASC";
          //echo $Sql;
      $row = 1;
      $meQuery = mysql_query( $Sql );
        while ($Result = mysql_fetch_assoc($meQuery)){
    ?>
      <tr>
        <th width="50px"><?=$row?></th>
        <td width="250px"><?=$Result["NameTH"]?></td>
        <td width="150px"><?=$Result["SalePrice"]?></td>
        <td width="100px"><?=$Result["order_qty"]?></td>
      </tr>
    <?
      $row++;

      }
    ?>

    </tbody>
</table></center>
</body>
</html>
