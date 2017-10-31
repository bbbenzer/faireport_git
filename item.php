<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];
$xItc = $_REQUEST["xItc"];
$CusCode = $_REQUEST["CusCode"];
$xUrl = $_REQUEST["xUrl"];
$xUrl2 = $_REQUEST["xUrl2"];
$IS = $_REQUEST["IS"];
$flag = $_REQUEST["flag"];

if(isset($_REQUEST["Fl"]))
{
	$Fl = $_REQUEST["Fl"];
}else {
	$Fl = 1;
}
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

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
			function gotoUrl(xLink,xItc,DueDate,xUrl2,CusCode,xUrl,flag) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = xLink+"?xItc="+xItc+"&xDate="+DueDate+"&xUrl=m3_1.php"+"&xUrl="+xUrl+"&xUrl2="+xUrl2+"&CusCode="+CusCode+"&flag="+flag;
			}


			function gotoMenu(xLink,DueDate,CusCode,xUrl2) {
				location.href = xLink+"?xDate="+DueDate+"&CusCode="+CusCode+"&xUrl="+xUrl2;
			}

			function gotoFilter(xLink,DueDate,Fl,IS,xItc,xUrl,xUrl2,CusCode,flag) {
				location.href = xLink+"?xDate="+DueDate+"&Fl="+Fl+"&IS="+IS+"&xItc="+xItc+"&xUrl="+xUrl+"&xUrl2="+xUrl2+"&CusCode="+CusCode+"&flag="+flag;
			}
		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("<?=$xUrl?>","<?=$eDate?>","<?=$CusCode?>","<?=$xUrl2?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1>สินค้าประจำวัน <?=$eDate?></h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>","<?=$CusCode?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>



	<div data-role="content">
    <? if($Fl==2){ ?>
    <fieldset data-role="controlgroup" data-mini="true">
    	<input type="radio" name="radio-mini" id="radio-mini-1" value="choice-1"  onClick='gotoFilter("m3_1.php","<?=$eDate?>",1,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-1">เรียงตามราคา</label>

		<input type="radio" name="radio-mini" id="radio-mini-2" value="choice-2"  checked="checked"onClick='gotoFilter("m3_1.php","<?=$eDate?>",2,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-2">เรียงตามห้อง/ราคา</label>
	</fieldset>
<? }else{ ?>
    <fieldset data-role="controlgroup" data-mini="true">
    	<input type="radio" name="radio-mini" id="radio-mini-1" value="choice-1" checked="checked" onClick='gotoFilter("m3_1.php","<?=$eDate?>",1,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-1">เรียงตามราคา</label>

		<input type="radio" name="radio-mini" id="radio-mini-2" value="choice-2"  onClick='gotoFilter("m3_1.php","<?=$eDate?>",2,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-2">เรียงตามห้อง/ราคา</label>
	</fieldset>
<? } ?>


  <fieldset data-role="controlgroup" data-type="horizontal">
        <legend>เลือกประเภท :</legend>
        <input type="radio" name="radio-choice-h-2" id="radio-choice-h-2a" value="on"
onClick='gotoFilter("m3_1.php","<?=$eDate?>",<?=$Fl?>,1,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2a">ชิ้น</label>
        <input type="radio" name="radio-choice-h-2" id="radio-choice-h-2b" value="off"
onClick='gotoFilter("m3_1.php","<?=$eDate?>",<?=$Fl?>,2,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2b">ของกรอบ</label>
        <input type="radio" name="radio-choice-h-2" id="radio-choice-h-2c" value="off"
onClick='gotoFilter("m3_1.php","<?=$eDate?>",<?=$Fl?>,3,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2c">สูตร</label>
		<input type="radio" name="radio-choice-h-2" id="radio-choice-h-2d" value="off"
onClick='gotoFilter("m3_1.php","<?=$eDate?>",<?=$Fl?>,4,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2d">Special</label>
		<input type="radio" name="radio-choice-h-2" id="radio-choice-h-2e" value="off"
onClick='gotoFilter("m3_1.php","<?=$eDate?>",<?=$Fl?>,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2e">All</label>
    </fieldset>

		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="50px" data-priority="2">ลำดับ</th>
                     <th width="150px"data-priority="2">ห้องผลิต</th>
					 <th width="150px"data-priority="2">บาร์โค้ด</th>
					 <th width="200px">ชื่อสินค้า</th>
					 <th width="50px">ราคา</th>
                     <th width="80px" data-priority="2">แบบ</th>
					 <th width="50px">order</th>
                     <th width="50px" data-priority="2">ยกมา</th>
					 <th width="50px" data-priority="2">com</th>
					 <th width="50px" data-priority="2" >get</th>
                     <th width="50px" data-priority="2" >sale</th>
				</tr>
			</thead>

			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT roomtype.roomname,
				item.Item_Code,
				item.Barcode,
				item.NameTH,
				item.SalePrice,
				CASE item.IsForm
				WHEN 0 THEN 'สูตร'
				WHEN 1 THEN 'ชิ้น'
				WHEN 2 THEN 'ของกรอบ'
				WHEN 3 THEN 'special'
				WHEN 4 THEN 'ซื้อมาขายไป'
				END AS IsForm,

				IFNULL((SELECT IFNULL(SUM(saleorder_detail.Qty),0)
				FROM saleorder_detail
				INNER JOIN saleorder ON saleorder.DocNo = saleorder_detail.DocNo
				WHERE saleorder_detail.Item_Code = '$xItc'
				AND DATE(saleorder.DueDate) = DATE('$eDate')),0) AS orderqty,

				IFNULL((SELECT IFNULL(SUM(itemstock.Qty),0)
				FROM itemstock
				WHERE itemstock.ItemCode = '$xItc'
				AND DATE(itemstock.DueDate) = DATE('$eDate')),0) AS bringqty,

				IFNULL((SELECT IFNULL(SUM(facorderdetail.SaleOrderQty),0)
				FROM facorderdetail
				INNER JOIN facorder ON facorder.DocNo = facorderdetail.DocNo
				WHERE facorderdetail.Item_Code = '$xItc'
				AND DATE(facorder.DueDate) = DATE('$eDate')),0) AS comqty,

				IFNULL((SELECT
				((SELECT IFNULL(SUM(wh_stock_receive_sub.Qty),0)
				FROM wh_stock_receive
				INNER join wh_stock_receive_sub on wh_stock_receive_sub.DocNo = wh_stock_receive.DocNo
				INNER JOIN item ON wh_stock_receive_sub.Item_Code = item.Item_Code
				WHERE wh_stock_receive.Modify_Date BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00'
				AND wh_stock_receive_sub.Item_Code = '$xItc'
				AND wh_stock_receive.Branch_Code = 2)
				-
				(SELECT IFNULL(SUM(wh_stock_transmit_sub.Qty),0)
				FROM wh_stock_transmit
				INNER join wh_stock_transmit_sub on wh_stock_transmit_sub.DocNo = wh_stock_transmit.DocNo
				INNER JOIN item ON wh_stock_transmit_sub.Item_Code = item.Item_Code
				WHERE wh_stock_transmit.Modify_Date BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00'
				AND wh_stock_transmit_sub.Item_Code = '$xItc'
				AND wh_stock_transmit.Branch_Code = 2)
				)
				),0) AS getqty,

				IFNULL((SELECT IFNULL(SUM(sale_pack_run_detail.Qty),0)
				FROM sale_pack_run_detail
				INNER JOIN sale_pack_run ON sale_pack_run_detail.DocNo = sale_pack_run.DocNo
				WHERE sale_pack_run_detail.Item_Code = '$xItc'
				AND DATE(sale_pack_run.DocDate) = DATE('$eDate')),0) AS saleqty
				FROM roomtype
				INNER JOIN item ON roomtype.roomtypeID = item.roomtypeID
				WHERE item.Item_Code = '$xItc'";

if($IS==1)
  $Sql .= "AND item.IsForm = 1 ";
else if($IS==2)
  $Sql .= "AND item.IsForm = 2 ";
else if($IS==3)
  $Sql .= "AND item.IsForm = 0 ";
else if($IS==4)
  $Sql .= "AND item.IsForm = 3 ";

if($Fl==2)
	$Sql .= "ORDER BY  roomtype.roomname ASC,item.SalePrice ASC" ;
else
	$Sql .= "ORDER BY  item.SalePrice ASC";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
                	<th width="50px"><?=$row?></th>
                    <th width="150px"><?=$Result["roomname"]?></th>
					<th width="150px"><?=$Result["Barcode"]?></th>
					<td width="200px"><?=$Result["NameTH"]?></td>
					<td width="50px"><?=$Result["SalePrice"]?></td>
                    <td width="80px"><?=$Result["IsForm"]?></td>
					<td width="50px" style="cursor: pointer;" onClick='gotoUrl("mCustomer.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","Order.php","<?=$flag?>")'><?=$Result["orderqty"]?></td>
                    <td width="50px"><?=$Result["bringqty"]?></td>
					<td width="50px" style="cursor: pointer;" onClick='gotoUrl("Factory.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","Order.php","<?=$flag?>")'><?=$Result["comqty"]?></th>
                    <td  width="50px" style="cursor: pointer;" onClick='gotoUrl("Receive.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","Order.php","<?=$flag?>")'><? echo (int)$Result["getqty"]?></th>
					<td width="50px" style="cursor: pointer;" onClick='gotoUrl("Sale.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","Order.php","<?=$flag?>")'><?=$Result["saleqty"]?></th>
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
