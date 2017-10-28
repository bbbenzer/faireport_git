<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
	$eDate = $_REQUEST["xDate"];
	$date = strtotime($eDate);
	$eDate =  date('Y-m-d', $date);
	$toDate =  date('d/m/Y', $date);
$Copies = 1;

	$Sql = "SELECT
						item.Item_Code,
						item.Barcode,
						item.NameTH,
						item.SalePrice,
						CASE item.IsForm
						WHEN '0' THEN 'สูตร'
						WHEN '1' THEN 'ชิ้น'
						WHEN '2' THEN 'ของกรอบ'
						ELSE 'Special'
						END AS IsForm,
						CONVERT( IFNULL((SELECT Qty
						FROM wh_inventory
						WHERE wh_inventory.Item_Code = item.Item_Code
						AND wh_inventory.Branch_Code = 2
						ORDER BY wh_inventory.Inv_Code DESC LIMIT 1
						),0), DECIMAL(4,0) )  AS  STockQty,
						Sum(saleorder_detail.Qty) AS Qty
						FROM saleorder 
						inner join saleorder_detail on saleorder_detail.DocNo = saleorder.DocNo 
						inner join item on saleorder_detail.Item_Code = item.Item_Code 
						where date(saleorder.DueDate) = DATE('$eDate')
						AND saleorder_detail.Item_Code = item.Item_Code
						AND saleorder.IsCancel = 0
						AND saleorder.Objective = 1 
						AND saleorder.IsNormal = 1 
						AND (item.Barcode LIKE '%8856129000207%'
						OR item.Barcode LIKE '%8856129005967%'
						OR item.Barcode LIKE '%8856129000580%'

						OR item.Barcode LIKE '%8856129000221%'
						OR item.Barcode LIKE '%8856129000238%'
						OR item.Barcode LIKE '%8856129000245%'
						OR item.Barcode LIKE '%8856129005967%'
						OR item.Barcode LIKE '%8856129007039%'
						
						OR item.Barcode LIKE '%8856129003642%'
						OR item.Barcode LIKE '%8856129003130%'
						OR item.Barcode LIKE '%8856129003680%'
						OR item.Barcode LIKE '%8856129000177%'
						OR item.Barcode LIKE '%8856129000184%'
						OR item.Barcode LIKE '%8856129007244%'

						OR item.Barcode LIKE '%8856129003987%'
						OR item.Barcode LIKE '%8856129000016%'
						
						OR item.Barcode LIKE '%8856129004076%'
						OR item.Barcode LIKE '%8856129000030%'
						
						OR item.Barcode LIKE '%8856129002577%'
						OR item.Barcode LIKE '%8856129003642%'
						OR item.Barcode LIKE '%8856129006346%'
						
						OR item.Barcode LIKE '%8856129005110%'
						OR item.Barcode LIKE '%8856129005318%'
						
						OR item.Barcode LIKE '%8856129003482%'
						
						OR item.Barcode LIKE '%8856129004236%'
						
						OR item.Barcode LIKE '%8856129000023%'
						)
						GROUP BY item.Item_Code
						ORDER BY item.SalePrice ASC";
	$meQuery = mysql_query( $Sql );

	$Row = 0;
	while ($Result = mysql_fetch_assoc($meQuery))
	{
		$xBarcode[$Row] = $Result["Barcode"];
		$xItemName[$Row] = $Result["NameTH"];
		$StockQty[$Row] = $Result["STockQty"];
		$xQty[$Row] = $Result["Qty"];
		$xPrice[$Row] = $Result["SalePrice"];
		$xTotal[$Row] = "";
		$xShelfNo[$Row] = "";
		
		$Row++;
	}

	$xPage =$Row;
	$xRowNow =$xPage;
	$xPage = ceil($xPage/20);
	
	$z="-";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="tHeader.css" rel="stylesheet" type="text/css">
    </head>
        <style >
			.xHeader {
	color: #000;
	background-color: #FFF;
	font-family: Tahoma;
	font-size: 18px;
	font-weight: bold;
			}
			.tHeader {
	color: #FFF;
	font-family: Tahoma;
	font-size: small;
	font-weight: bold;
			}
			.tBody{
	font-family: Tahoma;
	font-size: small;
			}
			.tBody_B{
	font-family: Tahoma;
	font-size: small;
	font-weight: bold;
	color: #000;
			}
			.tBody_s{
	font-family: Tahoma;
	font-size: x-small;
	font-weight: normal;
	color: #000;
			}
			.tPrice_B{
	font-family: Tahoma;
	font-size: small;
	font-weight: bold;
			}
			.BD_Color{
   				border: 1px solid black;
			}		
			.TD_LineColor01{
	border-color: black;
	background-image: url(imgs/Line01.png);
	font-family: Tahoma;
	font-size: small;
			}	
			.TD_LineColor02{
	border-color: black;
	background-image: url(imgs/Line02.png);
			}
			.TD_LineColor03{
	border-color: black;
	background-image: url(imgs/Line01.png);
	font-family: Tahoma;
	font-size: 12px;
		</style>

<body onload="window.print();window.close();">

<?php 
// 
for($cp=1;$cp<=$Copies;$cp++){ 
$Row = 0;
	for($i=1;$i<=$xPage;$i++){ 
?>
<table width="500" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td align="center" valign="top">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tr>
             <td align="center"></td>
             <td align="right" valign="top"><span class="tBody">
<span class="xHeader">
<?php 
	if($cp > 1) echo "Copy : $cp : $i / $xPage ";
?>
</span>
             </span></td>
           </tr>

           <tr>
    	    <td colspan="2" align="center"><span class="xHeader">ใบบันทึกสั่งผลิตก่อนกรุ๊ป</span></td>
  	      </tr>
    	  <tr>
    	    <td colspan="2" align="center" class="tBody_s">389/2 หมู่ 5 ต.ยางเนิ้ง อ.สารภี เชียงใหม่ 50140</td>
  	    </tr>
    	  <tr>
    	    <td colspan="2" align="center" class="tBody_s">โทร. 0-5322-2828</td>
  	    </tr>
    	<tr>
    	    <td colspan="2" align="center" class="tBody_s">Email : faibakerylanna@hotmail.com</td>
  	    </tr>
		  <tr>
    	    <td colspan="2" align="center" class="tBody_s"></td>
  	    </tr>
		  <tr>
    	    <td colspan="2" align="center" class="tBody_s"><h2>วันทีส่งลูกค้า  : <?= $toDate ?></h2></td>
  	    </tr>
		
      </table>
    </td>
  </tr>

  <tr>
    <td  height="455px" valign="top">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#333333" class="tHeader">
    <td width="10%" height="24" align="center">ลำดับ</td>
    <td width="20%">บาร์โค้ด</td>
    <td width="30%">รายการ</td>
	<td width="10%">ราคา</td>
	<td width="10%">Order</td>
	<td width="10%">Stock</td>
    <td width="10%" align="center">ผลิต</td>
  </tr>

  <?php
	for($j=$Row;$j<(20*$i);$j++){ 
		if($j < $xRowNow){
  ?> 
  <tr class="TD_LineColor01">
    <td height="19" align="center"><?= ($j+1) ?></td>
    <td height="19">&nbsp;<?= $xBarcode[$j] ?></td>
    <td class="TD_LineColor03" height="19">&nbsp;<?= $xItemName[$j] ?></td>
	<td class="TD_LineColor03" height="19">&nbsp;<?= $xPrice[$j] ?></td>
	<td height="19" align="center">&nbsp;<?= $xQty[$j] ?></td>
    <td height="19" align="center">&nbsp;<?= $StockQty[$j] ?></td>
	<td height="19" align="center">&nbsp;<strong><?= $xQty[$j]-$StockQty[$j] ?></strong></td>
  </tr>
  <?php 
		}
	}
  	$Row=$j;
	if($i == $xPage){
  ?>
  <tr class="tPrice_B">
    <td height="24" colspan="6" align="right">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><span class="tBody_s">วันทีสั่งผลิต  : <?= date("d/m/Y H:i:s") ?></span></td>
            <td align="right"><span class="tBody_B"></span></td>
            <td align="right" class="TD_LineColor02"><?= $xSumTotal ?></td>
          </tr>
	</table>
    </td>
  </tr>
<?php }else{ ?>
  <tr class="tPrice_B">
    <td width="8%" height="24" align="center">&nbsp;</td>
    <td width="21%" height="24">&nbsp;</td>
    <td height="24" colspan="2" align="right">&nbsp;</td>
	<td height="24" colspan="2" align="right">&nbsp;</td>
    <td height="24" colspan="2" align="right">&nbsp;</td>
    </tr>
<?php } ?>
    </table>

    </td>
  </tr>
  <tr>
  
    <td  valign="top" height="95px">
    
       <table width="100%"  border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
	    <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><span class="tBody">( ..................... )</span></td>
            <td align="center"><span class="tBody"></span></td>
            <td align="center"><span class="tBody"></span></td>
	    <td align="center"><span class="tBody">( ...................... )</span></td>
          </tr>
          <tr valign="bottom" class="tBody_B">
            <td align="center" height="30"><span class="tBody">.......................</span></td>
            <td align="center"><span class="tBody"></span></td>
            <td align="center"><span class="tBody"></span></td>
	    <td align="center"><span class="tBody">.....................</span></td>
          </tr>
          <tr>
            <td align="center"><span class="tBody_B">ผู้บันทึกรายการ</span></td>
            <td align="center"><span class="tBody_B"></span></td>
            <td align="center"><span class="tBody_B"></span></td>
            <td align="center"><span class="tBody_B">ผู้ผลิต</span></td>
          </tr>
 
      </table>

    </td>
  </tr>

</table>

<?php } 
	}
?>

</body>
</html>