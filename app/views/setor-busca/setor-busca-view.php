<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

	<div class="first_hint">
		<p id="first"><?php echo $this->title; ?></p>
		<p>Por favor, preencha os dados abaixo para realizar a busca:</p>
	</div>

<?php
// Metodos
	$lista = $modelo->validate_busca_form();
?>

<div class="border_blue">

	<br><div>
		<form method="post">
			<div class="form_left">
				<div class="form-group" id="setor_busca_div_1">
					<label for="codigosetor">Código: </label>
					<input type="text" name="codigosetor" class="form-control" id="setor_busca_input_2" />
				</div>
				<div class="form-group">
					<label for="nomesetor">Nome: </label>
					<input type="text" name="nomesetor" class="form-control" id="setor_busca_input_3" />
				</div>
			
				<input type="submit" value="Buscar" class="button" />
			</div>
		
			<?php echo '<br>'.$modelo->form_msg;?>
		</form>
	</div>

</div>

<div>
<?php if( is_array($lista) ): ?>

	<br><table id="paginacao_busca_view" class="dataTable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th id="table_THstart">ID</th>
			<th>Nome</th>
			<th>Setor</th>
			<th>Ações</th>
		</tr>
	</thead>
			
	<tbody>
			
		<?php foreach ($lista as $fetch_userdata): ?>

			<tr>
				
				<td> <?php echo $fetch_userdata['idsetor'] ?> </td>	
				<td> <?php echo $fetch_userdata['codigosetor'] ?> </td>
				<td> <?php echo $fetch_userdata['nomesetor'] ?> </td>
				
				<td> 
					<a href="<?php echo HOME_URI ?>/setor-view/index/view/<?php echo $fetch_userdata['idsetor'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
					</a>
					<a href="<?php echo HOME_URI ?>/setor-edit/index/edit/<?php echo $fetch_userdata['idsetor'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/edit.gif'; ?> alt="Editar" title="Editar" />&nbsp;
					</a>
					<a href="<?php echo HOME_URI ?>/setor-list/index/del/<?php echo $fetch_userdata['idsetor'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/delete.gif'; ?> alt="Deletar" title="Deletar" />
					</a>
					
				</td>

			</tr>
			
		<?php endforeach;?>
			
	</tbody>
</table>

<?php endif; ?>
</div>

</div>
</div>
</div>
</div>