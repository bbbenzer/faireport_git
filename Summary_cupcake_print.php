<?php
require 'db_connect.php';
require 'class.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];

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
<center><b style="font-size: 24">รายงานคัพเค้ก</b>
<br>389/2 หมู่ 5 ต.ยางเนิ้ง อ.สารภี เชียงใหม่ 50140
<br>โทร. 0-5322-2828
<br>Email : faibakerylanna@hotmail.com
<br><br>
</center>
<hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: dashed; border-width: 1px;">
  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-size: 20; float: right;"><b>วันที่รับขนม : วัน<?=$dateobj->getTHday(date('l', strtotime($eDate)));?>
     ที่ <?=date('d',strtotime($eDate));?> เดือน <?=$dateobj->getTHmonth(date('F',strtotime($eDate)));?> พ.ศ. <?=$dateobj->getTHyear(date('Y',strtotime($eDate)));?> </b></div>
  <br>
  <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">
  <?php
  $i = 0;
  $Detail = "";
  $Sql_main = "SELECT NameTH,SalePrice,SUM(sd.Qty) AS QtyOrder,CONCAT(FName,'  ',LName) AS Customer,
              DATE(saleorder.DueDate) AS Duedate
              ,sd.Price,sd.DocNo

              from saleorder_detail AS sd
              LEFT JOIN saleorder on saleorder.DocNo = sd.DocNo
              LEFT JOIN customer on customer.Cus_Code = saleorder.Cus_Code
              LEFT JOIN item on item.Item_Code = sd.Item_Code

              where DATE(saleorder.DueDate) = DATE('$eDate')

               and IsBakery = '1'

               and saleorder.Objective = '1'

               and item.Item_Code IN ('1405014791','9900000084','0504010275','1405058436')

               GROUP BY NameTH,SalePrice

              ORDER BY Customer,NameTH,SalePrice";
              $meQuery = mysql_query($Sql_main);
              while ($Result_m = mysql_fetch_assoc($meQuery)) {
              $tempNameTH = $Result_m["NameTH"];
              $tempSalePrice = $Result_m["SalePrice"];
              ?>

              <div style="margin-left:10px; font-size:20px; font-weight:bold;">
                ► <?=$Result_m["NameTH"]?> &nbsp;&nbsp;&nbsp;&nbsp;ราคา <?=$Result_m["SalePrice"]?>
              </div>
              <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">

              <?php $Sql_sub = "SELECT NameTH,SalePrice,SUM(sd.Qty) AS QtyOrder,CONCAT(FName,'  ',LName) AS Customer,
                          DATE(saleorder.DueDate) AS Duedate
                          ,sd.Price,sd.DocNo

                          from saleorder_detail AS sd
                          LEFT JOIN saleorder on saleorder.DocNo = sd.DocNo
                          LEFT JOIN customer on customer.Cus_Code = saleorder.Cus_Code
                          LEFT JOIN item on item.Item_Code = sd.Item_Code

                          where DATE(saleorder.DueDate) = DATE('$eDate')

                           and IsBakery = '1'

                           and saleorder.Objective = '1'

                           and item.Item_Code IN ('1405014791','9900000084','0504010275','1405058436')

                           and item.NameTH = '$tempNameTH' and item.SalePrice ='$tempSalePrice'

                           GROUP BY FName,NameTH

                          ORDER BY Customer,NameTH,SalePrice
                          ";
                          $meQuery2 = mysql_query($Sql_sub);
                          while ($Result = mysql_fetch_assoc($meQuery2)) {
                              ?>
                            <fieldset class="ui-grid-a" style="margin-left:60px; margin-right:15px;">
                            <div class="ui-block-a" style="font-size:15px; font-weight:normal; width:50%">
                              <div align="left">
                                <?=$Result["Customer"]?> &nbsp;&nbsp; จำนวน <?=$Result["QtyOrder"]?>
                              </div>
                            </div>
                            <div class="ui-block-b" style="font-size:15px; font-weight:normal; width:50%">
                              <div align="right">
                              </div>
                            </div>
                            </fieldset>
                            <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">

                          <?php
                        }
          }

  ?>

</body>
</html>
