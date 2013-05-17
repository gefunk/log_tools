<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AttachmentModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	* get an attachment id to use
	* @param element_type the type of element this attachment refers to
	* @return the id of the attachment inserted
	*/
	function get_next_attachment_id($element_type)
	{
		$data = array("element" => $element_type);
		$this->db->insert("attachment", $data);
		return $this->db->insert_id();
	}
	
	/*
	CREATE TABLE `attachment_storage` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `key` varchar(255) NOT NULL DEFAULT '',
	  `content_type` varchar(255) NOT NULL DEFAULT '',
	  `attachment_id` int(11) unsigned NOT NULL,
	  `local` tinyint(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	*/
	function add_attachment_for_id($attachment_id, $key, $content_type, $local)
	{
		$data = array("key" => $key, "content_type" => $content_type, "local" => $local, "attachment_id" => $attachment_id);
		$this->db->insert("attachment_storage", $data);
	}
	
}
/** end model **/