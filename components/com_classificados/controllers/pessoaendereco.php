<?php
/**
 * @package    classificados
 *
 * @author     jorge <your@endereco.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\SearchHelper;

//SearchHelper

/**
 * Classificados controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerPessoaEndereco extends BaseController
{

	const TB_ENDERECOPESSOA = '`#__endereco_pessoa`';

	const TB_USERS = '`#__users`';
	const TB_PESSOA = '`#__pessoa`';

	const STATUS_ATIVO = 'A';
    const STATUS_REMOVIDO = 'R';



    public function apagar(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->get->get('id', null,'int');

        $fields = array(
            '`status`  = \'R\'',
            '`id_user_alterador`  = ' . $db->quote($user->id),
            '`ip_alterador`  = ' . $db->quote($_SERVER['REMOTE_ADDR']),
            '`ip_alterador_proxiado`  = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
            '`data_alterado` = NOW()' 
        );
        $conditions = array(
            '  `id` = ' . $id       ,
            '  `status` = \'A\''        ,
            '  `id_pessoa` = ' . $user->id
        );
        $query = $db->getQuery(true);
        $query->update(ClassificadosControllerPessoaendereco::TB_ENDERECOPESSOA)->set($fields)->where($conditions);

        $db->setQuery($query);
        $db->execute();



        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=endereco&Itemid='.$itemid , false ), "");
    }

    public function endereco(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->get('id', null,'int');

        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`ddd`,`a`.`endereco`,`a`.`exibir`,`a`.`tipo`,`a`.`validado`,`a`.`status`,`a`.`id_user_criador`,
			`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
			`a`.`data_criado`,`a`.`data_alterado`,`b`.`name` AS `nomeAlterador` ')
			->from (ClassificadosControllerPessoaendereco::TB_ENDERECOPESSOA . ' AS `a`' )
			->join ('LEFT', ClassificadosControllerPessoaendereco::TB_USERS . ' AS `b` ON `a`.`id_user_alterador` = `b`.`id`')
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaendereco::STATUS_ATIVO), 'AND')
            ->where( '`a`.`id`  = ' . $db->quote($id))
            ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
            ->setLimit(1);
		$db->setQuery ( $query );
		$endereco = $db->loadObject();
		
        if( $endereco != null ){
            JRequest::setVar('ddd', $endereco->ddd);
            JRequest::setVar('endereco', $endereco->endereco);
            JRequest::setVar('exibir', $endereco->exibir);  
            JRequest::setVar('tipo', $endereco->tipo);   
        }

        $input->set('view', 'pessoa');
		$input->set('layout',  'endereco');
		parent::display (true);
    }


    public function salvar(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->post->get('id', null,'int');
        $endereco = $input->post->get('endereco', null,'string');
        $ddd = $input->post->get('ddd', null,'string');
        $exibir = $input->post->get('exibir', null,'boolean');
        $tipo = $input->post->get('tipo', null,'string');


        if($endereco != null && $endereco!= ''){
            $endereco = strtolower(trim($endereco));
        }

        if($ddd != null && $ddd!= ''){
            $ddd = strtolower(trim($ddd));
        }




        if(!JSession::checkToken()){
			JLog::add('Token inválido ao tentar salvar parceiro', JLog::DEBUG, 'com-socialblade-parceiro');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_ERRO_TOKEN'), 'error');
			$this->parceiros();
			return;// Se o token expriou não valida o resto.
		}

		if(($endereco==null || trim($endereco) == '' || strlen($endereco) < 3) || 
            ($ddd==null || trim($ddd) == '' || strlen($ddd) <2)){
			JLog::add('Não enviou o endereco do pessoa', JLog::DEBUG, 'com-classificados-pessoaendereco');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_endereco_OBRIGATORIO'), 'error');
			$isErro = true;
		}

        if($tipo==null || trim($tipo) == '' || strlen($tipo) < 3 ){
            JLog::add('Não enviou o tipo de endereco do pessoa', JLog::DEBUG, 'com-classificados-pessoaendereco');
            $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_TIPOendereco_OBRIGATORIO'), 'error');
            $isErro = true;
        }

        
 

		if($isErro){
			$this->endereco();
			return;
		}


		if($id != '' && $id != null && $id != 0 ){
            $fields = array(
                '`ddd` = ' . $db->quote($ddd),
                '`endereco` = ' . $db->quote($endereco),
                '`exibir` = ' . $db->quote(trim($exibir)),
                '`tipo` = ' . $db->quote(trim($tipo)),
                '`id_user_alterador`  = ' . $db->quote($user->id),
                '`ip_alterador`  = ' . $db->quote($_SERVER['REMOTE_ADDR']),
                '`ip_alterador_proxiado`  = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
                '`data_alterado` = NOW()' 
            );
            $conditions = array(
                '  `id` = ' . $id       ,
                '  `status` = \'A\''        ,
                '  `id_pessoa` = ' . $user->id
            );
			$query = $db->getQuery(true);
			$query->update(ClassificadosControllerPessoaendereco::TB_ENDERECOPESSOA)->set($fields)->where($conditions);

			$db->setQuery($query);
            $db->execute();
        }
        else { //INCLUSÃO
            $query = $db->getQuery(true);
            
            $columns = array('ddd','endereco', 'exibir', 'tipo', 'id_pessoa',
			'status', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'data_criado');
            $values = array(
                $db->quote($ddd),
                $db->quote($endereco),
                $db->quote(trim($exibir)),
                $db->quote(trim($tipo)),
                $db->quote($user->id), 
                $db->quote(ClassificadosControllerPessoaendereco::STATUS_ATIVO),
                $db->quote($user->id), 
                $db->quote($_SERVER['REMOTE_ADDR']), 
                $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
                'NOW()');
            
            $query
                ->insert(ClassificadosControllerPessoaendereco::TB_ENDERECOPESSOA)
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
        }





        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=endereco&Itemid='.$itemid , false ), "");
    }
}