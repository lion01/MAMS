<?php

// no direct access
defined('_JEXEC') or die;

class MAMSTableFeaturedMedia extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__mams_mediafeat', 'mf_id', $db);
	}
}