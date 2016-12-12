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
	$modelo->validate_edit_form( chk_array( $parametros, 1 ) );
	$modelo->get_edit_form( chk_array( $parametros, 1 ) );
	$lista = $modelo->get_list_tipoeqi();
	$detalhes_material = $modelo->recupera_detalhes_material( chk_array( $modelo->form_data, 'detalhes_material') );
?>

<br><div>
	<form method="post" action="">
		<div class="form_left">
			<div class="form-group">
				<label for="tombamento">Tombamento: </label> <input type="text" name="tombamento" value="<?php 
					echo htmlentities( chk_array( $modelo->form_data, 'tombamento') );
					?>" class="form-control" id="material_edit_input_01" />
			</div>
			<div class="form-group" id="material_edit_div_2">
				<label for="tipoeqi">Categoria: </label><br>
					<select name="tipoeqi" class="select" />
						<option>------ Selecione uma categoria abaixo ------</option>
						<?php foreach($lista as $fetch_lista) { ?>
							<?php if( $fetch_lista['tipo'] == $modelo->form_data['tipoequipamento_idtipoequipamento']['tipo'] ) { ?>
								<option selected> <?php echo $fetch_lista['tipo']; ?></option>
							<?php } else { ?>
								<option> <?php echo $fetch_lista['tipo']; ?></option>
						<?php } } ?>
					</select><br>
			</div>
			<div class="form-group">
				<label for="descricao">Descrição: </label> <input type="text" name="descricao" value="<?php 
					echo htmlentities( chk_array( $modelo->form_data, 'descricao') );
					?>" class="form-control" id="material_edit_input_02" />
			</div>
			<div class="form-group">
				<label for="detalhes">Detalhes do material (máximo 1000 caracteres):</label>
				<textarea name="detalhes" class="form-control" rows="5" style="width: 93%" ><?php echo $detalhes_material ?></textarea>
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