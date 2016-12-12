<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php 
// Lista os usuários
	$lista = $modelo->get_mov_list();
?>

<div class="table_ID_warning">
	<center><?php echo $this->title; ?></center>
	<p>A <b>Busca por ID</b> abaixo refere-se a busca apenas pelo ID da movimentação.
					Para buscas mais específicas, consulte a opção <b>Busca</b> no menu <b>Movimentações</b>.</p>
</div>


	<table id="paginacao" class="dataTable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<?php if( empty($lista) ) { echo '<th id="table_THstart">Nao existem movimentações disponíveis no sistema.</th></tr></table>'; } else { ?>
				<th>ID</th>
				<th>Data</th>
				<th>Usuário</th>
				<th>Ações</th>
			</tr>
		</thead>
			
		<tbody>
		
			<?php foreach ($lista as $fetch_movdata): ?>

				<?php $usu = $modelo->get_nome_usu( $fetch_movdata['usuario_idusuario'] ); ?>
				<?php $data = data_time( 'd/m/Y H:i:s', $fetch_movdata['data'] ); ?>
		
				<tr>
				
					<td> <?php echo $fetch_movdata['idmovimentacaoGeral'] ?> </td>
					<td> <?php echo $data.' hs' ?> </td>
					<td> <?php echo $usu['nome'] ?> </td>
				
					<td> 
						<a href="<?php echo HOME_URI ?>/movimentacao-view/index/view/<?php echo $fetch_movdata['idmovimentacaoGeral'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
						</a>
					</td>

				</tr>
			
			<?php endforeach;?>
			
		</tbody>
		
				<?php } ?>
	
	</table>

</div>
</div>
</div>
</div>