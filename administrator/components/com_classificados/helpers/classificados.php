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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * Classificados helper.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosHelper
{
	/**
	 * Render submenu.
	 *
	 * @param   string  $vName  The name of the current view.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function addSubmenu($vName)
	{
		HTMLHelper::_(
			'sidebar.addEntry',
			Text::_('COM_CLASSIFICADOS'),
			'index.php?option=com_classificados&view=classificadoss',
			$vName === 'classificadoss'
		);
	}
}
