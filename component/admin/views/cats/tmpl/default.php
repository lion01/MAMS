<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$published = $this->state->get('filter.published');
$sortFields = $this->getSortFields();
$db =& JFactory::getDBO();
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_mams&view=cats'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_MAMS_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_MAMS_SEARCH_IN_TITLE'); ?>" />
		</div>
		<div class="btn-group pull-left">
			<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
		</div>	
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
			<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
				<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
				<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
			</select>
		</div>
		<div class="btn-group pull-right">
			<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
			<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
				<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
			</select>
		</div>
	</div>
	
	<div class="clearfix"> </div>
	
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>	
				<th width="1%">
					<?php echo JHtml::_('grid.sort','JSTATUS','c.published', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort','COM_MAMS_CAT_HEADING_NAME','c.cat_title', $listDirn, $listOrder); ?>
				</th>		
				<th width="10%">
					<?php echo JHtml::_('grid.sort','COM_MAMS_CAT_ADDED','c.cat_added', $listDirn, $listOrder); ?>
				</th>		
				<th width="10%">
					<?php echo JHtml::_('grid.sort','COM_MAMS_CAT_MODIFIED','c.cat_modified', $listDirn, $listOrder); ?>
				</th>	
				<th width="5%">
					<?php echo JHtml::_('grid.sort','JGRID_HEADING_ACCESS','c.access', $listDirn, $listOrder); ?>
				</th>
				<th width="1%">
					<?php echo JText::_('COM_MAMS_CAT_HEADING_NUMITEMS'); ?>
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort','COM_MAMS_CAT_HEADING_ID','c.cat_id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		
		
		</thead>
		<tfoot><tr><td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
		<tbody>
		<?php foreach($this->items as $i => $item): ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><?php echo JHtml::_('grid.id', $i, $item->cat_id); ?></td>
				<td class="center"><?php echo JHtml::_('jgrid.published', $item->published, $i, 'cats.', true);?></td>
				<td class="nowrap has-context">
					<div class="pull-left">
						<a href="<?php echo JRoute::_('index.php?option=com_mams&task=cat.edit&cat_id='.(int) $item->cat_id); ?>">
						<?php echo $this->escape($item->cat_title); ?></a>
					</div>
					<div class="pull-left">
						<?php
							// Create dropdown items
							JHtml::_('dropdown.edit', $item->cat_id, 'cat.');
							JHtml::_('dropdown.divider');
							if ($item->published) :
								JHtml::_('dropdown.unpublish', 'cb' . $i, 'cats.');
							else :
								JHtml::_('dropdown.publish', 'cb' . $i, 'cats.');
							endif;
							
							JHtml::_('dropdown.divider');

							if ($trashed) :
								JHtml::_('dropdown.untrash', 'cb' . $i, 'cats.');
							else :
								JHtml::_('dropdown.trash', 'cb' . $i, 'cats.');
							endif;

							// Render dropdown list
							echo JHtml::_('dropdown.render');
							?>
					</div>
				</td>
				<td class="small"><?php echo $item->cat_added; ?></td>
				<td class="small"><?php echo $item->cat_modified; ?></td>
				<td class="small"><?php echo $item->access_level; ?></td>
				<td class="small"><?php 
					$query = 'SELECT count(*) FROM #__mams_artcat WHERE ac_cat='.$item->cat_id;
					$db->setQuery( $query );
					$num=$db->loadResult();
					echo $num;
				?></td>
				<td><?php echo $item->cat_id; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>


