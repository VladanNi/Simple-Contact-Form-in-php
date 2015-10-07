<?php 
//iclude name of tile 
include './includes/title.php';
//errors in form
$errors=[];
//missing fields in form
$missing=[];

if(isset($_POST['send'])){
	//email proccesing script
	$to='example@example.com';
	$subject='Title of mail';
	//list of expected fields
	$expect=['name', 'email', 'comments'];
	//list of required fields
	$required=['name', 'comments', 'email', 'subscribe', 'interesovanja', 'howhear', 'karakteristika', 'terms'];
	//set default values for variables that might not exist
		if(!isset($_POST['subscribe'])) {
			$_POST['subscribe'] = '';
		}
		if(!isset($_POST['interesovanja'])){
			$_POST['interesovanja'] = [];
		}
		if(!isset($_POST['karakteristika'])){
			$_POST['karakteristika'] = [];
		}
		if(!isset($_POST['terms'])) {
			// default value
			$_POST['terms'] = [];
			//when there is error
			$errors['terms'] = true; 
		}
	//minimum Checkboxes
	$minCheckboxes = 2;
		if(count($_POST['interesovanja']) < $minCheckboxes){
			$errors['interesovanja'] = true;
		}
	//minimum karakteristika
	$minKar = 1;
		if(count($_POST['karakteristika']) < $minKar){
			$errors['karakteristika'] = true;
		}
$headers = "From: <test@example.com>\r\n"; 
$headers .= 'Content-Type: text/plain; charset=utf-8';
	require './includes/procesmail.php';
//to thankyou page
if($mailSent){
	header('Location: ./thankyou.php');
	exit();
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Simple Contact Form<?php if(isset($title)){echo "&#8212;{$title}"; }?></title>
    <link href="styles/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<header>
    <h1>Simple Contact Form</h1>
</header>
<div id="wrapper">
    <main>
        <h2>Contact Us</h2>
		<?php if(($_POST && $suspect) || ($_POST && isset($errors['mailfail']))) { ?>
			<p class="warning">Vas mail nije poslat. Probajte kasnije</p>
		<?php } elseif($missing || $errors) { ?>
			<p class="warning">Doslo je do greske, ispravite je</p>
		<?php }?>
      <p>Ut enim ad minim veniam, quis nostrud exercitation consectetur adipisicing elit. Velit esse cillum dolore ullamco laboris nisi in reprehenderit in voluptate. Mollit anim id est laborum. Sunt in culpa duis aute irure dolor excepteur sint occaecat.</p>
        <form method="post" action="">
            <p>
                <label for="name">Name:</label>
				<?php if($missing && in_array('name', $missing)){?>
				<span class="warning">Please enter your name</span>
				<?php } ?>
                <input name="name" id="name" type="text"
				<?php if($missing || $errors) {
					echo 'value="' . htmlentities($name) . '"';
				}?>>
            </p>
            <p>
                <label for="email">Email:</label>
				<?php if($missing && in_array('email', $missing)){?>
				<span class="warning">Enter mail</span>
				<?php } elseif (isset($errors['email'])) { ?>
				<span class="warning">Invalid emali address</span>
				<?php } ?>
                <input name="email" id="email" type="text"
				<?php if($missing || $errors){
					echo 'value="' . htmlentities($email) . '"';
				}?>>
            </p>
            <p>
                <label for="comments">Comments:</label>
				<?php if($missing && in_array('comments', $missing)){?>
				<span class="warning">Enter comment</span>
				<?php } ?>
                <textarea name="comments" id="comments">
				<?php if($missing || $errors){
					echo htmlentities($comments);
				}?>
				</textarea>
            </p>
			<fieldset id="subscribe">
				<h2>Subscribe to newsletter?
				<?php if($missing && in_array('subscribe', $missing)){ ?>
				<span class="warning">Please make a selection</span>
				<?php } ?>
				</h2>
				<p>
					<input name="subscribe" type="radio" value="Yes" id="subscribe_yes"
						<?php if($_POST && $_POST['subscribe'] == 'Yes'){
							echo 'checked';
						}?>>
					<label for="subscribe-yes">Yes</label>
					<input name="subscribe" type="radio" value="No" id="subscribe_no"
						<?php if(!$_POST || $_POST['subscribe'] == 'No'){
							echo 'checked';
						} ?>>
					<label for="subscribe-yes">No</label>
				</p>
			</fieldset>
			<fieldset id="interests">
				<h2>Interests
				<?php if(isset($errors['interesovanja'])) { ?>
					<span class="warning">Please select at least <?= $minCheckboxes;?></span>
				<?php } ?>
				</h2>
				<div>
				<p>
				<input type="checkbox" name="interesovanja[]" value="Zivotinje" 
				 id="zivotinja"
				 <?php if ($_POST && in_array('Option one', $_POST['interesovanja'])){
					 echo 'checked';
				 } ?>>
				 <label for="zivotinja">Option one</label>
				 </p>
				 <p>
				<input type="checkbox" name="interesovanja[]" value="Knjige"
				id="knjiga"
				<?php if($_POST && in_array('Option two', $_POST['interesovanja'])){
					echo 'checked';
				} ?>>
				<label for="knjiga">Option two</label>
				</p>
				</div>
			</fieldset>
			<p>
			<label for="howhear">How did you hear of our services?
				<?php if($missing && in_array('howhear', $missing)){ ?>
						<span class="warning">Please make a selection</span>
				<?php } ?>						
			</label>
			<select name="howhear" id="howhear">
				<option value=""
				<?php if(!$_POST || $_POST['howhear'] == ''){
					echo 'selected';
				}?>>Empty field</option>
				<option value="No replay"
					<?php if($_POST && $_POST['howhear'] == 'No replay'){
						echo 'selected';
					} ?>>Select one</option>
				<option value="Nekako"
					<?php if($_POST && $_POST['howhear'] == 'Nekako'){
					echo 'selected';
					} ?>>Select two</option>
			</select>
			</p>
			<p>
			<label for="karakteristika">Choose something
				<?php if(isset($errors['karakteristika'])){ // minimum kar?> 
					<span class="warning">Izaberite minimum <?= $minKar; ?></span>
				<?php } ?> 
			</label>
				<select name="karakteristika[]" size="6" multiple="multiple" id="karakteristika">
					<option value="Prva_karakteristika"
						<?php if($_POST && in_array('Prva_karakteristika', $_POST['karakteristika'])) {
							echo 'selected';
						} ?>>Prva karakteristika</option>
					<option value="Druga_karakteristika"
						<?php if($_POST && in_array('Druga_karakteristika', $_POST['karakteristika'])){
							echo 'selected';
						} ?>>Druga karakteristika</option>
					<option value="Treca_karakteristika"
					<?php
                        if ($_POST && in_array('Treca_karakteristika', $_POST['karakteristika'])) {
                            echo 'selected';
                        } ?>>Treca karakteristika</option>
                    <option value="Cetvrta_karakteristika"
                        <?php
                        if ($_POST && in_array('Cetvrta_karakteristika', $_POST['karakteristika'])) {
                            echo 'selected';
                        } ?>>Cetvrta karakteristika</option>
				</select>
			</p>
			<p>
			<!--POST if there some value and not errors-->
				<input type="checkbox" name="terms" value="accepted" id="terms"
						<?php if($_POST && !isset($errors['terms'])){ 
							echo 'checked';
						}?>>
				<label for="terms">
						<?php if(isset($errors['terms'])) { ?>
							<span class="warning">Accept the terms</span>
						<?php } ?>I accept the terms</label>
			</p>
			<p>
				 <input name="send" type="submit" value="Send message">
			</p>
        </form>
	</main>
</div>
</body>
</html>
