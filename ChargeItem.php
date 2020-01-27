<?php
/*****************************************
**		 	Flyff Earthquake v4.0		**
**			   ChargeItem.php			**
**		   Created by Treachery.		**
*****************************************/
require_once("inc/functions.php");

$itemid		= clean(abs($_GET['itemid']));
$giftflag	= abs($_GET['sendgiftflag']);
$friendinfo	= $_GET['friendinfo'];

if (strlen($friendinfo) > 0 && strlen($friendinfo) != 7)
{
	echo fail();
	exit;
}

$result=mssql_query("SELECT TOP 1 * FROM PREMIUM_SHOP_TBL WHERE id = {$itemid} AND forsale = 1");
$row=mssql_fetch_array($result);

if($row['price_sale']!=null&&$row['price_sale']!=0)
	$item_price=$row['price_sale'];
else
	$item_price=$row['price'];

if (isset($itemid)&&mssql_num_rows($result))
{
	if ($cash_count >= $item_price)
	{
		if (updateCash($cash_count-$item_price))
		{
			if ($giftflag!=1)
			{
				if ($row['isbundle'])
				{
					$send = giftBundle(
						$row['item1_id'],$row['item1_name'],$row['item1_count'],
						$row['item2_id'],$row['item2_name'],$row['item2_count'],
						$row['item3_id'],$row['item3_name'],$row['item3_count'],
						$row['item4_id'],$row['item4_name'],$row['item4_count'],
						$friendinfo
					);
				}
				else
					$send = giftItem($row['itemid'],$row['name'],$row['itemcount'],$friendinfo);
			}
			else
			{
				if ($row['isbundle'])
				{
					$send = sendBundle(
						$row['item1_id'],$row['item1_name'],$row['item1_count'],
						$row['item2_id'],$row['item2_name'],$row['item2_count'],
						$row['item3_id'],$row['item3_name'],$row['item3_count'],
						$row['item4_id'],$row['item4_name'],$row['item4_count']
					);
				}
				else
					$send = sendItem($row['itemid'],$row['name'],$row['itemcount']);
			}
			if ($send)
			{
				PurchaseCount($itemid);
				
				if ($giftflag!=1)
					echo success2($itemid, $giftflag);
				else
					echo success($itemid, $giftflag);
			}
			else
			{
				updateCash($cash_count);
				echo fail($itemid, $giftflag);
			}
		}
		else
			echo fail($itemid, $giftflag);
	}
	else
		echo fail($itemid, $giftflag);
}
else
	echo fail();
?>
