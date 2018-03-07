<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;


//Project API
require '../src/Project/project.php';


//Add New Team
$app->post('/api/team/add', function (Request $request, Response $response) {

    $TeamName = $request->getParam('TeamName'); 
    $ProjectID = $request->getParam('ProjectID');
   
    $sql = "INSERT INTO team(TeamName, ProjectID) VALUES (:TeamName, :ProjectID)";
 
    try{
        $db = new db();
        $db = $db->connect();
 
       $stmt = $db->prepare($sql);
 
       $stmt->bindParam(':TeamName',    $TeamName);
       $stmt->bindParam(':ProjectID',   $ProjectID);
       
       $stmt->execute();
 
       echo '[{"notice": {"text": "Team Successfully Added!"}]';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });

  //Get Teams
$app->get('/api/teams/{ProjectID}', function (Request $request, Response $response) {
    
    $ProjectID = $request->getAttribute('ProjectID');
   
    $sql = "SELECT * FROM team
            WHERE ProjectID = '$ProjectID'";
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Teams = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        if(empty($Teams))
        {
            echo '[{"notice": "No Teams Available!"}]';
        }
        else
        {
            echo json_encode($Teams);
        }
            
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

 //Get Team
 $app->get('/api/team/{TeamID}', function (Request $request, Response $response) {
    
    $TeamID = $request->getAttribute('TeamID');
   
    $sql = "SELECT * FROM team
            WHERE TeamID = '$TeamID'";
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Team = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        if(empty($Team))
        {
            echo '[{"notice": "No Team Found!"}]';
        }
        else
        {
            echo json_encode($Team);
        }
            
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


//Edit Team
$app->post('/api/team/edit/{TeamID}', function (Request $request, Response $response) {

    $TeamID = $request->getAttribute('TeamID');

    $TeamName = $request->getParam('TeamName');
    $ProjectID = $request->getParam('ProjectID'); 
 
   $sql = "UPDATE team
           SET TeamName       = :TeamName,
           ProjectID          = :ProjectID
           WHERE TeamID     = :TeamID
          ";
 
    try{
       $db = new db();
       $db = $db->connect();
 
       $stmt = $db->prepare($sql);
 
       $stmt->bindParam(':TeamName',    $TeamName);
       $stmt->bindParam(':ProjectID',   $ProjectID);
       $stmt->bindParam(':TeamID',    $TeamID);

       $stmt->execute();
 
       echo '{"notice": {"text": "Team Details Successfully Updated!"}';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });

 //Delete Team
 $app->get('/api/team/delete/{TeamID}', function (Request $request, Response $response) {

    $TeamID = $request->getAttribute('TeamID');

   $sql = "DELETE FROM team
           WHERE TeamID = '$TeamID'
          ";
 
    try{
        $db = new db();
        $db = $db->connect();
 
       $stmt = $db->prepare($sql);
    
       $stmt->execute();
 
       echo '[{"notice": "Team Successfully Deleted!"}]';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });
