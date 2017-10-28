<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$xUrl = $_REQUEST["xUrl"];
$xUrl2 = $_REQUEST["xUrl2"];
$flag = $_REQUEST["flag"];

if(isset($_REQUEST["Fl"]))
{
	$Fl = $_REQUEST["Fl"];
}else {
	$Fl = 1;
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
			function gotoUrl(xLink,xItc,DueDate,flag) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = xLink+"?xItc="+xItc+"&xDate="+DueDate+"&xUrl=m2_1.php"+"&flag="+flag;
			}

			function gotoMenu(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate;
			}

			function gotoFilter(xLink,DueDate,Fl,flag) {
				location.href = xLink+"?xDate="+DueDate+"&Fl="+Fl+"&flag="+flag;
			}
		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1><?=$eDate?></h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>

	<div data-role="content">
	  <h3>สินค้าที่ไม่พอจัด</h3>

<? if($Fl==2){ ?>
    <fieldset data-role="controlgroup" data-mini="true">
    	<input type="radio" name="radio-mini" id="radio-mini-1" value="choice-1"  onClick='gotoFilter("m2_1.php","<?=$eDate?>",1,"<?=$flag?>");'/>
    	<label for="radio-mini-1">เรียงตามราคา</label>

		<input type="radio" name="radio-mini" id="radio-mini-2" value="choice-2"  checked="checked"onClick='gotoFilter("m2_1.php","<?=$eDate?>",2,"<?=$flag?>");'/>
    	<label for="radio-mini-2">เรียงตามห้อง/ราคา</label>
	</fieldset>
<? }else{ ?>
    <fieldset data-role="controlgroup" data-mini="true">
    	<input type="radio" name="radio-mini" id="radio-mini-1" value="choice-1" checked="checked" onClick='gotoFilter("m2_1.php","<?=$eDate?>",1,"<?=$flag?>");'/>
    	<label for="radio-mini-1">เรียงตามราคา</label>

		<input type="radio" name="radio-mini" id="radio-mini-2" value="choice-2"  onClick='gotoFilter("m2_1.php","<?=$eDate?>",2,"<?=$flag?>");'/>
    	<label for="radio-mini-2">เรียงตามห้อง/ราคา</label>
	</fieldset>
<? } ?>

		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="50px" data-priority="2">ลำดับ</th>
                     <th width="150px"data-priority="2">ห้องผลิต</th>
					 <th width="80px"data-priority="2">บาร์โค้ด</th>
					 <th width="200px">ชื่อสินค้า</th>
					 <th width="50px">ราคา</th>
                     <th width="80px" data-priority="2">แบบ</th>
					 <th width="50px">order</th>
					 <th width="50px" data-priority="2">com</th>
                     <th width="50px" data-priority="2">ยกมา</th>
					 <th width="50px" data-priority="2" >get</th>
                     <th width="50px" data-priority="2" >sale</th>
				</tr>
			</thead>

			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT
buffer_fac_order.xDt,
buffer_fac_order.id,
buffer_fac_order.Item_Code,
buffer_fac_order.Barcode,
buffer_fac_order.NameTH,
buffer_fac_order.IsForm,
buffer_fac_order.SalePrice,
buffer_fac_order.SoQty,
buffer_fac_order.CrQty,
buffer_fac_order.FacQty,
buffer_fac_order.RvQty,
buffer_fac_order.SaQty,
buffer_fac_order.StQty,
roomtype.roomname
FROM buffer_fac_order
LEFT JOIN roomtype ON buffer_fac_order.roomtypeID = roomtype.roomtypeID
WHERE ( buffer_fac_order.CrQty + buffer_fac_order.RvQty ) < buffer_fac_order.SoQty
AND buffer_fac_order.RvQty = 0 AND buffer_fac_order.FacQty > 0
AND buffer_fac_order.Objective = 1
AND DATE(xDt) = DATE('$eDate')
AND buffer_fac_order.IsForm != 'ของกรอบ'";
if($Fl==2)
	$Sql .= "ORDER BY  roomtype.roomname ASC,buffer_fac_order.SalePrice ASC" ;
else
	$Sql .= "ORDER BY  buffer_fac_order.SalePrice ASC";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
                	<th width="50px"><?=$row?></th>
                    <th width="150px"><?=$Result["roomname"]?></th>
					<th width="80px"><?=$Result["Barcode"]?></th>
					<td width="200px"><?=$Result["NameTH"]?></td>
					<td width="50px"><?=$Result["SalePrice"]?></td>
                    <td width="80px"><?=$Result["IsForm"]?></td>
					<td  width="50px"style="cursor: pointer;" onClick='gotoUrl("mCustomer.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$flag?>")'><?=$Result["SoQty"]?></td>
					<td width="50px"><?=$Result["FacQty"]?></th>
                    <td width="50px"><?=$Result["CrQty"]?></th>
                    <td width="50px"><?=$Result["RvQty"]?></th>
					<td  width="50px"style="cursor: pointer;" onClick='gotoUrl("Sale.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$flag?>")'><?=$Result["SaQty"]?></th>

				</tr>
			<?
				$row++;

				}
			?>

			</tbody>
		</table>

</div>

<div data-role="footer">
			<h1>FAI BAKERY CHIANGMAI</h1>
</div>

	</body>
</html>
