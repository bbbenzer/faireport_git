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
		function gotoNewUrl(xLink,eDate) {
			window.open( xLink+"?xDate="+eDate );
		}
			function gotoUrl(xLink,sDate,lDate) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				location.href = xLink+"?sDate="+sDate+"&lDate="+lDate;
			}

			function gotoUrlDetail(DueDate,NameTH,eDate) {
				location.href = "precrisp_detail.php"+"?xDate="+eDate+"&DueDate="+DueDate+"&xUrl=precrisp.php"+"&NameTH="+NameTH;
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
            		<h1>รายการของกรอบก่อนเวลากรุ๊ป(ล่วงหน้า)</h1>
			<a href="#" onClick='gotoNewUrl("precrisp_print.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-size: 20">วันส่ง : <b><?=date(('d/m/'.(date('Y')+543)),strtotime($eDate))?></b></div>
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='display:block;'>
				<tr class="ui-bar-d">
           <th  style="vertical-align: middle;" rowspan="2" width="50px" data-priority="2">ลำดับ</th>
					 <th  style="vertical-align: middle;" rowspan="2" width="170px">ชื่อสินค้า</th>
					 <th  style="vertical-align: middle;" rowspan="2" width="50px">ราคา</th>
           <th  style="vertical-align: middle;" rowspan="2" width="150px">stock(real time)</th>
           <th colspan="7" width="350px" style="text-align: center;">วันที่จัดส่ง</th>
				</tr>
        <tr class="ui-bar-d">
           <th width="50px"><?=date('d', strtotime("+1 day", strtotime($eDate)));?></th>
					 <th width="50px"><?=date('d', strtotime("+2 day", strtotime($eDate)));?></th>
					 <th width="50px"><?=date('d', strtotime("+3 day", strtotime($eDate)));?></th>
           <th width="50px"><?=date('d', strtotime("+4 day", strtotime($eDate)));?></th>
           <th width="50px"><?=date('d', strtotime("+5 day", strtotime($eDate)));?></th>
           <th width="50px"><?=date('d', strtotime("+6 day", strtotime($eDate)));?></th>
           <th width="50px"><?=date('d', strtotime("+7 day", strtotime($eDate)));?></th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT item.NameTH,
        item.SalePrice,
        wh_inventory.Qty,
        saleorder.DueDate,
        customer.FName
        FROM saleorder
        INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
        INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
        INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
        INNER JOIN wh_inventory ON saleorder_detail.Item_Code = wh_inventory.Item_Code
        WHERE item.StatusRpt = 2
        AND saleorder.IsCancel = 0
        AND saleorder.IsFinish = 1
        AND saleorder.DueDate BETWEEN '$sDate' AND '$lDate'
				AND wh_inventory.Branch_Code = 2
        GROUP BY item.NameTH
        ORDER BY item.NameTH ASC";
						//echo $Sql;
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<td width="50px"><?=$row?></td>
					<td width="170px"><?=$Result["NameTH"]?></td>
					<td width="50px"><?=$Result["SalePrice"]?></td>
          <td width="150px"><?=$Result["Qty"]?></td>

          <?php
            for ($i=1; $i <=7 ; $i++) {
              $flag = 0;
                switch ($i) {
                  case '1': $datecheck = date('Y-m-d', strtotime("+1 day", strtotime($eDate))); break;
                  case '2': $datecheck = date('Y-m-d', strtotime("+2 day", strtotime($eDate))); break;
                  case '3': $datecheck = date('Y-m-d', strtotime("+3 day", strtotime($eDate))); break;
                  case '4': $datecheck = date('Y-m-d', strtotime("+4 day", strtotime($eDate))); break;
                  case '5': $datecheck = date('Y-m-d', strtotime("+5 day", strtotime($eDate))); break;
                  case '6': $datecheck = date('Y-m-d', strtotime("+6 day", strtotime($eDate))); break;
                  case '7': $datecheck = date('Y-m-d', strtotime("+7 day", strtotime($eDate))); break;
                }
                $subsql = "SELECT item.NameTH,
                item.SalePrice,
                saleorder_detail.Qty,
                saleorder.DueDate,
                customer.FName
                FROM saleorder
                INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
                INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
                INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
                INNER JOIN wh_inventory ON saleorder_detail.Item_Code = wh_inventory.Item_Code
                WHERE item.StatusRpt = 2
                AND saleorder.IsCancel = 0
                AND saleorder.IsFinish = 1
                AND saleorder.DueDate LIKE '$datecheck%'
								AND wh_inventory.Branch_Code = 2
								GROUP BY saleorder.Docno,item.NameTH
								ORDER BY item.NameTH,saleorder.DueDate ASC";
        				$meQuery2 = mysql_query($subsql);
            			while ($Result2 = mysql_fetch_assoc($meQuery2)){
                    if($Result["NameTH"]==$Result2["NameTH"])
                    {
                      $flag += $Result2["Qty"];
                    }
                  }
                if($flag==0)
                {
                  echo '<td width="50px">0</td>';
                }else {
                ?>  <td width="50px"><a style="text-decoration:none; color:#28d93c" href="#" onclick="gotoUrlDetail('<?=$datecheck?>','<?=$Result["NameTH"]?>','<?=$eDate?>')"><?=$flag?></a></td>
                <?}
            }
           ?>
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
