<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php 
// Metodos
	$lista = $modelo->get_user_list();
	$modelo->del_user( $parametros );
?>

<div class="table_ID_warning">
	<center><?php echo $this->title; ?></center>
	<p><b>NOTA: </b>A <b>Busca por ID</b> abaixo refere-se a busca apenas pelo ID do usuário.
					Para buscas mais específicas, consulte a opção <b>Busca</b> no menu <b>Usuários</b>.</p>
</div>

<table id="paginacao" class="dataTable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>Nome</th>
			<th>Setor</th>
			<th>Ações</th>
		</tr>
	</thead>
			
	<tbody>
			
		<?php foreach ($lista as $fetch_userdata): ?>

			<?php $setor = $modelo->get_nome_setor( $fetch_userdata['setor_idsetor'] ); ?>
		
			<tr>
				
				<td> <?php echo $fetch_userdata['idusuario'] ?> </td>
				<td> <?php echo $fetch_userdata['nome'] ?> </td>
				<td> <?php echo $setor['nomesetor'] ?> </td>
				
				<td> 
					<a href="<?php echo HOME_URI ?>/user-view/index/view/<?php echo $fetch_userdata['idusuario'] ?>">
						<img src = <?php echo HOME_URI . '/style/_images/details.gif'; ?> alt="Visualizar" title="Visualizar" />&nbsp;
					</a>
					
					 <!-- Caso o usuario seja SST -->
					<?php if($_SESSION['userdata']['idusuario'] == 1): ?>
						<a href="<?php echo HOME_URI ?>/user-edit/index/edit/<?php echo $fetch_userdata['idusuario'] ?>">
							<img src = <?php echo HOME_URI . '/style/_images/edit.gif'; ?> alt="Editar" title="Editar" />&nbsp;
						</a>
						
						<!-- Note que o usuario SST nao pode deletar a si proprio -->
						<?php if($fetch_userdata['idusuario'] != 1): ?>
							<a href="<?php echo HOME_URI ?>/user-list/index/del/<?php echo $fetch_userdata['idusuario'] ?>">
								<img src = <?php echo HOME_URI . '/style/_images/delete.gif'; ?> alt="Deletar" title="Deletar" />
							</a>
						<?php endif; ?>
					<?php endif; ?>
					
				</td>

			</tr>
			
		<?php endforeach;?>
			
	</tbody>
</table>

</div>
</div>
</div>
</div>