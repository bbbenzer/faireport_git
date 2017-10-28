<?php
require 'db_connect.php';
date_default_timezone_set("Asia/Bangkok");
$eDate = $_REQUEST["xDate"];
$St = $_REQUEST["St"];

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$Copies = 1;

	$Sql = "SELECT
						item.Item_Code,
						item.Barcode,
						item.NameTH,
						item.SalePrice,
						wh_inventory.Qty,
						
						CASE item.IsForm
							WHEN '0' THEN 'สูตร'
							WHEN '1' THEN 'ชิ้น'
							WHEN '2' THEN 'ของกรอบ'
							ELSE 'Special'
						END AS IsForm
						
						FROM
						wh_inventory
						INNER JOIN item ON item.Item_Code = wh_inventory.Item_Code
						WHERE wh_inventory.Item_Code = item.Item_Code
						AND wh_inventory.Branch_Code = 2
						AND wh_inventory.Qty > 0
						AND item.IsBakery = 1
						AND item.StatusRpt = $St
						ORDER BY  item.IsForm ASC,item.SalePrice ASC";
	$meQuery = mysql_query( $Sql );

	$Row = 0;
	while ($Result = mysql_fetch_assoc($meQuery))
	{
		$xBarcode[$Row] = $Result["Barcode"];
		$xItemName[$Row] = $Result["NameTH"];
		$xQty[$Row] = $Result["Qty"];
		$xPrice[$Row] = $Result["SalePrice"];
		$xTotal[$Row] = "";
		$xShelfNo[$Row] = "";
		
		$Row++;
	}

	$xPage =$Row;
	$xRowNow =$xPage;
	$xPage = ceil($xPage/35);
	
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
    	    <td colspan="2" align="center"><span class="xHeader">ใบบันทึกส๊อกสินค้า</span></td>
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
      </table>
    </td>
  </tr>

  <tr>
    <td  height="600px" valign="top">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr bgcolor="#333333" class="tHeader">
    <td width="10%" height="24" align="center">ลำดับ</td>
    <td width="20%">บาร์โค้ด</td>
    <td width="40%">รายการ</td>
	<td width="15%">ราคา</td>
    <td width="15%" align="center">จำนวน</td>
  </tr>

  <?php
	for($j=$Row;$j<(35*$i);$j++){ 
		if($j < $xRowNow){
  ?>
  <tr class="TD_LineColor01">
    <td height="19" align="center"><?= ($j+1) ?></td>
    <td height="19">&nbsp;<?= $xBarcode[$j] ?></td>
    <td class="TD_LineColor03" height="19">&nbsp;<?= $xItemName[$j] ?></td>
	<td class="TD_LineColor03" height="19">&nbsp;<?= $xPrice[$j] ?></td>
    <td height="19" align="center">&nbsp;<strong><?= number_format($xQty[$j], 0, '', ',')?>.</strong></td>
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
            <td><span class="tBody_s">วันทีสั่งผลิต่  : <?= date("d/m/Y H:i:s") ?></span></td>
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

</table>

<?php } 
	}
?>

</body>
</html>