<?php
	session_start();
	include "config.php";
	include "sqlinjection.php";
	include "function.php";
	$sv_index = $_POST['server_index'];
	$idplayer = $_POST['m_idPlayer'];
	$userid = $_POST['user_id'];
	$md5 = $_POST['md5'];
	$check = $_POST['check'];
	$character = str_pad($idplayer,7,0,STR_PAD_LEFT);
	$connect;
	mssql_select_db("$mssql_acdb") or die("Cannot connect to $mssql_acdb database");
	$pass = md5($md5_hash.cl($_POST['password']));
	$ss = "SELECT * FROM ACCOUNT_TBL WHERE account = '".cl($userid)."' and password = '".cl($check)."'";
	$query = mssql_query($ss);
	$get = mssql_fetch_array($query);
	if(!$get)
	{
		echo "<object width='790' height='444'><param name='movie' value='//www.youtube.com/v/rs2-6hcwFU4?hl=th_TH&amp;version=3'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='//www.youtube.com/v/rs2-6hcwFU4?hl=th_TH&amp;version=3' type='application/x-shockwave-flash' width='790' height='444' allowscriptaccess='always' allowfullscreen='true'></embed></object>";
	}
	else
	{
		//Character
		mssql_select_db("$mssql_chdb") or die("Cannot connect to $mssql_chdb database");
		$charsql = mssql_query("SELECT * FROM CHARACTER_TBL WHERE m_idPlayer = '".$character."'");
		$charfet = mssql_fetch_array($charsql);
		if(!$charfet)
		{
			echo "Cannot find Character";
		}
		else
		{
	//	$_SESSION['charid'] = $charfet['m_idPlayer'];
  //	$_SESSION['account'] = $get['account'];
					$_SESSION['ifs_account'] = $_POST['user_id'];
					$_SESSION['ifs_player'] = $character;
					$_SESSION['ifs_sindex'] = $_POST['server_index'];
					$_SESSION['ifs_passwd'] = $_POST['check'];
		session_write_close();
		echo "<script langquage='javascript'>
window.location='index.php';
</script>";
		}
	}
?>