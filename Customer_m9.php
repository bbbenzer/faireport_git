<?php
require 'db_connect.php';
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$xItc = $_REQUEST["xItc"];
$xUrl = $_REQUEST["xUrl"];

				$Sql = "SELECT item.Item_Code,item.NameTH,item.SalePrice FROM item WHERE item.Item_Code = '$xItc'" ;
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$NameTH = $Result["NameTH"];
					$SalePrice = $Result["SalePrice"];
				}

				$upeQuery = mysql_query( "UPDATE saleorder_detail SET chkOrder = 0" );

				$Sql = "SELECT saleorder_detail.Id,
CONCAT(customer.FName,' ',customer.LName) AS xName,
saleorder_detail.Qty,
CONVERT( IFNULL((SELECT Sum(sale_pack_run_detail.Qty)
FROM sale_pack_run
inner join sale_pack_run_detail on sale_pack_run_detail.DocNo = sale_pack_run.DocNo
INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo
where sale_pack_run.DocDate BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00' 
AND SO.Objective = 1
AND sale_pack_run_detail.Item_Code = '$xItc'
AND sale_pack_run.IsCancel = 0
AND sale_pack_run.Cus_Code =  customer.Cus_Code
),0), DECIMAL(4,0) )  AS  SaleQty
FROM saleorder
INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
where date(saleorder.DueDate) = DATE('$eDate')
AND saleorder_detail.Item_Code = '$xItc'
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder.Objective = 1";

				$row = 1;
				$Qty = 0;
				$sQty = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$c1 = $Result["Qty"];
					$c2 = $Result["SaleQty"];
					if( $c1 > $c2 ) $upeQuery = mysql_query( "UPDATE saleorder_detail SET chkOrder = 1 WHERE saleorder_detail.Id = '".$Result["Id"]."'" );
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

			function gotoMenu(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate;
			}

			function gotoUrl(CusCode,DueDate) {
				window.location.href = "Order.php?CusCode="+CusCode+"&xDate="+DueDate+"&xUrl=Customer.php";
			}

		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("m9.php","<?=$eDate?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1><?=$eDate?></h1>
			<a href=""#" onClick='gotoMenu("index.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>
    <form action="m0.php">
	<div data-role="content">
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
					 <th>ชื่อลูกค้า</th>
                     <th>บาร์โค้ด</th>
                     <th>รายการ</th>
                     <th>ราคา</th>
					 <th>สั่ง</th>
				</tr>
			</thead>

			<tbody>

			<?
				$Sql = "SELECT
item.Barcode,
item.NameTH,
item.SalePrice,
CONCAT(customer.FName,' ',customer.LName) AS xName,
saleorder_detail.Qty
FROM saleorder
INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
where date(saleorder.DueDate) = DATE('$eDate')
AND saleorder.IsFinish = 3
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder.Objective = 1
AND item.IsForm = 2

ORDER BY customer.Cus_Code ASC,saleorder_detail.chkOrder DESC,item.SalePrice ASC";

				$row = 1;
				$Qty = 0;
				$sQty = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$Qty += $Result["Qty"];
			?>
            	<tr>
					<th><?=$Result["xName"]?></th>
                    <th><?=$Result["Barcode"]?></th>
                    <th><?=$Result["NameTH"]?></th>
                    <th><?=$Result["SalePrice"]?></th>
                    <td><?=$Result["Qty"]?></td>
				</tr>
			<?
				$row++;

				}
			?>

			</tbody>
		</table>
    </form>
</div>

<div data-role="footer">
			<h1>FAI BAKERY CHIANGMAI</h1>
</div>

	</body>
</html>
