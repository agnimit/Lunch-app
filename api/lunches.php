<?php

	include_once("classes/DB.class.php");
	$DB = new DB();
	if($_SERVER["REQUEST_METHOD"] == 'DELETE') {
		error_log("print date" . json_encode($_GET));
		$temp['Conditions'] = $_GET;
		$lunch_id = $DB->getInstance()->Read('Lunches', null, $temp);
		error_log("this is lunch_id " . json_encode($lunch_id));
		$DB->getInstance()->Delete('Reviews', array("lunch_id" => $lunch_id[0]["id"]));
		die(json_encode(array('Result' => $DB->getInstance()->Delete('Lunches', $_GET))));
	}
	else if(!empty($_GET)) {
		if(isset($_GET["all"])) {
			unset($_GET["all"]);
		}
		if(array_key_exists('id', $_GET)) {
			die(json_encode($DB->getInstance()->Read('Lunches', $_GET['id'], null)));
		} else {
			$temp['Conditions'] = $_GET;
			die(json_encode($DB->getInstance()->Read('Lunches', null, $temp)));
		}
	
	}
	else if(!empty($_POST)) {
		die(json_encode(array('Result' => $DB->getInstance()->Write('Lunches',$_POST))));
	}

?>
