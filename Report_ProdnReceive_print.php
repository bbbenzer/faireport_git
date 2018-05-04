<?php
require 'db_connect.php';
require 'class.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);
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
<center><b style="font-size: 24">ยอดผลิตและบันทึกรับประจำวัน</b>
<br>389/2 หมู่ 5 ต.ยางเนิ้ง อ.สารภี เชียงใหม่ 50140
<br>โทร. 0-5322-2828
<br>Email : faibakerylanna@hotmail.com
<br><br>
</center>
<hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: dashed; border-width: 1px;">
  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-size: 20; float: right;"><b>วันที่เอกสาร : วัน<?=$dateobj->getTHday(date('l', strtotime($eDate)));?>
     ที่ <?=date('d',strtotime($eDate));?> เดือน <?=$dateobj->getTHmonth(date('F',strtotime($eDate)));?> พ.ศ. <?=$dateobj->getTHyear(date('Y',strtotime($eDate)));?> </b></div>
  <br>
  <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">

  <table class="table table-sm" data-role="table" style="font-size: 18" border="1">
    <thead>
      <tr>
         <th>ลำดับที่</th>
         <th>รายการ</th>
         <th>ราคา</th>
         <th>จำนวนสั่งผลิต</th>
         <th>จำนวนบันทึกรับ</th>
      </tr>
    </thead>
    <tbody>

  <?php
    $i = 1;
    $Detail = "";
    $Sql_main = "SELECT
                  List.Item_Code,item.Barcode,item.NameTH,item.SalePrice,List.QtyFac AS Qty,roomname,item.IsOrder,
                  item_unit.Unit_Name AS unitName,
                  item.isMain,
                  item.roomtypeID,
                  List.DueDate,
                  List.ProdDate
                  ,(select count(ID) from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = List.Item_Code AND (item.ismain = '1' or item.ismain = '2') )) AS bbb

                  ,(CASE WHEN COALESCE((select count(ID) from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = List.Item_Code AND (item.ismain = '1' or item.ismain = '2') ) ),0) > '0' THEN
                     CONCAT((select stocksw.Qty from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = List.Item_Code AND (item.ismain = '1' or item.ismain = '2') )),
                    (CASE WHEN item.isMain = '1' THEN ' ม้วน'  WHEN item.isMain = '2' THEN ' แท่ง'  END ) )
                  ELSE  coalesce((select itemstock.Qty from itemstock where DATE(itemstock.DueDate) = DATE('$eDate') and itemstock.ItemCode = List.Item_Code ),0)
                  END ) AS StockQty
                  ,
                  ( select List2.OrderQty from
                    ( select coalesce(SUM(SD.Qty),0)  AS OrderQty,SD.Item_Code
                      from saleorder_detail AS SD LEFT JOIN saleorder on saleorder.DocNo = SD.DocNo
                      where DATE(saleorder.DueDate) = DATE('$eDate')
                  and saleorder.Objective = '1'
                  and saleorder.IsFinish = '3'
                      GROUP BY Item_Code
                  ) AS List2 where List2.Item_Code = List.Item_Code ) AS OrderQty
                  ,
                  ( SELECT List3.Qty from
                  ( SELECT whrs.Item_Code,
                  -- whrs.Qty
                  SUM(whrs.Qty) AS Qty
                   FROM wh_stock_receive AS whr
                  LEFT JOIN wh_stock_receive_sub AS whrs ON whr.DocNo = whrs.DocNo
                  WHERE whr.Modify_Date BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
                  AND whr.Branch_Code = 2 AND IsCancel = 0
                  GROUP BY whrs.Item_Code
                  ) AS List3 where List3.Item_Code = List.Item_Code )  AS ReceiveQty

                  from

                  (
                  select facorderdetail.Item_Code,sum(facorderdetail.ItemFormula1) AS QtyFac,
                  DATE_FORMAT(facorder.DueDate ,'%d-%m-%Y') AS DueDate,DATE_FORMAT(Now() ,'%d-%m-%Y') AS ProdDate

                  from facorderdetail
                  LEFT JOIN facorder on facorder.DocNo = facorderdetail.DocNo

                  where DATE(facorder.DueDate) = DATE('$eDate')

                   and facorderdetail.ItemFormula1 > '0'
                  and facorderdetail.Objective = '1'

                  GROUP BY facorderdetail.Item_Code

                  ) AS List

                  LEFT JOIN item on item.Item_Code = List.Item_Code
                  LEFT JOIN item_unit on item_unit.Unit_Code = item.Unit_Code
                   LEFT JOIN roomtype on roomtype.roomtypeID = item.RoomPackID

                  where
                  -- IsPack = '1'
                   item.RoomPackID IN ('19','15','6')
                  and item.Item_Code not in ('0101010057','0101010008','0101010003','0102010297','0101010026','0101010005','0101010018','0101010015')

                  order by item.SalePrice,item.NameTH
                  ";
                $meQuery = mysql_query($Sql_main);
                while ($Result = mysql_fetch_assoc($meQuery)) {

                ?>
                  <tr>
                    <th width="10%"><?=$i?></th>
                    <td width="24%"><?=$Result["NameTH"]?></td>
                    <td width="20%"><?=$Result["SalePrice"]?></td>
                    <td width="20%"><?=$Result["Qty"]?></td>
                    <td width="20%"><?=(int)$Result["ReceiveQty"]?></td>
                  </tr>
                <?php
                $i++;

            }

   ?>
 </tbody>
</table>

</body>
</html>
