<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="camada_1">
<div class="camada_2">
<div class="camada_3">
<div class="camada_4_exec">

<?php 
// Metodos
	$lista = $modelo->get_tipoeqi_list();
	$modelo->del_tipoeqi( $parametros );
?>

<div class="table_ID_warning">
	<center><?php echo $this->title; ?></center>
	<p><b>NOTA 1: </b>A <b>Busca por ID</b> abaixo refere-se a busca apenas pelo ID da classificação do material. Outras buscas nao estao disponíveis.</p>
	<p><b>NOTA 2: </b> Categorias que não possuem o ícone de deletar ao lado de seus nomes não podem ser deletadas por já terem sido incluidas
		em algum material cadastrado.</p>
</div>

<div>
	<table id="paginacao" class="dataTable" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
				<th>Deletar</th>
			</tr>
		</thead>
			
		<tbody>
			
			<?php foreach ($lista as $fetch_tipoeqidata): ?>
		
				<tr>
				
					<!-- Verifica se a categoria ja foi utilizada em algum material -->
					<?php $verifica = $modelo->ver_usado_tipo( $fetch_tipoeqidata['idtipoequipamento'] ); ?>
				
					<td class="dt-body-center"> <?php echo $fetch_tipoeqidata['idtipoequipamento'] ?> </td>
					<td class="dt-body-center"> <?php echo $fetch_tipoeqidata['tipo'] ?> </td>
				
					<td class="dt-body-center">
						<!-- Categorias ja adicionadas em algum material nao podem ser deletadas -->
						<?php if( $verifica == false ): ?>
							<a href="<?php echo HOME_URI ?>/tipoequipamento-list/index/del/<?php echo $fetch_tipoeqidata['idtipoequipamento'] ?>">
								<img src = <?php echo HOME_URI . '/style/_images/delete.gif'; ?> alt="Deletar" title="Deletar" />
							</a>
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
</div>