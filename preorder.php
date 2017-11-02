<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("+1 day", $date);
$sDate =  date('Y-m-d', $date);

$date = strtotime($eDate);
$date = strtotime("+7 day", $date);
$lDate =  date('Y-m-d', $date);
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
		function gotoNewUrl(xLink,sDate,lDate) {
			window.open( xLink+"?sDate="+sDate+"&lDate="+lDate );
		}
			function gotoUrl(xLink,sDate,lDate) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				location.href = xLink+"?sDate="+sDate+"&lDate="+lDate;
			}

			function gotoUrlDetail(CusCode,DueDate,Objective,eDate,FName,DocNo) {
				location.href = "preorder_detail.php"+"?xDate="+eDate+"&DueDate="+DueDate+"&xUrl=preorder.php"+"&CusCode="+CusCode+"&Objective="+Objective+"&FName="+FName+"&DocNo="+DocNo;
			}

			function gotoMenu(xLink,DueDate) {
				location.href = xLink+"?xDate="+DueDate;
			}

		</script>
	</head>

	<body>
 	<div data-demo-html="true">
		<div data-role="header">
			<a href="#" onClick='gotoMenu("fai_suborder.php","<?=$eDate?>");' class="ui-btn-left ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-circle-triangle-w ui-icon-carat-l">Back</a>
            		<h1>ออเดอร์ล่วงหน้า</h1>
			<a href="#" onClick='gotoNewUrl("preorder_print.php","<?=$sDate?>","<?=$lDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">

		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='height:35px;display:block;'>
				<tr class="ui-bar-d">
                	 <th width="150px" data-priority="2">วันที่ส่งบิล</th>
					 <th width="170px">ชื่อลูกค้า</th>
					 <th width="100px">Objective</th>
                     <th width="150px">วันส่ง</th>
					 <th width="100px">ยอดเงิน</th>
                     <th width="300px">หมายเหตุ</th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT S.DocDate,
        S.Cus_Code,
        customer.FName,
        CASE S.Objective
        WHEN 1 THEN 'ทั่วไป'
        WHEN 2 THEN 'เบรค'
        WHEN 3 THEN 'น้ำ'
        WHEN 4 THEN 'อาหาร'
        WHEN 7 THEN 'ขนมชิ้น'
        END AS Objective,
        S.DueDate,
				S.Total,
        S.Description,
				S.DocNo
        FROM saleorder as S
        LEFT JOIN saleorder_detail as SD ON S.DocNo = SD.DocNo
        LEFT JOIN customer ON customer.Cus_Code = S.Cus_Code
        WHERE S.IsCancel = 0
        AND S.IsFinish = 1
				AND date(S.DueDate) BETWEEN date('$sDate') AND date('$lDate')
				GROUP BY S.DocNo
				ORDER BY S.DueDate,customer.FName";
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<td width="150px"><?=date(('d/m/'.(date('Y')+543)),strtotime($Result["DocDate"]))?></td>
					<td width="170px"><a style="text-decoration:none; color:#28d93c" href="#" onclick="gotoUrlDetail('<?=$Result["Cus_Code"]?>','<?=$Result["DueDate"]?>','<?=$Result["Objective"]?>','<?=$eDate?>','<?=$Result["FName"]?>','<?=$Result["DocNo"]?>')"><?=$Result["FName"]?></a></td>
					<td width="100px"><?=$Result["Objective"]?></td>
                    <th width="150px"><?=date(('d/m/'.(date('Y')+543)),strtotime($Result["DueDate"]))?></th>
					<td width="100px"><?=$Result["Total"]?></td>
                    <td width="300px"><?=$Result["Description"]?></td>
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
