<?php

/**
 * Template library with layouts
 *
 * @version 1.1
 * @author Ibragimov Renat <info@mrak7.com>
 */
class Template{
	protected
		$layout		=	'layouts/index',	// default layout name
        $theme      =   '',         // 
		$settings	=	array(),	// dafault settings
		$_data		=	array()		// data
	;

	/**
	 * Construct take settings from config/template or from parameters
	 *
	 * @param array $settings	settings array
	 */
	public function __construct( $settings='' ){
		CI()->config->load('template');
		$this->settings = CI()->config->item('template');
	}

	/**
	 * Render all template page or part of it
	 *
	 * @param	string	$template
	 * @param	array	$data
	 * @return	string
	 */
	public function render( $template='', $data = array() ){
		if( empty($data) ) $data = &$this->_data;
		$template = not_empty( $template, $this->layout );
		return CI()->load->view( $this->theme.$template, $data, TRUE );
	}

	/**
	 * Render and Show template
	 *
	 * @param	string	$template
	 * @param	array	$data
	 * @return	string
	 */
	public function show( $template='', $data = array() ){
		if( empty($data) ) $data = &$this->_data;
		$template = not_empty( $template, $this->layout );
		CI()->load->view( $this->theme.$template, $data );
	}

	/**
	 * Render template to inside template variable
	 *
	 * @param string $variable
	 * @param string $template
	 * @param mixed $data
	 * @return Template
	 */
	public function render_to( $variable, $template='', $data=array() ){		
		return $this->set( $variable, $this->render( $template, $data ) );
	}

	/**
	 * Рендер страницы или отдача кусочка
	 *
	 * @param string $template
	 * @param bool $comeback
	 * @return Template
	 */
	public function display( $template = '' ){
		if( empty($template) ) $template = $this->layout;
		$this->render( $template, $this->_data, FALSE );
		return $this;
	}

	/**
	 * Set template variable
	 *
	 * @param mixed $name		variable name
	 * @param string $values	value
	 * @return Template
	 */
    public function set( $name, $values = '' ){
        if ( is_array($name) ){
            foreach ($name as $key => $value){
                $this->_data[$key] = $value;
            }
            return $this;
        }        
        if( empty($values) ) $this->data[$name] = '';
        if ( ! is_array($values) ){
            $this->_data[$name] = $values;
            return $this;
        }
        if( is_object($values) ){
            $this->_data[$name] = $values;
            return $this;
        }
        
        foreach ($values as $key => $value){
            $this->_data[$name][$key] = $value;
        }
        return $this;
    }

    /**
     * Append value to the end of variable (alias: append)
	 *
	 * @see append
     * @param mixed $name
     * @param string $value
	 * @return Template
     */
    public function after( $name, $value ){
        return $this->append( $name, $value );
    }

    /**
     * Append value
     *
     * @param mixed $name
     * @param string $value
	 * @return Template
     */
    public function append( $name, $value ){
        if ( ! is_array($value) )
            $this->_data[$name] .= $value;
        else
            $this->_data[$name][] = $value;
		return $this;
    }

    /**
     * Prepend value to the beginning of variable
	 * 
     * @param mixed $name
     * @param string $value
     */
    public function before( $name, $value ){
        if ( !is_array($values) ) $this->_data[$name] = $value.$this->data[$name];
    }

    /**
     * Clean all variables
	 * @return Template
     */
    public function clear_all(){ $this->_data  = ''; return $this; }

    /**
	 * Clean only one parameter
	 */
    public function clean( $name = '' ){ unset($this->_data[ $name ]); return $this; }

	/**
	 * Add javascript file to variable
	 *
	 * @param string	$file			путь к файлу
	 * @param bool		$insert_before	надо ли вставлять ранее уже значений
	 * @return Template
	 */
	public function add_js( $file, $insert_before = FALSE ){
		$file = '<script type="text/javascript" src="'.$file.'"></script>'."\n";
		if( $insert_before )
			$this->before( 'js', $file );
		else
			$this->append( 'js', $file );
		return $this;
	}

	/**
	 * Add css file to the template variable
	 *
	 * @param string	$file			путь к файлу
	 * @param bool		$insert_before	надо ли вставлять ранее уже значений
	 * @return Template
	 */
	public function add_css( $file, $insert_before = FALSE ){
		$file = '<link type="text/css" media="all" rel="stylesheet" href="'.$file.'" />'."\n";
		if( $insert_before )
			$this->before( 'css', $file );
		else
			$this->append( 'css', $file );
		return $this;
	}

	/**
	 * Change our current layout to new
	 *
	 * @param string $layout	path to layout file
	 * @return Template
	 */
	public function set_layout( $layout ){
		$this->layout = $layout;
		return $this;
	}
    
    /**
     * Set the default theme
     * 
     * @param  string $theme theme folder inside views
     * @return Template 
     */
    public function set_theme( $theme='' ){
        $this->theme = $theme;
        return $this;
    }

	/**
	 * Create new object like self
	 *
	 * @return Template
	 */
	public static function factory(){ return new self; }

}