<?php
/**
 * @todo: need to understand why this class now it's not extend and don't working
 */
class MY_Profiler extends CI_Profiler{

 	public function __construct(){
 		parent::__construct();
 	}

	/**
	 * Run the Profiler
	 *
	 * @access	private
	 * @return	string
	 */
	public function run(){
		$output = '
			<a href="#" id="open_debug"><span class="ui-icon ui-icon-gear"></span></a>
			<div id="codeigniter_profiler" class="ui-corder-all">
		';
		$output .= $this->_compile_uri_string();
		$output .= $this->_compile_controller_info();
		$output .= $this->_compile_memory_usage();
		$output .= $this->_compile_benchmarks();
		$output .= $this->_compile_get();
		$output .= $this->_compile_post();
		$output .= $this->_compile_queries();
		$output .= $this->_compile_config();
		$output .= $this->_compile_http_headers();
		$output .= '</div>';
		return $output;
	}

}