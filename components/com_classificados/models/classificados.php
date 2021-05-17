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

use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * Classifi cados model.
 *
 * @package   classificados
 * @since     1.0.0
 */
class ClassificadosModelClassificados extends BaseDatabaseModel
{


    	/**
	 * Verifica se o usuário está logado.
	 *
	 * @param $task Tarefa que deve ser acionada do componente após o login.
	 * @return bool Retorna False se não estiver logado e true caso esteja.
	 * @throws Exception Não prvisto.
	 */
	public function isLogado($task){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
        $itemid = $app->input->get('Itemid', '', 'string');
		$urlRetorno = urlencode(base64_encode( 'index.php?option=com_classificados&task=' . $task . '&Itemid=' . $itemid));
		$login =  JRoute::_ ( 'index.php?option=com_users&view=login&Itemid=' . $itemid . '&return=' . $urlRetorno, false );
		if ($user == null || $user->id == null || $user->id == 0) {
			$app->redirect ($login, "" );
			return false;
		}
		return true;
	}

	public function gerarToken($token){
		$retorno = ''; 
		$id = uuid();
		switch(rand ( 1 , 4 )){
			case 1:
				$retorno = "<input id='$id' type='hidden' value='$token' name='1' id=/>";
				break;
			case 2:
				$retorno = "<input value='$token' type='hidden' id='$id' name='1' />";
				break;
			case 3:
				$retorno = "<input id='$id' name='1' value='$token' type='hidden' />";
				break;
			default:
				$retorno = "<input type='hidden' id='$id'  name='1'  value='$token' />";
				break;
		}

		return $retorno;
	}

}
