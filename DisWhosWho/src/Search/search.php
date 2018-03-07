<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;

//Search Project
$app->post('/api/search/project', function (Request $request, Response $response) {

    $SearchQuery = $request->getParam('SearchQuery'); 
   
    $sql = "SELECT * FROM project 
            WHERE ProjectName   LIKE '%$SearchQuery%' OR
            ProjectAbbreviation LIKE '%$SearchQuery%' OR
            ProjectDescription  LIKE '%$SearchQuery%'
            ";
 
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

 //Search Team
$app->post('/api/search/team', function (Request $request, Response $response) {

    $SearchQuery = $request->getParam('SearchQuery'); 
   
    $sql = "SELECT * FROM team 
            WHERE TeamName   LIKE '%$SearchQuery%'
            ";
 
 try{
    $db = new db();
    $db = $db->connect();
    $stmt = $db->query($sql);
    $Team = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    
    if(empty($Team))
    {
        echo '[{"notice": "No Team Available!"}]';
    }
    else
    {
        echo json_encode($Team);
    }
        
}catch(PDOException $e){
    echo '{"error": {"text": '.$e->getMessage().'}';
}
 });

 //Search Member
$app->post('/api/search/member', function (Request $request, Response $response) {

    $SearchQuery = $request->getParam('SearchQuery'); 
   
    $sql = "SELECT * FROM member 
            WHERE MemberName   LIKE '%$SearchQuery%' OR
            MemberFunFactAbout LIKE '%$SearchQuery%' 
            ";
 
 try{
    $db = new db();
    $db = $db->connect();
    $stmt = $db->query($sql);
    $Member = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    
    if(empty($Member))
    {
        echo '[{"notice": "No Member Available!"}]';
    }
    else
    {
        echo json_encode($Member);
    }
        
}catch(PDOException $e){
    echo '{"error": {"text": '.$e->getMessage().'}';
}
 });