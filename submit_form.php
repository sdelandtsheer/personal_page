<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CAPTCHA
    $captcha = trim($_POST['captcha']);
    if ($captcha != '7') {
        echo "Incorrect CAPTCHA answer.";
        exit;
    }

    // Sanitize and validate input
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    if (empty($name) || empty($email) || empty($message)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Protect against email injection
    $pattern = "/(content-type|bcc:|cc:|to:)/i";
    if (preg_match($pattern, $name) || preg_match($pattern, $email) || preg_match($pattern, $message)) {
        echo "Invalid input.";
        exit;
    }

    // Your email address
    $to = 'your-email@example.com';

    // Email subject and body
    $subject = "New contact form submission from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    // Email headers
    $headers = "From: $email";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo "Thank you! Your message has been sent.";
    } else {
        echo "Sorry, there was an error sending your message. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
?>
