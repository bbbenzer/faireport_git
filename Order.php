<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
if($_REQUEST["xDate"] == "") header('location:index.php');
$eDate = $_REQUEST["xDate"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$xUrl = $_REQUEST["xUrl"];
$CusCode = $_REQUEST["CusCode"];

  $upeQuery = mysql_query( "UPDATE saleorder_detail SET chkOrder = 0" );

				/*$row = 1;
				$TotalQty1 = 0;
				$TotalQty2 = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){
					$TotalQty1 += $Result["Qty"];
					$TotalQty2 += $Result["SaleQty"];
					$c1 = $Result["Qty"];
					$c2 = $Result["SaleQty"];
					if( $c1 > $c2 ) $upeQuery = mysql_query( "UPDATE saleorder_detail SET chkOrder = 1 WHERE saleorder_detail.Id = '".$Result["Id"]."'" );

				}*/
function redtr($CusCode,$Item_Code,$eDate,$sDate)
{
  $TotalQty1 = 0;
  $TotalQty2 = 0;

  $Sql = "SELECT Sum(saleorder_detail.Qty) AS Sum1
	FROM saleorder
	INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
	WHERE date(saleorder.DueDate) = date('$eDate')
	AND saleorder.Objective = 1
	AND saleorder.IsFinish = 3
  AND saleorder.IsCancel = 0
	AND saleorder.IsNormal = 1
	AND saleorder.Cus_Code = '$CusCode'
	AND saleorder_detail.Item_Code = '$Item_Code'
	GROUP BY saleorder_detail.Item_Code";
  $meQuery = mysql_query( $Sql );
    while ($Result = mysql_fetch_assoc($meQuery)){
    $TotalQty1 = $Result["Sum1"];
  }

  $Sql = "SELECT Sum(sale_pack_run_detail.Qty) AS Sum2
	FROM sale_pack_run
	inner join sale_pack_run_detail on sale_pack_run_detail.DocNo = sale_pack_run.DocNo
	where sale_pack_run.DocDate BETWEEN '$sDate 19:00:00' AND '$eDate 19:00:00'
	AND sale_pack_run.Cus_Code = '$CusCode'
	AND sale_pack_run.IsCancel = 0
	AND sale_pack_run_detail.Item_Code = '$Item_Code'
	GROUP BY sale_pack_run_detail.Item_Code";
  $meQuery = mysql_query( $Sql );
    while ($Result = mysql_fetch_assoc($meQuery)){
    $TotalQty2 = $Result["Sum2"];
  }

  if($TotalQty1 > $TotalQty2)
  {
    return true;
  }else {

    return false;
  }
}

function getOrderqty($CusCode,$Item_Code,$eDate)
{
  $TotalQty1 = 0;
  $Sql = "SELECT Sum(saleorder_detail.Qty) AS Sum1
	FROM saleorder
	INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
	WHERE date(saleorder.DueDate) = date('$eDate')
	AND saleorder.Objective = 1
	AND saleorder.IsFinish = 3
  AND saleorder.IsCancel = 0
	AND saleorder.IsNormal = 1
	AND saleorder.Cus_Code = '$CusCode'
	AND saleorder_detail.Item_Code = '$Item_Code'
	GROUP BY saleorder_detail.Item_Code";
  $meQuery = mysql_query( $Sql );
    while ($Result = mysql_fetch_assoc($meQuery)){
    $TotalQty1 = $Result["Sum1"];
  }
  return $TotalQty1;
}

function getGetqty($CusCode,$Item_Code,$eDate,$sDate)
{
  $TotalQty2 = 0;
  $Sql = "SELECT Sum(sale_pack_run_detail.Qty) AS Sum2
	FROM sale_pack_run
	inner join sale_pack_run_detail on sale_pack_run_detail.DocNo = sale_pack_run.DocNo
	where sale_pack_run.DocDate BETWEEN '$sDate 19:00:00' AND '$eDate 19:00:00'
	AND sale_pack_run.Cus_Code = '$CusCode'
	AND sale_pack_run.IsCancel = 0
	AND sale_pack_run_detail.Item_Code = '$Item_Code'
	GROUP BY sale_pack_run_detail.Item_Code";
  $meQuery = mysql_query( $Sql );
    while ($Result = mysql_fetch_assoc($meQuery)){
    $TotalQty2 = $Result["Sum2"];
  }
  return $TotalQty2;
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
			function gotoUrl(xLink,xItc,DueDate,xCusCode,xUrl2) {
				//alert("mCustomer.php?xItc="+xItc+"&DueDate="+DueDate)
				window.location.href = xLink+"?xItc="+xItc+"&xDate="+DueDate+"&xUrl=Order.php"+"&CusCode="+xCusCode+"&xUrl2="+xUrl2;
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
            		<h1>วันที่รับ : <?=$eDate?></h1>
			<a href="#" onClick='gotoMenu("fai_menu.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-power">Exit</a>
		</div>
	</div>
    <form action="m0.php">
	<div data-role="content">
		<table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
			<thead>
				<tr class="ui-bar-d">
                	 <th data-priority="2">ลำดับ</th>
					 <th data-priority="2">บาร์โค้ด</th>
					 <th>ชื่อสินค้า</th>
					 <th>ราคา</th>
                     <th>order</th>
                     <th>get</th>
				</tr>
			</thead>

			<tbody>

			<?

				$Sql = "SELECT
        item.Item_Code,item.Barcode,item.NameTH,item.SalePrice
        FROM saleorder
        INNER JOIN saleorder_detail ON saleorder.DocNo = saleorder_detail.DocNo
        INNER JOIN item ON saleorder_detail.Item_Code = item.Item_Code
        WHERE Date( saleorder.DueDate ) = '$eDate'
        AND saleorder.Cus_Code = '$CusCode'
        AND saleorder.Objective = 1

        UNION

        SELECT
        item.Item_Code,item.Barcode,item.NameTH,item.SalePrice
        FROM sale_pack_run
        INNER JOIN sale_pack_run_detail ON sale_pack_run.DocNo = sale_pack_run_detail.DocNo
        LEFT JOIN saleorder ON saleorder.DocNo = sale_pack_run.RefDocNo
        INNER JOIN item ON sale_pack_run_detail.Item_Code = item.Item_Code
        WHERE sale_pack_run.Modify_Date BETWEEN '$sDate 19:00:00' AND '$eDate 19:00:00'
        AND sale_pack_run.Cus_Code = '$CusCode'
        AND saleorder.Objective = 1";

        $list = array();
        $count = 0;
				$row = 1;
        $TotalQty1 = 0;
        $TotalQty2 = 0;
				$meQuery = mysql_query( $Sql );
    			while ($Result = mysql_fetch_assoc($meQuery)){

					if( redtr($CusCode,$Result["Item_Code"],$eDate,$sDate) == true )
						{
              $list[$count] = $Result["Item_Code"];
              $count++;
              echo '<tr style="color:red;">';
              ?>
                    		<td><?=$row?></td>
        					<th><?=$Result["Barcode"]?></th>
                            <th style="cursor: pointer;" onClick='gotoUrl("item.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$CusCode?>","<?=$xUrl?>")'><?=$Result["NameTH"]?></th>
                            <th><?=$Result["SalePrice"]?></th>
                            <th><?=getOrderqty($CusCode,$Result["Item_Code"],$eDate)?></th>
                            <th><?=getGetqty($CusCode,$Result["Item_Code"],$eDate,$sDate)?></th>
        				</tr>
        			<?
                $TotalQty1 += getOrderqty($CusCode,$Result["Item_Code"],$eDate);
                $TotalQty2 += getGetqty($CusCode,$Result["Item_Code"],$eDate,$sDate);
        				$row++;
        			}
            }

            $meQuery = mysql_query( $Sql );
        			while ($Result = mysql_fetch_assoc($meQuery)){

                $flag = 0;
                for ($i=0; $i <= ($count-1) ; $i++) {
                  if($Result["Item_Code"]!=$list[$i])
                  {
                    $flag += 1;
                  }
                }
          if(getOrderqty($CusCode,$Result["Item_Code"],$eDate)!=0 || getGetqty($CusCode,$Result["Item_Code"],$eDate,$sDate)!=0)
          {
            if($flag==$count)
            {
        			?>
            <tr>
                		<td><?=$row?></td>
    					<th><?=$Result["Barcode"]?></th>
                        <th style="cursor: pointer;" onClick='gotoUrl("item.php","<?=$Result["Item_Code"]?>","<?=$eDate?>","<?=$CusCode?>","<?=$xUrl?>")'><?=$Result["NameTH"]?></th>
                        <th><?=$Result["SalePrice"]?></th>
                        <th><?=getOrderqty($CusCode,$Result["Item_Code"],$eDate)?></th>
                        <th><?=getGetqty($CusCode,$Result["Item_Code"],$eDate,$sDate)?></th>
    				</tr>
    			<?
            $TotalQty1 += getOrderqty($CusCode,$Result["Item_Code"],$eDate);
            $TotalQty2 += getGetqty($CusCode,$Result["Item_Code"],$eDate,$sDate);
    				$row++;
          }
        }
      }
			?>
            	<tr>
            		<td  colspan="4" >รวมทั้งสิ้น</td>
                    <th><?=number_format($TotalQty1, 0, '.', '');?></th>
                    <th><?=number_format($TotalQty2, 0, '.', '');?></th>
				</tr>
			</tbody>
		</table>
    </form>
</div>

<div data-role="footer">
			<h1>FAI BAKERY CHIANGMAI</h1>
</div>

	</body>
</html>
