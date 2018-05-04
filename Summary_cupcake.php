<?php
require 'db_connect.php';
require 'class.php';
date_default_timezone_set("Asia/Bangkok");
if(isset($_REQUEST["eDate"])){
  $eDate = $_REQUEST["eDate"];
}else {
  $eDate = $_REQUEST["xDate"];
}

$lDate =  date('Y-m-d', $date);
$dateobj = new DatetimeTH;
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.5.min.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/jquery.js"></script>
		<script src="js/jquery.mobile-1.4.5.min.js"></script>
        <script type="application/javascript">
		function gotoNewUrl(xLink,eDate) {
			window.open( xLink+"?xDate="+eDate );
		}
			function gotoUrl(xLink) {
        var day = document.getElementById('day').value;
        var month = document.getElementById('month').value;
        var year = document.getElementById('year').value;
        var date = year+'-'+month+'-'+day;
				location.href = xLink+"?eDate="+date;
			}

			function gotoUrlDetail(DueDate,NameTH,eDate,Item_Code) {
				location.href = "precrisp_detail.php"+"?xDate="+eDate+"&DueDate="+DueDate+"&xUrl=precrisp.php"+"&NameTH="+NameTH+"&Item_Code="+Item_Code;
			}

			function gotoMenu(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate;
			}
		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1>รายงานคัพเค้ก</h1>
		  <a href="#" onClick='gotoNewUrl("Summary_cupcake_print.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">
    <form action="Summary_break.php" method="GET">
    <div style="font-size: 20px;">
      <fieldset class="ui-grid-a">
        <div class="ui-block-a">
        <center>
          วันที่<select name="day" id="day">
            <?php for ($i=1; $i <=31 ; $i++) {
              if($i==date('d')){
                $msg = "selected";
              }else{
                $msg = "";
              }
              echo  '<option value="'.$dateobj->getNumber($i).'" '.$msg.'>'.$dateobj->getNumber($i).'</option>';
            } ?>
          </select>
        </center>
        </div>
        <div class="ui-block-b">
          <center>
            เดือน
            <select name="month" id="month">
              <?php for ($i=1; $i <=12 ; $i++) {
								if($dateobj->getNumber($i)==date('m')){
	                $msg = "selected";
	              }else{
	                $msg = "";
	              }
                echo  '<option value="'.$dateobj->getNumber($i).'" '.$msg.'>'.$dateobj->getTHmonth(date('F',strtotime('2018-'.$dateobj->getNumber($i).'-01'))).'</option>';
              } ?>
            </select>
          </center>
        </div>
      </fieldset>
      <center>ปี</center>
      <select name="year" id="year">
        <?php for ($i=date('Y'); $i >=2017 ; $i--) {
          echo  '<option value="'.$dateobj->getNumber($i).'">'.$dateobj->getTHyear($dateobj->getNumber($i)).'</option>';
        } echo $dateobj->getTHyear(date('Y'));?>

      </select>

      <a href="#" onClick='gotoUrl("Summary_cupcake.php");' data-role="button">ตกลง</a>
    </div>
  </form>
<br>
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

</div>
<div data-role="footer">
  <h1>FAI BAKERY CHIANGMAI</h1>
		</div>
</div>

	</body>
</html>
