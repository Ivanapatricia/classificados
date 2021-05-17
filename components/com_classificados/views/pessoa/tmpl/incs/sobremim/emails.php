<?php 
$itens = $input->get('emails',null, 'array');
if($itens!=null) : ?>
            <table class="table table-striped table-hover" title="<?=  JText::_('COM_SOCIALBLADES_PARCEIROS');?>">
                <caption><?=  JText::_('COM_SOCIALBLADES_PARCEIROS');?></caption>
                <thead>
                <tr>
                    <th id="excluir_col" class="span1">&nbsp;</th>
                    <th id="editar_col" class="span1">&nbsp;</th>
                    <th id="editar_col"><?=  JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL');?></th>
                    <th id="editar_col"><?=  JText::_('COM_CLASSIFICADOS_PESSOA_EXIBIR');?></th>
                </tr>
                </thead>
                <tbody>
<?php

		foreach ($itens as $item) :
			$urlEditar = JRoute::_ ( 'index.php?option=com_socialblade&task=parceiro.parceiro&id='.$item->id.'&Itemid=' . $itemid, false );
			$urlRemover = JRoute::_ ( 'index.php?option=com_socialblade&task=parceiro.apagar&idRem='.$item->id.'&Itemid='. $itemid, false );
            
            $tooltipo = JText::sprintf('COM_CLASSIFICADOS_CRIADO_POR', $item->nomeCriador,
            $this->umOuOutro( $item->ip_criador, $item->ip_criador_proxiado), $this->formatarDataHora($item->data_criado)) ;
        ?>	<tr>
				<td><a href="<?=  $urlEditar;?>"><em class="icon-pencil"></em></a></td>
				<td><a href="javascript: if(confirm('<?= 
					JText::sprintf('COM_CLASSIFICADOS_INFORMACOES_CONFIRMA_REMOCAO', $item->email) ;
				?>')){ window.location='<?=  $urlRemover;?>'}"
				       class="text-error"><em
								class="icon-remove"></em></a></td>
				<td><?= JHTML::tooltip($tooltipo, JText::_('COM_CLASSIFICADOS_INFORMACOES'), $item->email) ?></td>
				<td><?=  $item->exibir ? JText::_('SIM') : JText::_('NAO');?></td>
			</tr>
		<?php	endforeach;?>
                </tbody>
            </table>
<?php else: ?>
<h3><?=  JText::_('COM_CLASSIFICADOS_SEM_ITENS');?></h3>

<?php endif; ?>



