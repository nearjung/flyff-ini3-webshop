<?php

$enable_shop	= true; //Whether the shop is open or not. default: true

$mssql_server	= "DEXSERVER-63153\SQLEXPRESS"; //MSSQL server and instance; ex: (COMPUTERNAME\SQLEXPRESS); default ".\SQLEXPRESS"
$mssql_username	= "sa"; //MSSQL username; default "sa"
$mssql_password	= "0877840762As"; //MSSQL password; default ""
$mssql_db['account'] = "ACCOUNT_DBF"; //Account database; default "ACCOUNT_DBF"
$mssql_db['character'] = "CHARACTER_01_DBF"; //Character database; default "CHARACTER_01_DBF"

$shopurl = "index.php"; //Base shop URL; default "./index.php"
$shop_title = "ª×èÍªçÍ»"; //Title of the shop (when shown in a browser); default "FlyffEarthquake v4"
$new_window = true; //If set to true, upon logging in, the main shop will open in a popup window.

$editor_list = array("squidjung"); //Accounts allowed to edit the shop. There is no limit. "test","test2","test3"; default "test","test2"
$enable_edit = true; //Whether the shop is available for editing (mainly for demo site use); default true
$enable_search = true; //Whether the shop will allow searching or not; default true

$client_salt = "baruchenikutakabekamarvisdeveloper"; //ÍÑ¹¹Õéà»ç¹ MD5 Hash ¶éÒäÁèÁÕ¡çäÁèµéÍ§ãÊè
$cash_row = "cash"; //row that stores cash points (in ACCOUNT_TBL); default "cash"
$cash_name = "คุ๊กกี้"; //Name of cash currency; default "dPoints"
$cash_name_min = "คุ๊กกี้"; //Shortened version of cash currency; default "dP"

$popular = 0; //Popular item. Set to the list ID of an item, 0 to show the most purchased item; default 0

$paragraph['notice'] = '<b>&#3585;&#3619;&#3640;&#3603;&#3634;&#3629;&#3618;&#3656;&#3634;&#3649;&#3592;&#3657;&#3591;&#3586;&#3657;&#3629;&#3617;&#3641;&#3621;&#3585;&#3634;&#3619;&#3648;&#3605;&#3636;&#3617;&#3648;&#3591;&#3636;&#3609;&#3651;&#3627;&#3657;&#3649;&#3585;&#3656;&#3612;&#3641;&#3657;&#3629;&#3639;&#3656;&#3609;&#3650;&#3604;&#3618;&#3648;&#3604;&#3655;&#3604;&#3586;&#3634;&#3604; &#3648;&#3619;&#3634;&#3592;&#3632;&#3652;&#3617;&#3656;&#3619;&#3633;&#3610;&#3612;&#3636;&#3604;&#3594;&#3629;&#3610;&#3649;&#3621;&#3632;&#3652;&#3617;&#3656;&#3626;&#3634;&#3617;&#3634;&#3619;&#3606;&#3651;&#3627;&#3657;&#3588;&#3623;&#3634;&#3617;&#3594;&#3656;&#3623;&#3618;&#3648;&#3627;&#3621;&#3639;&#3629;&#3651;&#3604;&#3654; &#3652;&#3604;&#3657;</b>'; //ãÊè»ÃÐ¡ÒÈ
$paragraph['warning'] = '&#3626;&#3634;&#3617;&#3634;&#3619;&#3606;&#3595;&#3639;&#3657;&#3629;&#3652;&#3629;&#3648;&#3607;&#3617;&#3651;&#3609;&#3619;&#3657;&#3634;&#3609; flyff &#3652;&#3604;&#3657;&#3650;&#3604;&#3618;&#3651;&#3594;&#3657;&#3588;&#3640;&#3585;&#3585;&#3637;&#3657;<br>
&#3626;&#3634;&#3617;&#3634;&#3619;&#3606;&#3648;&#3605;&#3636;&#3617;&#3648;&#3591;&#3636;&#3609;&#3652;&#3604;&#3657;&#3605;&#3634;&#3617;&#3623;&#3636;&#3608;&#3637;&#3607;&#3637;&#3656;&#3648;&#3621;&#3639;&#3629;&#3585;<br>
&#3652;&#3629;&#3648;&#3607;&#3617;&#3607;&#3637;&#3656;&#3595;&#3639;&#3657;&#3629;&#3651;&#3609; Flyff plaza &#3652;&#3617;&#3656;&#3626;&#3634;&#3617;&#3634;&#3619;&#3606;&#3648;&#3611;&#3621;&#3637;&#3656;&#3618;&#3609;&#3627;&#3619;&#3639;&#3629;&#3649;&#3621;&#3585;&#3588;&#3639;&#3609;&#3652;&#3604;&#3657;<br>
&#3627;&#3634;&#3585;&#3617;&#3637;&#3588;&#3635;&#3606;&#3634;&#3617;&#3627;&#3619;&#3639;&#3629;&#3586;&#3657;&#3629;&#3626;&#3591;&#3626;&#3633;&#3618;&#3626;&#3634;&#3617;&#3634;&#3619;&#3606;&#3605;&#3636;&#3604;&#3605;&#3656;&#3629;&#3626;&#3629;&#3610;&#3606;&#3634;&#3617;&#3652;&#3604;&#3657;&#3607;&#3637;&#3656; www.flyff-eer.com'; //ãÊè¤Óàµ×Í¹

$max_items = 6; //Max items per page; default 6
$max_list = 10; //Max number of pages to link at the bottom; default 10
$max_panel_list = 8; //Max items to be listed per page in the panel; default: 8
$show_count=array('G','H','I','J','K','L','P','X','Y'); //Pages to show item counts on by the name; default 'G','H','I','J','K','L','P','X','Y'
$gift_title = false; //If the player is sending an item as a gift, the "ITEM DETAILS" header will read "SEND GIFT"; default false

if(stristr($_SERVER['PHP_SELF'], "config.php"))
	header("Location: ../");
?>