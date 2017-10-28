<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$fn = $_REQUEST["FN"];
if($fn==""){
	$eDate = $_REQUEST["xDate"];
	$date = strtotime($eDate);
	$date = strtotime("+1 day", $date);
	$eDate =  date('Y-m-d', $date);
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
				location.href = xLink+"?xDate="+DueDate+"&xUrl=m8.php";
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
    <a href="#" onClick='gotoUrlDetail("Customer_m8.php","<?=$eDate?>");' data-role="button" data-theme="b">แสดงรายละเอียด</a>

		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="50px" data-priority="2">ลำดับ</th>
					 <th width="200px">ชื่อสินค้า</th>
					 <th width="50px">ราคา</th>
                     <th width="50px">order</th>
					 <th width="50px">stock</th>
                     <th width="50px">ผลิต</th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT
						item.Item_Code,
						SUBSTR(item.Barcode,10,4) AS Barcode,
						item.NameTH,
						item.SalePrice,
						CASE item.IsForm
						WHEN '0' THEN 'สูตร'
						WHEN '1' THEN 'ชิ้น'
						WHEN '2' THEN 'ของกรอบ'
						ELSE 'Special'
						END AS IsForm,
						CONVERT( IFNULL((SELECT Qty
						FROM itemstock,item
						WHERE itemstock.ItemCode = item.Item_Code
						AND itemstock.DueDate = '$eDate'
						),0), DECIMAL(4,0) )  AS  STockQty,
						Sum(saleorder_detail.Qty) AS Qty,
						saleorder.CurrentTime
						FROM saleorder
						inner join saleorder_detail on saleorder_detail.DocNo = saleorder.DocNo
						inner join item on saleorder_detail.Item_Code = item.Item_Code
						where date(saleorder.DueDate) = DATE('$eDate')
						AND saleorder_detail.Item_Code = item.Item_Code
						AND saleorder.IsCancel = 0

						AND saleorder.IsNormal = 1
						AND (item.Barcode LIKE '%8856129000207%'
						OR item.Barcode LIKE '%8856129005967%'
						OR item.Barcode LIKE '%8856129000580%'

						OR item.Barcode LIKE '%8856129000221%'
						OR item.Barcode LIKE '%8856129000238%'
						OR item.Barcode LIKE '%8856129000245%'
						OR item.Barcode LIKE '%8856129005967%'
						OR item.Barcode LIKE '%8856129007039%'

						OR item.Barcode LIKE '%8856129003642%'
						OR item.Barcode LIKE '%8856129003130%'
						OR item.Barcode LIKE '%8856129003680%'
						OR item.Barcode LIKE '%8856129000177%'
						OR item.Barcode LIKE '%8856129000184%'
						OR item.Barcode LIKE '%8856129007244%'

						OR item.Barcode LIKE '%8856129003987%'
						OR item.Barcode LIKE '%8856129000016%'

						OR item.Barcode LIKE '%8856129004076%'
						OR item.Barcode LIKE '%8856129000030%'

						OR item.Barcode LIKE '%8856129002577%'
						OR item.Barcode LIKE '%8856129003642%'
						OR item.Barcode LIKE '%8856129006346%'

						OR item.Barcode LIKE '%8856129005110%'
						OR item.Barcode LIKE '%8856129005318%'

						OR item.Barcode LIKE '%8856129003482%'

						OR item.Barcode LIKE '%8856129004236%'

						OR item.Barcode LIKE '%8856129000023%'
						)
						GROUP BY item.Item_Code
						ORDER BY item.SalePrice ASC";
						//echo $Sql;
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<th width="50px"><?=$row?></th>
					<td width="200px"><?=$Result["NameTH"]?></td>
					<td width="50px"><?=$Result["SalePrice"]?></td>
                    <td width="50px"><?=$Result["Qty"]?></td>
					<td width="50px"><?=$Result["STockQty"]?></td>
                    <td width="50px"><?=$Result["Qty"]-$Result["STockQty"]?></td>
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
			<a href="#" onClick='gotoUrl("m8.php","<?=$eDate?>",1);' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-arrow-l">Previous</a>
            		<h1>FAI BAKERY CHIANGMAI</h1>
			<a href="#" onClick='gotoUrl("m8.php","<?=$eDate?>",2);' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-arrow-r">Next</a>
		</div>
</div>

	</body>
</html>
