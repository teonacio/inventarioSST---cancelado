<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Por favor, preencha os dados abaixo para inserir o novo material no sistema:</p>
	</div>

<?php
// Metodos
	$modelo->validate_register_form();
	$lista = $modelo->get_list_tipoeqi();
?>

<br><div>
	<form method="post">
		<div class="form_left">
			<div class="form-group">
				<label for="tombamento">Tombamento: </label>
				<input type="text" name="tombamento" class="form-control" id="material_register_input_1" />
			</div>
			<div class="form-group" id="material_register_div_2">
				<label for="tipoeqi">Categoria: </label><br>
					<select name="tipoeqi" class="select" />
						<option selected>------ Selecione uma categoria abaixo ------</option>
						<?php foreach($lista as $fetch_lista): ?>
								<option> <?php echo $fetch_lista['tipo']; ?> </option>
						<?php endforeach; ?>
					</select>
			</div>
			<div class="form-group">
				<label for="nome">Descrição (nome do material): </label>
				<input type="text" name="nome" class="form-control" id="material_register_input_2" />
			</div>
			<div class="form-group">
				<label for="detalhes">Detalhes do material (máximo 1000 caracteres):</label>
				<textarea name="detalhes" class="form-control" rows="5" style="width: 93%"></textarea>
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