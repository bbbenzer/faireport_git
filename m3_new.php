<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];
$IS = $_REQUEST["IS"];
$xItc = $_REQUEST["xItc"];
$CusCode = $_REQUEST["CusCode"];
$xUrl = $_REQUEST["xUrl"];
$xUrl2 = $_REQUEST["xUrl2"];
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);
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
			function gotoUrl(xLink,xItc,DueDate,xUrl2,CusCode,xUrl,flag) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = xLink+"?xItc="+xItc+"&xDate="+DueDate+"&xUrl="+xUrl+"&xUrl2="+xUrl2+"&CusCode="+CusCode+"&flag="+flag;
			}

			function gotoMenu(xLink,DueDate,CusCode,xUrl) {
				location.href = xLink+"?xDate="+DueDate+"&CusCode="+CusCode+"&xUrl="+xUrl;
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
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>



	<div data-role="content">
    <? if($Fl==2){ ?>
    <fieldset data-role="controlgroup" data-mini="true">
    	<input type="radio" name="radio-mini" id="radio-mini-1" value="choice-1"  onClick='gotoFilter("m3_new.php","<?=$eDate?>",1,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-1">เรียงตามราคา</label>

		<input type="radio" name="radio-mini" id="radio-mini-2" value="choice-2"  checked="checked"onClick='gotoFilter("m3_new.php","<?=$eDate?>",2,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-2">เรียงตามห้อง/ราคา</label>
	</fieldset>
<? }else{ ?>
    <fieldset data-role="controlgroup" data-mini="true">
    	<input type="radio" name="radio-mini" id="radio-mini-1" value="choice-1" checked="checked" onClick='gotoFilter("m3_new.php","<?=$eDate?>",1,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-1">เรียงตามราคา</label>

		<input type="radio" name="radio-mini" id="radio-mini-2" value="choice-2"  onClick='gotoFilter("m3_new.php","<?=$eDate?>",2,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'/>
    	<label for="radio-mini-2">เรียงตามห้อง/ราคา</label>
	</fieldset>
<? } ?>


  <fieldset data-role="controlgroup" data-type="horizontal">
        <legend>เลือกประเภท :</legend>
        <input type="radio" name="radio-choice-h-2" id="radio-choice-h-2a" value="on"
onClick='gotoFilter("m3_new.php","<?=$eDate?>",<?=$Fl?>,1,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2a">ชิ้น</label>
        <input type="radio" name="radio-choice-h-2" id="radio-choice-h-2b" value="off"
onClick='gotoFilter("m3_new.php","<?=$eDate?>",<?=$Fl?>,2,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2b">ของกรอบ</label>
        <input type="radio" name="radio-choice-h-2" id="radio-choice-h-2c" value="off"
onClick='gotoFilter("m3_new.php","<?=$eDate?>",<?=$Fl?>,3,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2c">สูตร</label>
		<input type="radio" name="radio-choice-h-2" id="radio-choice-h-2d" value="off"
onClick='gotoFilter("m3_new.php","<?=$eDate?>",<?=$Fl?>,4,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2d">Special</label>
		<input type="radio" name="radio-choice-h-2" id="radio-choice-h-2e" value="off"
onClick='gotoFilter("m3_new.php","<?=$eDate?>",<?=$Fl?>,5,"<?=$xItc?>","<?=$xUrl?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$flag?>");'>
        <label for="radio-choice-h-2e">All</label>
    </fieldset>

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
                     <th width="50px" data-priority="2">ยกมา</th>
					 <th width="50px" data-priority="2">com</th>
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
( SELECT COALESCE(List2.Qty,0) FROM (
SELECT saleorder_detail.Item_Code,

SUM(COALESCE(saleorder_detail.Qty,0)) AS Qty
FROM saleorder
INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
where date(saleorder.DueDate) = DATE('$eDate')
AND saleorder_detail.Item_Code LIKE '%%'
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder.Objective = 1
GROUP BY saleorder_detail.Item_Code) AS List2 where List2.Item_Code = buffer_fac_order.Item_Code ) AS SoQty,

/*(CASE WHEN COALESCE((select count(ID) from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = buffer_fac_order.Item_Code AND (item.IsMain = '1' or item.IsMain = '2') ) ),0) > '0' THEN
CONCAT((select stocksw.Qty from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = buffer_fac_order.Item_Code AND (item.IsMain = '1' or item.IsMain = '2') )),
(CASE WHEN item.IsMain = '1' THEN ' ม้วน'  WHEN item.IsMain = '2' THEN ' แท่ง'  END ) )
ELSE  coalesce((select itemstock.Qty from itemstock where DATE(itemstock.DueDate) = DATE('$eDate') and itemstock.ItemCode = buffer_fac_order.Item_Code ),0)
END ) AS CrQty,*/
 buffer_fac_order.CrQty,
-- buffer_fac_order.StQty,

( SELECT List3.Qty FROM (SELECT
saleorder_detail.Item_Code,
SUM(saleorder_detail.Qty) AS Qty
FROM saleorder
INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
WHERE DATE(saleorder.DueDate) = DATE('$eDate')
AND saleorder.Objective = 1
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder_detail.Item_Code LIKE '%%'
GROUP BY saleorder_detail.Item_Code) AS List3 where List3.Item_Code = buffer_fac_order.Item_Code ) AS FacQty,

( SELECT CONVERT(List3.Qty, UNSIGNED) from
( SELECT whrs.Item_Code,
-- whrs.Qty
SUM(whrs.Qty) AS Qty
FROM wh_stock_receive AS whr
LEFT JOIN wh_stock_receive_sub AS whrs ON whr.DocNo = whrs.DocNo
WHERE whr.Modify_Date BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
AND whr.Branch_Code = 2 AND IsCancel = 0
GROUP BY whrs.Item_Code
) AS List3 where List3.Item_Code = buffer_fac_order.Item_Code ) AS RvQty,

( SELECT List4.Qty FROM (SELECT
item.Item_Code,
SUM(sale_pack_run_detail.Qty) AS Qty
FROM sale_pack_run
INNER JOIN sale_pack_run_detail ON sale_pack_run_detail.DocNo = sale_pack_run.DocNo
INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo
INNER JOIN item ON sale_pack_run_detail.Item_Code = item.Item_Code
INNER JOIN customer ON sale_pack_run.Cus_Code = customer.Cus_Code
where sale_pack_run.DocDate BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
AND SO.Objective = 1
AND sale_pack_run_detail.Item_Code LIKE '%%'
AND sale_pack_run.IsCancel = 0
GROUP BY item.Item_Code
ORDER BY sale_pack_run_detail.chkOrder DESC) AS List4 where List4.Item_Code = buffer_fac_order.Item_Code ) AS SaQty,
roomtype.roomname
FROM buffer_fac_order
LEFT JOIN roomtype ON buffer_fac_order.roomtypeID = roomtype.roomtypeID
WHERE buffer_fac_order.Objective = 1
AND buffer_fac_order.Item_Code LIKE '%%'
AND buffer_fac_order.xDt BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
";

if($IS==1)
  $Sql .= "AND buffer_fac_order.IsForm = 'ชิ้น' ";
else if($IS==2)
  $Sql .= "AND buffer_fac_order.IsForm = 'ของกรอบ' ";
else if($IS==3)
  $Sql .= "AND buffer_fac_order.IsForm = 'สูตร' ";
else if($IS==4)
  $Sql .= "AND buffer_fac_order.IsForm = 'Special' ";

  $Sql .= "GROUP BY buffer_fac_order.Item_Code ";

if($Fl==2)
	$Sql .= "ORDER BY  roomtype.roomname ASC,buffer_fac_order.SalePrice ASC" ;
else
	$Sql .= "ORDER BY  buffer_fac_order.SalePrice ASC";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
            if(((int)$Result["SoQty"]!=0)||((int)$Result["StQty"]!=0)||((int)$Result["FacQty"]!=0)||((int)$Result["RvQty"]!=0)||((int)$Result["SaQty"]!=0)){
			?>
				<tr>
                	<th width="50px"><?=$row?></th>
                    <th width="150px"><?=$Result["roomname"]?></th>
					<th width="80px"><?=$Result["Barcode"]?></th>
					<td width="200px"><?=$Result["NameTH"]?></td>
					<td width="50px"><?=$Result["SalePrice"]?></td>
                    <td width="80px"><?=$Result["IsForm"]?></td>
					<td width="50px" style="cursor: pointer;" onClick='gotoUrl("mCustomer.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$xUrl?>","<?=$flag?>")'><?=(int)$Result["SoQty"]?></td>
                    <td width="50px"><?=(int)$Result["CrQty"]?></td>
					<td width="50px" style="cursor: pointer;" onClick='gotoUrl("Factory.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$xUrl?>","<?=$flag?>")'><?=(int)$Result["FacQty"]?></th>
                    <td  width="50px" style="cursor: pointer;" onClick='gotoUrl("Receive.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$xUrl?>","<?=$flag?>")'><?=(int)$Result["RvQty"]?></th>
					<td width="50px" style="cursor: pointer;" onClick='gotoUrl("Sale.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$xUrl2?>","<?=$CusCode?>","<?=$xUrl?>","<?=$flag?>")'><?=(int)$Result["SaQty"]?></th>
				</tr>
			<?
				$row++;
      }

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
