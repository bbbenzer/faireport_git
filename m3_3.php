<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$fn = $_REQUEST["FN"];
if($fn==""){
	$eDate = $_REQUEST["xDate"];
}else if($fn==1){
	$eDate = $_REQUEST["xDate"];
	$date = strtotime($eDate);
	$date = strtotime("-1 day", $date);
	$eDate =  date('Y-m-d', $date);
}else if($fn==2){
	$eDate = $_REQUEST["xDate"];
	$date = strtotime($eDate);
	$date = strtotime("+1 day", $date);
	$eDate =  date('Y-m-d', $date);
}

?>
<!--
 *  -- ************************************************************
 -- Author		:	Tanadech
 -- Create date	:	03-09-2017
 -- Update By	:	Tanadech
 -- Update date	:   03-09-2017
 -- Description	:
 -- ************************************************************
-->

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
		function gotoNewUrl(xLink,DueDate) {
			window.open( xLink+"?xDate="+DueDate, '_blank' );
		}
			function gotoUrl(xLink,DueDate,FN) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				location.href = xLink+"?xDate="+DueDate+"&FN="+FN;
			}

			function gotoUrlDetail(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate+"&xUrl=m3_3.php";
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
            		<h1>วันที่จัดส่ง : <?=$eDate?></h1>
			<a href="#" onClick='gotoNewUrl("bill_01.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">

		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="50px" data-priority="2">ลำดับ</th>
					 <th width="100px" data-priority="2">รหัส</th>
					 <th width="300px">ชื่อลูกค้า</th>
                     <th width="100px">สถานะ</th>
					 <!--<th width="100px">บาร์โค้ด</th> -->
                     <th width="300px">รายการ</th>
                     <th width="100px">ราคา</th>
					 <th width="100px" data-priority="2">จำนวน</th>
                     <th width="150px" data-priority="2">วันที่จัดส่ง</th>
                     <th width="500px" data-priority="2">รายละเอียด</th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
$Sql = "SELECT
checkitemlist.RowID,
checkitemlist.SODocNo,
checkitemlist.CusCode,
CONCAT(customer.FName,' ',customer.LName) AS Customer,
SUBSTR(item.Barcode,10,4) AS Barcode,
item.NameTH,
item.SalePrice,
checkitemlist.Qty AS DetailQty,
CASE checkitemlist.IsFinish
WHEN '1' THEN 'ไม่ได้'
ELSE 'ได้'
END AS IsFinish,
DATE_FORMAT(checkitemlist.DueDate ,'%d-%m-%Y') AS DueDate,
checkitemlist.Remark
FROM checkitemlist
LEFT JOIN customer ON customer.Cus_Code = checkitemlist.CusCode
INNER JOIN item ON checkitemlist.Item_Code = item.Item_Code
where DATE(checkitemlist.DueDate) = DATE('$eDate') and checkitemlist.Objective = '7'";
						//echo $Sql;
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
					<?php if($Result["IsFinish"]=="ได้"){ ?>
							<tr style="color:#009900;">
					<?php }else{ ?>
							<tr style="color:#FE2E2E;">
					<?php } ?>
					<?php if($row==1||$string!=$Result["CusCode"])
					{
						$string = $Result["CusCode"];
						?>
						<th width="50px"><?=$row?></th>
	          <th width="100px"><?=$Result["CusCode"]?></th>
						<td width="300px"><?=$Result["Customer"]?></td>
					<?php $row++; }else {
						?>
							<th width="50px"></th>
		          <th width="100px"></th>
							<td width="300px"></td>
						<?php }


					 ?>
					<!--<th width="50px"><?=$row?></th>
          <th width="100px"><?=$Result["CusCode"]?></th>
					<td width="300px"><?=$Result["Customer"]?></td> -->
          <td width="100px"><?=$Result["IsFinish"]?></td>
          <!--<td width="100px"><?=$Result["Barcode"]?></td> -->
          <td width="300px"><?=$Result["NameTH"]?></td>
					<td width="100px"><?=$Result["SalePrice"]?></td>
          <td width="100px" ><?=$Result["DetailQty"]?></td>
          <td width="150px"><?=$Result["DueDate"]?></td>
					<td width="500px"><?=$Result["Remark"]?></td>
				</tr>
			<?

				}
			?>

			</tbody>
		</table>

</div>
</form>
<div data-role="footer">
			<a href="#" onClick='gotoUrl("m3_3.php","<?=$eDate?>",1);' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-arrow-l">Previous</a>
            		<h1>FAI BAKERY CHIANGMAI</h1>
			<a href="#" onClick='gotoUrl("m3_3.php","<?=$eDate?>",2);' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-arrow-r">Next</a>
		</div>
</div>

	</body>
</html>
