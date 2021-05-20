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
use Joomla\CMS\Uri\Uri;
//SearchHelper

/**
 * Classificados controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerPessoaEmail extends BaseController
{

	const TB_EMAILPESSOA = '`#__email_pessoa`';
	const TB_EMAILBLACKLIST = '`#__emailblacklist`';

	const TB_USERS = '`#__users`';
	const TB_PESSOA = '`#__pessoa`';

	const STATUS_ATIVO = 'A';
    const STATUS_REMOVIDO = 'R';

    const REGEXP_EMAIL = "/^[a-z]{1}[a-z0-9\\-\\.\\_]*\@[a-z]{1}[a-z0-9\\-\\.\\_]*\.[a-z]{2,3}(\.[a-z]{1,2}){0,1}$/";
    const REGEXP_GMAIL = "/^[a-z]{1}[a-z0-9\\_]*\@[a-z]{1}[a-z0-9\\-\\.\\_]*\.[a-z]{2,3}(\.[a-z]{1,2}){0,1}$/";

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
        $query->update(ClassificadosControllerPessoaEmail::TB_EMAILPESSOA)->set($fields)->where($conditions);

        $db->setQuery($query);
        $db->execute();



        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=email&Itemid='.$itemid , false ), "");
    }


    public function principal(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->get->get('id', null,'int');


        $query = $db->getQuery ( true );
		$query->select('`a`.`email`')
			->from (ClassificadosControllerPessoaEmail::TB_EMAILPESSOA . ' AS `a`' )
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEmail::STATUS_ATIVO), 'AND')
            ->where( '`a`.`id`  = ' . $db->quote($id))
            ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
            ->setLimit(1);
		$db->setQuery ( $query );
		$email = $db->loadObject();

        if($email != null && $email != '' && $email->email != null && $email->email != ''){
            $fields = array(
                '`email`  = '.$db->quote($email->email),
            );
            $conditions = array(
                '  `id` = ' . $user->id
            );
            $query = $db->getQuery(true);
            $query->update(ClassificadosControllerPessoaEmail::TB_USERS)->set($fields)->where($conditions);

            $db->setQuery($query);
            $db->execute();
            $user->email = $email->email;
        }


        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=email&Itemid='.$itemid , false ), "");
    }

    public function email(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->get('id', null,'int');

        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`email`,`a`.`exibir`,`a`.`contato`,`a`.`validado`,`a`.`status`,`a`.`id_user_criador`,
			`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
			`a`.`data_criado`,`a`.`data_alterado`,`b`.`name` AS `nomeAlterador` ')
			->from (ClassificadosControllerPessoaEmail::TB_EMAILPESSOA . ' AS `a`' )
			->join ('LEFT', ClassificadosControllerPessoaEmail::TB_USERS . ' AS `b` ON `a`.`id_user_alterador` = `b`.`id`')
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEmail::STATUS_ATIVO), 'AND')
            ->where( '`a`.`id`  = ' . $db->quote($id))
            ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
            ->setLimit(1);
		$db->setQuery ( $query );
		$email = $db->loadObject();
		
        if( $email != null ){
            JRequest::setVar('email', $email->email);
            JRequest::setVar('exibir', $email->exibir);  
            JRequest::setVar('contato', $email->contato);   
            JRequest::setVar('validado', $email->validado);   
        }

        $input->set('view', 'pessoa');
		$input->set('layout',  'email');
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
        $email = $input->post->get('email', null,'string');
        $exibir = $input->post->get('exibir', null,'boolean');
        $contato = $input->post->get('contato', null,'boolean');
        $principal = $input->post->get('principal', null,'boolean');

        if($email != null && $email!= ''){
            $email = strtolower(trim($email));
        }



        if(!JSession::checkToken()){
			JLog::add('Token inválido ao tentar salvar parceiro', JLog::DEBUG, 'com-socialblade-parceiro');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_ERRO_TOKEN'), 'error');
			$this->parceiros();
			return;// Se o token expriou não valida o resto.
		}

		if($email==null || trim($email) == '' || strlen($email) < 3){
			JLog::add('Não enviou o e-mail do pessoa', JLog::DEBUG, 'com-classificados-pessoaemail');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_OBRIGATORIO'), 'error');
			$isErro = true;
		}


        $exp = preg_match(ClassificadosControllerPessoaEmail::REGEXP_EMAIL, $email);

        if($exp===false || $exp<=0) {
			JLog::add('Não enviou o e-mail invalido', JLog::DEBUG, 'com-classificados-pessoaemail');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_INVALIDO'), 'error');
			$isErro = true;

        }
        else{
            $host = substr($email, strpos($email,'@') +1);
            if(!checkdnsrr ($host , "MX" )){
                JLog::add('Dominio não encontrado de e-mail', JLog::DEBUG, 'com-classificados-pessoaemail');
                $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_DNS_INVALIDO'), 'error');
                $isErro = true;
            }

        }

        


        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`')
			->from (ClassificadosControllerPessoaEmail::TB_EMAILPESSOA . ' AS `a`' );

        if($id != null && $id != '' && $id != 0){
            $query->where( '`a`.`id`  <> ' . $db->quote($id), 'AND');
        }
        $query->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEmail::STATUS_ATIVO), 'AND')
            ->where( '`a`.`email` = ' . $db->quote($email ), 'AND')
            ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
            ->setLimit(1);
		$db->setQuery ( $query );
		$existe = $db->loadObject();

        if($existe!= null && $existe->id != null  && $existe->id != ''  && $existe->id != 0 ){
			JLog::add('E-mail já cadastrado para essa pessoa', JLog::DEBUG, 'com-classificados-pessoaemail');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_JA_EXISTE'), 'error');
			$isErro = true;
        }



        if($id != null && $id != '' && $id != 0){

            $query = $db->getQuery ( true );
            $query->select(' `a`.`id`')
                ->from (ClassificadosControllerPessoaEmail::TB_EMAILPESSOA . ' AS `a`' )
                ->where( '`a`.`id`  = ' . $db->quote($id), 'AND')
                ->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEmail::STATUS_ATIVO), 'AND')
                ->where( '`a`.`email` = ' . $db->quote($user->$email ), 'AND')
                ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
                ->setLimit(1);
            $db->setQuery ( $query );
            $existe = $db->loadObject();

            if($existe!= null && $existe->id != null  && $existe->id != ''  && $existe->id != 0 ){
                $principal= true;
            }
        }




        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`')
			->from (ClassificadosControllerPessoaEmail::TB_EMAILBLACKLIST . ' AS `a`' )
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEmail::STATUS_ATIVO), 'AND')
            ->where( '`a`.`email` = ' . $db->quote($email))
            ->setLimit(1);
		$db->setQuery ( $query );
		$existe = $db->loadObject();

        if($existe!= null && $existe->id != null  && $existe->id != ''  && $existe->id != 0 ){
			JLog::add('E-mail bloqueado', JLog::DEBUG, 'com-classificados-pessoaemail');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_BLOQUEADO'), 'error');
			$isErro = true;
        }


		if($isErro){
			$this->email();
			return;
		}




        $uuid = $this->uuidMAIL();




		if($id != '' && $id != null && $id != 0 ){


            $query = $db->getQuery ( true );
            $query->select(' `a`.`id`, `a`.`email`, `a`.`validado`')
                ->from (ClassificadosControllerPessoaEmail::TB_EMAILPESSOA . ' AS `a`' )
                ->where( '`a`.`id`  = ' . $db->quote($id), 'AND')
                ->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEmail::STATUS_ATIVO), 'AND')
                ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
                ->setLimit(1);
            $db->setQuery ( $query );
            $existe = $db->loadObject();

            if($existe->email != $email ){

                if(!$this->_sendValidator($email, $uuid)){
                    JLog::add('Problema ao enviar o e-mail de validação', JLog::DEBUG, 'com-classificados-pessoaemail');
                    $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_FALHA_ENVIO_VALIDACAO'), 'error');
                    $this->email();
                    return;
                }
            }


            $fields = array(
                '`email` = ' . $db->quote($email),
                '`exibir` = ' . $db->quote(trim($exibir)),
                '`contato` = ' . $db->quote(trim($contato)),
                '`id_user_alterador`  = ' . $db->quote($user->id),
                '`ip_alterador`  = ' . $db->quote($_SERVER['REMOTE_ADDR']),
                '`ip_alterador_proxiado`  = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
                '`hash_validar` = ' . $db->quote($uuid),
                '`data_alterado` = NOW()' 
            );
            $conditions = array(
                '  `id` = ' . $id       ,
                '  `status` = \'A\''        ,
                '  `id_pessoa` = ' . $user->id
            );
			$query = $db->getQuery(true);
			$query->update(ClassificadosControllerPessoaEmail::TB_EMAILPESSOA)->set($fields)->where($conditions);

			$db->setQuery($query);
            $db->execute();


            if($existe->email == $email && $existe->validado != null ){
                if($principal != null && trim($principal) != '' && $principal) {
                    $fields = array(
                        '`email`  = '.$db->quote($email),
                    );
                    $conditions = array(
                        '  `id` = ' . $user->id
                    );
                    $query = $db->getQuery(true);
                    $query->update(ClassificadosControllerPessoaEmail::TB_USERS)->set($fields)->where($conditions);
        
                    $db->setQuery($query);
                    $db->execute();
                    $user->email = $email;
                }
            }
        }
        else { //INCLUSÃO

            if(!$this->_sendValidator($email, $uuid)){
                JLog::add('Problema ao enviar o e-mail de validação', JLog::DEBUG, 'com-classificados-pessoaemail');
                $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_FALHA_ENVIO_VALIDACAO'), 'error');
                $this->email();
                return;
            }


            $query = $db->getQuery(true);
            
            $columns = array('email', 'exibir', 'contato', 'id_pessoa','hash_validar',
			'status', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'data_criado');
            $values = array(
                $db->quote($email),
                $db->quote(trim($exibir)),
                $db->quote(trim($contato)),
                $db->quote($user->id), 
                $db->quote($uuid),
                $db->quote(ClassificadosControllerPessoaEmail::STATUS_ATIVO),
                $db->quote($user->id), 
                $db->quote($_SERVER['REMOTE_ADDR']), 
                $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
                'NOW()');
            
            $query
                ->insert(ClassificadosControllerPessoaEmail::TB_EMAILPESSOA)
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
        }





        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=email&Itemid='.$itemid , false ), "");
    }

    public function validar(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
        $input = $app->input;
        $email = $input->get->get('email', null,'string');
        $h = $input->get->get('h', null,'string');


        if($h==null || trim($h)=='' || $email==null || $email==''){
            $input->set('view', 'pessoa');
            $input->set('layout',  'falha_valicao_email');
            parent::display (true);
            return;
        }

        $query = $db->getQuery(true);
        $fields = array(
            '`validado` = NOW()' ,
            '`hash_validar` = null' ,
            '`ip_alterador`  = ' . $db->quote($_SERVER['REMOTE_ADDR']),
            '`ip_alterador_proxiado`  = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
            '`data_alterado` = NOW()' 
        );
        $conditions = array(
            '  `hash_validar` = ' .  $db->quote($h ) ,
            '  `status` = \'A\''        ,
            '  `email` = ' . $db->quote($email)
        );
        $query = $db->getQuery(true);
        $query->update(ClassificadosControllerPessoaEmail::TB_EMAILPESSOA)->set($fields)->where($conditions);
 
        $db->setQuery($query);
        $db->execute();
        $input->set('view', 'pessoa');
        $input->set('layout',  'validado_email');
        parent::display (true);

    }

    private function _sendValidator($email, $uuid){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
        $mailer = JFactory::getMailer();





        $config = JFactory::getConfig();
        $sender = array( 
            $config->get( 'mailfrom' ),
            $config->get( 'fromname' ) 
        );

        $mailer->setSender($sender);
        $mailer->addRecipient($email);
        $mailer->setSubject($app->getName() . ' : ' . JText::_('COM_CLASSIFICADOS_PESSOA_EMAIL_VALIDACAO') );
        $mailer->isHtml(true);
        $mailer->Encoding = 'base64';



        $urlValidacao=Uri::root() .  JRoute::_( 'index.php?option=com_classificados&task=pessoaemail.validar&email='.$email.'&h='. $uuid, false );

        $mailer->setBody(JText::sprintf('COM_CLASSIFICADOS_PESSOA_EMAIL_TEXTO_EMAIL', $urlValidacao, $urlValidacao ) );

        $send = $mailer->Send();
        return !( $send !== true );
        
    }

    private function uuidMAIL(){
        return md5(uniqid(rand(), true));
    }
}