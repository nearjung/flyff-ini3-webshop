<?php
// MSSQL CONNECT
$mssql_host	=	"DEXSERVER-63153\SQLEXPRESS";
$mssql_user	=	"sa";
$mssql_pass	=	"0877840762As";
$mssql_acdb	=	"ACCOUNT_DBF";
$mssql_chdb	=	"CHARACTER_01_DBF";


// Row Setting
$row_cash	=	"";

// Don't Edit
$connect = mssql_connect("$mssql_host","$mssql_user","$mssql_pass") or die("Cannot Connect to server");
?>