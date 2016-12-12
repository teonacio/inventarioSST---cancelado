<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">
<div id="reclogin_page_input">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Digite abaixo o email de cadastro para recuperamos o seu login:</p>
	</div>

<?php
	// Metodos
	$modelo->validate_register_form();
?>

<br><div>
	<form method="post">
		<div class="form-group" id="reclogin">
			<label for="email" id="reclogin_form_label">E-mail: </label> <input type="text" name="email" placeholder="E-mail de cadastro"
				class="form-control" id="reclogin_input" /><br>
			<input type="submit" value="Enviar" class="button" />
		</div>
		
		<?php echo '<br>'.$modelo->form_msg;?>
		
	</form>
</div>

</div>

<div class="link_return">
	<b>Caso queira retornar a página de login, clique no link abaixo:</b></br>
	<a href="<?php echo HOME_URI . '/login'; ?>">
		<img src = <?php echo HOME_URI . '/style/_images/back.gif'; ?> alt="Retornar" title="Retornar" />
	</a>
</div>

</div>
</div>
</div>
</div>
