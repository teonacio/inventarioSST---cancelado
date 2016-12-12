<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php
// Metodos
	$lista = $modelo->validate_busca_form();
	$execute = $modelo->ver_mov_ini();
	$modelo->add_mat_admin();
	$adic = $modelo->get_mat_mov_form();
?>

<div class="border_blue">
	<?php if( empty($execute) ){ ?>
		<div class="first_hint">
			<p id="first">Nova Movimentacao</p>
			<p>Para iniciar uma movimentação, preencha o espaço correspondente a pesquisa desejada:</p>
		</div>
	<?php } else { ?>
		<div class="first_hint">
			<p id="first">Editar Movimentacao</p>
			<p>Para adicionar um material a movimentação, preencha o espaço correspondente a pesquisa desejada:</p>
		</div>
	<?php } ?>

	<br><div>
		<form method="post">
			<div class="form_left">
				<div class="form-group">
					<label for="pesqID">Pesquisa por ID</label>
					<input type="text" name="pesqID" placeholder="ID" class="form-control" id="mov_pesquisa_input_01" />
				</div>
				<div class="form-group">
					<label for="pesqID">Pesquisa por tombamento</label>
					<input type="text" name="tombID" placeholder="Tombamento" class="form-control" id="mov_pesquisa_input_02" />
				</div>
				<div class="form-group">
				<label for="pesqID">Pesquisa por nome</label>
					<input type="text" name="nomeID" placeholder="Nome" class="form-control" id="mov_pesquisa_input_03" />
				</div>

				<div class="form-group">
					<input type="submit" value="Pesquisar" class="button" />
				</div>
			</div>
		
			<?php if( !empty($modelo->form_msg) ) { echo '<br>'.$modelo->form_msg; }?>
		
			<?php if( !empty($execute) ){ ?>
				<br><div class="warning" id="mov_s_w">
					<p style="margin-left:10px;">
						<img src = <?php echo HOME_URI . '/style/_images/warning.png'; ?> />
						&nbsp&nbspVocê já iniciou uma movimentação. Para mais detalhes <a href="<?php echo HOME_URI ?>/movimentacao-admin">Clique Aqui</a>
					</p>
				</div>
			<?php } else { if( chk_array( $parametros, 0 ) == 'cancelado' ){ ?>
				<br><div class="success" id="mov_s_w">
					<p style="margin-left:10px;">
						<img src = <?php echo HOME_URI . '/style/_images/sim.gif'; ?> />
						&nbsp&nbspMovimentação cancelada com sucesso.
					</p>
			
				</div>
			<?php }} ?>
		</form>
	</div>

</div><br>

<div>
<?php if( is_array($lista) ): ?>

	<form method="post" action="">
	<?php $flag = 0; // Flag para adicionar materiais ?>

	<table id="paginacao_busca_view" class="dataTable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Adicionar</th>
				<th>ID</th>
				<th>Tombamento</th>
				<th>Descrição</th>
				<th>Categoria</th>
			</tr>
		</thead>
			
		<tbody>
			
			<?php foreach ($lista as $fetch_userdata): ?>
		
				<?php $tipoeqi = $modelo->get_nome_tipoeqi( $fetch_userdata['tipoequipamento_idtipoequipamento'] ); ?>
				<?php $busca = $modelo->ver_add_atmov( $fetch_userdata['idmaterial'], $adic ); ?>

				<tr>
				
					<td>
						<?php if( $busca === 1 ) { // Caso o material ja tenha sido adicionado a movimentacao - Impede que seja adicionado novamente ?>
							ADICIONADO
						<?php } else { $flag = 1; ?>
							<input type="checkbox" name="mat_add[<?php echo $fetch_userdata['idmaterial']; ?>]">
						<?php } ?>
					</td>
					<td> <?php echo $fetch_userdata['idmaterial'] ?> </td>
					<td> <?php echo $fetch_userdata['tombamento'] ?> </td>
					<td> <?php echo $fetch_userdata['descricao'] ?> </td>
					<td> <?php echo $tipoeqi['tipo'] ?> </td>

				</tr>
			
			<?php endforeach;?>
			
		</tbody>
	</table>
	
	<?php if( $flag == 1 ) { ?>
		<div class="button_mov_admin">
			<input type="submit" value="Adicionar" class="button" id="button_mov_pesq" />
		</div>
	<?php } ?>

	</form>

<?php endif; ?>
	
</div>

</div>
</div>
</div>
</div>
