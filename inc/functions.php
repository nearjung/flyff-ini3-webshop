<?php
/*****************************************
**		 	Flyff Earthquake v4.0		**
**			 inc/functions.php			**
**		   Created by Treachery.		**
*****************************************/
require_once("inc/config.php");

if(stristr($_SERVER['PHP_SELF'], "functions.php"))
	header("Location: ../");

session_start();

if (!$enable_shop)
	die("<p style='text-align: center; color: #FF0000; padding-top: 30%; font-weight: bold'>The shop is currently unavailable.<br/>Please try again later.</p>");
	
$mssql_con = mssql_connect($mssql_server, $mssql_username, $mssql_password); //Attempt connection to MSSQL server using above server location.
if (!$mssql_con)
	die("Cannot connect to MSSQL Server."); //die, stating it could not connect.

if (isset($_POST['user_id']))
{
	session_destroy();
	session_start();
}

$account =	(isset($_SESSION['ifs_account'])) ? strtolower($_SESSION['ifs_account']) : strtolower($_POST['user_id']);
$player =	(isset($_SESSION['ifs_player'])) ? $_SESSION['ifs_player'] : addZeroes($_POST['m_idPlayer']);
$sindex =	(isset($_SESSION['ifs_sindex'])) ? $_SESSION['ifs_sindex'] : "0".$_POST['server_index'];
$passwd = 	(isset($_SESSION['ifs_passwd'])) ? $_SESSION['ifs_passwd'] : $_POST['check'];

$accountcheck = $account ? true : false;

$account2 = $account;
$account = clean($account);

if (!isset($_SESSION['ifs_account']))
{
	if ($accountcheck)
	{
		$account_query=mssql_query("SELECT * FROM [{$mssql_db['account']}].dbo.[ACCOUNT_TBL] WHERE account = {$account}");
		$account_row=mssql_fetch_array($account_query);
		if (mssql_num_rows($account_query))
		{
			if ($account_row['password']==$passwd)
			{
				$character_query=mssql_query("SELECT * FROM [{$mssql_db['character']}].dbo.[CHARACTER_TBL] WHERE m_idPlayer = '{$player}' AND isblock!='D' AND account = {$account}");
				$character_row=mssql_fetch_array($character_query);
				if (mssql_num_rows($character_query))
				{
					$_SESSION['ifs_account'] = $account;
					$_SESSION['ifs_player'] = $player;
					$_SESSION['ifs_sindex'] = $sindex;
					$_SESSION['ifs_passwd'] = $passwd;
				}
				else
					header("Location: login.php?error=4"); //die("Invalid access (4)."); //Character not found or doesn't belong to the owner.
			}
			else
				header("Location: login.php?error=3"); //die("Invalid access (3)."); //Invalid account password hashed.
		}
		else
			header("Location: login.php?error=2"); //die("Invalid access (2)."); //Account does not exist.
	}
	else
		header("Location: login.php?error=1"); //die("Invalid access (1)."); //Account not entered.
}

$cash_query=mssql_query("SELECT * FROM [{$mssql_db['account']}].[dbo].[ACCOUNT_TBL] WHERE account = {$account}");
$cash_row_array=mssql_fetch_array($cash_query);
$cash_count=$cash_row_array[$cash_row];

mssql_select_db($mssql_db['character']);

$page = (isset($_GET['page'])) ? clean(abs($_GET['page'])) : 1;
	if ($page < 1) $page = 1;
$page_max = $page*$max_items;
$page_limit = $page_max-$max_items;

function clean($var)
{
	if (is_int($var))
	{
		$var = $var;
	}
	else
	if (is_array($var))
	{
		foreach($var as $key => $value)
		{
			$var[$key] = clean($value);
		}
	}
	else
	{
		$unpacked = unpack('H*hex',$var);
		$hex = '0x'.$unpacked['hex'];
		$var = $hex;
	}
	
	return $var;
}

function addZeroes($num)
{
	$max_len=7;
	$cur_len=strlen($num);
	
	while ($cur_len < $max_len)
	{
		$num="0".$num;
		$cur_len++;
	}
	
	return $num;
}

function item_query($itemid)
{
	global $popular;
	switch($itemid)
	{
		case "popular":
			if ($popular==0)
				$item_query="SELECT TOP 1 * FROM PREMIUM_SHOP_TBL WHERE forsale = 1 ORDER BY purchases DESC";
			else
				$item_query="SELECT TOP 1 * FROM PREMIUM_SHOP_TBL WHERE id= {$popular} AND forsale = 1";
		break;
		
		default:
			$item_query="SELECT TOP 1 * FROM PREMIUM_SHOP_TBL WHERE id = {$itemid} AND forsale = 1";
			$item_result=mssql_query($item_query);
			if(!mssql_num_rows($item_result))
				$item_query="SELECT TOP 1 * FROM PREMIUM_SHOP_TBL WHERE forsale = 1 ORDER BY purchases DESC";
	}
	
	return $item_query;
}

function roundUp( $value, $precision=0 )
{
    if ( $precision == 0 ) {
        $precisionFactor = 1;
    }
    else {
        $precisionFactor = pow( 10, $precision );
    }
    return ceil( $value * $precisionFactor )/$precisionFactor;
} 

function br($text)
{
	$text=str_replace("\\r\\n","<br />",$text);
	$text=str_replace("\n","<br />",$text);
	return $text;
}

function friends()
{
	global $player, $sindex;
	$stmt = mssql_init('shopMessengerList');

	mssql_bind($stmt, '@pserverindex',	$sindex,	SQLCHAR);
	mssql_bind($stmt, '@pPlayerID',		$player,	SQLCHAR);

	$result = mssql_execute($stmt);
	
	while ($row = mssql_fetch_assoc($result)) {
		$return.= '<option value="'.$row['idFriend'].'">'.$row['m_szName'].'</option>';
	}

	mssql_free_statement($stmt);
	
	return $return;
}

function updateCash($new_cash)
{
	global $account, $cash_row, $mssql_db;
	$new_cash = abs($new_cash);
	
	$query = "UPDATE [{$mssql_db['account']}].[dbo].[ACCOUNT_TBL] SET [{$cash_row}] = {$new_cash} WHERE account = {$account}";
	$result=mssql_query($query);
	
	if ($result)
		return true;
	else
		return false;
}

function giftBundle($item1_id, $item1_name, $item1_count, $item2_id, $item2_name, $item2_count, $item3_id, $item3_name, $item3_count, $item4_id, $item4_name, $item4_count, $player)
{
	global $sindex;
	
	if ($item1_id&&$item1_name&&$item1_count)
		$return = giftItem($item1_id, $item1_name, $item1_count, $player);
	
	if ($item2_id&&$item2_name&&$item2_count&&$return)
		$return = giftItem($item2_id, $item2_name, $item2_count, $player);
	
	if ($item3_id&&$item3_name&&$item3_count&&$return)
		$return = giftItem($item3_id, $item3_name, $item3_count, $player);
	
	if ($item4_id&&$item4_name&&$item4_count&&$return)
		$return = giftItem($item4_id, $item4_name, $item4_count, $player);
	
	if (!isset($return))
		$return = 0;
	
	return $return;
}

function giftItem($itemid, $itemname, $itemcount, $player_to)
{
    global $sindex, $player, $mssql_db;
        $user_online=mssql_query("SELECT [MultiServer] FROM [CHARACTER_01_DBF].[dbo].[CHARACTER_TBL] WHERE [m_idPlayer] = {$player_to}");
        $ison=mssql_fetch_array($user_online);
        if( $ison['MultiServer'] != 0 ){
            $Server_IP = '127.0.0.1';
            $m_idPlayer = (INT)$player_to;
            $ItemIDa = $itemid;
            $ItemCnt = $itemcount;
    
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $packet = pack("VVVVV", 01, $m_idPlayer, 0, $ItemIDa, $ItemCnt) . str_pad("8b8d0c753894b018ce454b2e", 21, ' ') . pack("V", 1);

            if(socket_connect($socket, $Server_IP, 29000))
                socket_write($socket, $packet, strlen($packet));
            socket_close($socket);
            $return = 1;
        }else{
            mssql_select_db($mssql_db['character']);
            $stmt = mssql_init('shopSendItem');

            mssql_bind($stmt, '@m_idPlayer',    $player_to,    SQLCHAR);
            mssql_bind($stmt, '@serverindex',    $sindex,    SQLCHAR);
            mssql_bind($stmt, '@item_name',        $itemname,    SQLTEXT);
            mssql_bind($stmt, '@item_count',    $itemcount,    SQLINT1);
            mssql_bind($stmt, '@item_id',        $itemid,    SQLINT1);
            mssql_bind($stmt, '@m_idSender',    $player,    SQLCHAR);
    
            $return = mssql_execute($stmt);

            mssql_free_statement($stmt);
        }
    return $return;
}  

function sendBundle($item1_id, $item1_name, $item1_count, $item2_id, $item2_name, $item2_count, $item3_id, $item3_name, $item3_count, $item4_id, $item4_name, $item4_count) {
	global $player, $sindex;
	mssql_select_db($mssql_db['character']);
	
	if ($item1_id&&$item1_name&&$item1_count)
		$return = sendItem($item1_id, $item1_name, $item1_count);
	
	if ($item2_id&&$item2_name&&$item2_count&&$return)
		$return = sendItem($item2_id, $item2_name, $item2_count);
	
	if ($item3_id&&$item3_name&&$item3_count&&$return)
		$return = sendItem($item3_id, $item3_name, $item3_count);
	
	if ($item4_id&&$item4_name&&$item4_count&&$return)
		$return = sendItem($item4_id, $item4_name, $item4_count);
	
	if (!isset($return))
		$return = 0;
	
	return $return;
}

function sendItem($itemid, $itemname, $itemcount)
{
    global $player, $sindex, $mssql_db;    
        $user_online=mssql_query("SELECT [MultiServer] FROM [CHARACTER_01_DBF].[dbo].[CHARACTER_TBL] WHERE [m_idPlayer] = {$player}");
        $ison=mssql_fetch_array($user_online);
        if( $ison['MultiServer'] != 0 ){
            $Server_IP = '127.0.0.1';
            $m_idPlayer = (INT)$player;
            $ItemIDa = $itemid;
            $ItemCnt = $itemcount;
    
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $packet = pack("VVVVV", 01, $m_idPlayer, 0, $ItemIDa, $ItemCnt) . str_pad("8b8d0c753894b018ce454b2e", 21, ' ') . pack("V", 1);

            if(socket_connect($socket, $Server_IP, 29000))
                socket_write($socket, $packet, strlen($packet));
            socket_close($socket);
            $return = 1;
        }else{
            mssql_select_db($mssql_db['character']);
            $stmt = mssql_init('shopSendItem');

            mssql_bind($stmt, '@m_idPlayer',    $player,    SQLCHAR);
            mssql_bind($stmt, '@serverindex',    $sindex,    SQLCHAR);
            mssql_bind($stmt, '@item_name',        $itemname,    SQLTEXT);
            mssql_bind($stmt, '@item_count',    $itemcount,    SQLINT1);
            mssql_bind($stmt, '@item_id',        $itemid,    SQLINT1);
        
        $return = mssql_execute($stmt);

        mssql_free_statement($stmt);
        }
    return $return;
}  

function PurchaseCount($itemid)
{
	global $mssql_db;
	if (is_int($itemid))
		return mssql_query("UPDATE [{$mssql_db['character']}].dbo.[PREMIUM_SHOP_TBL] SET purchases = purchases + 1 WHERE id = {$itemid}");
}

function getLastID()
{
	global $mssql_db;
	$result = mssql_query("SELECT id FROM [{$mssql_db['character']}].dbo.[PREMIUM_SHOP_TBL] ORDER BY id DESC");
	$row = mssql_fetch_array($result);
	return $row['id'];
}

function tableArray($query)
{
	$array = array();
	$result = mssql_query($query);
	
	for($i=0;$i<mssql_num_rows($result);$i++)
	{
		$mini_array = array();
		for($n=0;$n<mssql_num_fields($result);$n++)
		{
			$field = mssql_field_name($result, $n);
			$mini_array[$field] = mssql_result($result, $i, $field);
		}
		
		$array[$i] = $mini_array;
	}
	
	return $array;
}
	
function page_list($totalPages, $function)
{
	global $max_list, $page;
	
	$partial = ceil($max_list/2);

	$cP = 1;
	if ($page > $partial)
	{
		$cP = $page - $partial + 1;
	}

	if ($cP+$max_list > $totalPages+1)
	{
		while($cP+$max_list > $totalPages+1)
		{
			$cP--;
		}
	}
	
	if ($cP < 1)
		$cP = 1;
	
	$prev = ($page - 1 > 0) ? $page - 1 : 1;
	$next = ($page + 1 > $totalPages) ? $totalPages : $page + 1;

	echo "<a href=\"javascript:{$function}1);\">[First]</a> ";
	echo "<a href=\"javascript:{$function}{$prev});\">[Prev]</a> ";
	$i2=0;
	for($i=$cP;($i2<$max_list)&&($i<=$totalPages);$i++)
	{
		echo ($i==$page) ? "<span>" : "<a href=\"javascript:{$function}{$i});\">[";
			echo $i;
		echo ($i==$page) ? "</span>" : "]</a>";
		
		echo " ";
		$i2++;
	}
	echo "<a href=\"javascript:{$function}{$next});\">[Next]</a> ";
	echo "<a href=\"javascript:{$function}{$totalPages});\">[Last]</a>";
}

function likeClean($str)
{                                          
	return preg_replace('/(?!\s)(\W)/', '', $str); 
}

function fail($itemid = "popular", $giftflag = 1)
{
	return '<script type="text/javascript">
$(document).ready(function() {
	PurchaseFail('.$itemid.','.$giftflag.');
});
</script>';
}

function success($itemid, $giftflag = 1)
{
	return '<script type="text/javascript">
$(document).ready(function() {
	PurchaseSuccess('.$itemid.','.$giftflag.');
});
</script>';
}

function success2($itemid, $giftflag = 1)
{
	return '<script type="text/javascript">
$(document).ready(function() {
	PurchaseSuccess2('.$itemid.','.$giftflag.');
});
</script>';
}
?>