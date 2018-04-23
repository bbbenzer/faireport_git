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
            		<h1>ใบสรุปรายการขนมชิ้น - ลูกค้าสั่ง</h1>
			<!--<a href="#" onClick='gotoNewUrl("precrisp_print_nextday.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print ล่วงหน้า 1 วัน</a>-->
		</div>
	</div>

	<div data-role="content">
    <form action="Summary_bakery.php" method="GET">
    <div style="font-size: 20px;">
      <fieldset class="ui-grid-a">
        <div class="ui-block-a">
        <center>
          วันที่<select name="day" id="day">
            <?php for ($i=1; $i <=31 ; $i++) {
              echo  '<option value="'.$dateobj->getNumber($i).'">'.$dateobj->getNumber($i).'</option>';
            } ?>
          </select>
        </center>
        </div>
        <div class="ui-block-b">
          <center>
            เดือน
            <select name="month" id="month">
              <?php for ($i=1; $i <=12 ; $i++) {
                echo  '<option value="'.$dateobj->getNumber($i).'">'.$dateobj->getTHmonth(date('F',strtotime('2018-'.$dateobj->getNumber($i).'-01'))).'</option>';
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

      <a href="#" onClick='gotoUrl("Summary_bakery.php");' data-role="button">ตกลง</a>
    </div>
  </form>
<br>
  <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: dashed; border-width: 1px;">
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-size: 20; float: right;"><b>วันที่รับขนม : วัน<?=$dateobj->getTHday(date('l', strtotime($eDate)));?>
			 ที่ <?=date('d',strtotime($eDate));?> เดือน <?=$dateobj->getTHmonth(date('F',strtotime($eDate)));?> พ.ศ. <?=$dateobj->getTHyear(date('Y',strtotime($eDate)));?> </b></div>
    <br>
    <?php
      $j = 0;
      $count = 0;
      $HeadDetail = "";
      $name = "";
      $Sql = "SELECT sd.DocNo,saleorder.Cus_Code,sd.Item_Code,item.NameTH,item.SalePrice,sum(sd.Qty) AS Qty,
                  CONCAT(customer.FName,' ',customer.LName) AS customer,
                  item_unit.Unit_Name,
                  DATE_FORMAT(Now() ,'%d-%m-%Y') AS ProdDate,sd.Detail,
                  sd.IsNeed_BarCode,
                  (CASE WHEN sd.IsNeed_BarCode = '1' THEN '  ติด อย.'  ELSE 'ไม่ติด อย.'  END ) AS BarCodeNeed,
                  saleorder.Detail AS HeadDetail,
                  sd.IsTime,
                  (CASE WHEN sd.IsTime = '1' THEN 'บ่าย' ELSE 'เช้า'  END ) AS TimeName


                   from saleorder_detail as sd

                  LEFT JOIN saleorder on sd.DocNo = saleorder.DocNo
                  LEFT JOIN item on item.Item_Code = sd.Item_Code
                  LEFT JOIN item_unit on item_unit.Unit_Code = item.Unit_Code
                  LEFT JOIN customer on customer.Cus_Code = saleorder.Cus_Code


                  where DATE(saleorder.DueDate) = DATE('$eDate')

                   and sd.Qty > '0'
                  and saleorder.Objective = '7'
                  and saleorder.IsFinish = '3' and saleorder.IsCancel = '0'

                  GROUP BY sd.Detail,saleorder.Cus_Code,item.NameTH,item.saleprice,saleorder.Detail,sd.IsTime
                  ORDER BY saleorder.Cus_Code,sd.IsTime,sd.Detail

                  ";
                  $meQuery = mysql_query($Sql);
                  while ($Result = mysql_fetch_assoc($meQuery)) {
                      if($j==0 || ($name != $Result["customer"])){  ?>
                      <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">
                      <fieldset class="ui-grid-a" style="margin-left:15px; margin-right:15px; font-size:17px; font-weight:bold;">
                      <div class="ui-block-a" style="width:70%;">
                        ► <?=$Result["customer"]?>
                      </div>
                      <div class="ui-block-b" style="width:30%;">
                        ( <?=$Result["HeadDetail"]?> )
                      </div>
                      </fieldset>
                    <?php
                      $HeadDetail = $Result["HeadDetail"];
                      $name = $Result["customer"];
                      $count = 0;
                  }else{
                    if($j==0 || $HeadDetail != $Result["HeadDetail"]){ ?>
                      <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">
                      <fieldset class="ui-grid-a" style="margin-left:15px; margin-right:15px; font-size:17px; font-weight:bold;">
                      <div class="ui-block-a" style="width:70%;">
                        ► <?=$Result["customer"]?>
                      </div>
                      <div class="ui-block-b" style="width:30%;">
                        ( <?=$Result["HeadDetail"]?> )
                      </div>
                      </fieldset>
                    <?php
                    $HeadDetail = $Result["HeadDetail"];
                    $name = $Result["customer"];
                    $count = 0;
                  }else{ $count++; }
                    } ?>
                  <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">
                  <fieldset class="ui-grid-d" style="margin-left:60px; margin-right:15px; font-size:15px; font-weight:normal;">
                    <div class="ui-block-a" style="width:30%;">
                      <?=((int)$count+1).". ".$Result["NameTH"]?>
                    </div>
                    <div class="ui-block-b" style="width:10%;">
                      <?=$Result["SalePrice"]?>
                    </div>
                    <div class="ui-block-c" style="width:20%;">
                      <?=$Result["Qty"]." ชิ้น &nbsp;&nbsp;".$Result["BarCodeNeed"]?>
                    </div>
                    <div class="ui-block-d" style="width:10%;">
                      <?=$Result["TimeName"]?>
                    </div>
                    <div class="ui-block-e" style="width:30%;">
                      <?=$Result["Detail"]?>
                    </div>
                  </fieldset>
                  <?php
                  $j++;
                }
     ?>

</div>
<div data-role="footer">
  <h1>FAI BAKERY CHIANGMAI</h1>
		</div>
</div>

	</body>
</html>
