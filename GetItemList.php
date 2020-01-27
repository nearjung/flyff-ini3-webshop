<?php
/*****************************************
**		 	Flyff Earthquake v4.0		**
**			  GetItemList.php			**
**		   Created by Treachery.		**
*****************************************/
require_once("inc/functions.php");
$categoryid=$_GET['categoryid'];
$firstitem=$_GET['firstitemselect'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head> 
<?php
$ctg=substr($categoryid, 8);
switch($ctg) {
	case "A":
		$category="Special Offers";
		$subcategory="Special Bundles";
	break;
	case "B":
		$category="Special Offers";
		$subcategory="Chance Boxes";
	break;
	case "C":
		$category="Special Offers";
		$subcategory="New Player Packages";
	break;
	case "D":
		$category="Equipment Enhancement";
		$subcategory="Upgrading";
	break;
	case "E":
		$category="Equipment Enhancement";
		$subcategory="Stats & Awakenings";
	break;
	case "F":
		$category="Equipment Enhancement";
		$subcategory="Functional Items";
	break;
	case "G":
		$category="Consumable";
		$subcategory="Buffs & Amps";
	break;
	case "H":
		$category="Consumable";
		$subcategory="Flasks & Potions";
	break;
	case "I":
		$category="Consumable";
		$subcategory="Food";
	break;
	case "J":
		$category="Functional";
		$subcategory="Character";
	break;
	case "K":
		$category="Functional";
		$subcategory="Storage";
	break;
	case "L":
		$category="Functional";
		$subcategory="Guild & Party";
	break;
	case "M":
		$category="Functional";
		$subcategory="Premium EXP Areas";
	break;
	case "N":
		$category="Pets";
		$subcategory="Pick-Up Pets";
	break;
	case "O":
		$category="Pets";
		$subcategory="Buff Pets";
	break;
	case "P":
		$category="Pets";
		$subcategory="Pet Enhancements";
	break;
	case "Q":
		$category="Pets";
		$subcategory="Pet Beads";
	break;
	case "R":
		$category="Flying";
		$subcategory="Flying Items";
	break;
	case "S":
		$category="Apparel";
		$subcategory="Fashion Sets";
	break;
	case "T":
		$category="Apparel";
		$subcategory="Fashion Set Pieces";
	break;
	case "U":
		$category="Apparel";
		$subcategory="Cloaks & Glasses";
	break;
	case "V":
		$category="Apparel";
		$subcategory="Hair";
	break;
	case "W":
		$category="Apparel";
		$subcategory="Furniture";
	break;
	case "X":
		$category="Miscellaneous";
		$subcategory="Misc. Functional Items";
	break;
	case "Y":
		$category="Miscellaneous";
		$subcategory="Misc. Fashion";
	break;
	case "Z":
		if ($enable_search) {
			$category="Search";
			$subcategory='"'.$_GET['search'].'"';
			
			if ($subcategory=="*"||$subcategory=="%"||trim($subcategory)==""||trim(likeClean($subcategory))=="")
				$dead=true;
		}
		else
			$dead=true;
	break;
	default:
		$dead=true;
}
if (!$dead)
{
	?>   
	<div id="item_cat"><b class="title"><?php echo $category; ?> > <?php echo $subcategory; ?></b></div>
	<div id="item_content">
	<?php
		if ($ctg=="Z"&&$enable_search)
		{
			$search = likeClean($_GET['search']);
			$itemArray = tableArray("SELECT TOP {$page_max} * FROM [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL WHERE name LIKE '%{$search}%' AND forsale = 1 ORDER BY id ASC");
			
			$total_query=mssql_query("SELECT id FROM PREMIUM_SHOP_TBL WHERE name LIKE '%{$search}%' AND forsale = 1 ORDER BY id ASC");
		}
		else
		{
			$ctg_hex = clean($ctg);
			$itemArray = tableArray("SELECT TOP {$page_max} * FROM [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL WHERE category = {$ctg_hex} AND forsale = 1 ORDER BY id ASC");
			
			$total_query=mssql_query("SELECT id FROM PREMIUM_SHOP_TBL WHERE category = {$ctg_hex} AND forsale = 1 ORDER BY id ASC");
		}
		
		$total_get=mssql_fetch_array($total_query);
		if ($firstitem=='yes')
		{
			if (mssql_num_rows($total_query)==0)
				$total_get['id']="'popular'";
			?>
			<script type="text/javascript">
			DisplayRightItemAsync(<?php echo $total_get['id']; ?>, 1);
			</script>
			<?php
		}
		
		if (count($itemArray)==0)
		{
			if ($ctg=="Z"&&$enable_search)
				echo "<p style='text-align: center; color: #FF0000; padding-top: 27%; font-weight: bold'>No items were found.<br/>Please refine your search.</p>";
			else
				echo "<p style='text-align: center; color: #FF0000; padding-top: 27%; font-weight: bold'>No items found in this category.</p>";
		}
		
		for($i=$page_limit;$i<count($itemArray);$i++)
		{
			$shop_id	= $itemArray[$i]["id"];
			$name		= $itemArray[$i]["name"];
				$last_char = strlen($name)-1;
			$bundle		= $itemArray[$i]["isbundle"];
			$count		= $itemArray[$i]["itemcount"];
			$itemid		= $itemArray[$i]["itemid"];
			$price		= $itemArray[$i]["price"];
			$sale		= $itemArray[$i]["price_sale"];
			$image		= $itemArray[$i]["image"];
			$categry	= $itemArray[$i]["category"];
			
			?>
			<div class="item">
				<p class="item_name" onclick="javascript:DisplayRightItemAsync(<?php echo $shop_id ?>, 1);"><img src="images/bullet.jpg" /><?php echo $name; if(!$bundle&&in_array($categry,$show_count)) { echo " (".$count.")"; } ?></p>
				<img src="images/items/<?php echo $itemid; echo $image; ?>" class="item_pic" onclick="javascript:DisplayRightItemAsync(<?php echo $shop_id ?>, 1);" />
				<div class="item_descrip">
					<?php if ($sale!=null&&$sale!=0) { echo "<span>".$price." ".$cash_name."</span>".$sale." ".$cash_name; } else { echo $price." ".$cash_name; } ?>
					<?php if (!$bundle) { echo "<br/>Count: ".$count; } ?>
				</div>
				<a href="" onclick="javascript:DisplayRightItemAsync(<?php echo $shop_id ?>, 2);return false;" class="buttons">Send Gift</a>
				<a href="" onclick="javascript:OpenConfirm(<?php echo $shop_id ?>, 1); return false;" class="buttons buy">Buy</a>
			</div>
			<?php
		}
	?>
	</div>
	<?php
	$total_pgs=mssql_num_rows($total_query)/$max_items;
	$total_pages=roundUp($total_pgs);
	if ($total_pages < 1) $total_pages=1;
	
	$function = "goPage('AAAAAAAA".$ctg."',";
	if ($ctg=="Z") $function .= "'{$_GET['search']}',"; else $function .= "'',";
	
	echo '<p id="page_nav">';
	echo page_list($total_pages, $function);
	echo "</p>";
}
else
{
	?>
	<script type="text/javascript">
		DisplayRightItemAsync('popular', 1);
	</script>
	<div id="item_cat"><b class="title">CLICK ITEM CATEGORY ABOVE</b></div>
	<div id="item_content">
		<div class="pagetitle warn"><img src="images/bullet.jpg" />Notice</div>
		<div id="notice">
			<p><?php echo $paragraph['notice']; ?></p>
		</div>
		<div class="pagetitle warn"><img src="images/bullet.jpg" />Warning</div>
		<div id="warning">
			<p><?php echo $paragraph['warning']; ?></p>
			<p class="highlight"><br/>We are not responsible for any problem resulting from not reading the <br/>description of item carefully. "All accounts related to any fraudulent<br/> activities will be banned for good."</p>
		</div>
	</div>
	<?php
}
?>
</html>