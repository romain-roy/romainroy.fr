<?php
	// Check for empty fields
	if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		http_response_code(500);
		exit();
	}

	$name = strip_tags(htmlspecialchars($_POST['name']));
	$email = strip_tags(htmlspecialchars($_POST['email']));
	$phone = strip_tags(htmlspecialchars($_POST['phone']));
	$message = strip_tags(htmlspecialchars($_POST['message']));

	$to = "rroy.romain@gmail.com";
	$subject = "Contact de : $name";
	$body = "Nouveau message envoyé depuis le formulaire de contact présent sur romainroy.fr.\n\n"."Nom : $name\n\nAdresse mail : $email\n\nMessage :\n\n$message";
	$header = "From: noreply@romainroy.fr\n";
	$header .= "Reply-To: $email";	

	if (!mail($to, $subject, $body, $header))
	{
		http_response_code(500);
	}
?>
