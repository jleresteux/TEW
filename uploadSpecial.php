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
$email->setSubject("File(s) submission");
$email->setHtmlContent("");
$email->addCustomHeader('X-FirstHeader', "value");
$email->addCustomHeader('X-SecondHeader', "value");
$email->addCustomHeader('X-Header-to-be-removed', 'value');

// creation of the email to be sent
$email_ar = new Email();
$email_ar->setFrom("info@the-endless-wanderer.com");
$email_ar->setCcList("");
$email_ar->setBccList("");	
$email_ar->setSubject("File(s) submission");
$email_ar->setHtmlContent("");
$email_ar->addCustomHeader('X-FirstHeader', "value");
$email_ar->addCustomHeader('X-SecondHeader', "value");
$email_ar->addCustomHeader('X-Header-to-be-removed', 'value');



$target = "upload/Contest0/";  //folder where it goes + ADD FOLDER with NAME of applicant/email address

$son_message = "";
$dear = "";
$toolarge = "";
$numbers = "";
$notsupported ="";
$onlyimage="";
$notuploaded="";
$uploaded="";
$up_prob="";


if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	if (isset($_POST["contests_name"])) 
		$_POST["contests_name"]=trim(stripslashes($_POST["contests_name"]));
	/*if (isset($_POST["description1"])) 
		$_POST["description1"]=trim(stripslashes($_POST["description1"]));*/
	
	$dear = "Dear ". $_POST["contests_name"] .",<br><br>";
	$son_message = $dear;
	echo $dear;
	
	$t=date("YmdHis",time());
	mkdir((string)$target.(string)$_POST["contests_name"]."_".$t,0777);
	$name_app = (string)$target.(string)$_POST["contests_name"]."_".$t."/";
	$dir = $name_app;
	
	//creates descriptions text file
	$descriptions = fopen($dir."descriptions.txt", "wb");
	fwrite($descriptions, $_POST["contests_name"]."\r\n");
	fwrite($descriptions, $_POST["contests_email"]."\r\n");
	fwrite($descriptions, $_POST["contests_site"]."\r\n");
	fwrite($descriptions, $_POST["description1"]);
	
	$ok=1;
	$prob=0;
	
	for($f=0; $f<count($_FILES["uploaded"]['name']); $f++) {
	//foreach ($_FILES['uploaded']["name"] as $f => $name) {
		if($ok==1){
			$target = $dir . basename( $_FILES['uploaded']["name"][$f]) ; 
			$uploaded_size = $_FILES["uploaded"]["size"][$f] ;
			$uploaded_type = $_FILES['uploaded']["type"][$f] ;
			   

		/* 	echo "place ". $dir. "<br>";
			echo "file ". $target. " <br>";
			echo "size ". $uploaded_size. "<br>";
			echo "type ". $uploaded_type. " <br>"; */
			
			//This is our size condition  
			if ($uploaded_size > 5242880)  {  //max 5MB
				$toolarge = "The file ". basename( $_FILES['uploaded']['name'][$f]). " is too large.";
				echo $toolarge;
				$numbers = " It is more than ".round (($uploaded_size/1048576)). "MB instead of less than 5MB.<br>";
				echo $numbers;
				$son_message = $son_message.$toolarge.$numbers;
				$ok=0;
			}     

			//This is our limit file type condition  
			if ($uploaded_type =="text/php" or $uploaded_type =="text/html" or $uploaded_type =="text/cgi")  {  //no php, html, cgi
				$notsupported = "The file ". basename( $_FILES['uploaded']['name'][$f]). " is not a supported format<br>";
				echo $notsupported;
				$onlyimage = "You may only upload jpg, jpeg, png or gif files.<br>";
				echo $onlyimage;
				$son_message = $son_message.$notsupported.$onlyimage;
				$ok=0;  
			}   

			//This applies the function to our file  
			/*$ext = findexts ($_FILES['uploaded']['name']) ; */

			//This is our image file type condition
			if ( $uploaded_type!="image/jpeg" and $uploaded_type!="image/jpg" and $uploaded_type!="image/png" and $uploaded_type!="image/gif") { 
				$notsupported = "The file ". basename( $_FILES['uploaded']['name'][$f]). " is not a supported format<br>";
				echo $notsupported;
				$onlyimage = "You may only upload jpg, jpeg, png or gif files.<br>";
				echo $onlyimage;
				$son_message = $son_message.$notsupported.$onlyimage;
				$ok=0;
			}

			//Here we check that $ok was not set to 0 by an error 
			if ($ok==0)  { 
				$notuploaded = "Sorry, the file ". basename( $_FILES['uploaded']['name'][$f]). " was not uploaded<br><br>";
				echo $notuploaded;
				$son_message = $son_message.$notuploaded;
				$ok=1;
				$prob++;
				
			}   
			//If everything is ok we try to upload it  
			else  {  
				if(move_uploaded_file($_FILES['uploaded']['tmp_name'][$f], $target))  {  
					$uploaded = "The file ". basename( $_FILES['uploaded']['name'][$f]). " has been uploaded<br><br>";
					echo $uploaded;
					$son_message = $son_message.$uploaded;
				}   
				else  {  
					$up_prob = "Sorry, there was a problem while uploading ". basename( $_FILES['uploaded']['name'][$f]). ".<br><br>";
					echo $up_prob;
					$son_message = $son_message.$up_prob;
					$prob++;
				}  
			}
		}
		else{break;}
	}
	
	$son_pseudo = $_POST["contests_name"]; // On stocke les variables r嶰up廨嶪s du formulaire
	$son_email = $_POST["contests_email"];
	$son_objet = "Files submission";
	
 
	$mon_email = "info@the-endless-wanderer.com"; // Mise en forme du message que vous recevrez
	$mon_pseudo = "The Endless Wanderer";
	$mon_url = "http://www.the-endless-wanderer.com";
	$msg_pour_moi = "- Name : $son_pseudo \n 	- E-mail : $son_email \n 	- Message : \n $son_message \n\n";
 
 $email->setToList("$mon_email");
$email->setContent($msg_pour_moi);
 
	// Mise en forme de l'accus?r嶰eption qu'il recevra
	$accuse_pour_lui = "Dear $son_pseudo,\n 	Your submission has been received. If any issue occured, please check the message below\n\n - Status of upload : \n $son_message \n\n 	Thank you and see you soon on The Endless Wanderer !";

	$email_ar->setToList("$son_email");
$email_ar->setContent($accuse_pour_lui);
	
	// Envoie du mail
	$entete = "From: " . $mon_pseudo . " <" . $mon_email . ">\n"; // On pr廧are l'ent皻e du message
	$entete .='Content-Type: text/plain; charset="utf-8"'."\n"; 
	$entete .='Content-Transfer-Encoding: 8bit';
 
	//mail($mon_email, $son_objet, $son_message);
 
 //creation of a client that connects with turbo-smtp APIs
$turboApiClient = new TurboApiClient("info@the-endless-wanderer.com", "RCV1jQBw");

// email sending
$response = $turboApiClient->sendEmail($email);
$response_ar = $turboApiClient->sendEmail($email_ar);
 
	
	if (($response['message'] == 'OK') && ($response_ar['message'] == 'OK')){ // Si le mail a 彋?envoy?
		// Redirection du visiteur vers la page du minichat
		if($ok==1 and $prob==0){
			header('Location: entryFormSpecial.html#contact');
		}
		else{
			echo "<br><br>".$prob." problem(s) detected.";
			echo "<br><a href=entryFormSpecial.html#form>back</a>"; // Avec un lien de retour vers l'accueil du site
		}
			
		//echo "<p style=\"text-align:center\">Your message has been successfully sent, you will receive a confirmation mail.<br /><br />\n"; // On affiche un message de confirmation
		//echo "<a href=\"" . $mon_url . "\">back</a></p>\n"; // Avec un lien de retour vers l'accueil du site
	}
	else { // Sinon il y a eu une erreur lors de l'envoi
		// Redirection du visiteur vers la page du minichat
		if($ok==1 and $prob==0){
			echo "The upload was successful but an issue occured while sending the confirmation email.\n";
			echo "<br>Your submission is completed. Back to <a href=index2.html>The Endless Wanderer</a>";
		}
		else{
			echo "<br><br>".$prob." problem(s) detected.";
			echo "The upload was unsuccessful and an issue occured while sending the confirmation email.\n";
			echo "<br>Please <a href=entryFormSpecial.html#form>try again</a>"; // Avec un lien de retour vers l'accueil du site
		}
		
		//echo "<p style=\"text-align:center\">An issue occured while sending\n";
		//echo "<a href=\"".$_SERVER["PHP_SELF"]."\">Try again...</a></p>\n"; // On propose un lien de retour vers le formulaire
	}
	
	
	
}

//This function separates the extension from the rest of the file name and returns it  
function findexts ($filename)  {  
	$filename = strtolower($filename) ;  
	$exts = split("[/\\.]", $filename) ;  
	$n = count($exts)-1;  
	$exts = $exts[$n];  
	return $exts;  
}


/* 
The first line $target = "upload/"; is where we assign the folder that files will be uploaded to. As you can see in the second line, this folder is relative to the upload.php file. So for example, if your file was at www.yours.com/files/upload.php then it would upload files to www.yours.com/files/upload/yourfile.gif. Be sure you remember to create this folder!

We are not using $ok=1; at the moment but we will later in the tutorial.

We then move the uploaded file to where it belongs using move_uploaded_file (). This places it in the directory we specified at the beginning of our script. If this fails the user is given an error message, otherwise they are told that the file has been uploaded.

Assuming that you didn't change the form field in our HTML form (so it is still named uploaded), this will check to see the size of the file. If the file is larger than 350k, they are given a file too large error, and we set $ok to equal 0.

The code above checks to be sure the user is not uploading a PHP file to your site. If they do upload a PHP file, they are given an error, and $ok is set to 0.

Obviously if you are allowing file uploads you are leaving yourself open to people uploading lots of undesirable things. One precaution is not allowing them to upload any php, html, cgi, etc. files that could contain malicious code. This provides more safety but is not sure fire protection.

!!!!!! Another idea is to make the upload folder private, so that only you can see it. Then once you have seen what has been uploaded, you can approve (move) it or remove it.
Depending on how many files you plan on receiving this could be time consuming and impractical.

In short, this script is probably best kept in a private folder. We don't recommend putting it somewhere where the public can use it, or you may end up with a server full of useless or potentially dangerous files. If you really want the general public to be able to utilize your server space, we suggest writing in as much security as possible.

More security:
When you allow users to upload files to your website, you are putting yourself at a security risk. While nobody is ever completely safe, here are some precautions you can incorporate to make your site safer.

- Check the referrer: Check to make sure that the information being sent to your script is from your website and not an outside source. While this information can be faked, it's still a good idea to check.

- Restrict file types: You can check the mime-type and file extension and only allow certain types to be uploaded.

- Rename files: You can rename the files that are uploaded. In doing so, check for double-barreld extensions like yourfile.php.gif and eliminate extensions you don't allow, or remove the file completely.
//This line assigns a random number to a variable. You could also use a timestamp here if you prefer.  $ran = rand () ;  //This takes the random number (or timestamp) you generated and adds a . on the end, so it is ready of the file extension to be appended. $ran2 = $ran.".";  //This assigns the subdirectory you want to save into... make sure it exists! $target = "images/";
//This combines the directory, the random file name, and the extension $target = $target . $ran2.$ext;

This code uses the rand () function to generate a random number as the file name. Another idea is to use the time () function so that each file is named after its timestamp. It then combines this name with the extension from the original file. We also assign the subdirectory... make sure this actually exists!
if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))  { echo "The file has been uploaded as ".$ran2.$ext; }  else { echo "Sorry, there was a problem uploading your file."; } ?>
 
- !!!! Change permissions: Change the permissions on the upload folder so that files within it are not executable. Your FTP program probably allows you to chmod right from it.

- Login and Moderate: Making your users login might deter some deviant behavior. You can also take the time to moderate all file uploads before allowing them to become live on the web.
*/



?>