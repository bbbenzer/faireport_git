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
            		<h1>ใบสรุปขนมเบรค</h1>
			<!--<a href="#" onClick='gotoNewUrl("precrisp_print_nextday.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print ล่วงหน้า 1 วัน</a>-->
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

      <a href="#" onClick='gotoUrl("Summary_break.php");' data-role="button">ตกลง</a>
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
      $Sql = "SELECT saleorder.Cus_Code,DATE_FORMAT(saleorder.DueDate ,'%d-%m-%Y') AS DueDate
              From  saleorder
              LEFT JOIN customer on customer.Cus_Code = saleorder.Cus_Code
              Where DATE(saleorder.DueDate) = DATE('$eDate')
              and saleorder.Objective = '2'
              and saleorder.IsFinish = '3' and saleorder.IsCancel = '0'
              GROUP BY saleorder.Cus_Code
              order by saleorder.Cus_Code;
              ";
      $meQuery = mysql_query($Sql);
      while ($Result = mysql_fetch_assoc($meQuery)) {
        $Cus_Code[$i] = $Result["Cus_Code"];
        $j = 0;
        $count = 0;
        $Sql_sub = "SELECT group_concat(`aaa` separator ' / ') AS NameTH ,customer,CONCAT(Detail,'  เบรคชุด ',BreakGroup) AS CusName,Qty
                    FROM
                      (
                    SELECT
                    		CONCAT(	(case when IsDrink = '1' THEN NameTH ELSE '' END   ) ,
                    	(CASE WHEN IsDrink = '1' THEN '' ELSE CONCAT(NameTH,' ',ROUND(item.SalePrice,0),'  บาท' ) END  ) ) AS aaa,
                    saleorder.Detail,CONCAT(customer.FName,' ',customer.LName) AS customer2
                    ,(CASE WHEN customer.Cus_Code = '9897' THEN  CONCAT(customer.FName,' ',customer.LName,'    ส่งที่สาขาสวนดอก ') ELSE CONCAT(customer.FName,' ',customer.LName) END ) AS customer
                    ,sd.DocNo,
                    saleorder.BreakGroup ,(select Qty from saleorder_detail where DocNo = sd.DocNo limit 1 ) AS Qty

                     from saleorder_detail as sd
                    LEFT JOIN saleorder on sd.DocNo = saleorder.DocNo
                    LEFT JOIN customer on customer.Cus_Code = saleorder.Cus_Code

                    LEFT JOIN item on item.Item_Code = sd.Item_Code

                    WHERE DATE(saleorder.DueDate) = DATE('$eDate')
                    and saleorder.Objective = '2'
                    and saleorder.IsFinish = '3' and saleorder.IsCancel = '0'
                    and customer.Cus_Code = '$Cus_Code[$i]'

                    ORDER BY IsDrink DESC
                      )
                      AS PP
                     GROUP BY Customer,Detail,DocNo,BreakGroup;
                    ";
                    $meQuery2 = mysql_query($Sql_sub);
                    while ($Result = mysql_fetch_assoc($meQuery2)) {
                        if($j==0){  ?>
                        <div style="margin-left:10px; font-size:20px; font-weight:bold;">
                          ► <?=$Result["customer"]?>
                        </div>
                        <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">
                      <?php }
                        if($Detail != $Result["CusName"] || $Detail == ""){ ?>
                          <div style="margin-left:40px; font-size:17px; font-weight:bold;">
                            <?=$Result["CusName"]?>
                          </div>
                          <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">
                      <?php
                          $Detail = $Result["CusName"];
                          $count = 0;
                    }else {
                      $count++;
                    }

                    ?>

                      <fieldset class="ui-grid-a" style="margin-left:60px; margin-right:15px;">
                      <div class="ui-block-a" style="font-size:15px; font-weight:normal; width:80%">
                        <div align="left">
                          <?=((int)$count+1).". ".$Result["NameTH"]?>
                        </div>
                      </div>
                      <div class="ui-block-b" style="font-size:15px; font-weight:normal; width:20%">
                        <div align="right">
                          <?=$Result["Qty"]?> ชุด
                        </div>
                      </div>
                      </fieldset>
                      <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">

                    <?php
                    $j++;
                  }
          $i++;
      }
     ?>

</div>
<div data-role="footer">
  <h1>FAI BAKERY CHIANGMAI</h1>
		</div>
</div>

	</body>
</html>
