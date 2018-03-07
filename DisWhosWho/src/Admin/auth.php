<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Todo API
//require '../src/Todo/todolist.php';

if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }

//User LogIn
$app->post('/api/authentication/Login', function (Request $request, Response $response) {

   $Email = $request->getParam('AdminEmail'); 
   $Password = $request->getParam('AdminPassword'); 

   $sql = "SELECT * FROM admin WHERE AdminEmail = '$Email'";

   try{
       $db = new db();
       $db = $db->connect();

       $stmt = $db->query($sql);
       $user = $stmt->fetchAll(PDO::FETCH_OBJ);
       $db = null;

        if(empty($user))
        {
             echo '[{"notice": "Incorrect Username/Password"}]';
        }
        else
        {
            //UnHash
            
            if(password_verify(!$Password,$user[0]->AdminPassword))
            {
                 echo json_encode($user);
            }
            else
            {
                 echo '[{"notice": "ZIncorrect Username/Password"}]';
            }
        }

   }catch(PDOException $e){
       echo '{"error": {"text": '.$e->getMessage().'}';
   }

});

$app->get('/api/authentication/test', function (Request $request, Response $response) {
    echo 'Current PHP version: ' . phpversion();
});

//User Registration
$app->post('/api/authentication/add', function (Request $request, Response $response) {

   $AdminEmail = $request->getParam('AdminEmail'); 

   //Password Hashing
   $AdminPassword = $request->getParam('AdminPassword'); 
   $Password = password_hash($AdminPassword,PASSWORD_DEFAULT);

  $sql = "INSERT INTO admin(AdminEmail,AdminPassword) VALUES (:AdminEmail,:AdminPassword)";

   try{
       $db = new db();
       $db = $db->connect();

      $stmt = $db->prepare($sql);

      $stmt->bindParam(':AdminEmail',       $AdminEmail);
      $stmt->bindParam(':AdminPassword',    $Password);
      
      $stmt->execute();

      echo '{"notice": {"text": "Admin Successfully Added!"}';
       
   }catch(PDOException $e){
       echo '{"error": {"text": '.$e->getMessage().'}';
   }
});




