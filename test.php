<!DOCTYPE html>
<html>
<head>
  <title>
    My Name
  </title>
</head>

<body>
  <h1>Get My Name from Facebook</h1>

<?php

require_once __DIR__ . '/vendor/autoload.php';

$fb = new \Facebook\Facebook([
  'app_id' => '283618165934200',           //Replace {your-app-id} with your app ID
  'app_secret' => 'e325a833af74bcfcfd375226542c8d66',   //Replace {your-app-secret} with your app secret
  'graph_api_version' => 'v5.0',
]);


try {

// Get your UserNode object, replace {access-token} with your token
  $response = $fb->get('/me', 'EAAEB8wAISHgBAA0ZBMvpChTloMsHYJZCjaRaGAaLA9ApQMrZBGJZBL3xd6Gsh4BCsGqEThuEoDJ6tZBkJHUHlVM32iEzrHkFZCwpabR8DPsZCMWA3HZA4yYlAT9kxWiYdequDM4KAGrWNPqxQpCN2OZAPoPUmVViOaRX0d79SlIqVXqtfxsZAAniIcZCIC81le1EaCvZCv7ZBYexUYpbMVwarEfCuNrtjcLvXGbybZB4RZBOVAljQZDZD');

} catch(\Facebook\Exceptions\FacebookResponseException $e) {
        // Returns Graph API errors when they occur
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
        // Returns SDK errors when validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$me = $response->getGraphUser();

       //All that is returned in the response
echo 'All the data returned from the Facebook server: ' . $me;

       //Print out my name
echo 'My name is ' . $me->getName();

?>

</body>
</html>
