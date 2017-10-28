<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$DueDate = $_REQUEST["DueDate"];
$xUrl = $_REQUEST["xUrl"];
$NameTH = $_REQUEST["NameTH"];
$stock = $_REQUEST["stock"];
$totalqty = 0;
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
		function gotoNewUrl(xLink,DueDate,eDate) {
			window.open( xLink+"?xDate="+eDate+"&DueDate="+DueDate );
		}

			function gotoUrl(xLink,DueDate,FN) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				location.href = xLink+"?xDate="+DueDate+"&FN="+FN;
			}

			function gotoUrlDetail(CusCode,DueDate,Objective) {
				location.href = "preorder_detail.php"+"?xDate="+DueDate+"&xUrl=preorder.php"+"&CusCode="+CusCode+"&Objective="+Objective;
			}

			function gotoMenu(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate;
			}

		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("<?=$xUrl?>","<?=$eDate?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1>รายละเอียดเบรคก่อนเวลากรุ๊ป(ล่วงหน้า)</h1>
			<a href="#" onClick="gotoNewUrl('prebreak-detail_print.php','<?=$DueDate?>','<?=$eDate?>');" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">
     <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่จัดส่ง : <b><?=date(('d/m/'.(date('Y')+543)),strtotime($DueDate))?></b>
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="50px" data-priority="2">ลำดับ</th>
					 <th width="250px">ชื่อสินค้า</th>
					 <th width="150px">ราคา</th>
           <th width="100px">Order</th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT item.NameTH,
        item.SalePrice,
        saleorder.DueDate,
        SUM(saleorder_detail.Qty) AS order_qty
        FROM saleorder
        INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
        INNER JOIN item ON item.Item_Code = saleorder_detail.Item_Code
        WHERE saleorder.Objective = 2
        AND saleorder.IsCancel = 0
        AND saleorder.IsFinish = 1
        AND saleorder.DueDate LIKE '$DueDate%'
        GROUP BY item.NameTH
        ORDER BY item.NameTH,saleorder.DueDate ASC";
						//echo $Sql;
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<th width="50px"><?=$row?></th>
					<td width="250px"><?=$Result["NameTH"]?></td>
					<td width="150px"><?=$Result["SalePrice"]?></td>
          <td width="100px"><?=$Result["order_qty"]?></td>
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
  <h1>FAI BAKERY CHIANGMAI</h1>
		</div>
</div>

	</body>
</html>
