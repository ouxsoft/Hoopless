<?php
include_once('lib/alert.class.php');

// set var
if(isset($_POST['contact'])){
	$contact = $_POST['contact'];
} else {
	$contact = array(
		'name' => null,
		'subject' => null,
		'email' => null,
		'message' => null
	);
}
if(isset($_SESSION['account']['username'])){$contact['name'] = $_SESSION['account']['username'];}

// handled post
if(isset($_POST['contact'])) {
	$error = false;

	if(strlen($contact['name'])>0) {$contact['name'] = trim($contact['name']);} else { $alert->add('warning','Provide a valid name'); $error = true;}

	if(strlen($contact['subject'])>0) {$contact['subject'] = trim($contact['subject']);} else {$alert->add('warning','Provide a valid subject');	$error = true;}

	if((strlen($contact['email'])>0)&&(preg_match('/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i', $contact['email']))) {$contact['email'] = trim($contact['email']);} else {$alert->add('warning','Provide a valid email address'); $error = true;	}

	if(strlen($contact['message'])>10) {
		if(function_exists('stripslashes')) {
			$contact['message'] = stripslashes(trim($contact['message']));
		} else {
			$message = trim($contact['message']);
		}
	} else {
		$alert->add('warning','Provide a message longer than 10 charaters');
		$error = true;
	}

	if(!isset($_POST['norobot'])||(md5($_POST['norobot'])!=$_SESSION['code'])){
		$error = true;
		$alert->add('warning','Incorrect security code supplied');
	}

	if($error==false) {
		$headers = 'From: contact form <'.$contact['email'].'>'.PHP_EOL;
		$headers .= 'Reply-To: <'.$contact['email'].'>'.PHP_EOL;
		$headers .= 'MIME-Version: 1.0'.PHP_EOL;
		$headers .= 'Content-Type: text/html; charset=ISO-8859-1'.PHP_EOL;
		$message .= 'Name: '.$contact['name'].'<br/>';
		$message .= 'Subject: '.$contact['subject'].'<br/>';
		$message .= 'IP address: '.$_SERVER['REMOTE_ADDR'].'<br/><br/>';
		$message .= $contact['message'].PHP_EOL;
		mail($instance->website['email'], $contact['subject'], '<html><body>'.$message.'</body></html>', $headers);
		$alert->add('success','Your message was successfully sent. I will be in contact with you shortly');
		$contact['subject'] = '';
		$contact['message'] = '';
	}
}

$alert->get();

echo '<div class="container background-white load-transition">';
echo '<div class="row">';
echo '<div class="col-md-8">';
echo '<h2>Message</h2>';
echo '<p>Complete the form below to contact me and I will do everything I can to respond as quickly as possible.</p>';

echo '<form name="contact" id="contact" method="post" enctype="multipart/form-data">';

echo '<fieldset class="form-group">';
echo '<label for="name">Name<em class="required">*</em></label>';
echo '<input name="contact[name]" id="name" value="'.$contact['name'].'" class="form-control" placeholder="Enter name" aria-required="true"/>';
echo '</fieldset>';

echo '<fieldset class="form-group">';
echo '<label for="email">Email Address<em class="required">*</em></label>';
echo '<input name="contact[email]" id="email" value="'.$contact['email'].'" type="email" class="form-control"  placeholder="Enter email" aria-required="true"/>';
echo '<small class="text-muted">Your email with will not be shared with anyone else.</small>';
echo '</fieldset>';

echo '<fieldset class="form-group">';
echo '<label for="subject">Subject<em class="required">*</em></label>';
echo '<input name="contact[subject]" id="subject" value="'.$contact['subject'].'" class="form-control" placeholder="Enter subject" aria-required="true"/>';
echo '</fieldset>';

echo '<fieldset class="form-group">';
echo '<label for="message">Message<em class="required">*</em></label>';
echo '<textarea name="contact[message]" id="message" class="form-control" placeholder="Enter message" aria-required="true">'.$contact['message'].'</textarea>';
echo '</fieldset>';

echo '<fieldset class="form-group">';
echo '<label for="norobot">Security Code<em class="required">*</em></label>';
echo '<br/>';
echo '<img src="'.$instance->href('images/captcha/norobot.png').'" alt="security key"/>';
echo '<input name="norobot" id="norobot" class="form-control" placeholder="Enter security code" aria-required="true"/>';
echo '<small class="text-muted">Case-sensitive</small>';
echo '</fieldset>';
echo '<input type="submit" class="btn btn-lg" name="command" title="Send"/>';
echo '</form>';
echo '</div>';

echo '<div class="col-md-4">';

// stay connected
echo '<h2>Stay Connected</h2>';
echo '<h4>Linkedin <span class="glyphicon glyphicon-thumbs-up"></span></h4>';
echo '<p>Please feel free to connect with me:</p>';
echo '<p><a href="https://www.linkedin.com/in/mrheroux" target="_blank"><img src="https://static.licdn.com/scds/common/u/img/webpromo/btn_myprofile_160x33.png" width="160" height="33" alt="View Matt Heroux\'s profile on LinkedIn"></a></p>';
echo '<hr/>';

// contribute
echo '<h2>Contributions</h2>';
echo '<h4>Stack Overflow <span class="glyphicon glyphicon-sunglasses"></span></h4>';
echo '<p><a href="http://stackoverflow.com/users/6701768/mrheroux" target="_blank"><img src="http://stackoverflow.com/users/flair/6701768.png" width="208" height="58" alt="profile for mrheroux at Stack Overflow, Q&amp;A for professional and enthusiast programmers" title="profile for mrheroux at Stack Overflow, Q&amp;A for professional and enthusiast programmers"></a></p>';
echo '<hr/>';

// repo
echo '<h2>Repositories</h2>';
echo '<h4>GitHub <span class="glyphicon glyphicon-retweet"></span></h4>';
echo '<p><a href="https://github.com/mrheroux" target="_blank">Check me out on GitHub <img src="https://a248.e.akamai.net/assets.github.com/images/icons/emoji/octocat.png" alt="GitHub" height="20" width="20"/></a></p>';
echo '<hr/>';

echo '</div>';
echo '</div>';
echo '</div>';
?>