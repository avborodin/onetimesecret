<?php
require 'src/crypto.php';
use Themeart\Crypto\Crypto;

session_start();
	
$id = $_SESSION['id'];
$key = $_SESSION['key'];

if(empty($id) && empty($key)){
	header('location: /');
	exit;
}

if(!empty($_POST['is_show'])){
	

	if(!empty($_POST['is_check'])){
		$key = $_POST['key'];
		$file = "list/".$id.'#'.$key;
		if (!file_exists($file)) {
			echo "error";
			exit;
		} 
	}
	if(!empty($key)){
		$crypto = new Crypto();
		$file = "list/".$id.'#'.$key;

		$fh = fopen($file, "r") or die("Unable to open file!");
		$data = fread($fh,filesize($file));
		fclose($fh);
		unlink($file);

		$txt_decrypt = $crypto->decrypt($data,$key);
	}

	$html = '
	<div class="container">
		<div class="form-group">
			<label for="contents" class="font-weight-bold">Contents of note:</label>';

	if(!empty($key)){
		$html .= '<textarea class="form-control" rows="5">'.$txt_decrypt.'</textarea>';
		$html .= '<p>This note has been removed. If you need to save the text, copy it before closing this window.</p>';
	}else{
		$html .= '<p>Do not close or reload this page or you will lose the note forever</p>';
		$html .= '<input type="password" class="form-control pwd" id="pwd" placeholder="Enter password" name="pwd">
				  <div id="pwd_invalid" class="invalid-feedback">Wrong password entered. Please try again.</div>';
	}
	$html .= '</div>';

	if(empty($key)){
		$html .= '<button type="button" onclick="btn_continue(1);" class="btn btn-primary">Сontinue</button>';
	}

	$html .= '</div>';
	echo $html;
	
	if(!empty($key)){
		session_destroy();
	}
	exit;
}



require_once 'inc/header.php';
?>
<section class="contents">
	<div class="container">
		<div class="form-group">
			<label for="contents" class="font-weight-bold">Read and destroy?</label>
			<p>Are you going to read and destroy the note with id <?php echo $id;?>?</p>
			<?php if(!empty($key)){ ?>
				<p>You will be prompted to enter a password to read the note. If you don't have one, ask the person who sent you the note before continuing.</p>
			<?php } ?>
		</div>
		<button type="button" onclick="btn_continue(0);" class="btn btn-primary">Yes, show me the note</button>
	</div>
</section>
<script>
function btn_continue(is_check)
{
	var key = '<?php echo $key;?>';
	if(is_check){
		key = $('#pwd').val();
	}
	$.ajax({
		url: 'hidden.php',
		method: 'post',
		dataType: 'html',
		data: {'is_show':1,'is_check':is_check,'key': key},
		success: function(data){
			if(is_check){
				if(data == 'error'){
					$('#pwd').addClass('is-invalid');
					$('#pwd_invalid').show();
					return false;
				}
			}
			$('.contents').html(data);
		}
	});
}
</script>
<?php
require_once 'inc/footer.php';