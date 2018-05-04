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
            		<h1>ตรวจสอบการส่งบิลออเดอร์</h1>
			<!--<a href="#" onClick='gotoNewUrl("precrisp_print_nextday.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print ล่วงหน้า 1 วัน</a>-->
		</div>
	</div>

	<div data-role="content">
    <form action="checkbillorder.php" method="post">
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

      <a href="#" onClick='gotoUrl("checkbillorder.php");' data-role="button">ตกลง</a>
    </div>
  </form>
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-size: 20"><b>วัน<?=$dateobj->getTHday(date('l', strtotime($eDate)));?>
			 ที่ <?=date('d',strtotime($eDate));?> เดือน <?=$dateobj->getTHmonth(date('F',strtotime($eDate)));?> พ.ศ. <?=$dateobj->getTHyear(date('Y',strtotime($eDate)));?> </b></div>
		<table style="font-size: 11px;" data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='display:block;'>
				<tr class="ui-bar-d">
           <th  style="vertical-align: middle;" width="100px" data-priority="2">เลขที่เอกสาร</th>
					 <th  style="vertical-align: middle;" width="100px">วันที่เอกสาร</th>
           <th  style="vertical-align: middle;" width="250px">ชื่อลูกค้า</th>
           <th  style="vertical-align: middle;" width="120px">แก้ไขครั้งสุดท้าย</th>
					 <th  style="vertical-align: middle;" width="120px">ส่งบิล</th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT saleorder.DocNo,saleorder.DocDate,CONCAT(customer.FName,' ',customer.LName) AS Fullname
              -- userCreate
              ,saleorder.LastUpdate,CASE WHEN saleorder.SendTime IS NULL THEN '' ELSE saleorder.SendTime END as SendTime
              FROM saleorder INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
              WHERE date(saleorder.DocDate) = date('".$eDate."') AND saleorder.IsCancel = 0";
						//echo $Sql;
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<td width="100px"><?=$Result["DocNo"]?></td>
					<td width="100px"><?=$Result["DocDate"]?></td>
					<td width="250px"><?=$Result["Fullname"]?></td>
          <?php
          $lastupdate = $Result["LastUpdate"];
          $sendtime = $Result["SendTime"];

          $diff = strtotime($lastupdate) - strtotime($sendtime);
          $diff = $diff/86400;

          if($diff>0.30){ ?>
            <td width="120px"><center><font color="#ff0000"><?=$Result["LastUpdate"]?></font></center></td>
            <td width="120px"><center><font color="#ff0000"><?=$Result["SendTime"]?></font></center></td>
          <?php }else { ?>
            <td width="120px"><center><?=$Result["LastUpdate"]?></center></td>
            <td width="120px"><center><?=$Result["SendTime"]?></center></td>
          <?php } ?>

				</tr>
			<?
				$row++;

				}
			?>

			</tbody>
		</table>

</div>
</form>
<div data-role="footer">
  <h1>FAI BAKERY CHIANGMAI</h1>
		</div>
</div>

	</body>
</html>
