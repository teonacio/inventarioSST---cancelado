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
	$lista = $modelo->get_list_setor();
?>

<br><div>
	<form method="post">
		<div class="form_left">
			<div class="form-group">
				<label for="setor">Setor: </label><br>
					<select name="setor" class="select" />
						<option>------ Selecione um setor abaixo ------</option>
						<?php foreach($lista as $fetch_lista) { ?>
							<?php if( $fetch_lista['nomesetor'] == $modelo->form_data['setor_idsetor']['nomesetor'] ) { ?>
								<option selected> <?php echo $fetch_lista['nomesetor']; ?></option>
							<?php } else { ?>
								<option> <?php echo $fetch_lista['nomesetor']; ?></option>
						<?php } } ?>
					</select><br>
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