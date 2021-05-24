<?php 
/*JFactory::getDocument()->addScriptDeclaration('
jQuery(document).ready(function(){
    jQuery("#btnBuscar").click(function(){
        window.location=("'   . JRoute::_( 'index.php?option=com_classificados&task=empresamail.email&Itemid='.$itemid , false ) . '");

    });
});');*/


$itens = $input->get('emails',null, 'array');
if($itens!=null) : ?>
            <table class="table table-striped table-hover" title="<?=  JText::_('COM_CLASSIFICADOS_EMPRESA_LISTA_EMAILS');?>">
                <caption><?=  JText::_('COM_CLASSIFICADOS_EMPRESA_LISTA_EMAILS');?></caption>
                <thead>
                <tr>
                    <th id="excluir_col" class="span1">&nbsp;</th>
                    <th id="editar_col" class="span1">&nbsp;</th>
                    <th id="editar_col" class="span1">&nbsp;</th>
                    <th id="editar_col" class="text-center"><?=  JText::_('COM_CLASSIFICADOS_EMPRESA_EMAIL') ?></th>
                    <th id="editar_col" class="text-center"><?=  JText::_('COM_CLASSIFICADOS_EMPRESA_EXIBIR_EMAIL') ?></th>
                    <th id="editar_col" class="text-center"><?=  JText::_('COM_CLASSIFICADOS_EMPRESA_EMAIL_VALIDADO') ?></th>
                </tr>
                </thead>
                <tbody>
<?php

		foreach ($itens as $item) :
			$urlEditar = JRoute::_ ( 'index.php?option=com_classificados&task=empresaemail.email&id='.$item->id.'&Itemid=' . $itemid, false );
			$urlRemover = JRoute::_ ( 'index.php?option=com_classificados&task=empresaemail.apagar&id='.$item->id.'&Itemid='. $itemid, false );
			$urlPrincipal = JRoute::_ ( 'index.php?option=com_classificados&task=empresaemail.principal&id='.$item->id.'&Itemid='. $itemid, false );


            $tooltipo = JText::sprintf('COM_CLASSIFICADOS_CRIADO_POR', $item->nomeCriador,
            $this->umOuOutro( $item->ip_criador, $item->ip_criador_proxiado), $this->formatarDataHora($item->data_criado)); 
            
            if($item->nomeAlterador != null && $item->nomeAlterador != ''){    
                $tooltipo .=  
                JText::sprintf('COM_CLASSIFICADOS_ALTERADO_POR', $item->nomeAlterador,
                $this->umOuOutro( $item->ip_alterador, $item->ip_alterador_proxiado), $this->formatarDataHora($item->data_alterado));
            }

            
        ?>	<tr>
				<td><a href="<?=  $urlEditar;?>" title="<?= JText::_('COM_CLASSIFICADOS_BTN_EDITAR_TEXT') ?>"><em class="icon-pencil"></em></a></td>
				<td>
                <?php
                if(JFactory::getUser()->email !=  $item->email) :
                ?>
                <a href="javascript: if(confirm('<?= 
					JText::sprintf('COM_CLASSIFICADOS_INFORMACOES_CONFIRMA_REMOCAO', $item->email)  ;
				?>')){ window.location='<?=  $urlRemover;?>'}"
				       class="text-error"  title="<?= JText::_('COM_CLASSIFICADOS_BTN_REMOVER_TEXT') ?>"><em
								class="icon-remove"></em></a><?php 
                else :?>
                    <em class="icon-ban-circle text-error"  title="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_EMAIL_PRINCIPAL')?>"></em><?php
                endif; ?></td>
				<td><?php if(JFactory::getUser()->email ==  $item->email ) :?>
                    <!--em class="icon-ban-circle text-error" title="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_EMAIL_PRINCIPAL')?>"></em-->
                <?php else :
                    if($item->validado==null || $item->validado == '') :?>
                        <em class="icon-thumbs-up" title="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_EMAIL_TORNAR_PRINCIPAL_VALIDADO')?>"></em>
                    <?php else:?>
                        <a href="<?= $urlPrincipal ?>" title="<?= JText::_('COM_CLASSIFICADOS_EMPRESA_EMAIL_TORNAR_PRINCIPAL')?>"><em class="icon-thumbs-up text-success"></em></a>
                <?php
                    endif; 
                endif; ?></td>
                <td><?= JHTML::tooltip($tooltipo, JText::_('COM_CLASSIFICADOS_INFORMACOES'), '', $item->email . ' ' ) ?></td>
				<td><?=  $item->exibir ? JText::_('SIM') : JText::_('NAO');?></td>
                <td><?=  $item->validado==null || $item->validado == '' ?  JText::_('NAO') : $this->formatarData($item->validado) ?></td>
			</tr>
		<?php	endforeach;?>
                </tbody>
            </table>
<?php else: ?>
<h3><?=  JText::_('COM_CLASSIFICADOS_SEM_ITENS');?></h3>

<?php endif; ?>

<div class="btn-toolbar text-right">
    <div class="btn-group">
        <a type="button" id="btnBuscar" class="btn btn-success" href="<?= JRoute::_( 'index.php?option=com_classificados&task=empresaemail.email&Itemid='.$itemid , false )  ?>"><?=
        JText::_('COM_CLASSIFICADOS_BTN_NOVO') . JText::_('COM_CLASSIFICADOS_BTN_NOVO_TEXT') ?></a>
    </div>
</div>

