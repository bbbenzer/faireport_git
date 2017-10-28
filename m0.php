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
			function gotoUrl(xItc,DueDate) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = "mCustomer.php?xItc="+xItc+"&xDate="+DueDate+"&xUrl=m0.php";
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
            		<h1>รายการ สินค้าที่ผลิต</h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>

	<div data-role="content">

		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
                	 <th data-priority="2">ลำดับ</th>	
					 <th data-priority="2">บาร์โค้ด</th>
					 <th>ชื่อสินค้า</th>
					 <th>ราคา</th>
                     <th data-priority="2">แบบ</th>
					 <th>order</th>
					 <th data-priority="2">com</th>
					 <th data-priority="2" >get</th>
                     <th data-priority="2" >sale</th>
                     <th data-priority="2" >stock</th>
				</tr>
			</thead>
		 
			<tbody>
			
			<?
				$chk = 0;
				$meQuery = mysql_query( "SELECT chk_fac.order_fac FROM chk_fac" );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$chk = $Result["order_fac"];
				}
if($chk==0){
		$upQuery = mysql_query( "UPDATE chk_fac SET order_fac = 1" );
		$upQuery = mysql_query( "DELETE FROM buffer_fac_order WHERE DATE(xDt) = DATE('$eDate')" );
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
						facorderdetail.Objective,
						facorderdetail.roomtypeID,
						CONVERT( IFNULL((SELECT Sum(saleorder_detail.Qty) 
						FROM saleorder 
						inner join saleorder_detail on saleorder_detail.DocNo = saleorder.DocNo 
						where date(saleorder.DueDate) = date(facorder.DueDate) 
						AND saleorder_detail.Item_Code = item.Item_Code
						AND saleorder.IsFinish = 3 
						AND saleorder.IsCancel = 0
						AND saleorder.Objective = facorderdetail.Objective 
						AND saleorder.IsNormal = 1 ),0), 
						DECIMAL(4,0) )  AS  OrderQty,
						facorderdetail.ItemFormula1,
						CONVERT( IFNULL((SELECT
						fdd1.ItemFormula1
						FROM facorder as fd1
						INNER JOIN facorderdetail AS fdd1 ON fd1.DocNo = fdd1.DocNo
						WHERE DATE(fd1.DueDate) = DATE(facorder.DueDate)
						AND fdd1.Item_Code = item.Item_Code
						AND fdd1.Objective = facorderdetail.Objective
						GROUP BY fdd1.Item_Code
						),0), DECIMAL(4,0) )  AS  FacQty,
						CONVERT( IFNULL((
						SELECT itemstock.Qty
						FROM itemstock
						WHERE DATE(itemstock.DueDate) = '2017-09-12'
						AND itemstock.ItemCode = facorderdetail.Item_Code
						),0), DECIMAL(4,0) ) AS Carry,
						CONVERT( IFNULL((SELECT Sum(wh_stock_receive_sub.Qty) 
						FROM wh_stock_receive 
						inner join wh_stock_receive_sub on wh_stock_receive_sub.DocNo = wh_stock_receive.DocNo 
						where wh_stock_receive.Modify_Date BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00'      
						AND wh_stock_receive_sub.Item_Code = item.Item_Code 
						AND wh_stock_receive.Branch_Code = 2 ),0),DECIMAL(4,0) ) AS  ReceiveQty,
						CONVERT( IFNULL((SELECT SUM(wh_stock_transmit_sub.Qty)
						FROM wh_stock_transmit
						INNER JOIN wh_stock_transmit_sub ON wh_stock_transmit.DocNo = wh_stock_transmit_sub.DocNo
						where wh_stock_transmit.Modify_Date BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00' 
						AND wh_stock_transmit_sub.Item_Code = item.Item_Code 
						AND wh_stock_transmit.Branch_Code = 2
						GROUP BY wh_stock_transmit_sub.Item_Code ),0),DECIMAL(4,0) ) AS  TransmitQty,
						CONVERT( IFNULL((SELECT Sum(sale_pack_run_detail.Qty)  
						FROM sale_pack_run  
						inner join sale_pack_run_detail on sale_pack_run_detail.DocNo = sale_pack_run.DocNo 
						INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo
						where sale_pack_run.DocDate BETWEEN '$sDate 17:00:00' AND '$eDate 16:00:00' 
						AND SO.Objective = 1
						AND sale_pack_run_detail.Item_Code = item.Item_Code  
						AND sale_pack_run.IsCancel = 0  
						),0), DECIMAL(4,0) )  AS  SaleQty,
						CONVERT( IFNULL((SELECT Qty
						FROM wh_inventory
						WHERE wh_inventory.Item_Code = item.Item_Code
						AND wh_inventory.Branch_Code = 2
						ORDER BY wh_inventory.Inv_Code DESC LIMIT 1
						),0), DECIMAL(4,0) )  AS  STockQty
						FROM facorder
						INNER JOIN facorderdetail ON facorder.DocNo = facorderdetail.DocNo
						INNER JOIN item ON facorderdetail.Item_Code = item.Item_Code
						INNER JOIN roomtype ON facorderdetail.roomtypeID = roomtype.roomtypeID
						WHERE DATE(facorder.DueDate) = DATE('$eDate')
						AND facorderdetail.IsForm = 0
						AND (facorderdetail.Objective = 1
						OR facorderdetail.Objective = 2
						OR facorderdetail.Objective = 7 )
						AND (item.Barcode LIKE '%%'
						OR item.NameTH LIKE '%%')
						GROUP BY facorderdetail.Item_Code
						ORDER BY  item.SalePrice ASC" ;

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr onClick='gotoUrl("<?=$Result["Item_Code"]?>","<?=$eDate?>")'>
					<th><?=$row?></th>
                    <th><?=$Result["Barcode"]?></th>
					<td><?=$Result["NameTH"]?></td>
					<td><?=$Result["SalePrice"]?></td>
                    <td><?=$Result["IsForm"]?></td>
					<td><?=$Result["OrderQty"]?></td>
					<td><?=$Result["FacQty"]?></th>
                    <td><?=$Result["ReceiveQty"]-$Result["TransmitQty"]?></th>
					<td><?=$Result["SaleQty"]?></th>
                    <td><?=$Result["STockQty"]?></th>
                    
				</tr>
			<?
				$Item_Code  = $Result["Item_Code"];
				$Barcode 	= $Result["Barcode"];
				$NameTH		= $Result["NameTH"];
				$SalePrice	= $Result["SalePrice"];
				$IsForm		= $Result["IsForm"];
				$SoQty		= $Result["OrderQty"];
				$FacQty		= $Result["FacQty"];
				$RvQty		= $Result["ReceiveQty"]-$Result["TransmitQty"];
				$SaQty		= $Result["SaleQty"];
				$StQty		= $Result["STockQty"];
				$Objective	= $Result["Objective"];
				$roomtypeID = $Result["roomtypeID"];
				
				$upSql = "INSERT INTO buffer_fac_order 
				(xDt,roomtypeID,Item_Code,Barcode,NameTH,SalePrice,IsForm,SoQty,
				FacQty,RvQty,SaQty,StQty,Objective)
				VALUES 
				(
				NOW(),$roomtypeID,'$Item_Code','$Barcode','$NameTH',$SalePrice,'$IsForm'
				,$SoQty,$FacQty,$RvQty,$SaQty,$StQty,$Objective
				)";
				//echo $upSql."<br>";
				$upQuery = mysql_query( $upSql );
				$row++;
				}
				
}
$upQuery = mysql_query( "UPDATE chk_fac SET order_fac = 0" );
			?>  
			</tbody>
		</table>
 
</div> 

<div data-role="footer">
			<h1>FAI BAKERY CHIANGMAI</h1>
</div>
	
	</body>
</html>


