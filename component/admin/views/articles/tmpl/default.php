<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$app	= JFactory::getApplication();
$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder = ($listOrder == 'a.ordering');
$published = $this->state->get('filter.published');
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_mams&task=articles.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'MAMSArtList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
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
<form action="<?php echo JRoute::_('index.php?option=com_mams&view=articles'); ?>" method="post" name="adminForm" id="adminForm">
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
	
	<table class="adminlist table table-striped" id="MAMSArtList">
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
				</th>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>	
				<th width="1%" style="min-width:55px" class="nowrap center">
					<?php echo JHtml::_('grid.sort','JSTATUS','s.published', $listDirn, $listOrder); ?>
				</th>		
				<th>
					<?php echo JHtml::_('grid.sort','COM_MAMS_ARTICLE_HEADING_TITLE','a.art_title', $listDirn, $listOrder); ?>
				</th>		
				<th width="13%">
					<?php echo JHtml::_('grid.sort','COM_MAMS_ARTICLE_HEADING_PUBLISH_ON','a.art_publish_up', $listDirn, $listOrder); ?> - 
					<?php echo JHtml::_('grid.sort','COM_MAMS_ARTICLE_HEADING_PUBLISH_DOWN','a.art_publish_down', $listDirn, $listOrder); ?>
				</th>	
				<th width="10%">
					<?php echo JText::_('COM_MAMS_ARTICLE_HEADING_TAGS'); ?>
				</th>
				<th width="10%" class="hidden-phone">
					<?php echo JHtml::_('grid.sort','COM_MAMS_ARTICLE_HEADING_ADDED','a.art_added', $listDirn, $listOrder); ?>
				</th>		
				<th width="10%" class="hidden-phone">
					<?php echo JHtml::_('grid.sort','COM_MAMS_ARTICLE_HEADING_MODIFIED','a.art_modified', $listDirn, $listOrder); ?>
				</th>	
				<th width="5%">
					<?php echo JText::_('JFEATURED'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort','JGRID_HEADING_ACCESS','a.access', $listDirn, $listOrder); ?>
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort','COM_MAMS_ARTICLE_HEADING_HITS','a.art_hits', $listDirn, $listOrder); ?>
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort','COM_MAMS_ARTICLE_HEADING_ID','a.art_id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		
		
		</thead>
		<tfoot><tr><td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
		<tbody>
		<?php foreach($this->items as $i => $item): 
			$canCreate = $user->authorise('core.create', 'com_mams.sec.'.$item->art_sec);
			$canEdit = $user->authorise('core.edit', 'com_mams.article.'.$item->art_id);
			$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canEditOwn = $user->authorise('core.edit.own', 'com_mams.article.'.$item->art_id) && $item->art_added_by == $userId;
			$canEditDrilldowns = $user->authorise('core.edit.drilldowns', 'com_mams.article.'.$item->art_id);
			$canFeature = $user->authorise('core.edit.featured', 'com_mams.article.'.$item->art_id);
			$canChange = $user->authorise('core.edit.state', 'com_mams.article.'.$item->art_id) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->art_publish_up?>">
				<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " /> 
					<?php else : ?>	
						<span class="sortable-handler inactive" >
						<i class="icon-menu"></i>
						</span>
					<?php endif; ?>

				</td>
				<td><?php echo JHtml::_('grid.id', $i, $item->art_id); ?></td>
				<td class="center">
					<div class="btn-group">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'articles.', $canChange); ?>
						<?php echo JHtml::_('mamsadministrator.featured', $item->featured, $i, $canFeature); ?>
					</div>
				</td>
				<td class="nowrap has-context">
					<div class="pull-left">
					
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'articles.', $canCheckin); ?>
						<?php endif; ?>
						<?php if ($canEdit || $canEditOwn) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_mams&task=article.edit&art_id=' . $item->art_id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
							<?php echo $this->escape($item->art_title); ?></a>
						<?php else : ?>
							<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->art_title); ?></span>
						<?php endif; ?>
					
						<div class="small">Section: <?php echo $item->sec_name;?></div>
					</div>
					<div class="pull-left">
						<?php
							// Create dropdown items
							if ($canEdit || $canEditOwn) :
								JHtml::_('dropdown.edit', $item->art_id, 'article.');
								JHtml::_('dropdown.divider');
							endif;
							
							if ($canChange) :
								if ($item->state) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'articles.');
								else :
									JHtml::_('dropdown.publish', 'cb' . $i, 'articles.');
								endif;
								
								JHtml::_('dropdown.divider');
								
								if ($canFeature) :
									if ($item->featured) :
										JHtml::_('dropdown.unfeatured', 'cb' . $i, 'articles.');
									else :
										JHtml::_('dropdown.featured', 'cb' . $i, 'articles.');
									endif;
									
									JHtml::_('dropdown.divider');
								endif;
		
								if ($trashed) :
									JHtml::_('dropdown.untrash', 'cb' . $i, 'articles.');
								else :
									JHtml::_('dropdown.trash', 'cb' . $i, 'articles.');
								endif;
							endif;
							// Render dropdown list
							if ($canEdit || $canEditOwn || $canChange) :
								echo JHtml::_('dropdown.render');
							endif;
							?>
					</div>
				</td>
				<td class="small"><?php echo $item->art_publish_up; ?> - <?php echo $item->art_publish_down; ?></td>
				<td class="small"><?php 
					if ($canEditDrilldowns) :
						echo '<button class="btn btn-small" type="button" onclick="return listItemTask(\'cb'.$i.'\',\'articles.drilldowns\')">Drill Downs';
						echo '</button>';
					endif;
				?></td>
				<td class="small hidden-phone"><?php echo $item->art_added.'<br />'.$item->adder; ?></td>
				<td class="small hidden-phone"><?php echo $item->art_modified.'<br />'.$item->modifier; ?></td>
				<td class="small"><?php echo $item->feataccess_level; ?></td>
				<td class="small"><?php echo $item->access_level; ?></td>
				<td class="small"><?php echo $item->art_hits; ?></td>
				<td><?php echo $item->art_id; ?></td>
				
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="modal hide fade" id="collapseModal">
		<div class="modal-header">
			<button type="button" role="presentation" class="close" data-dismiss="modal">x</button>
			<h3><?php echo JText::_('COM_MAMS_ARTICLE_BATCH_OPTIONS');?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::_('COM_MAMS_ARTICLE_BATCH_TIP'); ?></p>
			<div class="control-group">
				<div class="controls">
					<?php echo JHtml::_('batch.access'); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<?php 
						echo '<label id="batch-feataccess-lbl" for="batch-feataccess" class="hasTip" title="' . JText::_('COM_MAMS_ARTICLE_BATCH_FEATACCESS_LABEL') . '::'. JText::_('COM_MAMS_ARTICLE_BATCH_FEATACCESS_LABEL_DESC') . '">';
						echo JText::_('COM_MAMS_ARTICLE_BATCH_FEATACCESS_LABEL').'</label>';
						echo JHtml::_('access.assetgrouplist','batch[featassetgroup_id]', '','class="inputbox"',array('title' => JText::_('JLIB_HTML_BATCH_NOCHANGE'),'id' => 'batch-feataccess')); 
					?>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">		
					<?php 
						echo '<label id="batch-section-lbl" for="featsection_id" class="hasTip" title="' . JText::_('COM_MAMS_ARTICLE_BATCH_SECTION_LABEL') . '::'. JText::_('COM_MAMS_ARTICLE_BATCH_SECTION_LABEL_DESC') . '">';
						echo JText::_('COM_MAMS_ARTICLE_BATCH_SECTION_LABEL').'</label>';
					?>
					<select name="batch[featsection_id]" class="inputbox" id="featsection_id">
						<option value="*"><?php echo JText::_('COM_MAMS_SELECT_SEC');?></option>
						<?php echo JHtml::_('select.options', MAMSHelper::getSections("article"), 'value', 'text', "");?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">		
					<?php 
						echo '<label id="batch-section-lbl" for="batch-addcat" class="hasTip" title="' . JText::_('COM_MAMS_ARTICLE_BATCH_ADDCAT_LABEL') . '::'. JText::_('COM_MAMS_ARTICLE_BATCH_ADDCAT_DESC') . '">';
						echo JText::_('COM_MAMS_ARTICLE_BATCH_ADDCAT_LABEL').'</label>';
					?>
					<select name="batch[batch-addcat]" class="inputbox" id="batch-addcat">
						<option value="*"><?php echo JText::_('COM_MAMS_SELECT_Cat');?></option>
						<?php echo JHtml::_('select.options', MAMSHelper::getCats(), 'value', 'text', "");?>
					</select>
				</div>
			</div>
		</div>
		<div class="modal-footer">	
			<button class="btn" type="button" onclick="document.id('batch-access').value='';document.id('batch-feataccess').value='';document.id('featsection_id').value='';" data-dismiss="modal">
				<?php echo JText::_('JCANCEL'); ?>
			</button>
			<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('article.batch');">
				<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
			</button>
		</div>
	</div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</div>
</form>


