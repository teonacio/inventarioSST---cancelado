<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4">

	<div class="first_hint">
		<p id="unique"><?php echo $this->title; ?></p>
	</div>

<?php
// Metodos
	$modelo->get_edit_form( chk_array( $parametros, 1 ) );
	$modelo->validate_edit_form( chk_array( $parametros, 1 ) );
	$detalhes_material = $modelo->recupera_detalhes_material( chk_array( $modelo->form_data, 'detalhes_material') );
?>

<br><div>
	<form method="post" action="">
			<div class="form_left">
				<div class="form-group">
					<label for="detalhes">Detalhes do material (máximo 1000 caracteres):</label>
					<textarea name="detalhes" class="form-control" rows="5" style="width: 93%" ><?php echo $detalhes_material; ?></textarea>
				</div>
			
				<input type="submit" value="Salvar" class="button" />
			</div>
		</div>
		
		<?php echo '<br>'.$modelo->form_msg;?>
	</form>
</div>


</div>
</div>
</div>
</div>