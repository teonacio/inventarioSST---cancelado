<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Por favor, preencha os dados abaixo para inserir a nova categoria de materiais ao sistema:</p>
	</div>

<?php
// Metodos
	$modelo->validate_register_form();
?>

<br><div>
	<form method="post">
		<div class="form_left">
			<div class="form-group">
				<label for="tipo">Nome: </label>
				<input type="text" name="tipo"  class="form-control" id="tipoequipamento_register_input" />
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
