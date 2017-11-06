<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$sDate = $_REQUEST["sDate"];
$lDate = $_REQUEST["lDate"];
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
<table class="table table-sm" data-role="table" style="font-size: 14" border="1">
  <thead>
    <tr>
       <th>วันที่ส่งบิล</th>
       <th>ชื่อลูกค้า</th>
       <th>Objective</th>
       <th>วันส่ง</th>
       <th width="5%">ยอดเงิน</th>
       <th>หมายเหตุ</th>
    </tr>
  </thead>
  <tbody>

    <?
      $Sql = "SELECT S.DocDate,
      S.Cus_Code,
      customer.FName,
      CASE S.Objective
      WHEN 1 THEN 'ทั่วไป'
      WHEN 2 THEN 'เบรค'
      WHEN 3 THEN 'น้ำ'
      WHEN 4 THEN 'อาหาร'
      WHEN 7 THEN 'ขนมชิ้น'
      END AS Objective,
      S.DueDate,
      S.Total,
      S.Description,
      S.DocNo
      FROM saleorder as S
      LEFT JOIN saleorder_detail as SD ON S.DocNo = SD.DocNo
      LEFT JOIN customer ON customer.Cus_Code = S.Cus_Code
      WHERE S.IsCancel = 0
      AND S.IsFinish = 1
      AND DATE(S.DueDate) BETWEEN DATE('$sDate') AND DATE('$lDate')
      GROUP BY S.DocNo
      ORDER BY S.DueDate,customer.FName";
      $row = 1;
      $meQuery = mysql_query( $Sql );
        while ($Result = mysql_fetch_assoc($meQuery)){
    ?>
      <tr>
        <td width="150px"><?=date(('d/m/'.(date('Y')+543)),strtotime($Result["DocDate"]))?></td>
        <td width="170px"><a style="text-decoration:none; color:#000000" href="#" onclick="gotoUrlDetail('<?=$Result["Cus_Code"]?>','<?=$Result["DueDate"]?>','<?=$Result["Objective"]?>','<?=$eDate?>','<?=$Result["FName"]?>','<?=$Result["DocNo"]?>')"><?=$Result["FName"]?></a></td>
        <td width="100px"><?=$Result["Objective"]?></td>
                  <th width="150px"><?=date(('d/m/'.(date('Y')+543)),strtotime($Result["DueDate"]))?></th>
        <td width="100px"><?=$Result["Total"]?></td>
                  <td width="300px"><?=$Result["Description"]?></td>
      </tr>
    <?
      $row++;

      }
    ?>

    </tbody>
</table></center>
</body>
</html>
