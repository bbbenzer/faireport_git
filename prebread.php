<?php
require 'db_connect.php';
require 'class.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$date = strtotime($eDate);
$date = strtotime("+1 day", $date);
$sDate =  date('Y-m-d', $date);

$date = strtotime($eDate);
$date = strtotime("+5 day", $date);
$lDate =  date('Y-m-d', $date);

$dateobj = new DatetimeTH;
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

			function gotoUrlDetail(DueDate,NameTH,eDate,Item_Code) {
				location.href = "prebread_detail.php"+"?xDate="+eDate+"&DueDate="+DueDate+"&xUrl=prebread.php"+"&NameTH="+NameTH+"&Item_Code="+Item_Code;
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
            		<h1>รายการขนมปังก่อนเวลากรุ๊ป(ล่วงหน้า)</h1>
			<a href="#" onClick='gotoNewUrl("prebread_print_nextday.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini  ui-btn-icon-right ui-icon-grid">Print ล่วงหน้า 1 วัน</a>
			<a href="#" style="float: right;" onClick='gotoNewUrl("prebread_print.php","<?=$eDate?>");' class="ui-btn ui-btn-b ui-btn-inline ui-mini  ui-btn-icon-right ui-icon-grid">Print ล่วงหน้า 5 วัน</a>
		</div>
	</div>

	<div data-role="content">
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-size: 20"><b>วัน<?=$dateobj->getTHday(date('l', strtotime($eDate)));?>
			 ที่ <?=date('d',strtotime($eDate));?> เดือน <?=$dateobj->getTHmonth(date('F',strtotime($eDate)));?> พ.ศ. <?=$dateobj->getTHyear(date('Y',strtotime($eDate)));?> </b></div>
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead style='display:block;'>
				<tr class="ui-bar-d">
           <th  style="vertical-align: middle;" rowspan="3" width="50px" data-priority="2">ลำดับ</th>
					 <th  style="vertical-align: middle;" rowspan="3" width="170px">ชื่อสินค้า</th>
					 <th  style="vertical-align: middle;" rowspan="3" width="50px">ราคา</th>
           <th  style="vertical-align: middle;" rowspan="3" width="150px">stock(real time)</th>
           <th colspan="5" width="350px" style="text-align: center;">วันที่จัดส่ง</th>
				</tr>
				<tr class="ui-bar-d">
           <th width="70px"><center><?=$dateobj->getTHday(date('l', strtotime("+1 day", strtotime($eDate))));?></center></th>
					 <th width="70px"><center><?=$dateobj->getTHday(date('l', strtotime("+2 day", strtotime($eDate))));?></center></th>
					 <th width="70px"><center><?=$dateobj->getTHday(date('l', strtotime("+3 day", strtotime($eDate))));?></center></th>
           <th width="70px"><center><?=$dateobj->getTHday(date('l', strtotime("+4 day", strtotime($eDate))));?></center></th>
           <th width="70px"><center><?=$dateobj->getTHday(date('l', strtotime("+5 day", strtotime($eDate))));?></center></th>
				</tr>
        <tr class="ui-bar-d">
           <th width="70px"><center><?=date('d', strtotime("+1 day", strtotime($eDate)));?></center></th>
					 <th width="70px"><center><?=date('d', strtotime("+2 day", strtotime($eDate)));?></center></th>
					 <th width="70px"><center><?=date('d', strtotime("+3 day", strtotime($eDate)));?></center></th>
           <th width="70px"><center><?=date('d', strtotime("+4 day", strtotime($eDate)));?></center></th>
           <th width="70px"><center><?=date('d', strtotime("+5 day", strtotime($eDate)));?></center></th>
				</tr>
			</thead>
			<tbody style='height:300px;display:block;overflow:scroll'>

			<?
				$Sql = "SELECT item.Item_Code,
				item.NameTH,
        item.SalePrice,
        wh_inventory.Qty,
        saleorder.DueDate,
        customer.FName
        FROM saleorder
        INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
        INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
        INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
        INNER JOIN wh_inventory ON saleorder_detail.Item_Code = wh_inventory.Item_Code
        WHERE item.StatusRpt = 3
        AND saleorder.IsCancel = 0
        AND date(saleorder.DueDate) BETWEEN date('$sDate') AND date('$lDate')
				AND wh_inventory.Branch_Code = 2
				AND (saleorder.Objective = 7
				OR saleorder.Objective = 1)
				AND (saleorder.IsFinish <> 2 OR saleorder.IsFinish <> 0 )
        GROUP BY item.Item_Code
        ORDER BY item.NameTH,item.SalePrice ASC";
						//echo $Sql;
				$row = 1;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
			?>
				<tr>
					<td width="50px"><?=$row?></td>
					<td width="170px"><?=$Result["NameTH"]?></td>
					<td width="50px"><?=$Result["SalePrice"]?></td>
          <td width="150px"><center><?=(int)$Result["Qty"]?></center></td>

          <?php
            for ($i=1; $i <=5 ; $i++) {
              $flag = 0;
                switch ($i) {
                  case '1': $datecheck = date('Y-m-d', strtotime("+1 day", strtotime($eDate))); break;
                  case '2': $datecheck = date('Y-m-d', strtotime("+2 day", strtotime($eDate))); break;
                  case '3': $datecheck = date('Y-m-d', strtotime("+3 day", strtotime($eDate))); break;
                  case '4': $datecheck = date('Y-m-d', strtotime("+4 day", strtotime($eDate))); break;
                  case '5': $datecheck = date('Y-m-d', strtotime("+5 day", strtotime($eDate))); break;
                }
                $subsql = "SELECT item.Item_Code,
								item.NameTH,
                item.SalePrice,
                saleorder_detail.Qty,
                saleorder.DueDate,
                customer.FName
                FROM saleorder
                INNER JOIN customer ON saleorder.Cus_Code = customer.Cus_Code
                INNER JOIN saleorder_detail ON saleorder_detail.DocNo = saleorder.DocNo
                INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
                INNER JOIN wh_inventory ON saleorder_detail.Item_Code = wh_inventory.Item_Code
                WHERE item.StatusRpt = 3
                AND saleorder.IsCancel = 0
                AND date(saleorder.DueDate) = date('$datecheck%')
								AND wh_inventory.Branch_Code = 2
								AND (saleorder.Objective = 7
								OR saleorder.Objective = 1)
								AND (saleorder.IsFinish <> 2 OR saleorder.IsFinish <> 0 )
								GROUP BY saleorder.DocNo,item.Item_Code
								ORDER BY item.NameTH,saleorder.DueDate ASC";
        				$meQuery2 = mysql_query($subsql);
            			while ($Result2 = mysql_fetch_assoc($meQuery2)){
                    if($Result["Item_Code"]==$Result2["Item_Code"])
                    {
                      $flag += $Result2["Qty"];
                    }
                  }
                if($flag==0)
                {
                  echo '<td width="70px"><center>0</center></td>';
                }else {

                ?>  <td width="70px"><center><a style="text-decoration:none; color:#28d93c" href="#" onclick="gotoUrlDetail('<?=$datecheck?>','<?=$Result["NameTH"]?>','<?=$eDate?>','<?=$Result["Item_Code"]?>')"><?=$flag?></a></center></td>
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
