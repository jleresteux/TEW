<?php

require_once "lib/TurboApiClient.php";

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-75123378-1', 'auto');
  ga('send', 'pageview');

</script>


// creation of the email to be sent
$email = new Email();
$email->setFrom("info@the-endless-wanderer.com");
$email->setCcList("");
$email->setBccList("");	
$email->setSubject("Request");
$email->setHtmlContent("");
$email->addCustomHeader('X-FirstHeader', "value");
$email->addCustomHeader('X-SecondHeader', "value");
$email->addCustomHeader('X-Header-to-be-removed', 'value');

// creation of the email to be sent
$email_ar = new Email();
$email_ar->setFrom("info@the-endless-wanderer.com");
$email_ar->setCcList("");
$email_ar->setBccList("");	
$email_ar->setSubject("Request");
$email_ar->setHtmlContent("");
$email_ar->addCustomHeader('X-FirstHeader', "value");
$email_ar->addCustomHeader('X-SecondHeader', "value");
$email_ar->addCustomHeader('X-Header-to-be-removed', 'value');

//if (isset($_POST["envoyer"])){ // Si le formulaire a été soumis
	$etat = "erreur"; // On initialise notre etat à erreur, il sera changé à "ok" si la vérification du formulaire est un succès, sinon il reste à erreur
 
	// On récupère les champs du formulaire, et on arrange leur mise en forme
	if (isset($_POST["name"])) $_POST["name"]=trim(stripslashes($_POST["name"])); // trim()  enlève les espaces en début et fin de chaine
 
	if (isset($_POST["email"])) $_POST["email"]=trim(stripslashes($_POST["email"])); // stripslashes()  retire les backslashes ==> \' devient '
 
	if (isset($_POST["message"])) $_POST["message"]=trim(stripslashes($_POST["message"]));
 
	// Après la mise en forme, on vérifie la validité des champs
	if (empty($_POST["name"])) { // L'utilisateur n'a pas rempli le champ pseudo
		$erreur="Please, enter your name"; // On met dans erreur le message qui sera affiché
	}
	elseif (empty($_POST["email"])) { // L'utilisateur n'a pas rempli le champ email
		$erreur="Please, enter your email address";
	}
	elseif (!preg_match("$[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\.[a-z]{2,4}$",$_POST["email"])){ // On vérifie si l'email est bien de la forme messagerie@domaine.tld (cf cours d'expressions régulières)
		$erreur="Sorry, but your email is not valid";
	}
	elseif (empty($_POST["message"])) { // L'utilsateur n'a écrit aucun message
		$erreur="Please, enter your message";
	}
	else { // Si tous les champs sont valides, on change l'état à ok
		$etat="ok";
	}
//}
//else { // Sinon le formulaire n'a pas été soumis
//	$etat="attente"; // On passe donc dans l'état attente
//	echo "attente";
//}
 
if ($etat!="ok"){ // Le formulaire a été soumis mais il y a des erreurs (etat=erreur) OU le formulaire n'a pas été soumis (etat=attente)
	if ($etat=="erreur"){ // Cas où le formulaire a été soumis mais il y a des erreurs
		echo "<span style=\"color:red\">".$erreur."</span><br /><br />\n"; // On affiche le message correspondant à l'erreur
	}
	}
else { // Sinon l'état est ok donc on envoie le mail
	$son_pseudo = $_POST["name"]; // On stocke les variables récupérées du formulaire
	$son_email = $_POST["email"];
	$son_objet = "Question";
	$son_message = $_POST["message"];
 
	$mon_email = "info@the-endless-wanderer.com"; // Mise en forme du message que vous recevrez
	$mon_pseudo = "The Endless Wanderer";
	$mon_url = "http://www.the-endless-wanderer.com";
	$msg_pour_moi = "- Son pseudo : $son_pseudo \n 	- Son E-mail : $son_email \n 	- Message : \n $son_message \n\n";
 
 $email->setToList("$mon_email");
$email->setContent($msg_pour_moi);
 
	// Mise en forme de l'accusé réception qu'il recevra
	$accuse_pour_lui = "Hello $son_pseudo,\n 	Your message has been successfully received. We will answer as fast as we can.\n\n 	- Your E-mail : $son_email \n 	- Your message : \n $son_message \n\n 	Thank you and see you soon!";
 
 $email_ar->setToList("$son_email");
$email_ar->setContent($accuse_pour_lui);
 
	// Envoie du mail
	$entete = "From: " . $mon_pseudo . " <" . $mon_email . ">\n"; // On prépare l'entête du message
	$entete .='Content-Type: text/plain; charset="utf-8"'."\n"; 
	$entete .='Content-Transfer-Encoding: 8bit';
 
	//mail($mon_email, $son_objet, $son_message);
 
	
	// if (@mail($mon_email, $son_objet, $msg_pour_moi, $entete) && @mail($son_email, $son_objet, $accuse_pour_lui,$entete)){  Si le mail a été envoyé
	//	echo "<p style=\"text-align:center\">Your message has been successfully sent, you will receive a confirmation mail.<br /><br />\n"; // On affiche un message de confirmation
	//	echo "<a href=\"" . $mon_url . "\">back</a></p>\n"; // Avec un lien de retour vers l'accueil du site
	//}
	//else { // Sinon il y a eu une erreur lors de l'envoi
	//	echo "<p style=\"text-align:center\">An issue occured while sending\n";
	//	echo "<a href=\"".$_SERVER["PHP_SELF"]."\">Try again...</a></p>\n"; // On propose un lien de retour vers le formulaire
	//}
	
	//creation of a client that connects with turbo-smtp APIs
$turboApiClient = new TurboApiClient("info@the-endless-wanderer.com", "RCV1jQBw");

// email sending
$response = $turboApiClient->sendEmail($email);
$response_ar = $turboApiClient->sendEmail($email_ar);

if (($response['message'] == 'OK') && ($response_ar['message'] == 'OK')){
	echo "<p style=\"text-align:center\">Your message has been successfully sent, you will receive a confirmation mail.<br /><br />\n"; // On affiche un message de confirmation
	echo "<a href=\"" . $mon_url . "\">back</a></p>\n"; // Avec un lien de retour vers l'accueil du site
}
 else {
	echo "<p style=\"text-align:center\">An issue occured while sending\n";
	echo "<a href=\"".$_SERVER["PHP_SELF"]."\">Try again...</a></p>\n"; // On propose un lien de retour vers le formulaire
}

// display of the operations outcome
//var_dump($response);
//var_dump($response_ar);
}
?>