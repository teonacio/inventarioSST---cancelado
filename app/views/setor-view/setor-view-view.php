<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">

<?php
// Metodos
	$lista_mat_setitens = $modelo->get_all_setitens( chk_array( $parametros, 1 ) );

// Verifica a utilizacao do setor
	$verifica = $modelo_2->ver_usado_setor( $lista['idsetor'] );
?>

<div class="camada_4_view">

	<?php if( $verifica == true ): ?>
		<div class='warning_exists'>
			<p style="margin-left:10px;">
				<img src = <?php echo HOME_URI . '/style/_images/warning.png'; ?> />
				&nbsp&nbspNOTA: Esse setor não pode ser deletado por já ter sido cadastrado em uma (ou mais) movimentações.
			</p>
		</div><br>
	<?php endif; ?>

	<table class="table_view">
		<caption><?php echo $this->title ?></caption>
		<tbody>

			<tr>
				<td>
					<p> <b>ID:</b> <?php echo $lista['idsetor']; ?> </p>
				</td>
				<td>
					<p> <b>Código:</b> <?php echo $lista['codigosetor']; ?> </p>
				</td>
			</tr>
			<tr>
				<td>
					<p> <b>Nome:</b> <?php echo $lista['nomesetor']; ?> </p>
				</td>
			</tr>
			
		</tbody>
	</table><br><br>
	
	<table id="paginacao_admin" class="dataTable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<?php if( empty($lista_mat_setitens) ) { echo '<th>Nao existem materiais instalados nesse setor.</th></tr></table>';} else { ?>
				<th>ID</th>
				<th>Tombamento</th>
				<th>Descrição</th>
				<th>Categoria</th>
			</tr>
		</thead>
			
		<tbody>
		
			<?php foreach ($lista_mat_setitens as $fetch_lmatdata): ?>

				<?php $dados_mat = $modelo->get_all_mat( $fetch_lmatdata['material_idmaterial'] ); ?>
				<?php $tipoeqi = $modelo->get_nome_tipoeqi( $dados_mat['tipoequipamento_idtipoequipamento'] ); ?>
		
				<tr>
				
					<td> <?php echo $dados_mat['idmaterial'] ?> </td>
					<td> <?php echo $dados_mat['tombamento']; ?> </td>
					<td> <?php echo $dados_mat['descricao']; ?> </td>
					<td> <?php echo $tipoeqi; ?> </td>
					
					</td>

				</tr>
			
			<?php endforeach;?>
			
		</tbody>
		
				<?php } ?>
	
	</table>
	
<div class="link_return">
	<b>Caso queira retornar ao histórico de setores, clique no link abaixo:</b></br>
	<a href="<?php echo HOME_URI . '/setor-list'; ?>">
		<img src = <?php echo HOME_URI . '/style/_images/back.gif'; ?> alt="Retornar" title="Retornar" />
	</a>
</div>

</div>
</div>
</div>
</div>