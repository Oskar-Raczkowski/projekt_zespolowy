<?php
/**
 * Kunena Component
 * @package Kunena.Template.Blue_Eagle
 * @subpackage Announcement
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

?>
<div class="kblock kannouncement">
	<div class="kheader">
			<span><?php echo $this->displayField('title') ?></span>
	</div>
	<div class="kcontainer" id="kannouncement">
		<?php echo $this->displayActions() ?>
		<div class="kbody">
			<div class="kanndesc">
				<?php if ($this->showdate) : ?>
				<div class="anncreated" title="<?php echo $this->displayField('created', 'ago'); ?>">
					<?php echo $this->displayField('created', 'date_today') ?>
				</div>
				<?php endif; ?>
				<div class="anndesc"><?php echo $this->displayField('description') ?></div>
			</div>
		</div>
	</div>
</div>
