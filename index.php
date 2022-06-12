<?php
require 'src/crypto.php';
use Themeart\Crypto\Crypto;

session_start();

IF(isset($_GET['id'])){
	$id = $_GET['id'];
	if(isset($_GET['is_link'])){
		
		$key = $_GET['key'];
		$_SESSION['id'] = $id;
		$_SESSION['key'] = $key;

		header('location:hidden.php');
		exit;
	}
?>
	<script type="text/javascript">
		var key = window.location.hash.slice(1);
		document.location.href = 'index.php?id=<?php echo $id;?>&key='+key+'&is_link=1';
	</script>
<?php
}

if(isset($_POST['contents'])){
	$crypto = new Crypto();
	$contents = $_POST['contents'];
	$password = $_POST['pwd1'];

	if(empty($password)){
		$password = $crypto->uniqueId(9);
	}

	$id = $crypto->uniqueId();
 	$txt_encrypt = $crypto->encrypt($contents, $password);
 	
	$file = $id.'#'.$password;

	$fh = fopen("list/".$file, "w") or die("Unable to open file!");
	fwrite($fh, $txt_encrypt);
	fclose($fh);

	if(!empty($_POST['pwd1'])){
		$file = $id;
 	}

	$html = '
	<div class="container">
		<div class="form-group">
			<label for="contents">Note link ready:</label>
			<input type="text" class="form-control" id="link" value="https://'.$_SERVER['HTTP_HOST']."/".$file.'">
		</div>
		<button type="button" id="btn_copy" class="btn btn-primary" onclick="copyLink();">Copy link</button>
	</div>';
	echo $html;
	exit;
}
require_once 'inc/header.php';
?>
<section class="contents">
	<div class="container">
		<form id="form_contents" class="needs-validation" method="post" novalidate>
			<div class="form-group">
				<label for="contents" class="font-weight-bold">Contents of note:</label>
				<textarea class="form-control required" rows="5" id="contents" placeholder="Write your text here" required></textarea>
				<div class="invalid-feedback">Please write contents.</div>
			</div>
			<div id="pwd" class="form-row" style="display:none;">
				<div class="form-group col-md-6">
					<label for="pwd" class="font-weight-bold">Enter the password to decrypt the note:</label>
					<input type="password" class="form-control pwd" id="pwd1" placeholder="Enter password" name="pwd1">
					<div id="txt_invalid" class="invalid-feedback">Please enter password.</div>
				</div>
				<div class="form-group col-md-6">
					<label for="pwd" class="font-weight-bold">Repeat password:</label>
					<input type="password" class="form-control pwd" id="pwd2" placeholder="Enter password" name="pwd2">
					<div id="pwd_invalid" class="invalid-feedback">Please repeat password.</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-6">
					<button type="button" id="btn_create" class="btn btn-primary" disabled>Create contents</button>
				</div>
				<div class="col-md-6 text-right">
					<button type="button" id="btn_options" class="btn btn-primary">Show options</button>
				</div>
			</div>
		</form>
	</div>
</section>
<script src="js/validate.js"></script>
<?php 
require_once 'inc/footer.php';