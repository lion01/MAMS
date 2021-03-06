<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class MAMSTableSec extends JTable
{
	protected $tagsHelper = null;
	
	function __construct(&$db) 
	{
		parent::__construct('#__mams_secs', 'sec_id', $db);

		$this->tagsHelper = new JHelperTags();
		$this->tagsHelper->typeAlias = 'com_mams.sec';
	}
	
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_mams.sec.' . (int) $this->$k;
	}
	
	protected function _getAssetTitle()
	{
		return $this->sec_name;
	}
	
	protected function _getAssetParentId($table = null, $id = null)
	{
		$assetId = null;
	
		if ($assetId === null)
		{
			// Build the query to get the asset id for the parent category.
			$query = $this->_db->getQuery(true)
			->select($this->_db->quoteName('id'))
			->from($this->_db->quoteName('#__assets'))
			->where($this->_db->quoteName('name') . ' = ' . $this->_db->quote("com_mams"));
	
			// Get the asset id from the database.
			$this->_db->setQuery($query);
			if ($result = $this->_db->loadResult())
			{
				$assetId = (int) $result;
			}
		}
	
		// Return the asset id.
		if ($assetId)
		{
			return $assetId;
		}
		else
		{
			return parent::_getAssetParentId($table, $id);
		}
	}
	
	public function bind($array, $ignore = '')
	{

		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}
	
		return parent::bind($array, $ignore);
	}
	
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		if ($this->sec_id) {
			// Existing item
			$this->sec_modified		= $date->toSql();
		} else {
			// New section. A section created on field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!intval($this->sec_cadded)) {
				$this->sec_added = $date->toSql();
				$this->sec_modified		= $date->toSql();
			}
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Sec', 'MAMSTable');
		if ($table->load(array('sec_alias'=>$this->sec_alias)) && ($table->sec_id != $this->sec_id || $this->sec_id==0)) {
			$this->setError(JText::_('COM_MAMS_ERROR_UNIQUE_ALIAS'));
			return false;
		}
		// Attempt to store the user data.	
		$this->tagsHelper->preStoreProcess($this);
		$result = parent::store($updateNulls);
		return $result && $this->tagsHelper->postStoreProcess($this);
	}
	
	public function check()
	{
		// check for valid name
		if (trim($this->sec_name) == '') {
			$this->setError(JText::_('COM_MAMS_ERR_TABLES_TITLE'));
			return false;
		}

		// check for existing name
		$query = 'SELECT sec_id FROM #__mams_secs WHERE sec_name = '.$this->_db->Quote($this->sec_name);
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->sec_id)) {
			$this->setError(JText::_('COM_MAMS_ERR_TABLES_NAME'));
			return false;
		}

		if (empty($this->sec_alias)) {
			$this->sec_alias = $this->sec_name;
		}
		$this->sec_alias = JApplication::stringURLSafe($this->sec_alias);
		if (trim(str_replace('-','',$this->sec_alias)) == '') {
			$this->sec_alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		return true;
	}
	
	public function delete($pk = null)
	{
		$result = parent::delete($pk);
		return $result && $this->tagsHelper->deleteTagData($this, $pk);
	}
	
}