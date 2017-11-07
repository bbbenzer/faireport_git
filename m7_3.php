<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$xUrl = $_REQUEST["xUrl"];
$xUrl2 = $_REQUEST["xUrl2"];
$flag = $_REQUEST["flag"];

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
			function gotoNewUrl(xLink,DueDate,St) {
				window.open( xLink+"?xDate="+DueDate+"&St="+St, '_blank' );
			}

			function gotoUrl(xLink,xItc,DueDate,flag) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = xLink+"?xItc="+xItc+"&xDate="+DueDate+"&xUrl=m7_3.php"+"&flag="+flag;
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
            		<h1>รายการ สต๊อกขนมปัง,ของสด</h1>
			<a href="#" onClick='gotoNewUrl("StockToDay.php","<?=$eDate?>",3);' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th  width="50px" data-priority="2">ลำดับ</th>
					 <th  width="200px">ชื่อสินค้า</th>
					 <th  width="50px">ราคา</th>
                     <th  width="50px">stock</th>
				</tr>
			</thead>

			<tbody  style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT
						item.Item_Code,
						item.Barcode,
						item.NameTH,
						item.SalePrice,
						wh_inventory.Qty,

						CASE item.IsForm
							WHEN '0' THEN 'สูตร'
							WHEN '1' THEN 'ชิ้น'
							WHEN '2' THEN 'ของกรอบ'
							ELSE 'Special'
						END AS IsForm

						FROM
						wh_inventory
						INNER JOIN item ON item.Item_Code = wh_inventory.Item_Code
						WHERE wh_inventory.Item_Code = item.Item_Code
						AND wh_inventory.Branch_Code = 2
						AND wh_inventory.Qty > 0
						AND item.IsBakery = 1
						AND item.StatusRpt = 3
						ORDER BY  item.IsForm ASC,item.SalePrice ASC" ;

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<td  width="50px"><?=$row?></td>
					<td  width="200px" style="cursor: pointer;" onClick='gotoUrl("Receive.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$flag?>")'><?=$Result["NameTH"]?></td>
					<td  width="50px"><?=$Result["SalePrice"]?></td>
					<td  width="50px"><?=number_format($Result["Qty"], 0, '.', '');?></td>
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
