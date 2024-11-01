<?php
require_once '../../../wp-config.php';

//initialize variables
$error = 0;
$email_regex = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";

if(empty($_POST) || !wp_verify_nonce($_POST['wti_contact_back_nonce'], 'wtideas'))
{
	$msg = __('Sorry, some error occured.', 'wti-contact-back');
	$error = 1;
}
else
{
	// process form data
	$contact_name = esc_attr(trim($_POST['contact_name']));
	$contact_mode = esc_attr(trim($_POST['contact_mode']));
	$contact_value = esc_attr(trim($_POST['contact_value']));
	
	//setting values
	$contact_to = get_option('wti_contact_back_to_email');
	$subject = __('Contact me back', 'wti-contact-back');
	
	if(empty($contact_name)) {
		$msg = __('Please enter your name', 'wti-contact-back');
		$error++;
	}/* else if(empty($contact_mode)) {
		$msg = __('Please select contact mode', 'wti-contact-back');
		$error++;
	}*/ else if(empty($contact_value)) {
		$msg = __('Please enter your email address', 'wti-contact-back');
		$error++;
	} else if(!ereg($email_regex, $contact_value)) {		
		$msg = __('Please enter valid email address', 'wti-contact-back');
		$error++;
	}
	
	if(!$error) {
		$headers  = "From: " . strip_tags($contact_value) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($contact_value) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$message  = __('Hi,', 'wti-contact-back');
		$message .= '<br />';
		$message .= __('Can you please contact me on', 'wti-contact-back') . ' ' . $contact_value . '?<br /><br />';
		$message .= __('Thanks,', 'wti-contact-back');
		$message .= '<br />' . $contact_name;		

		$is_sent = wp_mail($contact_to, $subject, $message, $headers);
		
		if($is_sent) {
			$msg = __('Mail sent successfully.', 'wti-contact-back');
		} else {
			$msg = __('Mail could not be sent.', 'wti-contact-back');
			$error++;
		}
	}
}

$result = array("msg" => $msg, "error" => $error);

echo json_encode($result);
?>