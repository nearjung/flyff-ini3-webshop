<?php
/*****************************************
**		 	Flyff Earthquake v4.0		**
**			  GetItemInfo.php			**
**		   Created by Treachery.		**
*****************************************/
require_once("inc/functions.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body,td,th {
	font-family: Tahoma;
	font-size:12px;
}
</style>
</head> 

<?php
$itemid = ($_GET['itemid'] != "popular") ? clean(abs($_GET['itemid'])) : "popular";
$giftflag = abs($_GET['sendgiftflag']);

if ($itemid=='popular')
	$detail_type="<font face='Tahoma' size='2'>ไอเท็มขายดี</font>";
else
if ($giftflag==2&&$gift_title)
	$detail_type="<font face='Tahoma' size='2'>ส่งของขวัญ</font>";
else
	$detail_type="<font face='Tahoma' size='2'>รายละเอียดไอเท็ม</font>";

$query = item_query($itemid);
$result = mssql_query($query);

if (!mssql_num_rows($result))
{
	$query = item_query("popular");
	$result = mssql_query($query);
}

$row = mssql_fetch_array($result);

$shop_id	= $row["id"];
$name		= $row["name"];
	$last_char = strlen($name)-1;
$desc		= $row["desc"];
$bundle		= $row["isbundle"];
$count		= $row["itemcount"];
$itemid		= $row["itemid"];
$price		= $row["price"];
$sale		= $row["price_sale"];
$image		= $row["image"];
$categry	= $row["category"];
?>
<div id="item_title"><b class="title"><?php echo $detail_type; ?></b></div>
<div id="item_detail">
	<p class="item_name"><img src="images/bullet.jpg" /><?php echo strtoupper($name); echo (!$bundle&&in_array($categry,$show_count)) ? " (".$count.")" : ""; ?></p>
	<img src="images/items/<?php echo $itemid; echo $image; ?>" class="item_img" />
	<b class="billing_info"><?php
	echo ($sale!=null&&$sale!=0) ? "<span>".$price." <font face='Tahoma' size='1'>".$cash_name_min."</font></span>".$sale." ".$cash_name_min : $price." ".$cash_name_min;
	echo (!$bundle) ? "<br/>Count: ".$count : "";
	?></b>
	<p class="item_descrip" style="clear: both;"><font face='Tahoma' size='2'><?php echo br($desc); ?></font></p>
	<div id="item_bill">
		<div id="friendlist">
			<?php if ($giftflag==2) { ?><select name='friend' id='friend' style='width:100%;'>
			  <option value=""><font face="Tahoma" size="2">เลือกเพื่อน</font></option>
			  <?php echo friends(); ?></select><?php } ?>
		</div>
		<b class="dets">- ราคา <span><?php echo ($sale!=null&&$sale!='0') ? $sale : $price; echo " " .$cash_name_min; ?></span></b>
		<b class="dets">- พ้อยที่เหลือ <span><?php echo $cash_count. " " .$cash_name_min; ?></span></b>
		<div id="buy">
			<img src="images/button_buy.jpg" onclick="OpenConfirm(<?php echo $shop_id ?>, <?php echo ($giftflag==2) ? 2 : 1; ?>)" />
		</div>
	</div>
</div>
</html>