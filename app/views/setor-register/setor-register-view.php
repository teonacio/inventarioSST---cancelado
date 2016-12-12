<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Por favor, preencha os dados abaixo para inserir o novo setor no sistema:</p>
	</div>

<?php
// Metodos
	$modelo->validate_register_form();
?>

<br><div>
	<form method="post">
		<div class="form_left">
			<div class="form-group">
				<label for="nomesetor">Nome: </label>
				<input type="text" name="nomesetor" class="form-control" id="setor_register_input_1" />
			</div>
			<div class="form-group">
				<label for="codigosetor">Código: </label>
				<input type="text" name="codigosetor" class="form-control" id="setor_register_input_2" />
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
