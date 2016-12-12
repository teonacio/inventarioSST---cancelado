<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Dados do setor</p>
	</div>

<?php
// Metodos
	$modelo->validate_edit_form( chk_array( $parametros, 1 ) );
	$modelo->get_edit_form( chk_array( $parametros, 1 ) );
?>

<br><div>
	<form method="post">
		<div class="form_left">
			<div class="form-group">
				<label for="codigosetor">Código: </label> <input type="text" name="codigosetor" value="<?php 
					echo htmlentities( chk_array( $modelo->form_data, 'codigosetor') );
					?>" class="form-control" id="setor_edit_input_1" />
			</div>
			<div class="form-group">
				<label for="nomesetor">Descrição: </label> <input type="text" name="nomesetor" value="<?php 
					echo htmlentities( chk_array( $modelo->form_data, 'nomesetor') );
					?>" class="form-control" id="setor_edit_input_2" />
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