<?php
/**
 * Kunena Component
 * @package Kunena.Template.Blue_Eagle
 * @subpackage Category
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();
?>
<div class="kblock">
	<div class="kheader">
		<span><?php if (!empty($this->header)) echo $this->escape($this->header); ?></span>
	</div>

	<div class="kcontainer">
		<div class="kbody">
			<table class="kblocktable" id="kflattable">
			<?php if (!count ( $this->categories ) ) : ?>
			<tr class="krow2">
				<td class="kcol-first">
					<?php echo JText::_('COM_KUNENA_CATEGORY_SUBSCRIPTIONS_NONE') ?>
				</td>
			</tr>
			<?php
			else :
				foreach ($this->categories as $this->category) {
					$this->displayTemplateFile('category', 'user', 'row');
				}
			endif;
			?>
			</table>
		</div>
	</div>
</div>
