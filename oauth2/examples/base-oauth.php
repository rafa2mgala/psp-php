<?php
    session_start();
    require_once '../vendor/autoload.php';
     
    // init configuration 
    $clientID = '549376656660-5c62ccdn4mu6p7bri319gp45n049lbr7.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'http://localhost/psp/oauth2/examples/simple-oauth-redirect.php';
      
    // create Client Request to access Google API 
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("https://www.googleapis.com/auth/userinfo.email");
    //$client->addScope("OpenID");
     
    // Send Client Request
    $objOAuthService = new Google_Service_Oauth2($client);

    // Logout
    if (isset($_REQUEST['logout'])) {
        unset($_SESSION['access_token']);
        $client->revokeToken();
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); // redirect user back to page
    }


    // Authenticate code from Google OAuth Flow
    // Add Access Token to Session
    if (isset($_GET['code'])) {
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
        header('Location: ' . filter_var($redirectUri, FILTER_SANITIZE_URL));
    } else {
        $oauthaccess = $client->createAuthUrl();
        header('Location: ' . filter_var($oauthaccess, FILTER_SANITIZE_URL));
    }

    // Set Access Token to make Request
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
    }
/*
    // authenticate code from Google OAuth Flow 
    if (isset($_GET['code'])) {
      $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
      $client->setAccessToken($token['access_token']);
      
      // get profile info 
      $google_oauth = new Google_Service_Oauth2($client);
      $google_account_info = $google_oauth->userinfo->get();
      $email =  $google_account_info->email;
      $name =  $google_account_info->name;
     
      // now you can use this profile info to create account in your website and make user logged in. 
    } else {
        $oauthaccess = $client->createAuthUrl();
        header('Location: ' . filter_var($oauthaccess, FILTER_SANITIZE_URL));
    }
    */
    ?>