
<?php 

$temEmpresa = (isset($empresas) && isset($empresas->itens) && sizeof($empresas->itens) > 0 ) ;
$temProduto = (isset($produtos) && isset($produtos->itens) && sizeof($produtos->itens) > 0 );

if($temEmpresa || $temProduto ) : ?>
<div class="tabbable">
	<ul class="nav nav-tabs">
<?php if($temEmpresa) : ?>
		<li class="active">
			<a href="#empresas" data-toggle="tab"><?= JText::_('COM_CLASSIFICADOS_BUSCA_TAB_EMPRESAS') ?></a>
		</li>
<?php 
	endif;
	if($temProduto) : ?>
		<li<?php if(!$temEmpresa) : ?> class="active"<?php endif;?>>
			<a href="#produtos" data-toggle="tab"><?= JText::_('COM_CLASSIFICADOS_BUSCA_TAB_PRODUTOS') ?></a>
		</li>
<?php endif; ?>
	</ul>
	<div class="tab-content">
<?php if($temEmpresa ) : ?>
		<div class="tab-pane active" id="empresas">
<?php 	foreach ($empresas as $item) : ?>
<?php	endforeach;?>
		</div>
<?php 
	endif;
	if($temProduto ) : ?>
		<div class="tab-pane<?php if(!$temEmpresa ) : ?> active<?php endif;?>" id="produtos">
		</div>
<?php endif; ?>
	</div>


<?php
endif;
?>