<?php
if($_POST)
{
	$to_Email   	= "info@aries-et-scorpio.de"; //Replace with recipient email address
	$subject        = 'Message from website '.$_SERVER['SERVER_NAME']; //Subject line for emails
	
	
	//check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	
		//exit script outputting json data
		$output = json_encode(
		array(
			'type'=>'error', 
			'text' => 'Request must come from Ajax'
		));
		
		die($output);
    } 
	
	//check $_POST vars are set, exit if any missing
	if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userMessage"]))
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
		die($output);
	}

	//Sanitize input data using PHP filter_var().
	$user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
	$user_Email       = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
	$user_Message     = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
	
	$user_Message = str_replace("\&#39;", "'", $user_Message);
	$user_Message = str_replace("&#39;", "'", $user_Message);
	
	
	if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Bitte geben Sie eine gültige Email-Adresse an!'));
		die($output);
	}
	if(strlen($user_Message)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Nachricht zu kurz! Bitte beschreiben Sie Ihr Anliegen.'));
		die($output);
	}
	
	//proceed with PHP email.
	$headers = 'From: '.$user_Email.'' . "\r\n" .
	'Reply-To: '.$user_Email.'' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	$sentMail = @mail($to_Email, $subject, $user_Message . "\r\n\n"  .'-- '.$user_Name. "\r\n" .'-- '.$user_Email, $headers);
	
	if(!$sentMail)
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Mail konnte nicht gesendet werden! Bitte überprüfen Sie Ihre PHP-Mail-Konfiguration.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'Hallo '.$user_Name .'! Vielen Dank für Ihre Email. Wir werden und schnellstmöglich mit Ihnen in Verbindung setzen.'));
		die($output);
	}
}
?>