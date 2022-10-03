<?php
 
 $errors = [];
 $data = [];

  
 $name = ! empty( $_POST['name'] ) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
 $email = ! empty( $_POST['email'] ) ? filter_var($_POST['email'], FILTER_SANITIZE_STRING) : '';
 $phone = ! empty( $_POST['phone'] ) ? filter_var($_POST['phone'], FILTER_SANITIZE_STRING) : '';
 $postcode = ! empty( $_POST['postcode'] ) ? filter_var($_POST['postcode'], FILTER_SANITIZE_STRING) : '';
 $comments = ! empty( $_POST['comments'] ) ? filter_var($_POST['comments'], FILTER_SANITIZE_STRING) : '';
 
 if (empty($name)) {
    $errors['name'] = 'Name is required.';
 }
 
  //  Email Validation
 if (empty($email)) {
    $errors['email'] = 'Email is required.';
 }

 if(!empty($email) && !valid_email($email)) {
    $errors['email'] = 'Invalid Email address.';
 }

 //  Phone number validation
 if (empty($phone)) {
    $errors['phone'] = 'Phone is required.';
 }

 if(!empty($phone) && !is_numeric($phone)) {
    $errors['phone'] = 'Phone Number doesn\'t contains string.';
 }
 
 if(!empty($phone) && strlen($phone) <= 9) {
    $errors['phone'] = 'Phone number should be of length 9 or 10.';
 }

 if(!empty($phone) && !substr($phone, 0, 1) == '0') {
    $errors['phone'] = 'Phone number should start with 0.';
 }

 //  Postcode validation
 if (empty($postcode)) {
    $errors['postcode'] = 'Postcode is required.';
 }

 if(!empty($postcode) && strlen($postcode) != 4) {
   $errors['postcode'] = 'Postcode should be of 4 digits.';
 }

//  Recaptcha validation
 if(empty($_POST['recaptcha'])) {
   $errors['recaptcha'] = 'Please verify that you are not a robot.';
 }
 
 if (!empty($errors)) {
    $data['success'] = false;
    $data['errors'] = $errors;
 } else {
    $data['success'] = true;
    $data['message'] = 'Success!';
    send_email_to_admin($name, $email, $phone, $postcode, $comments);
    send_email_to_user($name, $email, $phone, $postcode, $comments);
 }

 /**
  * This function is responsible for email domain validation
  */  
 function valid_email($email) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        return false;
    }

    $domain = explode('@', $email);
    $domain_name = explode('.', $domain[1]);
    $allowed_domains = array('com', 'in', 'au');
    if (in_array(end($domain_name), $allowed_domains))
    {
        return true;
    }
    return false;
}

/**
 * This function is responsible for sending email to admin 
 * after successful form submission.
 */
function send_email_to_admin($name, $email, $phone, $postcode, $comments) {
    $email_to = 'it@gee.com.au';
    $email_from = 'it@gee.com.au';
    $subject = 'New Form Submission by '.$name;

    $headers = 'From: '.$email_from."\r\n".
    'Reply-To: '.$email_from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
    $body = "Form details are given below.\n\n";
    $body .= "Name: ".$name."\n";
    $body .= "Email: ".$email."\n";
    $body .= "Phone: ".$phone."\n";
    $body .= "Postcode: ".$postcode."\n";
    $body .= "Comments: ".$comments."\n";
 
    mail($email_to, $subject, $body, $headers);  
}

/**
 * This function is responsible for sending Confirmation email to user 
 * after successful form submission.
 */
function send_email_to_user($name, $email, $phone, $postcode, $comments) {
    $email_to = $email;
    $email_from = 'it@gee.com.au';
    $subject = 'Your response submitted successfully!';

    $headers = 'From: '.$email_from."\r\n".
    'Reply-To: '.$email_from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    $body = "Thanks for filling up the form. One of our team member will get back to you soon.\n\n";
    $body = "Form details are given below.\n\n";
    $body .= "Name: ".$name."\n";
    $body .= "Email: ".$email."\n";
    $body .= "Phone: ".$phone."\n";
    $body .= "Postcode: ".$postcode."\n";
    $body .= "Comments: ".$comments."\n";
 
    mail($email_to, $subject, $body, $headers);  
}

 echo json_encode($data);
?>