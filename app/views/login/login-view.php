<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div id="login_page">
<div id="login_page_input">

	<div class="first_hint">
		<p style="margin-top: 15px;">Login</p>
	</div>
	
	<br><div>
		<form method="post">
			<div>
				<div class="form-group">
					<label for="userdata[login]" id="label_login" />Login: </label>
					<input type="text" name="userdata[login]" class="form-control" id="login_input" />
				</div>
				<div class="form-group">
					<label for="userdata[senha]" id="label_login" />Senha: </label>
					<input type="password" name="userdata[senha]" class="form-control" id="login_input" />
				</div>
				<input type="submit" value="Entrar" class="button" id="login_button" />
			
			</div>
		</form>
		
		<img src = <?php echo HOME_URI . '/style/_images/logo_bczm_01.png'; ?> id="logo_bczm_login" />
	</div>
	
	<?php if ( $this->login_error ) { echo $this->login_error.'<br>'; } ?>
	
</div>
	
	<div class="extra_links">
		<div id="extra_links_div_login">
			<p>Caso queira recuperar o login,<br>por favor clique no link abaixo.</p>
			<a href="<?php echo HOME_URI ?>/reclogin" class="reclogin_id">
				<img src = <?php echo HOME_URI . '/style/_images/user.gif'; ?> alt="Recuperar Login" title="Recuperar Login" />
			</a>
		</div>
	</div>


</div>
</div>
</div>
</div>