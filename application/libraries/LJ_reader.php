<?php  
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    class LJ_reader implements Iterator { 
        
        //Response hash from the server
        protected $response;                    
        //Configuration of the XML-RPC requests
        protected $config = array(              
            'encoding' => 'utf-8',
            'escaping' => 'markup',
            'verbosity' => 'no_white_space'
        );
        
        //LJ username
        private $username;
        //LJ password
        private $password;  
        //LJ posts array
        private $posts;  
         //LJ lastN request parameter
        private $nlast;   
        //LJ current last post date
        private $cur_last_date;    
        //LJ cookie
        private $cookie;                        
        
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
            
            //Initializing posts array
            $this->posts = array();
            
            //Finally, authorise
            $this->authorization();
            
            //And fetching first portion of posts
            $this->getPosts($this->username);
        }

        /**
        * Method gets a cookie from LJ 
        */
        protected function authorization() 
        {
            // 1. Getting a challenge
            $params = array ();
            $this->request('getchallenge', $params);
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[authorisation]: XML-RPC error while getting a challenge. ' . 
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
            $this->request('sessiongenerate', $params);
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[authorisation]: XML-RPC error while getting a cookie. ' . 
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
        protected function getContext($request) 
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
        protected function request($procedure, $params)
        {
            $request = xmlrpc_encode_request("LJ.XMLRPC." . $procedure, $params, $this->config);
            $context = $this->getContext($request);
            var_dump($this->cookie);
            $file = file_get_contents("http://www.livejournal.com/interface/xmlrpc", false, $context);
            $this->response = xmlrpc_decode($file);
        }
        
        /**
        * Method makes XML-RPC request to the API and returns posts
        * @param string $user Posts author name
        */
        private function getPosts($user) 
        {
            
            if($user == '') throw new Exception('LJ_reader[getPosts]: User name must not equal to empty string!');                  

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
            $this->request('getevents', $params);
            
            //If response is fault - throw an exception
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[getPosts]: XML-RPC error while receiving posts. ' . 
                                    'Code = ' . $this->response['faultCode'] . ' ' .
                                    'ErrorString = ' . $this->response['faultString']
                                    ); 
            } else {
                //Pushing new posts to the $posts array
                foreach($this->response['events'] as $item) {
                    $this->posts[] = array(
                        'subject' => $item['subject']->scalar,
                        'eventtime' => $item['eventtime'],
                        'event' => $item['event']->scalar
                    );
                    
                    $this->cur_last_date = $item['eventtime'];
                    
                }
            }
            
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
                $this->getPosts($this->username);
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
