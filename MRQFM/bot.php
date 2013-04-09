<?php

include_once("../config.php");
include_once( '../botutil.php' );

require('libs/Smarty.class.php');

if (trim($_POST["input"])!="" and trim($_POST["output"])!=""){
	addMSE($_POST["input"], $_POST["output"]);
}

$smarty = new Smarty;

if (trim($_POST["message"])!="")
{
	$respond = searchMSE($_POST["message"]);
	
	$smarty->assign("respond", $respond);
}
$smarty->display('bot.tpl');
$db->close();

?>