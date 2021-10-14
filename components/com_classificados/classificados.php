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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;


DEFINE('VERSAO', '1.0.0');
DEFINE('SELECTED', ' SELECTED');



JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');
//JHtml::_('jquery.ui');
//JHTML::_('behavior.tooltip');
$doc = JFactory::getDocument();
$doc->addScript(JURI::base() . 'media/com_classificados/js/jquery.mask.js?v='.VERSAO);

if(!defined('DS')){
	DEFINE('DS',DIRECTORY_SEPARATOR);
}





$controller = BaseController::getInstance('classificados');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
