<?php
require 'db_connect.php';
require 'class.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("+1 day", $date);
$sDate =  date('Y-m-d', $date);

$date = strtotime($eDate);
$date = strtotime("+5 day", $date);
$lDate =  date('Y-m-d', $date);

$dateobj = new DatetimeTH;
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
<center><b style="font-size: 24">ใบบันทึกรายการเบรคก่อนเวลากรุ๊ป(ล่วงหน้า)</b>
<br>389/2 หมู 5 ต.ยางเนิ้ง อ.สารภี เชียงใหม 50140
<br>โทร. 0-5322-2828
<br>Email : faibakerylanna@hotmail.com
<br><br>
<!--<div style="font-size: 20">ชื่อลูกค้า : <b><?=$FName?></b> วันส่ง : <b><?=date(('d/m/'.(date('Y')+543)),strtotime($DueDate))?></b></div><br>-->
<table class="table table-sm" data-role="table" style="font-size: 14" border="1">
  <thead>
    <tr>
       <th  style="vertical-align: middle;" rowspan="3" data-priority="2">ลำดับ</th>
       <th  style="vertical-align: middle;" rowspan="3">ชื่อสินค้า</th>
       <th  style="vertical-align: middle;" rowspan="3">ราคา</th>
       <th colspan="7" width="490px" style="text-align: center;">วันที่จัดส่ง</th>
    </tr>
    <tr>
       <th width="70px"><center><?=$dateobj->getTHday(date('l', strtotime("+1 day", strtotime($eDate))));?></center></th>
    </tr>
    <tr>
       <th><center><?=date('d', strtotime("+1 day", strtotime($eDate)));?></center></th>
    </tr>
  </thead>
  <tbody>

    <?
      $Sql = "SELECT item.Item_Code,
      item.NameTH,
      item.SalePrice,
      saleorder.DueDate,
      saleorder_detail.Qty
      FROM saleorder
      INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
      INNER JOIN item ON item.Item_Code = saleorder_detail.Item_Code
      WHERE saleorder.Objective = 2
      AND saleorder.IsCancel = 0
      AND saleorder.IsFinish = 1
      AND date(saleorder.DueDate) = date('$sDate')
      AND item.IsBakery = 1
      GROUP BY item.Item_Code
      ORDER BY item.IsWater DESC,item.NameTH,item.SalePrice ASC";
          //echo $Sql;
      $row = 1;
      $meQuery = mysql_query( $Sql );
        while ($Result = mysql_fetch_assoc($meQuery)){
    ?>
      <tr>
        <td width="50px"><?=$row?></td>
        <td width="170px"><?=$Result["NameTH"]?></td>
        <td width="50px"><?=$Result["SalePrice"]?></td>

        <?php
          for ($i=1; $i <=1 ; $i++) {
            $flag = 0;
              switch ($i) {
                case '1': $datecheck = date('Y-m-d', strtotime("+1 day", strtotime($eDate))); break;
                case '2': $datecheck = date('Y-m-d', strtotime("+2 day", strtotime($eDate))); break;
                case '3': $datecheck = date('Y-m-d', strtotime("+3 day", strtotime($eDate))); break;
                case '4': $datecheck = date('Y-m-d', strtotime("+4 day", strtotime($eDate))); break;
                case '5': $datecheck = date('Y-m-d', strtotime("+5 day", strtotime($eDate))); break;
                case '6': $datecheck = date('Y-m-d', strtotime("+6 day", strtotime($eDate))); break;
                case '7': $datecheck = date('Y-m-d', strtotime("+7 day", strtotime($eDate))); break;
              }
              $subsql = "SELECT item.Item_Code,
              item.NameTH,
              item.SalePrice,
              saleorder.DueDate,
              saleorder_detail.Qty
              FROM saleorder
              INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
              INNER JOIN item ON item.Item_Code = saleorder_detail.Item_Code
              WHERE saleorder.Objective = 2
              AND saleorder.IsCancel = 0
              AND saleorder.IsFinish = 1
              AND date(saleorder.DueDate) = date('$sDate')
              GROUP BY saleorder.Docno,item.Item_Code
              ORDER BY item.NameTH,saleorder.DueDate ASC";
              $meQuery2 = mysql_query($subsql);
                while ($Result2 = mysql_fetch_assoc($meQuery2)){
                  if($Result["Item_Code"]==$Result2["Item_Code"])
                  {
                    $flag += $Result2["Qty"];
                  }
                }
              if($flag==0)
              {
                echo '<td width="70px" style="color:#e3e3e3"><center>0</center></td>';
              }else {
              ?>  <td width="70px"><center><a style="text-decoration:none; color:#000000" href="#" onclick="gotoUrlDetail('<?=$datecheck?>','<?=$Result["NameTH"]?>','<?=$eDate?>')"><?=$flag?></a></center></td>
              <?}
          }
         ?>
      </tr>
    <?
      $row++;

      }
    ?>

    </tbody>
</table></center>
</body>
</html>
