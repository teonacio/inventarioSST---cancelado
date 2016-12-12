<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4">

	<div class="first_hint">
		<p style="margin-top: 15px;"><?php echo $this->title; ?></p>
	</div>

<?php
// Metodos
	$modelo->validate_edit_form( $_SESSION['userdata']['idusuario'] );
?>

<br><div>
	<form method="post">
		<div class="form_left">
			<div class="form-group">
				<label for="atual_senha">Atual Senha: </label>
				<input type="password" name="atual_senha" class="form-control" id="user_changepass_input_1" />
			</div>
			<div class="form-group">
				<label for="senha">Nova Senha: </label>
				<input type="password" name="senha" class="form-control" id="user_changepass_input_2" />
			</div>
			<div class="form-group" id="user_changepass_div_3">
				<label for="confirma_senha">Confirmar Senha: </label>
				<input type="password" name="confirma_senha" class="form-control" id="user_changepass_input_3" />
			</div>
			
			<input type="submit" value="Salvar" class="button" />
		</div>
		
		<?php echo '<br>'.$modelo->form_msg;?>
	</form>
</div>


</div>
</div>
</div>
</div>