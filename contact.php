<?php
header('Content-Type: application/json');

// Set the recipient email address
$to = 'prayassth@gmail.com';
$subject = 'New Contact Form Submission from ' . trim($_POST['name'] ?? '');

// Check if the request is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $form_subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    if (empty($form_subject)) {
        $errors['subject'] = 'Subject is required';
    }
    
    if (empty($message)) {
        $errors['message'] = 'Message is required';
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['errors'] = $errors;
        $response['message'] = 'Please correct the errors in the form';
        echo json_encode($response);
        exit();
    }
    
    // Email headers
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // Email body
    $email_body = "
    <html>
    <head>
        <title>New Contact Form Submission</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #007bff; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; background-color: #f9f9f9; }
            .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #777; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
            </div>
            <div class='content'>
                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>"
                . (!empty($phone) ? "<p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>" : "") . "
                <p><strong>Subject:</strong> " . htmlspecialchars($form_subject) . "</p>
                <p><strong>Message:</strong></p>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
            </div>
            <div class='footer'>
                <p>This email was sent from the contact form on GE Construction Group's website (Gaithersburg, MD 20878-1964)</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send email
    if (mail($to, $subject, $email_body, $headers)) {
        $response['success'] = true;
        $response['message'] = 'Thank you for your message. We will get back to you soon!';
    } else {
        $response['message'] = 'Sorry, there was an error sending your message. Please try again later.';
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit();
?>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="GE Construction Group">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.html">SERVICES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="projects.html">PROJECTS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="contact.php">CONTACT</a>
                </li>
                <li class="nav-item ms-2">
                    <a href="#free-quote" class="btn btn-primary">GET A QUOTE</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
