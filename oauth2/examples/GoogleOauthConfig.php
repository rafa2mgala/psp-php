<?php
//Google API PHP Library includes
require_once '../vendor/autoload.php';

class GoogleOauthConfig{
  private $client_id;
  private $client_secret;
  private $redirect_uri;
  private $client;
  private $obj_res;
  private $google_auth_url;
  private static $instance_obj;
  private function __construct()
  {
    // Set config params to acces Google API
    $this->client_id = '549376656660-5c62ccdn4mu6p7bri319gp45n049lbr7.apps.googleusercontent.com';
    $this->client_secret = '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';
    $this->redirect_uri = 'http://localhost/psp-php/oauth2/examples/index-oauth.php';
    //Create and Request to access Google API
    $this->client = new Google_Client();
    $this->client->setApplicationName("Google OAuth Login With PHP");
    $this->client->setClientId($this->client_id);
    $this->client->setClientSecret($this->client_secret);
    $this->client->setRedirectUri($this->redirect_uri);
    $this->client->addScope('https://www.googleapis.com/auth/userinfo.email');  
    $this->obj_res = new Google_Service_Oauth2($this->client);

    //Add access token to php session after successfully authenticate
    if (isset($_GET['code'])) {
      $this->client->authenticate($_GET['code']);
      $_SESSION['access_token'] = $this->client->getAccessToken();
      header('Location: ' . filter_var($this->redirect_uri, FILTER_SANITIZE_URL));
    }

    //set token
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
      $this->client->setAccessToken($_SESSION['access_token']);
    }
    //store with user data
    if ($this->client->getAccessToken()) {
      $userData = $this->obj_res->userinfo->get();
      if(!empty($userData)) {
        //insert data into database
      }
      $_SESSION['access_token'] = $this->client->getAccessToken();
    } else {
        try{
          $this->google_auth_url  =  $this->client->createAuthUrl();
        }catch(Exception $e){
          var_dump($e);
        }
    }
  }
  public function getGoogleAuthUrl(){
    return $this->google_auth_url;
  }
  public static function instance()
  {
        if (!isset(self::$instance_obj)) {
            $c = __CLASS__;
            self::$instance_obj = new $c;
        }
 
        return self::$instance_obj;
  }
}
?>