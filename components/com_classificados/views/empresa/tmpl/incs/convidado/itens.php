
<table title="<?= JText::_('COM_CLASSIFICADOS_CONVITE_TABELA_CONVIDADOS') ?>" class="table table-striped table-hover">
    <caption><?= JText::_('COM_CLASSIFICADOS_CONVITE_TABELA_CONVIDADOS') ?></caption>
    <thead>
        <tr>
            <th id="cancelar_col" class="span1">&nbsp;</th>
            <th><?php echo JText::_('COM_CLASSIFICADOS_CONVITE_EMAIL');?></th>
            <th><?php echo JText::_('COM_CLASSIFICADOS_CONVITE_DATANEGADO');?></th>
            <th><?php echo JText::_('COM_CLASSIFICADOS_CONVITE_DATAACEITE');?></th>
        </tr>
    </thead>
    <<tbody>
<?php foreach ($itens as $item) : 
    $urlRemover = JRoute::_ ( 'index.php?option=com_socialblade&task=convite.cancelar&idRem=' . $item->uuid . '&Itemid=' . $itemid, false );
?>
        <tr>
            <td><a href="javascript: if(confirm('<?php echo JText::sprintf('COM_CLASSIFICADOS_BTN_CANCELAR', $iten->nome) ; ?>')){ window.location='<?php echo $urlRemover;?>'}"
				       class="text-error"><em  class="icon-remove"></em></a></td>
            <td><?= $item->email?></td>
            <td><?= $item->data_negado?></td>
            <td><?= $item->data_aceite?></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>