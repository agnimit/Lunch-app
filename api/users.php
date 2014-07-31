<?php


	include_once("classes/DB.class.php");
	$DB = new DB();
	if($_SERVER["REQUEST_METHOD"] == 'DELETE') {
		die(json_encode(array('Result' => $DB->getInstance()->Delete('Users', $_GET))));
	}
	else if($_SERVER["REQUEST_METHOD"] == 'UPDATE') {
		die(json_encode(array('Result' => $DB->getInstance()->Delete('Users', $_GET))));
	}
	else if(!empty($_GET)){
		if(array_key_exists('id', $_GET)){
			die(json_encode($DB->getInstance()->Read('Users', $_GET['id'], null)));
		} else{
			$temp = array();
			$temp['Conditions'] = $_GET;
			die(json_encode($DB->getInstance()->Read('Users', null, $temp)));
		}
	}	
	else if (!empty($_POST)) {
		$temp= array();
		$temp['Conditions'] = $_POST;
		unset($temp['Conditions']["password"]);
		if($DB->getInstance()->Read('Users', null, $temp) != null)
			die(json_encode(array("error" => "Username Exists")));
		die(json_encode(array('Result' => $DB->getInstance()->Write('Users', $_POST))));
	}
		

?>
