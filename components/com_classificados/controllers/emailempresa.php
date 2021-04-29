<?php
/**
 * @package    classificados
 *
 * @author     jorge <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\SearchHelper;
use Joomla\CMS\Date\Date;

//SearchHelper

/**
 * Classificados controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerEmailEmpresa extends BaseController
{
    const TB_EMPRESA = '`#__empresa`';
    const TB_PESSOA = '`#__pessoa`';

    const TB_EMAIL_EMPRESA = '`#__email_empresa`';


    const STATUS_ATIVO = 'A';
	const STATUS_REMOVIDO = 'R';
	const STATUS_BLOQUEADO= 'B';

    public function emails(){
        $this->getModel('classificados')->isLogado('emailempresa.emails') || exit();
		$user = JFactory::getUser();



    }

    
    public function gravar(){
        JSession::checkToken() or die( 'Invalid Token' );
		$this->getModel('classificados')->isLogado('emailempresa.emails') || exit();

		$user = JFactory::getUser();

        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;

		$email = $input->get->get('email', null, 'string');
        $exibir = $input->get->get('exibir', false, 'bool');
        $contato = $input->get->get('contato', false, 'bool');
		$id = $input->get->get('id', null, 'int');



        //ALTERAÇÃO
        if($id != null && $id != '' && $id != 0 ){
            $fields = array(
                $db->quoteName('email') . ' = ' . $db->quote($email),
                $db->quoteName('exibir') . ' = ' . $db->quote($exibir),
                $db->quoteName('contato') . ' = ' . $db->quote($contato),

                //Campos de controle de alteração
                $db->quoteName('id_user_alterador') . ' = ' . $db->quote($user->id),
                $db->quoteName('ip_alterador') . ' = ' . $db->quote($_SERVER['REMOTE_ADDR']),
                $db->quoteName('ip_alterador_proxiado') . ' = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
                $db->quoteName('data_alterado') . ' = NOW()' ,
            );
            $conditions = array(
                $db->quoteName('id') . ' = ' . $id,
                //Para evitar ataques, tentar garantir que quem altera, está al 
                $db->quoteName('status') . ' = ' . $db->quote($this->getModel('socialblade')->STATUS_ATIVO),
                $db->quoteName('id_empresa') . ' IN ( SELECT id_empresa FROM ' . ClassificadosControllerEmailEmpresa::TB_PESSOA . ' WHERE id_usuario  = ' . $user->id . ' )' 
            );
            $query->update($db->quoteName(ClassificadosControllerEmailEmpresa::TB_EMAIL_EMPRESA))->set($fields)->where($s);
            $db->setQuery($query);
            $db->execute();
            $app->enqueueMessage(JText::_('COM_SOCIALBLADES_INFORMACOES_ALTERADOS'), 'info');
        }
        else { //INCLUSÃO
            $query = $db->getQuery(true);
            
            $columns = array('id_empresa', 'email', 'exibir', 'contato', 'status', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'data_criado');
            $values = array('( SELECT id_empresa FROM ' . ClassificadosControllerEmailEmpresa::TB_PESSOA . ' WHERE id_usuario  = ' . $user->id . ' ) ' ,
                            $db->quote($email),
                            $db->quote($exibir),
                            $db->quote($contato),
                            $db->quote(ClassificadosControllerEmailEmpresa::STATUS_ATIVO),
                            //Campos para controle de hsitórico.
                            $db->quote($user->id), 
                            $db->quote($_SERVER['REMOTE_ADDR']), 
                            $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
                            ClassificadosControllerBusca::STATUS_ATIVO,
                            'NOW()');
            
            $query
                ->insert($db->quoteName(ClassificadosControllerEmailEmpresa::TB_EMAIL_EMPRESA))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            
            $db->setQuery($query);
            $db->execute();
        }

    }

}