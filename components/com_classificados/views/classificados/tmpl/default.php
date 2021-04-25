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
use Joomla\CMS\Layout\FileLayout;

/** @var ClassificadosViewClassificados $this */

HTMLHelper::_('script', 'com_classificados/script.js', ['version' => 'auto', 'relative' => true]);
HTMLHelper::_('stylesheet', 'com_classificados/style.css', ['version' => 'auto', 'relative' => true]);

$layout       = new FileLayout('classificados.page');
$data         = [];
$data['text'] = 'Hello Joomla!';
echo $layout->render($data);


