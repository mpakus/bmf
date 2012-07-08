<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    /**
    * Lj_reader
    *
    * @package	Import from social networks
    * @subpackage	Livejournal
    * @category	Data transfer
    * @author	Anton "denied" Lvov <ant.lvov@gmail.com>
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
            $users,             //LJ users
            $nlast,             //LJ lastN request parameter   
            $cur_last_date,     //LJ current last post date
            $cookie,            //LJ cookie 
            $comments;          //Download comments? (true/false)  
               
        /**
        * Constructor
        *
        * @param array $params Username/Password in array, Nlast records, prefetch true/false
        */
        public function __construct($params) 
        {
            //If either username or password are empty - throw an exception 
            if($params['username'] == '')
                throw new Exception('LJ_reader[__construct]: Username must not be empty!');
         
            if($params['password'] == '' and $params['cookie'] == '')
                throw new Exception('LJ_reader[__construct]: Password or cookie must not be empty!');
         
            //Filling up username and password
            $this->username = $params['username'];
            
            //Filling lastN parameter (default value = 10)
            if($params['nlast'] == '') $this->nlast = 10;
            else $this->nlast = $params['nlast'];
 
            //Filling download comments flag
            if($params['comments'] == true) $this->comments = true;
            else $this->comments = false;

            //Initializing posts array
            $this->posts = array();

            //Initializing users array
            $this->users = array();
            
            //If cookie is filled - set cookie
            if ($params['cookie'] != '') {
                
                $this->cookie = $params['cookie'];
                
              //If password is filled - do authorization  
            } elseif($params['password'] != '') {
                
                $this->password = $params['password'];

                //Finally, authorise
                $this->authorization();
                
            } 
            
            //And get the first portion of posts (if prefetch is enabled)
            if($params['prefetch'] == true) $this->fetch_posts($this->username);

        }

        /**
         * Methos returns current lj cookie
         *
         * @return string LJ cookie 
         */
        public function get_cookie() 
        {
            return $this->cookie;
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
        *
        * @param string $request XML-RPC request
        * @return resource
        */
        protected function get_context($request) 
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
        *
        * @param string $procedure Procedure's name
        * @param array $params Request's information
        */
        protected function request($procedure, $params)
        {
            if($procedure == '')
                throw new Exception('LJ_reader[request]: request must not be empty!');
            
            $request = xmlrpc_encode_request("LJ.XMLRPC." . $procedure, $params, $this->config);
            $context = $this->get_context($request);
            $file = file_get_contents("http://www.livejournal.com/interface/xmlrpc", false, $context);
            $this->response = xmlrpc_decode($file);
            
        }
        
        /**
        * Method makes HTTP request to the LJ FOAF service
        *
        * @param string $user user name
        */
        protected function foaf_request($user)
        {
            if($user == '')
                throw new Exception('LJ_reader[foaf_request]: user name must not be empty!');
            
            $context = $this->get_context(null);
            $file = file_get_contents("http://".$user.".livejournal.com/data/foaf", false, $context);
            
            return $file;            
        }
        
        /**
        * Method makes XML-RPC request to the API and returns posts
        *
        * @param string $user Posts author name
        */
        public function fetch_posts($user = '', $beforedate = '') 
        {
            
            if($user == '') $user = $this->username;
            if($user == '') throw new Exception('LJ_reader[fetch_posts]: User name must not equal to empty string!');                  
            if($beforedate == '') $beforedate = $this->cur_last_date;

            //Filling params array for request
            $params = array(
                'username' => $this->username,
                'auth_method' => 'cookie',
                'selecttype' => 'lastn',
                'howmany' => $this->nlast,
                'lineendings' => 'unix',
                'ver' => '1',
                'usejournal' => $user,
                'beforedate' => $beforedate
            );

            //Sending request 
            $this->request('getevents', $params);
            
            //If response is fault - throw an exception
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[fetch_posts]: XML-RPC error while receiving posts. ' . 
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
                        $this->posts[$item['itemid']]['comments'] = $this->fetch_comments($user, $item['itemid']*256 + $item['anum']);
                    }
                    
                    //Put current post to the return array 
                    $ret[$item['itemid']] = $this->posts[$item['itemid']];    

                    $this->cur_last_date = $item['eventtime'];
                    
                }
            }

            return $ret;
            
        }
        
        /**
        * Method makes XML-RPC request to the API and returns comments
        *
        * @param string $user Posts author name
        * @param number $ditemid Posts ditemid = jitemid*256 + anum 
        */
        protected function fetch_comments($user, $ditemid) 
        {
            
            if($user == '') throw new Exception('LJ_reader[fetch_comments]: User name must not equal to empty string!');                  
            if($ditemid == '') throw new Exception('LJ_reader[fetch_comments]: ditemid name must not equal to empty string!');                  
            
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
            $this->request('getcomments', $params);
            
            //If response is fault - throw an exception
            if (xmlrpc_is_fault($this->response)) {
                throw new Exception('LJ_reader[fetch_comments]: XML-RPC error while receiving posts. ' . 
                                    'Code = ' . $this->response['faultCode'] . ' ' .
                                    'ErrorString = ' . $this->response['faultString']
                                    ); 
            } else {
                //Filling up $ret array with a nested comments
                if(!empty($this->response['comments'])) {
                    foreach($this->response['comments'] as $comment) {
                        $ret[] = $this->fetch_comment_info($comment); 
                    }
                }
            }
            
            //Return nested comments tree for $ditemid post
            return $ret;
        }
        
        /**
        * Method makes recursive view into the each comment tree
        *
        * @param array $comment Comment
        */
        protected function fetch_comment_info($comment)
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
            
            //Filling up user info (for non-anonym users)
            if($comment['postername'] != '')
                $this->fetch_user_info($comment['postername']);   
            
            //If comment have childs - recursive search begins...
            if(!empty($comment['children'])) {
                foreach($comment['children'] as $child) {
                    $ret['children'][] = $this->fetch_comment_info($child);
                }
            }
            
            //Return comment array
            return $ret;
        }
        
        /**
        * Method stores LJ user info in $this->users array (with foaf_request method)
        *
        * and returns user data in the hash array
        * @param string $user user
        */
        protected function fetch_user_info($user)
        {
            //Check if $user is already in $this->users array
            //If not - fetching his info
            if($this->users[$user] == '') {
                
                //Making a HTTP request to the FOAF LJ service                        
                $response = $this->foaf_request($user);              
                
                libxml_use_internal_errors(true);
                $check = simplexml_load_string($response);
                if (!$check) {
                    $this->users[$user]['nick'] = $user;
                    $this->users[$user]['deleted'] = true;                
                } else {
                
                        $fethed_user = new SimpleXMLElement($response);

                        //Nickname
                        $this->users[$user]['nick'] = (string) $fethed_user->children('foaf', true)->Person->
                                                                            children('foaf', true)->nick;
                        //Full name
                        $this->users[$user]['name'] = (string) $fethed_user->children('foaf', true)->Person->
                                                                            children('foaf', true)->name;
                        //Journal title
                        $this->users[$user]['journaltitle'] = (string) $fethed_user->children('foaf', true)->Person->
                                                                                    children('lj', true)->journaltitle;
                        //Journal subtitle
                        $this->users[$user]['journalsubtitle'] = (string) $fethed_user->children('foaf', true)->Person->
                                                                                        children('lj', true)->journalsubtitle;
                        //OpenID
                        if($fethed_user->children('foaf', true)->Person->children('foaf', true)->openid &&
                        $fethed_user->children('foaf', true)->Person->children('foaf', true)->openid->
                                        attributes()) {
                            $attr = $fethed_user->children('foaf', true)->Person->children('foaf', true)->openid->
                                            attributes('rdf', true);
                            $this->users[$user]['openid'] = (string) $attr['resource'];
                        }
                        //Country                
                        if($fethed_user->children('foaf', true)->Person->children('ya', true)->country &&
                        $fethed_user->children('foaf', true)->Person->children('ya', true)->country->
                                        attributes()) {
                            $attr = $fethed_user->children('foaf', true)->Person->children('ya', true)->country->
                                            attributes('dc', true);
                            $this->users[$user]['country'] = (string) $attr['title'];
                        }
                        //City
                        if($fethed_user->children('foaf', true)->Person->children('ya', true)->city &&
                        $fethed_user->children('foaf', true)->Person->children('ya', true)->city->
                                        attributes()) {
                            $attr = $fethed_user->children('foaf', true)->Person->children('ya', true)->city->
                                            attributes('dc', true);
                            $this->users[$user]['city'] = (string) $attr['title'];
                        }
                        //Date of birth                
                        $this->users[$user]['date_of_bitrh'] = (string) $fethed_user->children('foaf', true)->Person->
                                                                                    children('foaf', true)->dateOfBirth;
                        //User image URL
                        if($fethed_user->children('foaf', true)->Person->children('foaf', true)->img &&
                        $fethed_user->children('foaf', true)->Person->children('foaf', true)->img->
                                        attributes()) {
                                $attr = $fethed_user->children('foaf', true)->Person->children('foaf', true)->img->
                                            attributes('rdf', true);
                                $this->users[$user]['img'] = (string) $attr['resource'];
                        }
                        //ICQ
                        $this->users[$user]['icq'] = (string) $fethed_user->children('foaf', true)->Person->
                                                                            children('foaf', true)->icqChatID;
                        //Biography
                        $this->users[$user]['bio'] = (string) $fethed_user->children('foaf', true)->Person->
                                                                            children('ya', true)->bio;
                        //School (date start, date finish, school name)          
                        if($fethed_user->children('foaf', true)->Person->children('ya', true)->school &&
                        $fethed_user->children('foaf', true)->Person->children('ya', true)->school->
                                        attributes()) { 
                            $attr = $fethed_user->children('foaf', true)->Person->children('ya', true)->school->
                                            attributes('ya', true);
                            $this->users[$user]['school']['date_start'] = (string) $attr['dateStart'];
                            $this->users[$user]['school']['date_finish'] = (string) $attr['dateFinish'];
                        }
                        if($fethed_user->children('foaf', true)->Person->children('ya', true)->school &&
                        $fethed_user->children('foaf', true)->Person->children('ya', true)->school->
                                        attributes()) {
                            $attr = $fethed_user->children('foaf', true)->Person->children('ya', true)->school->
                                            attributes('dc', true);
                            $this->users[$user]['school']['name'] = (string) $attr['title'];
                        } 
                }
            } 
            
            return $this->users[$user];
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
                $this->fetch_posts($this->username);
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
