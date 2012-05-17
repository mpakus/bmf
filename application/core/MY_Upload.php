<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * File Uploading Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Uploads
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/file_uploading.html
 */
class MY_Upload extends CI_Upload {
	
	var $min_width		= 0;
	var $min_height		= 0;
	
	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */	
	function initialize($config = array())
	{
		$defaults = array(
							'min_width'         => 0,
							'min_height'        => 0,
							'max_size'			=> 0,
							'max_width'			=> 0,
							'max_height'		=> 0,
							'max_filename'		=> 0,
							'allowed_types'		=> "",
							'file_temp'			=> "",
							'file_name'			=> "",
							'orig_name'			=> "",
							'file_type'			=> "",
							'file_size'			=> "",
							'file_ext'			=> "",
							'upload_path'		=> "",
							'overwrite'			=> FALSE,
							'encrypt_name'		=> FALSE,
							'is_image'			=> FALSE,
							'image_width'		=> '',
							'image_height'		=> '',
							'image_type'		=> '',
							'image_size_str'	=> '',
							'error_msg'			=> array(),
							'mimes'				=> array(),
							'remove_spaces'		=> TRUE,
							'xss_clean'			=> FALSE,
							'temp_prefix'		=> "temp_file_"
						);	
	
	
		foreach ($defaults as $key => $val)
		{
			if (isset($config[$key]))
			{
				$method = 'set_'.$key;
				if (method_exists($this, $method))
				{
					$this->$method($config[$key]);
				}
				else
				{
					$this->$key = $config[$key];
				}			
			}
			else
			{
				$this->$key = $val;
			}
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set Minimum Image Width
	 *
	 * @access	public
	 * @param	integer
	 * @return	void
	 */	
	function set_min_width($n)
	{
		$this->min_width = ((int) $n < 0) ? 0: (int) $n;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set Minimum Image Height
	 *
	 * @access	public
	 * @param	integer
	 * @return	void
	 */	
	function set_min_height($n)
	{
		$this->min_height = ((int) $n < 0) ? 0: (int) $n;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Verify that the image is within the allowed width/height
	 *
	 * @access	public
	 * @return	bool
	 */	
	function is_allowed_dimensions()
	{
		if ( ! $this->is_image())
		{
			return TRUE;
		}

		if (function_exists('getimagesize'))
		{
			$D = @getimagesize($this->file_temp);

			if ($this->max_width > 0 AND $D['0'] > $this->max_width)
			{
				return FALSE;
			}

			if ($this->max_height > 0 AND $D['1'] > $this->max_height)
			{
				return FALSE;
			}
			
			if ($this->min_width > 0 AND $D['0'] < $this->min_width)
			{
				return FALSE;
			}

			if ($this->min_height > 0 AND $D['1'] < $this->min_height)
			{
				return FALSE;
			}

			return TRUE;
		}

		return TRUE;
	}

}
// END Upload Class

/* End of file Upload.php */
/* Location: ./system/libraries/Upload.php */
