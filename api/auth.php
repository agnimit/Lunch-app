<?php
	setcookie("test","test");
	include_once("classes/DB.class.php");
	$DB = new DB();
	if(!empty($_POST)){
		if(strlen($_POST['user']) == 0 || strlen($_POST['pass']) == 0) {
			die(json_encode(array("Condition" => "Not Authenticated")));
		}
		$temp = $DB->getInstance()->Read('Users',null,array('Conditions' => array('name'=>$_POST['user']), 'Selectors' => array('id', 'type')));
		$hashpass = $DB->getInstance()->Read('Users', null, array('Conditions' => array('name'=>$_POST['user']), 'Selectors' => array('password')));
		$hashpass = sha1($hashpass[0]['password'] . 'vanilla');
		$givenpass = $_POST['pass'];
		error_log("this is the inputted password- " . $givenpass);
		error_log("this is the server password- " . $hashpass);
		if(strcmp($hashpass, $givenpass) == 0) {
			if(isset($_COOKIE['userid']) ) {
				setcookie('userid', '', time() - 3600, '/', NULL, 0);
				setcookie('usertype', '', time() - 3600, '/', NULL, 0);
			}
			setcookie('userid', $temp[0]['id'], time()+7200, '/', NULL, 0);
			setcookie('usertype', $temp[0]['type'], time()+7200, '/', NULL, 0);
			echo(json_encode(array("Condition" => "Authenticated")));
		} else {
			echo(json_encode(array("Condition" => "Not Authenticated")));
		}
	}

	?>
