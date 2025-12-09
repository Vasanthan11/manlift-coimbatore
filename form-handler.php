<?php
// form-handler.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}

// Sanitize input
function clean_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Collect fields
$full_name = clean_input($_POST['full_name'] ?? '');
$phone     = clean_input($_POST['phone'] ?? '');
$email     = clean_input($_POST['email'] ?? '');
$location  = clean_input($_POST['location'] ?? '');
$equipment = clean_input($_POST['equipment'] ?? '');
$message   = clean_input($_POST['message'] ?? '');

// Validate
if (empty($full_name) || empty($phone)) {
    header("Location: error.html");
    exit;
}

// Email setup
$to      = "info@manliftcoimbatore.com";
$subject = "New Enquiry - Manlift Rentals Coimbatore";

$body  = "New enquiry received:\n\n";
$body .= "Full Name: $full_name\n";
$body .= "Phone: $phone\n";
$body .= "Email: " . ($email ?: 'Not provided') . "\n";
$body .= "Location: " . ($location ?: 'Not provided') . "\n";
$body .= "Equipment: " . ($equipment ?: 'Not specified') . "\n\n";
$body .= "Message:\n" . ($message ?: 'No details provided') . "\n";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$from_email = !empty($email) ? $email : "no-reply@manliftcoimbatore.com";
$headers   .= "From: Manlift Enquiry <{$from_email}>\r\n";
if (!empty($email)) {
    $headers .= "Reply-To: {$email}\r\n";
}

// Send mail
$mailSent = mail($to, $subject, $body, $headers);

// Redirect result
if ($mailSent) {
    header("Location: thankyou.html");
} else {
    header("Location: error.html");
}
exit;
