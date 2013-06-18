<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


// import Joomla view library
jimport('joomla.application.component.view');

class MAMSViewArtAuth extends JViewLegacy
{
	/**
	 * display method of view
	 * @return void
	 */
	public function display($tpl = null) 
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
		$script = $this->get('Script');
		// Set the submenu
		MAMSHelper::addSubmenu(JRequest::getVar('view'),JRequest::getCmd('extension', 'com_mams'));

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = $this->item->aa_id == 0;
		JToolBarHelper::title($isNew ? JText::_('COM_MAMS_MANAGER_ARTAUTH_NEW') : JText::_('COM_MAMS_MANAGER_ARTAUTH_EDIT'), 'mams');
		// Built the actions for new and existing records.
		if ($isNew) 
		{
			// For new records, check the create permission.
			JToolBarHelper::apply('artauth.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('artauth.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('artauth.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('artauth.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::apply('artauth.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('artauth.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('artauth.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::custom('artauth.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			JToolBarHelper::cancel('artauth.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = $this->item->aa_id == 0;
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_MAMS_ARTAUTH_CREATING') : JText::_('COM_MAMS_ARTAUTH_EDITING'));
		$document->addScript(JURI::root() . $this->script);
		JText::script('COM_MAMS_ARTAUTH_ERROR_UNACCEPTABLE');
	}
}
