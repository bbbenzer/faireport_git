<?php
require 'db_connect.php';
require 'class.php';
date_default_timezone_set("Asia/Bangkok");
if(isset($_REQUEST["eDate"])){
  $eDate = $_REQUEST["eDate"];
}else {
  $eDate = $_REQUEST["xDate"];
}

$date = strtotime($eDate);
$date = strtotime("-1 day", $date);
$sDate =  date('Y-m-d', $date);

$lDate =  date('Y-m-d', $date);
$dateobj = new DatetimeTH;
?>

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
			function gotoUrl(xLink) {
        var day = document.getElementById('day').value;
        var month = document.getElementById('month').value;
        var year = document.getElementById('year').value;
        var date = year+'-'+month+'-'+day;
				location.href = xLink+"?eDate="+date;
			}

			function gotoUrlDetail(DueDate,NameTH,eDate,Item_Code) {
				location.href = "precrisp_detail.php"+"?xDate="+eDate+"&DueDate="+DueDate+"&xUrl=precrisp.php"+"&NameTH="+NameTH+"&Item_Code="+Item_Code;
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
            		<h1>ยอดผลิตและบันทึกรับประจำวัน</h1>
		  <a href="#" onClick='gotoNewUrl("Report_ProdnReceive_print.php","<?=$eDate?>");' class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-right ui-icon-grid">Print</a>
		</div>
	</div>

	<div data-role="content">
    <form action="Report_ProdnReceive.php" method="GET">
    <div style="font-size: 20px;">
      <fieldset class="ui-grid-a">
        <div class="ui-block-a">
        <center>
          วันที่<select name="day" id="day">
            <?php for ($i=1; $i <=31 ; $i++) {
              if($i==date('d')){
                $msg = "selected";
              }else{
                $msg = "";
              }
              echo  '<option value="'.$dateobj->getNumber($i).'" '.$msg.'>'.$dateobj->getNumber($i).'</option>';
            } ?>
          </select>
        </center>
        </div>
        <div class="ui-block-b">
          <center>
            เดือน
            <select name="month" id="month">
              <?php for ($i=1; $i <=12 ; $i++) {
								if($dateobj->getNumber($i)==date('m')){
	                $msg = "selected";
	              }else{
	                $msg = "";
	              }
                echo  '<option value="'.$dateobj->getNumber($i).'" '.$msg.'>'.$dateobj->getTHmonth(date('F',strtotime('2018-'.$dateobj->getNumber($i).'-01'))).'</option>';
              } ?>
            </select>
          </center>
        </div>
      </fieldset>
      <center>ปี</center>
      <select name="year" id="year">
        <?php for ($i=date('Y'); $i >=2017 ; $i--) {
          echo  '<option value="'.$dateobj->getNumber($i).'">'.$dateobj->getTHyear($dateobj->getNumber($i)).'</option>';
        } echo $dateobj->getTHyear(date('Y'));?>

      </select>

      <a href="#" onClick='gotoUrl("Report_ProdnReceive.php");' data-role="button">ตกลง</a>
    </div>
  </form>
<br>
  <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: dashed; border-width: 1px;">
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="font-size: 20; float: right;"><b>วันที่เอกสาร : วัน<?=$dateobj->getTHday(date('l', strtotime($eDate)));?>
			 ที่ <?=date('d',strtotime($eDate));?> เดือน <?=$dateobj->getTHmonth(date('F',strtotime($eDate)));?> พ.ศ. <?=$dateobj->getTHyear(date('Y',strtotime($eDate)));?> </b></div>
    <br>
    <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">

    <fieldset class="ui-grid-d" style="margin-left:60px; margin-right:15px;">
    <div class="ui-block-a" style="font-size:18px; font-weight:normal; width:10%">
      <div align="center">
        <b>ลำดับที่</b>
      </div>
    </div>
    <div class="ui-block-b" style="font-size:18px; font-weight:normal; width:24%">
      <div align="center">
        <b>รายการ</b>
      </div>
    </div>
    <div class="ui-block-c" style="font-size:18px; font-weight:normal; width:20%">
      <div align="center">
        <b>ราคา</b>
      </div>
    </div>
    <div class="ui-block-d" style="font-size:18px; font-weight:normal; width:20%">
      <div align="center">
        <b>จำนวนสั่งผลิต</b>
      </div>
    </div>
    <div class="ui-block-e" style="font-size:18px; font-weight:normal; width:20%">
      <div align="center">
        <b>จำนวนบันทึกรับ</b>
      </div>
    </div>
    </fieldset>
    <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">
    <?php
      $i = 1;
      $Detail = "";
      $Sql_main = "SELECT
                    List.Item_Code,item.Barcode,item.NameTH,item.SalePrice,List.QtyFac AS Qty,roomname,item.IsOrder,
                    item_unit.Unit_Name AS unitName,
                    item.isMain,
                    item.roomtypeID,
                    List.DueDate,
                    List.ProdDate
                    ,(select count(ID) from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = List.Item_Code AND (item.ismain = '1' or item.ismain = '2') )) AS bbb

                    ,(CASE WHEN COALESCE((select count(ID) from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = List.Item_Code AND (item.ismain = '1' or item.ismain = '2') ) ),0) > '0' THEN
                     	 CONCAT((select stocksw.Qty from stocksw where DATE(stocksw.Date) = subdate(DATE('$eDate'),1) and stocksw.Item_Code = (select ItemCode7 from item where item.Item_Code = List.Item_Code AND (item.ismain = '1' or item.ismain = '2') )),
                     	(CASE WHEN item.isMain = '1' THEN ' ม้วน'  WHEN item.isMain = '2' THEN ' แท่ง'  END ) )
                    ELSE  coalesce((select itemstock.Qty from itemstock where DATE(itemstock.DueDate) = DATE('$eDate') and itemstock.ItemCode = List.Item_Code ),0)
                    END ) AS StockQty
                    ,
                    ( select List2.OrderQty from
                    	( select coalesce(SUM(SD.Qty),0)  AS OrderQty,SD.Item_Code
                    		from saleorder_detail AS SD LEFT JOIN saleorder on saleorder.DocNo = SD.DocNo
                    		where DATE(saleorder.DueDate) = DATE('$eDate')
                    and saleorder.Objective = '1'
                    and saleorder.IsFinish = '3'
                    		GROUP BY Item_Code
                    ) AS List2 where List2.Item_Code = List.Item_Code ) AS OrderQty
                    ,
                    ( SELECT List3.Qty from
                    ( SELECT whrs.Item_Code,
                    -- whrs.Qty
                    SUM(whrs.Qty) AS Qty
                     FROM wh_stock_receive AS whr
                    LEFT JOIN wh_stock_receive_sub AS whrs ON whr.DocNo = whrs.DocNo
                    WHERE whr.Modify_Date BETWEEN '$sDate 15:00:00' AND '$eDate 15:00:00'
                    AND whr.Branch_Code = 2 AND IsCancel = 0
                    GROUP BY whrs.Item_Code
                    ) AS List3 where List3.Item_Code = List.Item_Code )  AS ReceiveQty

                    from

                    (
                    select facorderdetail.Item_Code,sum(facorderdetail.ItemFormula1) AS QtyFac,
                    DATE_FORMAT(facorder.DueDate ,'%d-%m-%Y') AS DueDate,DATE_FORMAT(Now() ,'%d-%m-%Y') AS ProdDate

                    from facorderdetail
                    LEFT JOIN facorder on facorder.DocNo = facorderdetail.DocNo

                    where DATE(facorder.DueDate) = DATE('$eDate')

                     and facorderdetail.ItemFormula1 > '0'
                    and facorderdetail.Objective = '1'

                    GROUP BY facorderdetail.Item_Code

                    ) AS List

                    LEFT JOIN item on item.Item_Code = List.Item_Code
                    LEFT JOIN item_unit on item_unit.Unit_Code = item.Unit_Code
                     LEFT JOIN roomtype on roomtype.roomtypeID = item.RoomPackID

                    where
                    -- IsPack = '1'
                     item.RoomPackID IN ('19','15','6')
                    and item.Item_Code not in ('0101010057','0101010008','0101010003','0102010297','0101010026','0101010005','0101010018','0101010015')

                    order by item.SalePrice,item.NameTH
                    ";
                  $meQuery = mysql_query($Sql_main);
                  while ($Result = mysql_fetch_assoc($meQuery)) {

                  ?>
                    <fieldset class="ui-grid-d" style="margin-left:60px; margin-right:15px;">
                    <div class="ui-block-a" style="font-size:18px; font-weight:normal; width:10%">
                      <div align="center">
                        <?=$i?>
                      </div>
                    </div>
                    <div class="ui-block-b" style="font-size:18px; font-weight:normal; width:24%">
                      <div align="left">
                        <?=$Result["NameTH"]?>
                      </div>
                    </div>
                    <div class="ui-block-c" style="font-size:18px; font-weight:normal; width:20%">
                      <div align="right">
                        <?=$Result["SalePrice"]?>
                      </div>
                    </div>
                    <div class="ui-block-d" style="font-size:18px; font-weight:normal; width:20%">
                      <div align="right">
                        <?=$Result["Qty"]?>
                      </div>
                    </div>
                    <div class="ui-block-e" style="font-size:18px; font-weight:normal; width:20%">
                      <div align="right">
                        <?=(int)$Result["ReceiveQty"]?>
                      </div>
                    </div>
                    </fieldset>
                    <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px;">

                  <?php
                  $i++;

              }

     ?>

</div>
<div data-role="footer">
  <h1>FAI BAKERY CHIANGMAI</h1>
		</div>
</div>

	</body>
</html>
