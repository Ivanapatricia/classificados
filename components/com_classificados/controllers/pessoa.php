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

//SearchHelper

/**
 * Classificados controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerPessoa extends BaseController
{
	const TB_EMPRESA = '`#__empresa`';
	const TB_PESSOA = '`#__pessoa`';


	
	const TB_EMAILPESSOA = '`#__email_pessoa`';
	const TB_TELEFONEPESSOA = '`#__telefone_pessoa`';
	const TB_ENDERECOPESSOA = '`#__endereco_pessoa`';
	const TB_USERS = '`#__users`';
	const TB_CIDADE = '`#__cidade`';
	const TB_LOGRADOURO = '`#__logradouros`';


	const STATUS_ATIVO = 'A';


	private function _carregarDados(){


		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;


		$query = $db->getQuery ( true );
		$query->select('`a`.`id`, `a`.`nome`, `a`.`sobrenome`, `a`.`status`, `a`.`id_user_criador`, `a`.`id_user_alterador`, `a`.`data_criado`, `a`.`data_alterado`, `a`.`ip_criador`, 
		 	`a`.`ip_criador_proxiado`, `a`.`ip_alterador`, `a`.`ip_alterador_proxiado`, `a`.`genero`, `a`.`nascimento`, `a`.`cpf`,  `a`.`id_empresa`,
			`b`.`username`, `b`.`name`, `b`.`registerDate`, `b`.`email`, `b`.`lastvisitDate` ')
			->from (ClassificadosControllerPessoa::TB_PESSOA . 'AS `a`')
			->join ('LEFT', ClassificadosControllerPessoa::TB_USERS. 'AS `b` ON `a`.`id` = `b`.`id`')
			
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO), 'AND')
			->where( '`a`.`id` = ' . $db->quote($user->id),  'AND')
			->where( '`b`.`block`  = 0 ')
			->setLimit(1);
		$db->setQuery ( $query );
		$pessoa = $db->loadObject();

		$isCadastrado = !($pessoa == null || empty($pessoa) || $pessoa == '' || $pessoa->id == null ||  $pessoa->id == '' ||  $pessoa->id == 0 );


		$pessoa->usuario = $user;
		$input->set( 'item', $pessoa);
		return $isCadastrado ;

	}

    public function meusdados(){
		$this->getModel('classificados')->isLogado('pessoa.meusdados') || exit();
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;

		if(! $this->_carregarDados()){
			$this->editar();
			return;
		}

			

		$query = $db->getQuery ( true );
		$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`email`,`a`.`exibir`,`a`.`contato`,`a`.`validado`,`a`.`status`,`a`.`id_user_criador`,
			`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
			`a`.`data_criado`,`a`.`data_alterado`,`c`.`name` AS `nomeAlterador`, `b`.`name` AS `nomeCriador`   ')
			->from (ClassificadosControllerPessoa::TB_EMAILPESSOA . ' AS `a`' )
			->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `b` ON `a`.`id_user_criador` = `b`.`id`')
			->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `c` ON `a`.`id_user_alterador` = `c`.`id`')
			->where( '`status` = ' . $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO), 'AND')
			->where( '`id_pessoa` = ' . $db->quote($user->id))
			->setLimit(100);
		$db->setQuery ( $query );
		$itens = $db->loadObjectList();
		if($itens==null || empty($itens)){
			$query = $db->getQuery(true);
            
            $columns = array('email', 'exibir', 'contato', 'id_pessoa',
			'status', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'data_criado');
            $values = array(
                $db->quote($user->email),
                '1',
                '0',
                $db->quote($user->id), 
                $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO),
                $db->quote($user->id), 
                $db->quote($_SERVER['REMOTE_ADDR']), 
                $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
                'NOW()');
            
            $query
                ->insert(ClassificadosControllerPessoa::TB_EMAILPESSOA)
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();


			$query = $db->getQuery ( true );
			$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`email`,`a`.`exibir`,`a`.`contato`,`a`.`validado`,`a`.`status`,`a`.`id_user_criador`,
				`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
				`a`.`data_criado`,`a`.`data_alterado`,`c`.`name` AS `nomeAlterador`, `b`.`name` AS `nomeCriador`   ')
				->from (ClassificadosControllerPessoa::TB_EMAILPESSOA . ' AS `a`' )
				->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `b` ON `a`.`id_user_criador` = `b`.`id`')
				->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `c` ON `a`.`id_user_alterador` = `c`.`id`')
				->where( '`status` = ' . $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO), 'AND')
				->where( '`id_pessoa` = ' . $db->quote($user->id))
				->setLimit(100);
			$db->setQuery ( $query );
			$itens = $db->loadObjectList();
		}
		$input->set( 'emails', $itens);

		$query = $db->getQuery ( true );
		$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`ddd`,`a`.`telefone`,`a`.`exibir`,`a`.`tipo`,`a`.`validado`,`a`.`status`,`a`.`id_user_criador`,
			`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
			`a`.`data_criado`,`a`.`data_alterado`,`c`.`name` AS `nomeAlterador`, `b`.`name` AS `nomeCriador`   ')
			->from (ClassificadosControllerPessoa::TB_TELEFONEPESSOA . ' AS `a`' )
			->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `b` ON `a`.`id_user_criador` = `b`.`id`')
			->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `c` ON `a`.`id_user_alterador` = `c`.`id`')
			->where( '`status` = ' . $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO), 'AND')
			->where( '`id_pessoa` = ' . $db->quote($user->id))
			->setLimit(100);
		$db->setQuery ( $query );
		$itens = $db->loadObjectList();
		$input->set( 'telefones', $itens);







		$query = $db->getQuery ( true );
		$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`endereco`,`a`.`numero`, `a`.`complemento`, `a`.`bairro`, `a`.`cep`, 
			`d`.`nome` AS cidade, `e`.`nome` AS logradouro,
			`a`.`status`,`a`.`id_user_criador`,
			`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
			`a`.`data_criado`,`a`.`data_alterado`,`c`.`name` AS `nomeAlterador`, `b`.`name` AS `nomeCriador`   ')
			->from (ClassificadosControllerPessoa::TB_ENDERECOPESSOA . ' AS `a`' )
			->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `b` ON `a`.`id_user_criador` = `b`.`id`')
			->join ('LEFT', ClassificadosControllerPessoa::TB_USERS . ' AS `c` ON `a`.`id_user_alterador` = `c`.`id`')
			->join ('LEFT', ClassificadosControllerPessoa::TB_CIDADE . ' AS `d` ON `a`.`id_cidade` = `d`.`id`')
			->join ('LEFT', ClassificadosControllerPessoa::TB_LOGRADOURO . ' AS `e` ON `a`.`id_logradouro` = `e`.`id`')
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO), 'AND')
			->where( '`a`.`id_pessoa` = ' . $db->quote($user->id))
			->setLimit(100);
		$db->setQuery ( $query );
		$itens = $db->loadObjectList();
		$input->set( 'enderecos', $itens);


        $input->set( 'view', 'pessoa' );
		$input->set('layout',  'default' );
		parent::display (true);
    }


    public function editar(){
		$this->getModel('classificados')->isLogado('pessoa.meusdados') || exit();
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;


		if(JRequest::getVar('item',null) == null ){
			$this->_carregarDados();
		}



        $input->set( 'view', 'pessoa' );
		$input->set('layout', 'form' );
		parent::display (true);
    }

    public function salvar(){
		$this->getModel('classificados')->isLogado('pessoa.meusdados') || exit();
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
		
		$nome = $input->post->get('nome', '', 'string'); 
		$sobrenome = $input->post->get('sobrenome', '', 'string');
		$genero = $input->post->get('genero', '', 'string');
		$nascimento = $input->post->get('nascimento', '', 'date');
		$nascimentoFormatado = null;
		$cpf = $input->post->get('cpf', null, 'string');
		$cpfLimpo = trim(str_replace("-","",str_replace(".","", $cpf !=null ? $cpf : '')));
	
		if(!JSession::checkToken()){
			JLog::add('Token inválido ao tentar salvar parceiro', JLog::DEBUG, 'com-socialblade-parceiro');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_ERRO_TOKEN'), 'error');
			$this->parceiros();
			return;// Se o token expriou não valida o resto.
		}

		if($nome==null || trim($nome) == '' || strlen(trim($nome)) < 3){
			JLog::add('Não enviou o nome do pessoa', JLog::DEBUG, 'com-classificados-pessoas');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_NOME_OBRIGATORIO'), 'error');
			$isErro = true;
		}
		if($sobrenome==null || trim($sobrenome) == '' || strlen(trim($sobrenome)) < 3){
			JLog::add('Não enviou o sobrenome do pessoa', JLog::DEBUG, 'com-classificados-pessoas');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_NOMESOBRENOME_OBRIGATORIO'), 'error');
			$isErro = true;
		}
		if($genero==null || trim($genero) == ''){
			JLog::add('Não enviou o genero do pessoa', JLog::DEBUG, 'com-classificados-pessoas');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_GENERO_OBRIGATORIO'), 'error');
			$isErro = true;
		}
		
		if($nascimento==null || trim($nascimento) == ''){
			JLog::add('Não enviou o data de nascimento do pessoa', JLog::DEBUG, 'com-classificados-pessoas');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_NASCIMENTO_OBRIGATORIO'), 'error');
			$isErro = true;
		}
		else{
			$nascimentoFormatado = strtotime($nascimento);
			if(strtotime("+18 years", $nascimentoFormatado ) > mktime(0,0,0) ){
				JLog::add('Menor de 18 anos', JLog::DEBUG, 'com-classificados-pessoas');
				$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_MENOR_IDADE'), 'error');
				$isErro = true;	
			}
			if(strtotime("+123 years", $nascimentoFormatado ) < mktime(0,0,0) ){
				JLog::add('Maior anos', JLog::DEBUG, 'com-classificados-pessoas');
				$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_MAIOR_100ANOS'), 'error');
				$isErro = true;	
			}
		}

		if($cpf==null || trim($cpf) == '' || !$this->_validaCPF($cpfLimpo)){
			JLog::add('Não enviou ou é invaĺido o cpf do pessoa', JLog::DEBUG, 'com-classificados-pessoas');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_CPF_OBRIGATORIO'), 'error');
			$isErro = true;
		}

		//Verifica no banco de dados se o CPF já está cadastrado.
		$query = $db->getQuery ( true );
		$query->select('`a`.`id`')
			->from (ClassificadosControllerPessoa::TB_PESSOA . 'AS `a`')
			->where( '`a`.`status`  = ' . $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO), 'AND')
			->where( '`a`.`cpf`  = ' . $db->quote($cpfLimpo), 'AND')
			->where( '`a`.`id`  <> ' . $db->quote($user->id))
			->setLimit(1);

		$db->setQuery ( $query );
		$pessoa = $db->loadObject();

		if(!($pessoa == null || empty($pessoa) || $pessoa == '' || $pessoa->id == null ||  $pessoa->id == '' ||  $pessoa->id == 0 )){
			JLog::add("Não o CPF $cpfLimpo já está cadastrado.", JLog::DEBUG, 'com-classificados-pessoas');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_CPF_JA_CADASTRADO'), 'info');
			$isErro = true;
		}



		if($isErro){
			$this->editar();
			return;
		}

		$query = $db->getQuery ( true );
		$query->select('`a`.`id`')
			->from (ClassificadosControllerPessoa::TB_PESSOA . 'AS `a`')
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO), 'AND')
			->where( '`a`.`id` = ' . $db->quote($user->id))
			->setLimit(1);

		$db->setQuery ( $query );
		$pessoa = $db->loadObject();
		$isCadastrado = !($pessoa == null || empty($pessoa) || $pessoa == '' || $pessoa->id == null ||  $pessoa->id == '' ||  $pessoa->id == 0 );

		if($isCadastrado){

            
            $fields = array(
                $db->quoteName('nome') . ' = ' . $db->quote(trim($nome)),
                $db->quoteName('sobrenome') . ' = ' . $db->quote(trim($sobrenome)),
                $db->quoteName('genero') . ' = ' . $db->quote(trim($genero)),
                $db->quoteName('nascimento') . ' = ' . $db->quote((new JDate($nascimentoFormatado))->format('Y-m-d')),
				$db->quoteName('cpf') . ' = ' . $db->quote($cpfLimpo),



                //Campos de controle de alteração
                $db->quoteName('id_user_alterador') . ' = ' . $db->quote($user->id),
                $db->quoteName('ip_alterador') . ' = ' . $db->quote($_SERVER['REMOTE_ADDR']),
                $db->quoteName('ip_alterador_proxiado') . ' = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
                $db->quoteName('data_alterado') . ' = NOW()' 
            );
            $conditions = array(
                '  `id` = ' . $user->id             );

            
			$query = $db->getQuery(true);
            
			$query->update(ClassificadosControllerPessoa::TB_PESSOA)->set($fields)->where($conditions);

		
            	
			$db->setQuery($query);
            $db->execute();
        }
        else { //INCLUSÃO
            $query = $db->getQuery(true);
            
            $columns = array('id', 'nome', 'sobrenome', 'genero', 'nascimento','cpf',

			'status', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'data_criado');
            $values = array(
							$db->quote($user->id), 
                            $db->quote(trim($nome)),
                            $db->quote(trim($sobrenome)),
                            $db->quote(trim($genero)),
							$db->quote((new JDate($nascimentoFormatado))->format('Y-m-d')),
							$db->quote($cpfLimpo),
                            $db->quote(ClassificadosControllerPessoa::STATUS_ATIVO),
                            //Campos para controle de hsitórico.
                            $db->quote($user->id), 
                            $db->quote($_SERVER['REMOTE_ADDR']), 
                            $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
                            'NOW()');
            
            $query
                ->insert(ClassificadosControllerPessoa::TB_PESSOA)
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
        }






		

		//Atualiza o nome.
		$fields = array(
			$db->quoteName('name') . ' = ' . $db->quote($nome . ' ' . $sobrenome),
		);
		$conditions = array(
			$db->quoteName('id') . ' = ' . $user->id
		);
		$query = $db->getQuery(true);
            
		$query->update(ClassificadosControllerPessoa::TB_USERS)->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();
		$app->enqueueMessage(JText::_('INFORMACOES_ALTERADOS'), 'info');
        
        $this->meusdados();
    }



	private function _validaCPF($cpf) {

		// Extrai somente os números
		$cpf = preg_replace( '/[^0-9]/is', '', $cpf );
	
		// Verifica se foi informado todos os digitos corretamente
		if (strlen($cpf) != 11) {
			return false;
		}

		if ($cpf== '00000000000' ||
			$cpf== '11111111111' ||
			$cpf== '22222222222' ||
			$cpf== '33333333333' ||
			$cpf== '44444444444' ||
			$cpf== '55555555555' ||
			$cpf== '66666666666' ||
			$cpf== '77777777777' ||
			$cpf== '88888888888' ||
			$cpf== '99999999999' ||
			$cpf== '01234567890' ) {
			return false;
		}
	
		// Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
		if (preg_match('/(\d)\1{10}/', $cpf)) {
			return false;
		}
	
		// Faz o calculo para validar o CPF
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf[$c] * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf[$c] != $d) {
				return false;
			}
		}
		return true;
	
	}













}