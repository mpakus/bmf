<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


    /**
    * Lj_reader
    *
    * @package	Import from social networks
    * @subpackage	Livejournal
    * @category	Data transfer
    * @author	denied
    * @link	http://aomega.ru
    */
    class Lj_reader implements Iterator { 
        
        protected
            $response,          //Response hash from the server                  
            $config = array(    //Configuration of the XML-RPC requests        
                'encoding' => 'utf-8',
                'escaping' => 'markup',
                'verbosity' => 'no_white_space'
            );
        
        private
            $username,          //LJ username
            $password,          //LJ password
            $posts,             //LJ posts array  
            $nlast,             //LJ lastN request parameter   
            $cur_last_date,     //LJ current last post date
            $cookie,            //LJ cookie 
            $comments;          //Download comments? (true/false)       
        
        /**
        * Constructor
        * @param array $params Username/Password in array
        */
        public function __construct($params) 
        {
            //If either username or password are empty - throw an exception 
            if($params['username'] == '' || $params['password'] == '')
                throw new Exception('LJ_reader[__construct]: Username and password must not be empty!');
            
            //Filling up username and password
            $this->username = $params['username'];
            $this->password = $params['password'];
            
            //Filling lastN parameter (default value = 10)
            if($params['nlast'] == '') $this->nlast = 10;
            else $this->nlast = $params['nlast'];
            
            //Filling download comments flag
            if($params['comments'] == true) $this->comments = true;
            else $this->comments = false;
                   
            //Initializing posts array
            $this->posts = array();
            
            //Finally, authorise
            $this->_authorization();
            
            //And fetching first portion of posts
            $this->_get_posts($this->username);
        }

        /**
        * Method gets a cookie from LJ 
        */
        private function _authorization() 
        {
            // 1. Getting a challenge
            $params = array ();
            $this->_request('getchallenge', $params);
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[_authorisation]: XML-RPC error while getting a challenge. ' . 
                                    'Code = ' . $this->response['faultCode'] . ' ' .
                                    'ErrorString = ' . $this->response['faultString']
                                    );                  
            } else {
                $auth_challenge = $this->response['challenge'];
                $auth_response = md5($auth_challenge . md5($this->password));
            }
            
            // 2. Getting a cookie
            $params = array(
                'username' => $this->username,
                'auth_method' => 'challenge',
                'auth_challenge' => $auth_challenge,
                'auth_response' => $auth_response
            );
            $this->_request('sessiongenerate', $params);
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[_authorisation]: XML-RPC error while getting a cookie. ' . 
                                    'Code = ' . $this->response['faultCode'] . ' ' .
                                    'ErrorString = ' . $this->response['faultString']
                                    );                  
            } else {
                $this->cookie = $this->response['ljsession'];
            }
        }

        /**
        * Method returns context for the POST request to the API
        * @param string $request XML-RPC request
        * @return resource
        */
        private function _get_context($request) 
        {
            if ($this->cookie != '') 
                $header = array(
                    "Content-Type: text/xml; charset=UTF-8", 
                    "X-LJ-Auth: cookie",
                    "Cookie: ljsession=" . $this->cookie
                );
            else
                $header = "Content-Type: text/xml; charset=UTF-8";
            
            return stream_context_create(array('http' => array(
                            'method' => "POST",
                            'header' => $header,
                            'content' => $request
                            )));
        }

        /**
        * Method makes XML-RPC request to the API
        * @param string $procedure Procedure's name
        * @param array $params Request's information
        */
        private function _request($procedure, $params)
        {
            $request = xmlrpc_encode_request("LJ.XMLRPC." . $procedure, $params, $this->config);
            $context = $this->_get_context($request);
            $file = file_get_contents("http://www.livejournal.com/interface/xmlrpc", false, $context);
            $this->response = xmlrpc_decode($file);
        }
        
        /**
        * Method makes XML-RPC request to the API and returns posts
        * @param string $user Posts author name
        */
        private function _get_posts($user) 
        {
            
            if($user == '') throw new Exception('LJ_reader[_get_posts]: User name must not equal to empty string!');                  

            //Filling params array for request
            $params = array(
                'username' => $this->username,
                'auth_method' => 'cookie',
                'selecttype' => 'lastn',
                'howmany' => $this->nlast,
                'lineendings' => 'unix',
                'ver' => '1',
                'usejournal' => $user,
                'beforedate' => $this->cur_last_date
            );
            
            //Sending request 
            $this->_request('getevents', $params);
            
            //If response is fault - throw an exception
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[_get_posts]: XML-RPC error while receiving posts. ' . 
                                    'Code = ' . $this->response['faultCode'] . ' ' .
                                    'ErrorString = ' . $this->response['faultString']
                                    ); 
            } else {
                //Pushing new posts to the $posts array
                foreach($this->response['events'] as $item) {
                    $this->posts[$item['itemid']] = array(
                        'jitemid' => $item['itemid'],
                        'anum' => $item['anum'],
                        'subject' => $item['subject']->scalar,
                        'eventtime' => $item['eventtime'],
                        'event' => $item['event']->scalar
                    );
                    
                    //If comments is true - fetching the comments
                    if($this->comments) {
                        $this->posts[$item['itemid']]['comments'] = $this->_get_comments($user, $item['itemid']*256 + $item['anum']);
                    }
                    
                    $this->cur_last_date = $item['eventtime'];
                    
                }
            }
            
        }
        
        /**
        * Method makes XML-RPC request to the API and returns comments
        * @param string $user Posts author name
        * @param number $ditemid Posts ditemid = jitemid*256 + anum 
        */
        private function _get_comments($user, $ditemid) 
        {
            
            if($user == '') throw new Exception('LJ_reader[_get_comments]: User name must not equal to empty string!');                  
            if($ditemid == '') throw new Exception('LJ_reader[_get_comments]: ditemid name must not equal to empty string!');                  
            
            //Return array
            $ret = array();
           
            //Filling params array for request
            $params = array(
                'username' => $this->username,
                'auth_method' => 'cookie',
                'lineendings' => 'unix',
                'ver' => '1',
                'journal' => $user,
                'beforedate' => $this->cur_last_date,
                'ditemid' => $ditemid
            );
            
            //Sending request 
            $this->_request('getcomments', $params);
            
            //If response is fault - throw an exception
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[_get_comments]: XML-RPC error while receiving posts. ' . 
                                    'Code = ' . $this->response['faultCode'] . ' ' .
                                    'ErrorString = ' . $this->response['faultString']
                                    ); 
            } else {
                //Filling up $ret array with a nested comments
                if(!empty($this->response['comments'])) {
                    foreach($this->response['comments'] as $comment) {
                        $ret[] = $this->_get_comment_info($comment); 
                    }
                }
            }
            
            //Return nested comments tree for $ditemid post
            return $ret;
        }
        
        /**
        * Method makes recursive view into the each comment tree
        * @param array $comment Comment
        */
        private function _get_comment_info($comment)
        {
            //Returning array
            $ret = array();
            
            //Filling up the main fields
            $ret['posterid'] = $comment['posterid'];
            $ret['body'] = $comment['body']->scalar;
            $ret['level'] = $comment['level'];
            $ret['dtalkid'] = $comment['dtalkid'];
            $ret['postername'] = $comment['postername'];
            $ret['datepostunix'] = $comment['datepostunix'];
            
            //If comment have childs - recursive search begins...
            if(!empty($comment['children'])) {
                foreach($comment['children'] as $child) {
                    $ret['children'][] = $this->_get_comment_info($child);
                }
            }
            
            //Return comment array
            return $ret;
        }
        
        /* Iterator interface implementation */
        public function current() 
        {
            return current($this->posts);
        }
        
        public function key()
        {
            return key($this->posts);
        }
        
        public function next()
        {
            if ((key($this->posts) + 1) % $this->nlast == 0)
                $this->_get_posts($this->username);
            return next($this->posts);
        }
        
        public function rewind()
        {
            reset($this->posts);
        }
        
        public function valid()
        {
            $key = key($this->posts);
            return ($key !== NULL && $key !== FALSE);
        }
        
    }
?>
