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
<center><b style="font-size: 24">ใบบันทึกรายการขนมปังก่อนเวลากรุ๊ป(ล่วงหน้า)</b>
<br>389/2 หมู 5 ต.ยางเนิ้ง อ.สารภี เชียงใหม 50140
<br>โทร. 0-5322-2828
<br>Email : faibakerylanna@hotmail.com
<br><br>
<!--<div style="font-size: 20">ชื่อลูกค้า : <b><?=$FName?></b> วันส่ง : <b><?=date(('d/m/'.(date('Y')+543)),strtotime($DueDate))?></b></div><br>-->
<table class="table table-sm" data-role="table" style="font-size: 14" border="1">
  <thead>
    <tr>
       <th  style="vertical-align: middle;" width="10px" rowspan="3" data-priority="2">ลำดับ</th>
       <th  style="vertical-align: middle;" rowspan="3">ชื่อสินค้า</th>
       <th  style="vertical-align: middle;" rowspan="3">ราคา</th>
       <th  style="vertical-align: middle;" rowspan="3"><center>stock(real time)</center></th>
       <th colspan="1" width="50px" style="text-align: center;">วันที่จัดส่ง</th>
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
    wh_inventory.Qty,
    saleorder.DueDate,
    customer.FName
    FROM saleorder
    INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
    INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
    INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
    INNER JOIN wh_inventory ON saleorder_detail.Item_Code = wh_inventory.Item_Code
    WHERE item.StatusRpt = 3
    AND saleorder.IsCancel = 0
    AND date(saleorder.DueDate) = date('$sDate')
    AND wh_inventory.Branch_Code = 2
    AND (saleorder.Objective = 7
    OR saleorder.Objective = 1)
    AND (saleorder.IsFinish <> 2 OR saleorder.IsFinish <> 0 )
    AND item.IsBakery = 1
    GROUP BY item.Item_Code
    ORDER BY item.NameTH,item.SalePrice ASC";

    $row = 1;
    $meQuery = mysql_query( $Sql );
      while ($Result = mysql_fetch_assoc($meQuery)){
  ?>
    <tr>
      <td width="10px"><center><?=$row?></center></td>
      <td width="170px"><center><?=$Result["NameTH"]?></center></td>
      <td width="50px"><center><?=$Result["SalePrice"]?></center></td>
      <td width="150px"><center><?=(int)$Result["Qty"]?></center></td>

      <?php
        for ($i=1; $i <=1 ; $i++) {
          $flag = 0;
            switch ($i) {
              case '1': $datecheck = date('Y-m-d', strtotime("+1 day", strtotime($eDate))); break;
              case '2': $datecheck = date('Y-m-d', strtotime("+2 day", strtotime($eDate))); break;
              case '3': $datecheck = date('Y-m-d', strtotime("+3 day", strtotime($eDate))); break;
              case '4': $datecheck = date('Y-m-d', strtotime("+4 day", strtotime($eDate))); break;
              case '5': $datecheck = date('Y-m-d', strtotime("+5 day", strtotime($eDate))); break;
            }
            $subsql = "SELECT item.Item_Code,
            item.NameTH,
            item.SalePrice,
            saleorder_detail.Qty,
            saleorder.DueDate,
            customer.FName
            FROM saleorder
            INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
            INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
            INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
            INNER JOIN wh_inventory ON saleorder_detail.Item_Code = wh_inventory.Item_Code
            WHERE item.StatusRpt = 3
            AND saleorder.IsCancel = 0
            AND date(saleorder.DueDate) = date('$datecheck')
            AND wh_inventory.Branch_Code = 2
            AND (saleorder.Objective = 7
            OR saleorder.Objective = 1)
            AND (saleorder.IsFinish <> 2 OR saleorder.IsFinish <> 0 )
            AND item.IsBakery = 1
            GROUP BY saleorder.DocNo,item.Item_Code
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
              echo '<td  width="70px" style="color:#e3e3e3"><center>0</center></td>';
            }else {
            ?>  <td width="70px"><center><b><a style="text-decoration:none; color:#000000" href="#" onclick="gotoUrlDetail('<?=$datecheck?>','<?=$Result["NameTH"]?>','<?=$eDate?>')"><?=$flag?></a></b></center></td>
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
