<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

?>
<!--
 *  -- ************************************************************
 -- Author		:	PARADOX
 -- Create date	:	25-07-2017
 -- Update date	:	PARADOX
 -- Update By	:   27-07-2017
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
			function gotoUrl(xItc,DueDate) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = "mCustomer.php?xItc="+xItc+"&xDate="+DueDate+"&xUrl=m4.php";
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
            		<h1></h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>

	<div data-role="content">
    <h3>รายการ ขนมเค้ก ที่ไม่ผลิต</h3>
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
					<!--
					 <th data-priority="2" data-hiddendefault="false">ลำดับ</th>
					 -->
					 <th width="80px">ลำดับ</th>
					 <th width="200px">ชื่อสินค้า</th>
					 <th width="80px" data-priority="2">ราคา</th>
                     <th width="80px">order</th>
					 <th width="80px" data-priority="2">com</th>
					 <th width="80px" data-priority="2" >get</th>
				</tr>
			</thead>

			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$SQL_ = "SELECT FacOrderDetail.RowID, ".
							"item.Item_Code,item.Barcode, ".
							"item.NameTH, item.SalePrice, ".
							"item_unit.Unit_Name, ".
							"CONVERT( IFNULL((  ".
							"SELECT Sum(saleorder_detail.Qty)  ".
							"FROM saleorder ".
							"inner join saleorder_detail on saleorder_detail.DocNo = saleorder.DocNo ".
							"where date(saleorder.DueDate) = date( FacOrder.DueDate )  ".
							"AND saleorder_detail.Item_Code = item.Item_Code ".
							"AND saleorder.IsFinish = 3  ".
							"AND saleorder.Objective = 1  ".
							"AND saleorder.IsNormal = 1 ),0), ".
							"DECIMAL(4,0) )  AS  OrderQty, ".
							"CONVERT( IFNULL((  ".
							"SELECT Sum(FAD.ItemFormula1)  ".
							"FROM FacOrder as FA  ".
							"INNER JOIN FacOrderDetail AS FAD ON FA.DocNo = FAD.DocNo  ".
							"WHERE FA.DueDate = date( FacOrder.DueDate ) ".
							"AND FAD.Objective = 1  ".
							"AND FAD.Item_Code = item.Item_Code  ".
							"AND FAD.roomtypeID = FacOrderDetail.roomtypeID  ".
							"GROUP BY item.Item_Code ),0), ".
							"DECIMAL(4,0) ) AS FacQty, ".
							"CONVERT( IFNULL((  ".
							"SELECT Sum(wh_stock_receive_sub.Qty) ".
							"FROM wh_stock_receive ".
							"inner join wh_stock_receive_sub on wh_stock_receive_sub.DocNo = wh_stock_receive.DocNo  ".
							"where wh_stock_receive.Modify_Date BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00'   ".
							"AND wh_stock_receive_sub.Item_Code = item.Item_Code AND wh_stock_receive.Branch_Code = 2 ),0),  ".
							"DECIMAL(4,0) ) AS  ReceiveQty, ".
							"CONVERT( IFNULL((  ".
							"SELECT Sum(sale_pack_run_detail.Qty)  ".
							"FROM sale_pack_run   ".
							"inner join sale_pack_run_detail on sale_pack_run_detail.DocNo = sale_pack_run.DocNo  ".
							"INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo ".
							"where sale_pack_run.Modify_Date BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00'  ".
							"AND SO.Objective = 1 ".
							"AND sale_pack_run_detail.Item_Code = item.Item_Code ".
							"AND sale_pack_run.IsCancel = 0 ".
							"),0), DECIMAL(4,0) )  AS  SaleQty ".
							"FROM FacOrder INNER JOIN FacOrderDetail ON FacOrder.DocNo = FacOrderDetail.DocNo  ".
							"INNER JOIN item ON FacOrderDetail.Item_Code = item.Item_Code ".
							"INNER JOIN item_unit ON FacOrderDetail.Unit_Code = item_unit.Unit_Code ".
							"WHERE FacOrder.DueDate = date( '$eDate' ) ".
							"AND	( item.NameTH LIKE '%%' OR	item.Barcode LIKE '%%' )  ".
							"AND FacOrderDetail.Objective = 1  ".
							"AND FacOrderDetail.roomtypeID = 15 ".
							"AND CONVERT( IFNULL((  ".
							"SELECT Sum(wh_stock_receive_sub.Qty) ".
							"FROM wh_stock_receive  ".
							"inner join wh_stock_receive_sub on wh_stock_receive_sub.DocNo = wh_stock_receive.DocNo ".
							"where wh_stock_receive.Modify_Date BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00' ".
							"AND wh_stock_receive_sub.Item_Code = item.Item_Code AND wh_stock_receive.Branch_Code = 2 ),0), ".
							"DECIMAL(4,0) ) = 0 ".
							"ORDER BY item.SalePrice ASC,item.NameTH ASC " ;

				$query_ = mysql_query($SQL_) or die (mysql_error());

				$row = 1;

				while($result_ = mysql_fetch_array($query_)){
			?>
				<tr onClick='gotoUrl("<?=$Result["Item_Code"]?>","<?=$eDate?>")'>
					<th width="80px"><?=$row?></th>
					<td width="200px"><?=$result_["NameTH"];?></td>
					<td width="80px"><?=$result_["SalePrice"];?></td>
					<td width="80px"><?=$result_["OrderQty"];?></td>
					<td width="80px"><?=$result_["FacQty"];?></th>
					<td width="80px"><?=$result_["ReceiveQty"];?></th>

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
