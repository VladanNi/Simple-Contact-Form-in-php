<?php
$suspect = false;
// create a pattern to locate suspect phrases
$pattern = '/Content-Type:|Bcc:|Cc:/i';

	function isSuspect($val, $pattern, &$suspect) { 
	if (is_array($val)) {
		foreach ($val as $item) {
		isSuspect($item, $pattern, $suspect);
		}
	} else {
	// if one of the suspect phrases is found, set Boolean to true
	if (preg_match($pattern, $val)) {
		$suspect = true;
	}
		}
			}
			
// check the $_POST array and any subarrays for suspect content
isSuspect($_POST, $pattern, $suspect);

if(!$suspect){ 	
	foreach($_POST as $key => $value){
		$temp=is_array($value) ? $value:trim($value);
		if(empty($temp) && in_array($key, $required)){
		$missing[]=$key;
		${$key}= ' ';
		}
	elseif(in_array($key, $expect)){
		${$key}=$temp;
		}
	}
}
//validate user email
if(!$suspect && !empty($email)){   
 $validemail=filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); 
 if($validemail){
	 $headers .= "\r\n Replay to: $validemail";
	 }else{
		 $errors['email']=true; 
	 }
		}
$mailSent = false;

if (!$suspect && !$missing && !$errors) {
    $message = '';
    foreach($expect as $item) {
        if (isset(${$item}) && !empty(${$item})) {
            $val = ${$item};
        } else {
            $val = 'Not selected';
        }
        if (is_array($val)) {
            $val = implode(', ', $val);
        }
        $item = str_replace(['_', '-'], ' ', $item);
        // add label and value to the message body
        $message .= ucfirst($item).": $val\r\n\r\n";
    }
    // limit line length to 70 characters
    $message = wordwrap($message, 70);
    $mailSent = mail($to, $subject, $message, $headers);
    if (!$mailSent) {
        $errors['mailfail'] = true;
    }
}