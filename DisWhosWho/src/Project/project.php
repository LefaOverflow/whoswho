<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;

//Search API
require '../src/Search/search.php';

//Add New Project
$app->post('/api/project/add', function (Request $request, Response $response) {

    $ProjectName = $request->getParam('ProjectName'); 
    $ProjectAbbreviation = $request->getParam('ProjectAbbreviation');
    $ProjectDescription = $request->getParam('ProjectDescription');
   
    $sql = "INSERT INTO project(ProjectName, ProjectAbbreviation, ProjectDescription) VALUES (:ProjectName, :ProjectAbbreviation, :ProjectDescription)";
 
    try{
        $db = new db();
        $db = $db->connect();
 
       $stmt = $db->prepare($sql);
 
       $stmt->bindParam(':ProjectName',    $ProjectName);
       $stmt->bindParam(':ProjectAbbreviation',   $ProjectAbbreviation);
       $stmt->bindParam(':ProjectDescription',   $ProjectDescription);
       $stmt->execute();
 
       echo '[{"notice": {"text": "Project Successfully Added!"}]';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });

 //Get Projects
$app->get('/api/projects', function (Request $request, Response $response) {
    
   
    $sql = "SELECT * FROM project";
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Projects = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        if(empty($Projects))
        {
            echo '[{"notice": "No Projects Available!"}]';
        }
        else
        {
            echo json_encode($Projects);
        }
            
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

 //Get Project
 $app->get('/api/project/{ProjectID}', function (Request $request, Response $response) {
    
    $ProjectID = $request->getAttribute('ProjectID');
   
    $sql = "SELECT * FROM project 
            WHERE ProjectID = '$ProjectID'";
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Projects = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        if(empty($Projects))
        {
            echo '[{"notice": "No Project Found!"}]';
        }
        else
        {
            echo json_encode($Projects);
        }
            
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Edit Project
$app->post('/api/project/edit/{ProjectID}', function (Request $request, Response $response) {

    $ProjectID = $request->getAttribute('ProjectID');

    $ProjectName = $request->getParam('ProjectName');
    $ProjectAbbreviation = $request->getParam('ProjectAbbreviation'); 
    $ProjectDescription = $request->getParam('ProjectDescription'); 
 
   $sql = "UPDATE project
           SET ProjectName          = :ProjectName,
           ProjectAbbreviation      = :ProjectAbbreviation,
           ProjectDescription = :ProjectDescription
           WHERE ProjectID = :ProjectID
          ";
 
    try{
       $db = new db();
       $db = $db->connect();
 
       $stmt = $db->prepare($sql);
 
       $stmt->bindParam(':ProjectName',    $ProjectName);
       $stmt->bindParam(':ProjectAbbreviation',   $ProjectAbbreviation);
       $stmt->bindParam(':ProjectDescription',  $ProjectDescription);
       $stmt->bindParam(':ProjectID',  $ProjectID);
       $stmt->execute();
 
       echo '[{"notice": "Project Details Successfully Updated!"}]';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });

 //Delete Project
 $app->get('/api/project/delete/{ProjectID}', function (Request $request, Response $response) {

    $ProjectID = $request->getAttribute('ProjectID');

   $sql = "DELETE FROM project
           WHERE ProjectID = '$ProjectID'
          ";
 
    try{
        $db = new db();
        $db = $db->connect();
 
       $stmt = $db->prepare($sql);
    
       $stmt->execute();
 
       echo '[{"notice": "Project Successfully Deleted!"}]';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });