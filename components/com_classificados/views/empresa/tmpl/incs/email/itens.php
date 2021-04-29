<table title="<?= JText::_('COM_CLASSIFICADOS_EMAILEMPRESA__TABELA_EMAILS') ?>" class="table table-striped table-hover">
    <caption><?= JText::_('COM_CLASSIFICADOS_EMAILEMPRESA__TABELA_EMAILS') ?></caption>
        <thead>
        <tr>
            <th id="cancelar_col" class="span1">&nbsp;</th>
            <th id="cancelar_col" class="span1">&nbsp;</th>
            <th><?php echo JText::_('COM_CLASSIFICADOS_EMAILEMPRESA__CAMPO_EXIBIR');?></th>
            <th><?php echo JText::_('COM_CLASSIFICADOS_EMAILEMPRESA__CAMPO_CONTATO');?></th>
            <th><?php echo JText::_('COM_CLASSIFICADOS_EMAILEMPRESA__CAMPO_EMAIL');?></th>
        </tr>
    </thead>
    <<tbody>
<?php foreach ($itens as $item) : 
    $urlRemover = JRoute::_ ( 'index.php?option=com_socialblade&task=convite.remover&1='.$token .'&idRem=' . $item->uuid . '&Itemid=' . $itemid, false );
    $tooltipo = JText::sprintf('COM_CLASSIFICADOS_CRIADO_POR', $item->nomeCriador, $item->ipCriador, $item->dataCriacao->format('d/m/Y H:i') ) .
    ( $item->nomeAlterador != null &&  $item->nomeAlterador != '' ? 
        JText::sprintf('COM_CLASSIFICADOS_ALTERADO_POR', $item->nomeAlterador,$item->ipAlterador, $item->dataAlterado->format('d/m/Y H:i')) :  '');
?>
        <tr>
            <td><a href="javascript: if(confirm('<?php echo JText::sprintf('COM_CLASSIFICADOS_BTN_REMOVER', $iten->email) ; ?>')){ window.location='<?php echo $urlRemover;?>'}"
				       class="text-error"><?= JText::_('COM_CLASSIFICADOS_BTN_REMOVER') ?></a></td>
            <td><?= JHTML::tooltip($tooltipo, JText::_('COM_CLASSIFICADOS_INFORMACOES'), $item->exibir ? JText::_('SIM') : JText::_('NAO')) ?></td>
            <td><?= JHTML::tooltip($tooltipo, JText::_('COM_CLASSIFICADOS_INFORMACOES'), $item->contato  ? JText::_('SIM') : JText::_('NAO')) ?></td>
            <td><?= JHTML::tooltip($tooltipo, JText::_('COM_CLASSIFICADOS_INFORMACOES'), $item->email) ?></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>
