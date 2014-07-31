<?php
		
	include_once("classes/DB.class.php");
	$DB = new DB();
	if($_SERVER["REQUEST_METHOD"] == 'DELETE') {
		die(json_encode(array('Result' => $DB->getInstance()->Delete('Reviews', $_GET))));
	}
	else if(!empty($_GET)) {
		if($_GET["all"]) {
			unset($_GET["all"]);
		}
		if(array_key_exists('id', $_GET)) {
			$reviews = $DB->getInstance()->Read('Reviews',$_GET['id'],null);
			Reviews::getReviews($_GET);
		} else {
			$temp = array();
			$temp['Conditions'] = $_GET;
			$reviews = $DB->getInstance()->Read('Reviews',null,$temp);
		}
		if (!empty($reviews)) {
			foreach ($reviews as &$review) {
				$tmpName = $DB->getInstance()->Read('Users', $review['user_id'], array('Selectors' => array('name')));
				$review['Name'] = $tmpName[0]['name'];
				$tmpLunch = $DB->getInstance()->Read('Lunches', $review['lunch_id'], array('Selectors' => array('caterer')));
				$review['caterer'] = $tmpLunch[0]['caterer'];
				$tmpDate = $DB->getInstance()->Read('Lunches', $review['lunch_id'], array('Selectors' => array('date')));
				$review['date'] = $tmpDate[0]['date'];
				unset($review['lunch_id']);
			}
			die(json_encode($reviews));
		}else{
			die(json_encode(array('Failed' => 'unknown')));
		}
	}
	else if(!empty($_POST)) {
		die(json_encode(array('Result' => $DB->getInstance()->Write('Reviews', $_POST))));
	}

	
	//	die(json_encode(array('Result' => $DB->getInstance()->Delete('Reviews', $_DELETE))));
	
?>
