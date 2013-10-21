<?php
class User{
    
    public $id = null;
    
    function __construct($id=null){
        if (!$id){
            if (isset($_SESSION['loggeduser_id']) && $_SESSION['loggeduser_id']){
                $this->id = $_SESSION['loggeduser_id'];
            }
        } else {
            $this->id = null;
        }
    }
    
    public function addAdmin($params){
        $username = mysql_real_escape_string($params['username']);
        $password = md5($params['password']);
        
        $ins = mysql_query("INSERT INTO admin(`username`,`password`) VALUES('$username','$password')") or die(mysql_error());
        return mysql_insert_id();
    }
    
    public function updateAdmin($params,$admin_id){
        $username = mysql_real_escape_string($params['username']);
        $password = md5($params['password']);
        $admin_id = mysql_real_escape_string($admin_id);
        
        $up = mysql_query("UPDATE admin SET username='$username', `password`='$password' WHERE id='$admin_id'") or die(mysql_error());
    }
    
    public function removeAdmin($admin_id){
        $admin_id = mysql_real_escape_string($admin_id);
        $del = mysql_query("DELETE FROM admin WHERE id='$admin_id'") or die(mysql_error());
    }
    
    public function validateAdmin($admin,$edit_admin = null){
        $errors = array();
        
        if (!isset($admin['username']) || !$admin['username']){
            $errors[1] = "Please enter the admin username";
        } elseif (strlen($admin['username'])<5){
            $errors[1] = "Admin username must be at least 5 characters long";
        } else {
            if (!$edit_admin){
                $check = mysql_query("SELECT * FROM admin WHERE username='".mysql_real_escape_string($admin['username'])."'") or die(mysql_error());
                if (mysql_num_rows($check)){
                    $errors[1] = "There is already an admin user with this username";
                }
            } else {
                $edit_admin = mysql_real_escape_string($edit_admin);
                $check = mysql_query("SELECT * FROM admin WHERE username='".mysql_real_escape_string($admin['username'])."' AND id!='$edit_admin'") or die(mysql_error());
                if (mysql_num_rows($check)){
                    $errors[1] = "There is already an admin user with this username";
                }
            }
        }
        
        if (!isset($admin['password']) || !$admin['password']){
            $errors[2] = "Please enter the admin password";
        } elseif (strlen($admin['password'])<5){
            $errors[2] = "Admin password must be at least 5 characters long";
        }
        
        if (!isset($admin['password2']) || !$admin['password2'] || (isset($admin['password']) && $admin['password']!=$admin['password2'])){
            $errors[3] = "Invalid password confirmation";
        }
        
        return $errors;
    }
    
    public function getAdmin($admin_id){
        $admin_id = mysql_real_escape_string($admin_id);
        
        $e = mysql_query("SELECT * FROM admin WHERE id='$admin_id'") or die(mysql_error());
        if (mysql_num_rows($e)){
            return mysql_fetch_assoc($e);
        } else {
            return array();
        }
    }
    
    public function getAdminUsers(){
        $res = array();
        $e = mysql_query("SELECT * FROM admin ORDER BY id DESC") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $s;
            }
        }
        
        return $res;
    }
    
    public function getByUsername($username){
        $username = mysql_real_escape_string($username);
        
        $e = mysql_query("SELECT * FROM users WHERE username='$username'") or die(mysql_error());
        if (mysql_num_rows($e)){
            $s = mysql_fetch_assoc($e);
            if (!$s['avatar']){
                $s['avatar'] = "nopic.jpg";
            }
            return $s;
        } else {
            return false;
        }
    }
    
    public function get($id){
        $id = mysql_real_escape_string($id);
        $e = mysql_query("SELECT * FROM users WHERE id='$id'") or die(mysql_error());
        if (mysql_num_rows($e)){
            return mysql_fetch_assoc($e);
        } else {
            return false;
        }
    }
    
    public function getUserCount($search_term=null){
        if ($search_term){
            $search_term = "WHERE username LIKE '%".mysql_real_escape_string($search_term)."%' OR email LIKE '%".mysql_real_escape_string($search_term)."%'";
        } else {
            $search_term = "";
        }
        
        $e = mysql_query("SELECT count(*) as `cnt` FROM users $search_term") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $cnt;
    }
    
    public function search($query,$start=null,$limit=null,$sortby="id",$sortdir="DESC"){
        if (!$limit){
            $limit = 50;
        }
        
        $sortby = mysql_real_escape_string($sortby);
        $sortdir = mysql_real_escape_string($sortdir);
        
        $limit = mysql_real_escape_string($limit);
        if ($start){
            $start = mysql_real_escape_string($start);
            $limit = "LIMIT $start,$limit";
        } else {
            $limit = "LIMIT $limit";
        }
        
        $query = mysql_real_escape_string($query);
        $e = mysql_query("SELECT * FROM users WHERE username LIKE '%$query%' OR email LIKE '%$query%' ORDER BY $sortby $sortdir $limit") or die(mysql_error());
        $users = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $users[$id]=array();
                $users[$id]['username'] = $username;
                $users[$id]['email'] = $email;
                $users[$id]['language'] = $language;
                $users[$id]['fb_id'] = $fb_id;
                
                if (!$avatar){
                    $avatar = "nopic.jpg";
                }
                $users[$id]['avatar'] = $avatar;
            }
        }
        return $users;
    }
    
    public function getAllUsers($page = null, $start=null, $limit=null, $sortby="id", $sortdir="DESC"){
        if (!$limit){
            $limit = 50;
        }
        
        $sortby = mysql_real_escape_string($sortby);
        $sortdir = mysql_real_escape_string($sortdir);
        
        $limit = mysql_real_escape_string($limit);
        
        
        if ($page){
            $page = mysql_real_escape_string($page);
            $start = ($page-1)*$limit;
            $limit = "LIMIT $start,$limit";
        } elseif ($start) {
            $start = mysql_real_escape_string($start);
            $limit = "LIMIT $start,$limit";
        } else {
            $limit = "LIMIT $limit";
        }
        
        
        $e = mysql_query("SELECT * FROM users ORDER BY $sortby $sortdir $limit") or die(mysql_error());
        $users = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $users[$id]=array();
                $users[$id]['username'] = $username;
                $users[$id]['email'] = $email;
                $users[$id]['language'] = $language;
                $users[$id]['fb_id'] = $fb_id;
                
                if (!$avatar){
                    $avatar = "nopic.jpg";
                }
                $users[$id]['avatar'] = $avatar;
            }
        }
        return $users;
    }
    
    public function getFollows($user_id = null){
        if (!$user_id){
            $user_id = $this->id;
        }
        
        $res = array();
        
        if ($user_id){
            $e = mysql_query("SELECT users.* FROM friends,users WHERE user2=users.id and user1='".$user_id."'") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    if (!$s['avatar']){
                        $s['avatar'] = "nopic.jpg";
                    }
                    $res[$s['id']] = $s;
                }
            }    
        }
        
        return $res;
    }
    
    public function getFollowers($user_id = null){
        if (!$user_id){
            $user_id = $this->id;
        }
        
        $res = array();
        
        if ($user_id){
            $e = mysql_query("SELECT users.* FROM friends,users WHERE user1=users.id and user2='".$user_id."'") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    if (!$s['avatar']){
                        $s['avatar'] = "nopic.jpg";
                    }
                    $res[$s['id']] = $s;
                }
            }    
        }
        
        return $res;
    }
    
    public function unfollow($user1,$user2){
        $user1 = mysql_real_escape_string($user1);
        $user2 = mysql_real_escape_string($user2);

        $del = mysql_query("DELETE FROM friends WHERE user1='$user1' AND user2='$user2'") or die(mysql_error());
    }
    
    public function follow($user1,$user2){
        $user1 = mysql_real_escape_string($user1);
        $user2 = mysql_real_escape_string($user2);
        
        $get_user = mysql_query("SELECT * FROM users WHERE id='$user2'") or die(mysql_error());
        if (mysql_num_rows($get_user)){
            $e = mysql_query("SELECT * FROM friends WHERE user1='$user1' AND user2='$user2'") or die(mysql_error());
            if (mysql_num_rows($e)==0){
                $ins = mysql_query("INSERT INTO friends(user1,user2,date_added) VALUES('$user1','$user2',NOW())") or die(mysql_error());
                return mysql_fetch_assoc($get_user);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function getFacebookUsers($ids){
        $e = mysql_query("SELECT * FROM users WHERE fb_id IN (".implode(",",$ids).")") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $s;
            }
        }
        
        return $res;
    }
    
    public function validateEmail($email){
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex){
            $isValid = false;
        }  else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64){
                // local part length exceeded
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255){
                // domain part length exceeded
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen-1] == '.'){
                // local part starts or ends with '.'
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)){
                // local part has two consecutive dots
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
                // character not valid in domain part
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)){
                // domain part has two consecutive dots
                $isValid = false;
            } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))){
                // character not valid in local part unless 
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local))){
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }
    
    public function getNewsletter(){
        $res = array();
        $e = mysql_query("SELECT id, username, avatar, email FROM users WHERE notify_new='1'") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $s;
            }
        }
        
        return $res;
    }
    
    public function deleteUser($userid){
        $userid = mysql_real_escape_string($userid);
        
        $e = mysql_query("DELETE FROM watches WHERE user_id='$userid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM likes WHERE user_id='$userid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM activity WHERE user_id='$userid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM friends WHERE user1='$userid' OR user2='$userid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM requests WHERE user_id='$userid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM submitted_links WHERE user_id='$userid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM broken_episodes WHERE user_id='$userid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM broken_movies WHERE user_id='$userid'") or die(mysql_error());
        
        $check = mysql_query("SELECT avatar FROM users WHERE id='$userid'") or die(mysql_error());
        if (mysql_num_rows($check)){
            extract(mysql_fetch_assoc($check));
            if ($avatar && file_exists($basepath."/thumbs/users/".$avatar)){
                unlink($basepath."/thumbs/users/".$avatar);
            }
        }
        
        $e = mysql_query("DELETE FROM users WHERE id='$userid'") or die(mysql_error());
                
    }
    
    public function validate($params){
        global $lang;
        
        $errors=array();
        if ((!@$params['username']) || (!@$params['pass1']) || (!@$params['pass2']) || (!@$params['email'])){
            $errors[0] = $lang['register_all_field_required'];
        } else {
            // Username checks
            
            $tmpuser = preg_replace("/[^a-zA-Z0-9_]/","",$params['username']);
            if ($tmpuser!=$params['username']){
                $errors[1] = $lang['register_no_special_chars'];
            } else {
                if ((strlen($params['username'])<5) || (strlen($params['username'])>25)){
                    $errors[1] = $lang['register_min_5_chars'];
                } else {
                    $params['username'] = strtolower(mysql_real_escape_string($params['username']));
                    $check = mysql_query("SELECT id FROM users WHERE LOWER(username)='{$params['username']}'") or die(mysql_error());
                    if (mysql_num_rows($check)){
                        $errors[1] = $lang['register_username_taken'];
                    }
                }
            }
            
            // password checks
            if ($params['pass1']!=$params['pass2']){
                $errors[2] = $lang['register_password_confirm_doesnt_match'];
            } else {
                if (strlen($params['pass1'])<5){
                    $errors[2] = $lang['register_password_min_5_chars'];
                }
            }
            
            // email checks
            if (!$this->validateEmail($params['email'])){
                $errors[3] = $lang['register_invalid_email'];
            } else {
                $params['email']=strtolower(mysql_real_escape_string($params['email']));
                $e = mysql_query("SELECT id FROM users WHERE LOWER(`email`)='{$params['email']}'") or die(mysql_error());
                if (mysql_num_rows($e)){
                    $errors[3] = $lang['register_email_in_use'];
                }
            }
        }
        
        return $errors;
    }
    
    public function startSession($s,$cookie=true){
        
        $_SESSION['loggeduser_id'] = $s['id'];
        $_SESSION['loggeduser_username'] = $s['username'];
        $_SESSION['loggeduser_details']=$s;
        
        $_SESSION['loggeduser_seen_movies'] = $this->getSeenMovies($s['id'],true);
        $_SESSION['loggeduser_seen_episodes'] = $this->getSeenEpisodes($s['id'],true);
        
        if (!isset($_SESSION['loggeduser_details']['avatar']) || !$_SESSION['loggeduser_details']['avatar']){
            $_SESSION['loggeduser_details']['avatar'] = "nopic.jpg";
        }
        
        if ($cookie){
            $cookiedata = $s['id']."|".md5($s['username'].$s['password']);
            setcookie("guid",$cookiedata,time()+60*60*24*30, "/");
        }
    }
    
    public function cookieLogin($cookie_content){
        $tmp = explode("|",$cookie_content);
        if (count($tmp)==2){
            $user_id = $tmp[0];
            $hash = $tmp[1];
            $user_id = mysql_real_escape_string($user_id);
            if (is_numeric($user_id)){
                $e = mysql_query("SELECT id,username,fb_id,fb_session,avatar,notify_new,notify_favorite,password FROM users WHERE id='$user_id'") or die(mysql_error());
                if (mysql_num_rows($e)){
                    $s = mysql_fetch_assoc($e);
                    $check_hash = md5($s['username'].$s['password']);
                    if ($check_hash == $hash){
                        
                        $this->startSession($s,false);
                        
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function facebookLogin($facebook_id,$token){
        $e = mysql_query("SELECT id,username,fb_id,fb_session,avatar,notify_new,notify_favorite,password FROM users WHERE fb_id='".mysql_real_escape_string($facebook_id)."'") or die(mysql_error());
        if (mysql_num_rows($e)==0){
            return false;
        } else {
            $s = mysql_fetch_assoc($e);
            
            if ($token){
                $up = mysql_query("UPDATE users SET fb_session='$token' WHERE id='{$s['id']}'") or die(mysql_error());
            }

            $this->startSession($s);
            
            return $s;
        }
    }
    
    public function login($username,$password){
        $username = mysql_real_escape_string($username);
        $password = md5($password);
        $e = mysql_query("SELECT id,username,fb_id,fb_session,avatar,notify_new,notify_favorite,password FROM users WHERE username='$username' AND password='$password'") or die(mysql_error());
        if (mysql_num_rows($e)){
            $s = mysql_fetch_assoc($e);
            $this->startSession($s);
            
            return 0;
        } else {
            return "Invalid login details";
        }
    }
    
    public function update($user_id, $params){
        $user_id = mysql_real_escape_string($user_id);
        $fields = array();
        foreach($params as $key => $val){
            $fields[] = "`$key` = '".mysql_real_escape_string($val)."'";
            
            $_SESSION['loggeduser_details'][$key] = $val;
        }    
        $up = mysql_query("UPDATE users SET ".implode(",",$fields)." WHERE id='$user_id'") or die(mysql_error());
    }
    
    public function save($params){
        $username = mysql_real_escape_string($params['username']);
        $password = md5($params['pass1']);
        $email = mysql_real_escape_string($params['email']);
        if (isset($params['fb_id'])){
            $fb_id = mysql_real_escape_string($params['fb_id']);
        } else {
            $fb_id = '';
        }
        
        if (isset($params['fb_session'])){
            $fb_session = mysql_real_escape_string($params['fb_session']);
        } else {
            $fb_session = '';
        }
        
        if (isset($params['language'])){
            $language = mysql_real_escape_string($params['language']);
        } else {
            $language = "en";
        }
        
        $e = mysql_query("INSERT INTO users(username,password,email,fb_id,fb_session,language) VALUES('$username','$password','$email','$fb_id','$fb_session','$language')") or die(mysql_error());
        
        $user_id = mysql_insert_id();
        
        $cookiedata = "$user_id|".md5($username.$password);
        setcookie("guid",$cookiedata,time()+60*60*24*30, "/");
        return mysql_insert_id();
    }
    
    public function getFavoriteMovies($user_id,$just_ids = false, $lang = false){
        $e = mysql_query("SELECT DISTINCT target_id FROM likes WHERE vote=1 AND target_type=2 AND user_id='$user_id'") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            $movie_ids = array();
            while($s = mysql_fetch_assoc($e)){
                $movie_ids[] = $s['target_id'];
            }
            
            if ($just_ids){
                return $movie_ids;
            }
            
            $e = mysql_query("SELECT * FROM movies WHERE id IN (".implode(",",$movie_ids).")") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $res[$s['id']] = $s;
                    if (!$lang){
                        $res[$s['id']]['title'] = json_decode($res[$s['id']]['title'], true);
                    } else {
                        $title = json_decode($res[$s['id']]['title'], true);
                        $res[$s['id']]['title'] = $title[$lang];                        
                    }
                    
                }
            }
        }
        
        return $res;
    }
    
    public function getFavoriteShows($user_id,$just_ids = false, $lang = false){
        $e = mysql_query("SELECT DISTINCT target_id FROM likes WHERE vote=1 AND target_type=1 AND user_id='$user_id'") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            $movie_ids = array();
            while($s = mysql_fetch_assoc($e)){
                $show_ids[] = $s['target_id'];
            }
            
            if ($just_ids){
                return $show_ids;
            }
            
            $e = mysql_query("SELECT * FROM shows WHERE id IN (".implode(",",$show_ids).")") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $res[$s['id']] = $s;
                    if (!$lang){
                        $res[$s['id']]['title'] = json_decode($res[$s['id']]['title'],true); 
                    } else {
                        $res[$s['id']]['title'] = json_decode($res[$s['id']]['title'],true);
                        $res[$s['id']]['title'] = $res[$s['id']]['title'][$lang];  
                    }
                }
            }
        }
        
        return $res;
    }
    
    public function getSeenMovies($user_id,$just_ids = false){
        if (!$user_id){
            $user_id = $this->id;
        }
        
        $user_id = mysql_real_escape_string($user_id);
        $e = mysql_query("SELECT target_id FROM watches WHERE target_type=2 AND user_id='$user_id'") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            $movie_ids = array();
            while($s = mysql_fetch_assoc($e)){
                $movie_ids[] = $s['target_id'];
            }
            
            if ($just_ids){
                return $movie_ids;
            }
            
            $e = mysql_query("SELECT * FROM movies WHERE id IN (".implode(",",$movie_ids).")") or die(mysql_error());
            if (mysql_num_rows($e)){
                $res[$s['id']] = $s;
            }
            
        }
        
        return $res;
    }
    
    public function getSeenEpisodes($user_id = null,$just_ids = false){
        if (!$user_id){
            $user_id = $this->id;
        }
        
        $user_id = mysql_real_escape_string($user_id);
        $e = mysql_query("SELECT target_id FROM watches WHERE target_type=3 AND user_id='$user_id'") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            $episode_ids = array();
            while($s = mysql_fetch_assoc($e)){
                $episode_ids[] = $s['target_id'];
            }
            
            if ($just_ids){
                return $episode_ids;
            }
            
            $e = mysql_query("SELECT episodes.id, episodes.season, episodes.episode, shows.title as show_title, shows.id as show_id, episodes.thumbnail, shows.thumbnail as show_thumbnail FROM episodes, shows WHERE shows.id=episodes.show_id AND episodes.id IN (".implode(",",$episode_ids).")") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $res[$s['id']] = $s;
                }
            }
        }
        
        return $res;
    }
    
}