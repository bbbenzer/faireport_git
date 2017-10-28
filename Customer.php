<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$xUrl = $_REQUEST["xUrl"];

function getCntSale($cus_code,$Date1,$Date2,$Obj){
	$row = 0;
	$Sql = "SELECT Sum(sale_pack_run_detail.Qty)  
	FROM sale_pack_run  
	inner join sale_pack_run_detail on sale_pack_run_detail.DocNo = sale_pack_run.DocNo 
	INNER JOIN saleorder AS SO ON sale_pack_run.RefDocNo = SO.DocNo
	where sale_pack_run.DocDate BETWEEN '$Date1 17:00:00' AND '$Date2 16:00:00' 
	AND date(SO.DueDate) = date('$Date2')
	AND SO.Objective = $Obj
	AND SO.Objective = 1
	AND SO.IsFinish = 3 
  	AND SO.IsCancel = 0
	AND SO.IsNormal = 1 
	AND sale_pack_run.Cus_Code = '$cus_code'
	AND sale_pack_run.IsCancel = 0  
	GROUP BY sale_pack_run_detail.Item_Code";
	$meQuery = mysql_query( $Sql );
    while ($Result = mysql_fetch_assoc($meQuery)){
		$row++;
	}
	return $row;
}
function getCntOrder($cus_code,$xDate,$Obj){
	$row = 0;
	$Sql1 = "SELECT Sum(saleorder_detail.Qty)
	FROM saleorder
	INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
	WHERE date(saleorder.DueDate) = date('$xDate') 
	AND saleorder.Objective = $Obj
	AND saleorder.Objective = 1
	AND saleorder.IsFinish = 3 
  	AND saleorder.IsCancel = 0
	AND saleorder.IsNormal = 1 
	AND saleorder.Cus_Code = '$cus_code'
	GROUP BY saleorder_detail.Item_Code";
	$result1 = mysql_query( $Sql1 );					
	while ($Row1 = mysql_fetch_array($result1)){
		$row++;
   	}
	return $row;
}
				
$upeQuery = mysql_query( "UPDATE customer SET chkOrder = 0" );
$Sql = "SELECT customer.Cus_Code,
CONCAT(customer.FName,' ',customer.LName) AS xName
FROM saleorder
INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
where date(saleorder.DueDate) = DATE('$eDate')
AND saleorder.IsFinish = 3 
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder.Objective = 1
GROUP BY customer.Cus_Code 
ORDER BY customer.chkOrder DESC";
$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$c1 = getCntOrder($Result["Cus_Code"],$eDate,1);
					$c2 = getCntSale($Result["Cus_Code"],$sDate,$eDate,1);
				if( $c1 > $c2 ) mysql_query("UPDATE customer SET chkOrder = 1 WHERE Cus_Code = '".$Result["Cus_Code"]. "'");
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
			function gotoUrl(CusCode,DueDate) {
				window.location.href = "Order.php?CusCode="+CusCode+"&xDate="+DueDate+"&xUrl=Customer.php";
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
			<a href=""#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>
    <form action="m0.php">
	<div data-role="content">
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
                	 <th>สั่ง</th>
					 <th>ชื่อลูกค้า</th>
                     <th>order</th>
                     <th>get</th>
				</tr>
			</thead>
		 
			<tbody>
			
			<?
$Sql = "SELECT customer.Cus_Code,
CONCAT(customer.FName,' ',customer.LName) AS xName
FROM saleorder
INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
where date(saleorder.DueDate) = DATE('$eDate')
AND saleorder.IsFinish = 3 
AND saleorder.IsCancel = 0
AND saleorder.IsNormal = 1
AND saleorder.Objective = 1
GROUP BY customer.Cus_Code 
ORDER BY customer.chkOrder DESC";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$c1 = getCntOrder($Result["Cus_Code"],$eDate,1);
					$c2 = getCntSale($Result["Cus_Code"],$sDate,$eDate,1);
				if( $c1 > $c2 ){ 
			?>
            		<tr style="cursor: pointer;color:red;" onClick='gotoUrl("<?=$Result["Cus_Code"]?>","<?=$eDate?>")'>
           <?   }else{ ?>     
                	<tr style="cursor: pointer;" onClick='gotoUrl("<?=$Result["Cus_Code"]?>","<?=$eDate?>")'>
           <? } ?>
            		<td><?=$row?></td>
					<th><?=$Result["Cus_Code"]?> : <?=$Result["xName"]?></th>
                    <th><?=getCntOrder($Result["Cus_Code"],$eDate,1)?></th>
                    <th><?=getCntSale($Result["Cus_Code"],$sDate,$eDate,1)?></th>
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


