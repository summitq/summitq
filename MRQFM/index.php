<?php

	include_once("../config.php");
	require('libs/Smarty.class.php');

	$id = $_GET["id"];
	$line = $db->queryUniqueObject("SELECT * FROM FM WHERE id='{$id}'");
	$playlist = json_decode(rawurldecode($line->playlist), true);
	
	

	//实例化
	$smarty = new Smarty;

	

	$smarty->assign("playlist", $playlist);

	$smarty->display('index.tpl');
	
	$db->close();

?>