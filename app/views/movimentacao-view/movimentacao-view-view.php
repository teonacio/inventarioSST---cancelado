<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">

<?php
// Metodo
	$lista_Itens = $modelo->get_view_form_movItens( chk_array( $parametros, 1 ) );
?>

<div class="camada_4_view">
	
	<table class="table_view">
		<caption><?php echo $this->title; ?></caption>
		<tbody>
		
			<?php $dataMOV = data_time( 'd/m/Y H:i', $lista_Geral['data'] ); ?>
			<?php $nomeUSU = $modelo->get_nome_usu( $lista_Geral['usuario_idusuario'] ); ?>

			<tr>
				<td>
					<p> <b>ID:</b> <?php echo $lista_Geral['idmovimentacaoGeral']; ?> </p>
				</td>
				<td>
					<p> <b>Data:</b> <?php echo $dataMOV .' hs'; ?> </p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p> <b>Usuário:</b> <?php echo $nomeUSU; ?> </p>
				</td>
			</tr>
			
		</tbody>
	</table><br><br>
	
	<table id="paginacao_admin" class="dataTable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Tombamento</th>
				<th>Nome</th>
				<th>Setor Antigo</th>
				<th>Setor Novo</th>
				<th>Status</th>
			</tr>
		</thead>
		
		<tbody>
		
			<?php foreach( $lista_Itens as $lista_matchItens ): ?>
			
				<tr>
			
				<?php $nomeMAT = $modelo->get_nome_material( $lista_matchItens['material_idmaterial'] ); ?>
				<?php $nomeSETORant = $modelo->get_nome_setor( $lista_matchItens['setor_antigo'] ); ?>
				<?php $nomeSETORdep = $modelo->get_nome_setor( $lista_matchItens['setor_novo'] ); ?>
				<?php $status = $modelo->get_nome_status( $lista_matchItens['status_idstatus'] ); ?>
		
					<td> <?php echo $lista_matchItens['material_tomb']; ?> </td>
					<td> <?php echo $nomeMAT; ?> </td>
					<td> <?php echo $nomeSETORant; ?> </td>
					<td> <?php echo $nomeSETORdep; ?> </td>
					<td> <?php echo $status; ?> </td>
				
				</tr>
		
			<?php endforeach; ?>
	
		</tbody>
	</table>
	
	<?php echo '<br>'.$modelo->form_msg; ?>
	
	<?php if( isset( $_SERVER['HTTP_REFERER'] ) ): ?>
	<div class="link_return">
		<b>Caso queira retornar ao histórico de movimentações, clique no link abaixo:</b></br>
		<a href="<?php echo HOME_URI . '/movimentacao-list'; ?>">
			<img src = <?php echo HOME_URI . '/style/_images/back.gif'; ?> alt="Retornar" title="Retornar" />
		</a>
	</div>
	<?php endif; ?>

</div>
</div>
</div>
</div>