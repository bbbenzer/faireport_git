<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$DocNo = $_REQUEST["DocNo"];
$FName = $_REQUEST["FName"];
$DueDate = $_REQUEST["DueDate"];
$xUrl = $_REQUEST["xUrl"];
$CusCode = $_REQUEST["CusCode"];
$Objective = $_REQUEST["Objective"];
$totalqty = 0;
$totalprice = (float) 0;

if($Objective=='ทั่วไป'){
  $obj = 1;
}elseif ($Objective=='เบรค') {
  $obj = 2;
}elseif ($Objective=='น้ำ') {
  $obj = 3;
}elseif ($Objective=='อาหาร') {
  $obj = 4;
}else {
  $obj = 7;
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
		function gotoNewUrl(xLink,CusCode,DueDate,Objective,eDate,FName,DocNo) {
			window.open( xLink+"?xDate="+eDate+"&CusCode="+CusCode+"&Objective="+Objective+"&FName="+FName+"&DueDate="+DueDate+"&DocNo="+DocNo );
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
            		<h1>ออเดอร์ล่วงหน้า</h1>
			<a href="#" onClick="gotoNewUrl('preorder-detail_print.php','<?=$CusCode?>','<?=$DueDate?>','<?=$Objective?>','<?=$eDate?>','<?=$FName?>','<?=$DocNo?>');" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">
     <br>&nbsp;&nbsp;&nbsp;ชื่อลูกค้า : <b><?=$FName?></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันส่ง : <b><?=date(('d/m/'.(date('Y')+543)),strtotime($DueDate))?></b>
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="50px" data-priority="2">ลำดับ</th>
					 <th width="170px">รายการขนม</th>
					 <th width="100px">ราคา</th>
					 <th width="100px">จำนวน</th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT item.NameTH,
        item.SalePrice,
        saleorder_detail.Qty,
				(item.SalePrice*saleorder_detail.Qty),
				saleorder.DocNo,
				saleorder.DueDate
        FROM item
        INNER JOIN saleorder_detail ON saleorder_detail.Item_Code = item.Item_Code
        INNER JOIN saleorder ON saleorder.DocNo = saleorder_detail.DocNo
        WHERE saleorder.Objective = '$obj'
        AND date(saleorder.DueDate) = date('$DueDate')
				AND saleorder.DocNo = '$DocNo'
        AND saleorder.Cus_Code = '$CusCode'
				AND saleorder.IsCancel = 0
        AND saleorder.IsFinish = 1
        ORDER BY item.NameTH ASC";

				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<th width="50px"><?=$row?></th>
					<td width="170px"><?=$Result["NameTH"]?></td>
					<td width="100px"><?=$Result["SalePrice"]?></td>
					<td width="100px"><?=$Result["Qty"]?></td>
				</tr>
			<?
        $totalprice += ($Result["SalePrice"]*$Result["Qty"]);
        $totalqty += $Result["Qty"];
				$row++;

				}
			?>
      <tr>
        <th width="50px"></th>
        <td width="170px"><center><b>รวม</b></center></td>
        <td width="100px"><?=$totalprice?></td>
        <td width="100px"><?=$totalqty?></td>
      </tr>

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
