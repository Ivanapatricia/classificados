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

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;



DEFINE('VERSAO', '1.0.0');
DEFINE('SELECTED', ' SELECTED');




if(!defined('DS')){
	DEFINE('DS',DIRECTORY_SEPARATOR);
}





$controller = BaseController::getInstance('classificados');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
