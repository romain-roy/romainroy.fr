<?php
if(empty($_POST['name'])  	||
   empty($_POST['email']) 	||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }

$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$message = strip_tags(htmlspecialchars($_POST['message']));

$to = "rroy.romain@gmail.com";
$email_subject = "Contact de : $name";
$email_body = "Nouveau message envoyé depuis le formulaire de contact présent sur romainroy.fr.\n\n"."Nom : $name\n\nAdresse mail : $email_address\n\nMessage :\n\n$message";
$headers = "From: noreply@romainroy.fr\n";
$headers .= "Reply-To: $email_address";
mail($to,$email_subject,$email_body,$headers);
return true;
?>
