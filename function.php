<?php
// QUERY
function status($a)
{
	if($a == ""){
		echo "";
	} else if($a == "F"){
		echo "?????????????";
	} else if($a == "J"){
		echo "???????";
	} else if($a == "P"){
		echo "GM";
	}
}
function cl($info){ 
    return preg_replace("/[^a-z\d]/i", '', $info);
} 

function donate($status)
{
	if($status == "0")
	{
		echo "<span style='color:white'>???????????</span>";
	}
	else if($status == "1")
	{
		echo "<span style='color:blue'>?????????????</span>";
	}
	else if($status == "2")
	{
		echo "<span style='color:green'>??????</span>";
	}
	else if($status == "3")
	{
		echo "<span style='color:red'>?????????</span>";
	}
	else if($status == "4")
	{
		echo "<span style='color:black'>?????????</span>";
	}
}
?>