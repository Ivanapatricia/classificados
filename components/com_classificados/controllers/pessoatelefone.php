<?php
/**
 * @package    classificados
 *
 * @author     jorge <your@telefone.com>
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
class ClassificadosControllerPessoaTelefone extends BaseController
{

	const TB_TELEFONEPESSOA = '`#__telefone_pessoa`';
	const TB_TELEFONEBLACKLIST = '`#__telefoneblacklist`';

	const TB_USERS = '`#__users`';
	const TB_PESSOA = '`#__pessoa`';

	const STATUS_ATIVO = 'A';
    const STATUS_REMOVIDO = 'R';

    const REGEXP_TELEFONE = "/^(9[0-9]{4}[\\-\\s]{0,1}[0-9]{4})|([2-8]{1}[0-9]{3}[\\-\\s]{0,1}[0-9]{4})$/";
    const REGEXP_DDD = "/^[1-9]{1}[0-9]{1,2}$/";


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
        $query->update(ClassificadosControllerPessoaTelefone::TB_TELEFONEPESSOA)->set($fields)->where($conditions);

        $db->setQuery($query);
        $db->execute();



        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=telefone&Itemid='.$itemid , false ), "");
    }

    public function telefone(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->get('id', null,'int');

        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`ddd`,`a`.`telefone`,`a`.`exibir`,`a`.`tipo`,`a`.`validado`,`a`.`status`,`a`.`id_user_criador`,
			`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
			`a`.`data_criado`,`a`.`data_alterado`,`b`.`name` AS `nomeAlterador` ')
			->from (ClassificadosControllerPessoaTelefone::TB_TELEFONEPESSOA . ' AS `a`' )
			->join ('LEFT', ClassificadosControllerPessoaTelefone::TB_USERS . ' AS `b` ON `a`.`id_user_alterador` = `b`.`id`')
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaTelefone::STATUS_ATIVO), 'AND')
            ->where( '`a`.`id`  = ' . $db->quote($id))
            ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
            ->setLimit(1);
		$db->setQuery ( $query );
		$telefone = $db->loadObject();
		
        if( $telefone != null ){
            JRequest::setVar('ddd', $telefone->ddd);
            JRequest::setVar('telefone', $telefone->telefone);
            JRequest::setVar('exibir', $telefone->exibir);  
            JRequest::setVar('tipo', $telefone->tipo);   
        }

        $input->set('view', 'pessoa');
		$input->set('layout',  'telefone');
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
        $telefone = $input->post->get('telefone', null,'string');
        $ddd = $input->post->get('ddd', null,'string');
        $exibir = $input->post->get('exibir', null,'boolean');
        $tipo = $input->post->get('tipo', null,'string');


        if($telefone != null && $telefone!= ''){
            $telefone = strtolower(trim($telefone));
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

		if(($telefone==null || trim($telefone) == '' || strlen($telefone) < 3) || 
            ($ddd==null || trim($ddd) == '' || strlen($ddd) <2)){
			JLog::add('Não enviou o telefone do pessoa', JLog::DEBUG, 'com-classificados-pessoatelefone');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_TELEFONE_OBRIGATORIO'), 'error');
			$isErro = true;
		}

        if($tipo==null || trim($tipo) == '' || strlen($tipo) < 3 ){
            JLog::add('Não enviou o tipo de telefone do pessoa', JLog::DEBUG, 'com-classificados-pessoatelefone');
            $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_TIPOTELEFONE_OBRIGATORIO'), 'error');
            $isErro = true;
        }

 
        $exp = preg_match(ClassificadosControllerPessoaTelefone::REGEXP_TELEFONE, $telefone);
        $expddd = preg_match(ClassificadosControllerPessoaTelefone::REGEXP_DDD, $ddd);




        if($exp===false || $exp<=0 || $expddd===false || $expddd<=0 ) {
			JLog::add('Não enviou o telefone invalido', JLog::DEBUG, 'com-classificados-pessoatelefone');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_TELEFONE_INVALIDO'), 'error');
			$isErro = true;

        }

        


        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`')
			->from (ClassificadosControllerPessoaTelefone::TB_TELEFONEPESSOA . ' AS `a`' );

        if($id != null && $id != '' && $id != 0){
            $query->where( '`a`.`id`  <> ' . $db->quote($id), 'AND');
        }
        $query->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaTelefone::STATUS_ATIVO), 'AND')
            ->where( '`a`.`telefone` = ' . $db->quote($telefone ), 'AND')
            ->where( '`a`.`ddd` = ' . $db->quote($ddd ), 'AND')
            ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
            ->setLimit(1);
		$db->setQuery ( $query );
		$existe = $db->loadObject();

        if($existe!= null && $existe->id != null  && $existe->id != ''  && $existe->id != 0 ){
			JLog::add('Telefone já cadastrado para essa pessoa', JLog::DEBUG, 'com-classificados-pessoatelefone');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_TELEFONE_JA_EXISTE'), 'error');
			$isErro = true;
        }

        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`')
			->from (ClassificadosControllerPessoaTelefone::TB_TELEFONEBLACKLIST . ' AS `a`' )
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaTelefone::STATUS_ATIVO), 'AND')
            ->where( '`a`.`telefone` = ' . $db->quote($telefone), 'AND')
            ->where( '( `a`.`ddd` is null or `a`.`ddd` = ' . $db->quote($ddd) . ')')
            ->setLimit(1);
		$db->setQuery ( $query );
		$existe = $db->loadObject();

        if($existe!= null && $existe->id != null  && $existe->id != ''  && $existe->id != 0 ){
			JLog::add('Telefone bloqueado', JLog::DEBUG, 'com-classificados-pessoatelefone');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_TELEFONE_BLOQUEADO'), 'error');
			$isErro = true;
        }


		if($isErro){
			$this->telefone();
			return;
		}


		if($id != '' && $id != null && $id != 0 ){
            $fields = array(
                '`ddd` = ' . $db->quote($ddd),
                '`telefone` = ' . $db->quote($telefone),
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
			$query->update(ClassificadosControllerPessoaTelefone::TB_TELEFONEPESSOA)->set($fields)->where($conditions);

			$db->setQuery($query);
            $db->execute();
        }
        else { //INCLUSÃO
            $query = $db->getQuery(true);
            
            $columns = array('ddd','telefone', 'exibir', 'tipo', 'id_pessoa',
			'status', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'data_criado');
            $values = array(
                $db->quote($ddd),
                $db->quote($telefone),
                $db->quote(trim($exibir)),
                $db->quote(trim($tipo)),
                $db->quote($user->id), 
                $db->quote(ClassificadosControllerPessoaTelefone::STATUS_ATIVO),
                $db->quote($user->id), 
                $db->quote($_SERVER['REMOTE_ADDR']), 
                $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
                'NOW()');
            
            $query
                ->insert(ClassificadosControllerPessoaTelefone::TB_TELEFONEPESSOA)
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
        }





        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=telefone&Itemid='.$itemid , false ), "");
    }
}