<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';
$ordering = ($listOrder == 'a.ordering');
?>
<form action="<?php echo JRoute::_('index.php?option=com_mams&view=auths'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			
		</div>
		<div class="filter-select fltrt">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
			<select name="filter_access" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
			</select>

		</div>
	</fieldset>
	
	<div class="clr"> </div>
	
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_MAMS_AUTH_HEADING_ID'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>			
				<th>
					<?php echo JHtml::_('grid.sort','COM_MAMS_AUTH_HEADING_NAME','a.auth_name', $listDirn, $listOrder); ?>
				</th>		
				<th width="120">
					<?php echo JHtml::_('grid.sort','COM_MAMS_AUTH_HEADING_SEC','a.auth_sec', $listDirn, $listOrder); ?>
				</th>	
				<th width="120">
					<?php echo JHtml::_('grid.sort','COM_MAMS_AUTH_ADDED','a.auth_added', $listDirn, $listOrder); ?>
				</th>		
				<th width="120">
					<?php echo JHtml::_('grid.sort','COM_MAMS_AUTH_MODIFIED','a.auth_modified', $listDirn, $listOrder); ?>
				</th>	
				<th width="100">
					<?php echo JText::_('JPUBLISHED'); ?>
				</th>
				<th width="100">
					<?php echo JText::_('JGRID_HEADING_ACCESS'); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'auths.saveorder'); ?>
				</th>
			</tr>
		
		
		</thead>
		<tfoot><tr><td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
		<tbody>
		<?php foreach($this->items as $i => $item): ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><?php echo $item->auth_id; ?></td>
				<td><?php echo JHtml::_('grid.id', $i, $item->auth_id); ?></td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_mams&task=auth.edit&auth_id='.(int) $item->auth_id); ?>">
					<?php echo $this->escape($item->auth_name); ?></a>
					<?php if ($item->auth_mirror) echo 'Mirror: '.$item->auth_mirror; ?>
					<p class="smallsub"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->auth_alias));?></p>
				</td>
				<td><?php echo $item->sec_name; ?></td>
				<td><?php echo $item->auth_added; ?></td>
				<td><?php echo $item->auth_modified; ?></td>
				<td class="center"><?php echo JHtml::_('jgrid.published', $item->published, $i, 'auths.', true);?></td>
				<td><?php echo $item->access_level; ?></td>
				<td class="order">
				<?php if ($saveOrder) :?>
					<?php if ($listDirn == 'asc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->auth_sec == @$this->items[$i-1]->auth_sec), 'auths.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->auth_sec == @$this->items[$i+1]->auth_sec), 'auths.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php elseif ($listDirn == 'desc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->auth_sec == @$this->items[$i-1]->auth_sec), 'auths.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->auth_sec == @$this->items[$i+1]->auth_sec), 'auths.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				<?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
				
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>


