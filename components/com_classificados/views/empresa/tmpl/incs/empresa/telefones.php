<?php 
/*JFactory::getDocument()->addScriptDeclaration('
jQuery(document).ready(function(){
    jQuery("#btnBuscar").click(function(){
        window.location=("'   . JRoute::_( 'index.php?option=com_classificados&task=empresatelefone.telefone&Itemid='.$itemid , false ) . '");

    });
});');*/


$itens = $input->get('telefones',null, 'array');
if($itens!=null) : ?>
            <table class="table table-striped table-hover" title="<?=  JText::_('COM_CLASSIFICADOS_EMPRESA_LISTA_TELEFONES');?>">
                <caption><?=  JText::_('COM_CLASSIFICADOS_EMPRESA_LISTA_TELEFONES');?></caption>
                <thead>
                <tr>
                    <th id="excluir_col" class="span1">&nbsp;</th>
                    <th id="editar_col" class="span1">&nbsp;</th>
                    <th id="editar_col"><?=  JText::_('COM_CLASSIFICADOS_EMPRESA_TELEFONE');?></th>
                    <th id="editar_col"><?=  JText::_('COM_CLASSIFICADOS_EMPRESA_EXIBIR_TELEFONE');?></th>
                </tr>
                </thead>
                <tbody>
<?php

		foreach ($itens as $item) :
			$urlEditar = JRoute::_ ( 'index.php?option=com_classificados&task=empresatelefone.telefone&id='.$item->id.'&Itemid=' . $itemid, false );
			$urlRemover = JRoute::_ ( 'index.php?option=com_classificados&task=empresatelefone.apagar&id='.$item->id.'&Itemid='. $itemid, false );
            
            $tooltipo = JText::sprintf('COM_CLASSIFICADOS_CRIADO_POR', $item->nomeCriador,
            $this->umOuOutro( $item->ip_criador, $item->ip_criador_proxiado), $this->formatarDataHora($item->data_criado)); 
            
            if($item->nomeAlterador != null && $item->nomeAlterador != ''){    
                $tooltipo .= 
                JText::sprintf('COM_CLASSIFICADOS_ALTERADO_POR', $item->nomeAlterador,
                $this->umOuOutro( $item->ip_alterador, $item->ip_alterador_proxiado), $this->formatarDataHora($item->data_alterado));
            }
        ?>	<tr>
				<td><a href="<?=  $urlEditar;?>" title="<?= JText::_('COM_CLASSIFICADOS_BTN_EDITAR_TEXT') ?>"><em class="icon-pencil"></em></a></td>
				<td><a href="javascript: if(confirm('<?= 
					JText::sprintf('COM_CLASSIFICADOS_INFORMACOES_CONFIRMA_REMOCAO', $item->ddd . ' ' .  $item->telefone)  ;
				?>')){ window.location='<?=  $urlRemover;?>'}"
				       class="text-error"  title="<?= JText::_('COM_CLASSIFICADOS_BTN_REMOVER_TEXT') ?>"><em
								class="icon-remove"></em></a></td>
                <td><?= JHTML::tooltip($tooltipo, JText::_('COM_CLASSIFICADOS_INFORMACOES'), '', '('. $item->ddd . ') ' . $item->telefone ) ?></td>
				<td><?=  $item->exibir ? JText::_('SIM') : JText::_('NAO');?></td>
			</tr>
		<?php	endforeach;?>
                </tbody>
            </table>
<?php else: ?>
<h3><?=  JText::_('COM_CLASSIFICADOS_SEM_ITENS');?></h3>

<?php endif; ?>

<div class="btn-toolbar text-right">
    <div class="btn-group">
        <a type="button" id="btnBuscar" class="btn btn-success" href="<?= JRoute::_( 'index.php?option=com_classificados&task=empresatelefone.telefone&Itemid='.$itemid , false )  ?>"><?=
        JText::_('COM_CLASSIFICADOS_BTN_NOVO') . JText::_('COM_CLASSIFICADOS_BTN_NOVO_TEXT') ?></a>
    </div>
</div>

