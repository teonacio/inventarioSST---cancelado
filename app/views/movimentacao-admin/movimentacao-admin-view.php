<?php if ( ! defined('ABSPATH') ) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php 
// Metodos
	$lista = $modelo->get_mat_mov_form(); 
	$lista_setor = $modelo->get_list_setor();
	$lista_status = $modelo->get_list_status();
	$modelo->cancel_mov();
	$modelo->concluic_mov();
	$modelo->del_mat_mov_form();
	$modelo->redirec_pesquisa();
?>

<div class="div_information_mov_admin">
	<p id="div_information_mov_admin_1">
		<?php echo $this->title; ?>
	</p>
	<p id="div_information_mov_admin_2">
		<img src = <?php echo HOME_URI . '/style/_images/user.gif'; ?> >
		Usuário: <?php echo $_SESSION['userdata']['nome']; ?> 
	</p>
	<p id="div_information_mov_admin_3">
		<img src = <?php echo HOME_URI . '/style/_images/calendar.gif'; ?> > 
		Data: <?php $data = data_time('d/m/Y H:i'); echo $data.' hs'; ?> 
	</p><br>
</div>
	
<div>
	<form method="post" action="">
		<table id="paginacao_admin" class="dataTable" cellspacing="0" width="100%">
			<thead>
				<tr>
					<?php if( empty($lista) ) { echo '<th>Nao foram adicionados materiais a movimentacao.</th></tr></table>';} else { ?>
					<th>Deletar</th>
					<th>ID</th>
					<th>Tombamento</th>
					<th>Descrição</th>
					<th>Categoria</th>
					<th>Setores Envolvidos</th>
					<th>Ação</th>
				</tr>
			</thead>
			
			<tbody>
				
				<?php foreach ($lista as $fetch_movdata): ?>
			
					<?php $mat = $modelo->get_all_mat( $fetch_movdata['material_idmaterial'] ) ?>	
					<?php $setor_atual = $modelo->get_nome_setor( $fetch_movdata['setor_antigo'] ); ?>
					<?php $setor_novo = $modelo->get_nome_setor( $fetch_movdata['setor_novo'] ); ?>
					<?php $status = $modelo->get_nome_status( $fetch_movdata['status_idstatus'] ); ?>
					<?php $tipoeqi = $modelo->get_nome_tipoeqi( $mat['tipoequipamento_idtipoequipamento'] ); ?>
		
					<tr>
				
						<td> <input type="checkbox" name="del_mat[<?php echo $mat['idmaterial']; ?>]"> </td>
						<td> <?php echo $mat['idmaterial'] ?> </td>
						<td> <?php echo $mat['tombamento'] ?> </td>
						<td> <?php echo $mat['descricao'] ?> </td>
						<td> <?php echo $tipoeqi['tipo'] ?> </td>
						<td style="text-align: center;">
							<label><b>Setor Antigo: </b></label><select name="setor_atual[<?php echo $mat['idmaterial'] ?>]">
								<option>---------- Selecione um setor ---------</option>
								<?php foreach($lista_setor as $fetch_listasetor): ?>
									<?php if( $fetch_listasetor['nomesetor'] == $setor_atual ) { ?>
										<option selected> <?php echo $fetch_listasetor['nomesetor']; ?> </option>
									<?php } else { ?>
										<option> <?php echo $fetch_listasetor['nomesetor']; ?> </option>
									<?php } ?>
								<?php endforeach; ?>
							</select><br><br>
							<label><b>Setor Novo: </b></label><select name="setor_novo[<?php echo $mat['idmaterial'] ?>]">
								<option>---------- Selecione um setor ---------</option>
								<?php foreach($lista_setor as $fetch_listasetor): ?>
									<?php if( $fetch_listasetor['nomesetor'] == $setor_novo ) { ?>
										<option selected> <?php echo $fetch_listasetor['nomesetor']; ?> </option>
									<?php } else { ?>
										<option> <?php echo $fetch_listasetor['nomesetor']; ?> </option>
									<?php } ?>
								<?php endforeach; ?>
							</select><br><br>
						</td>
						<td>
							<label><b>Ação: </b></label><br><select name="tipo_mov[<?php echo $mat['idmaterial'] ?>]">
								<option>----- Selecione -----</option>
								<?php foreach($lista_status as $fetch_listastatus): ?>
									<?php if( $fetch_listastatus['status'] == $status ) { ?>
										<option selected> <?php echo $fetch_listastatus['status']; ?> </option>
									<?php } else { ?>
										<option> <?php echo $fetch_listastatus['status']; ?> </option>
									<?php } ?>
								<?php endforeach; ?>
							</select>
						</td>

					</tr>
			
				<?php endforeach;?>
			
			</tbody>
		
					<?php } ?>
		
		</table>
		
		<div class="button_mov_admin">
			<input type="submit" name="cancelar" value="Cancelar" class="button" />
			<input type="submit" name="alterar" value="Alterar" class="button" />
			<input type="submit" name="buscar" value="Buscar" class="button" />
			<input type="submit" name="concluir" value="Concluir" class="button" />
		</div>
		
		<br><br>
		
		<?php if( !empty($modelo->form_msg) ) { echo $modelo->form_msg.'<br>'; } ?>
		
</div>
		
</div>
</div>
</div>
</div>