<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class MAMSViewArtLinks extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $arttitle;
	
	function display($tpl = null) 
	{
		$this->state		= $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->arttitle = $this->get('ArticleTitle');
		
		MAMSHelper::addArtDDSubmenu(JRequest::getVar('view'),$this->arttitle);
		
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->addToolBar();
		$this->sidebar = JHtmlSidebar::render();	

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		$state	= $this->get('State');
		JToolBarHelper::title(JText::_('COM_MAMS_MANAGER_ARTLINKS'), 'mams');
		JToolBarHelper::addNew('artlink.add', 'COM_MAMS_TOOLBAR_ADD');
		JToolBarHelper::editList('artlink.edit', 'JTOOLBAR_EDIT');
		JToolBarHelper::divider();
		JToolBarHelper::custom('artlinks.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('artlinks.unpublish', 'unpublish.png', 'unpublish_f2.png','JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		if ($state->get('filter.state') == -2) {
			JToolBarHelper::deleteList('', 'artlinks.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else  {
			JToolBarHelper::trash('artlinks.trash');
		}
		
		JHtmlSidebar::setAction('index.php?option=com_mams&view=artlinks');
		
		JHtmlSidebar::addFilter(JText::_('JOPTION_SELECT_PUBLISHED'),'filter_state',JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true));
		JHtmlSidebar::addFilter(JText::_('COM_MAMS_SELECT_FIELD'),'filter_field',JHtml::_('select.options', MAMSHelper::getFields("links"), 'value', 'text', $this->state->get('filter.sec')));
		
	}
	
	protected function getSortFields()
	{
		return array(
				'a.ordering' => JText::_('JGRID_HEADING_ORDERING')
		);
	}
}
