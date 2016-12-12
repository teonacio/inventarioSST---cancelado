<?php if ( ! defined('ABSPATH')) exit; ?>

<?php if ( isset($modelo) && $this->login_required && ! $this->logged_in ) return; ?>

<?php if( isset($_SESSION['userdata']) ): ?>
<div id='cssmenu' />
	<ul>
		<!-- <li><a href="<?php echo HOME_URI;?>">Home</a></li> -->
		<!-- <li><a href="<?php echo HOME_URI;?>/login/">Login</a></li> -->
		<li><a href="#"> <img src = <?php echo HOME_URI . '/style/_images/user.gif'; ?> > &nbspUsuários </a>
			<ul>
				<li><a href="<?php echo HOME_URI;?>/user-register/" /> <img src = <?php echo HOME_URI . '/style/_images/new.gif'; ?> > &nbspNovo Usuário </a></li>
				<li><a href="<?php echo HOME_URI;?>/user-list/"> <img src = <?php echo HOME_URI . '/style/_images/historico.png'; ?> > &nbspHistórico</a></li>
				<li><a href="<?php echo HOME_URI;?>/user-busca/"> <img src = <?php echo HOME_URI . '/style/_images/search.png'; ?> > &nbspBusca </a></li>
				<li><a href="<?php echo HOME_URI;?>/user-changepass/"> <img src = <?php echo HOME_URI . '/style/_images/changepass.png'; ?> > &nbspAlterar Senha </a></li>
			</ul>
		</li>
		<li><a href="#"> <img src = <?php echo HOME_URI . '/style/_images/materials.gif'; ?> > &nbspMateriais</a>
			<ul>
				<li><a href="#">Categorias</a>
					<ul>
						<li><a href="<?php echo HOME_URI;?>/tipoequipamento-register/" /> <img src = <?php echo HOME_URI . '/style/_images/new.gif'; ?> > &nbspNova Categoria </a></li>
						<li><a href="<?php echo HOME_URI;?>/tipoequipamento-list/"> <img src = <?php echo HOME_URI . '/style/_images/historico.png'; ?> > &nbspHistórico</a></li>
					</ul>
				</li>
				<li><a href="<?php echo HOME_URI;?>/material-register/"> <img src = <?php echo HOME_URI . '/style/_images/new.gif'; ?> > &nbspNovo Material </a></li>
				<li><a href="#">Histórico</a>
					<ul>
						<li><a href="<?php echo HOME_URI;?>/material-list-geral/" /> <img src = <?php echo HOME_URI . '/style/_images/letter_A.png'; ?> > &nbspPor Nome</a></li>
						<li><a href="<?php echo HOME_URI;?>/material-list/"> <img src = <?php echo HOME_URI . '/style/_images/number_1.png'; ?> > &nbspPor Tombamento</a></li>
					</ul>
				</li>
				<li><a href="<?php echo HOME_URI;?>/material-busca/"> <img src = <?php echo HOME_URI . '/style/_images/search.png'; ?> > &nbspBusca </a></li>
			</ul>
		</li>
		<li><a href="#"> <img src = <?php echo HOME_URI . '/style/_images/movimentacoes.png'; ?> > Movimentações</a>
			<ul>
				<li><a href="<?php echo HOME_URI;?>/movimentacao-pesquisa/"> <img src = <?php echo HOME_URI . '/style/_images/new.gif'; ?> > &nbspNova Movimentação </a></li>
				<li><a href="<?php echo HOME_URI;?>/movimentacao-list/"> <img src = <?php echo HOME_URI . '/style/_images/historico.png'; ?> > &nbspHistórico </a></li>
				<li><a href="<?php echo HOME_URI;?>/movimentacao-busca/"> <img src = <?php echo HOME_URI . '/style/_images/search.png'; ?> > &nbspBusca </a></li>
			</ul>
		</li>
		<li><a href="#"> <img src = <?php echo HOME_URI . '/style/_images/sectors.png'; ?> > Setores</a>
			<ul>
				<li><a href="<?php echo HOME_URI;?>/setor-register/"> <img src = <?php echo HOME_URI . '/style/_images/new.gif'; ?> > &nbspNovo Setor </a></li>
				<li><a href="<?php echo HOME_URI;?>/setor-list/"> <img src = <?php echo HOME_URI . '/style/_images/historico.png'; ?> > &nbspHistórico </a></li>
				<li><a href="<?php echo HOME_URI;?>/setor-busca/"> <img src = <?php echo HOME_URI . '/style/_images/search.png'; ?> > &nbspBusca </a></li>
			</ul>
		</li>
		<li><a href="<?php echo HOME_URI;?>/logout"> <img src = <?php echo HOME_URI . '/style/_images/exit.gif'; ?> > &nbspLogout (<?php echo $_SESSION['userdata']['login'] ?>)</a></li>
	</ul>
</div>
<?php endif; ?>