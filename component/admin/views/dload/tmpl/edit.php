<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$params = $this->form->getFieldsets('params');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'dload.cancel' || document.formvalidator.isValid(document.id('mams-form'))) {
			Joomla.submitform(task, document.getElementById('mams-form'));
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_mams&extension='.JRequest::getCmd('extension', 'com_mams').'&layout=edit&dl_id='.(int) $this->item->dl_id); ?>" method="post" name="adminForm" id="mams-form" class="form-validate">
	<div class="row-fluid">
		<div class="span12 form-horizontal">
			<h4><?php echo JText::_( 'COM_MAMS_DLOAD_DETAILS' ); ?></h4>
			<?php foreach($this->form->getFieldset('details') as $field): ?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label;?></div>
					<div class="controls"><?php echo $field->input;?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div>
		<input type="hidden" name="task" value="dload.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

