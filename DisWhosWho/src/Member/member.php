<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;

//Team API
require '../src/Team/team.php';

//Add New Member
$app->post('/api/member/add/{TeamID}', function (Request $request, Response $response) {

    $MemberName = $request->getParam('MemberName'); 
    $MemberFunFactAbout = $request->getParam('MemberFunFactAbout'); 
    $MemberPhotoUrl = $request->getParam('MemberPhotoUrl'); 
    $TeamID = $request->getAttribute('TeamID');
   
    $sql = "INSERT INTO member(MemberName, MemberFunFactAbout, MemberPhotoUrl, TeamID) VALUES (:MemberName, :MemberFunFactAbout, :MemberPhotoUrl, :TeamID)";
 
    try{
        $db = new db();
        $db = $db->connect();
 
       $stmt = $db->prepare($sql);
 
       $stmt->bindParam(':MemberName',    $MemberName);
       $stmt->bindParam(':MemberFunFactAbout',   $MemberFunFactAbout);
       $stmt->bindParam(':MemberPhotoUrl',    $MemberPhotoUrl);
       $stmt->bindParam(':TeamID',    $TeamID);
       
       $stmt->execute();
 
       echo '[{"notice": {"text": "Member Successfully Added!"}]';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });

 //Get Members
$app->get('/api/members/{teamID}', function (Request $request, Response $response) {
    
    $teamID = $request->getAttribute('teamID');
   
    $sql = "SELECT * FROM member
            WHERE TeamID = '$teamID'";
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Members = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        if(empty($Members))
        {
            echo '[{"notice": "No Members Available!"}]';
        }
        else
        {
            echo json_encode($Members);
        }
            
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

 //Get Member
 $app->get('/api/member/{MemberID}', function (Request $request, Response $response) {
    
    $MemberID = $request->getAttribute('MemberID');
   
    $sql = "SELECT * FROM member
            WHERE MemberID = '$MemberID'";
    
    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $Member = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        if(empty($Member))
        {
            echo '[{"notice": "No Member Found!"}]';
        }
        else
        {
            echo json_encode($Member);
        }
            
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


//Edit Member
$app->post('/api/member/edit/{MemberID}', function (Request $request, Response $response) {

    $MemberID = $request->getAttribute('MemberID');

    $MemberName = $request->getParam('MemberName'); 
    $MemberFunFactAbout = $request->getParam('MemberFunFactAbout'); 
    $MemberPhotoUrl = $request->getParam('MemberPhotoUrl');
    $TeamID = $request->getParam('TeamID'); 
 
   $sql = "UPDATE member
           SET MemberName     = :MemberName,
           MemberFunFactAbout = :MemberFunFactAbout,
           MemberPhotoUrl     = :MemberPhotoUrl,
           TeamID             = :TeamID
           WHERE MemberID     = :MemberID
          ";
 
    try{
       $db = new db();
       $db = $db->connect();
 
       $stmt = $db->prepare($sql);
 
       $stmt->bindParam(':MemberName',    $MemberName);
       $stmt->bindParam(':MemberFunFactAbout',   $MemberFunFactAbout);
       $stmt->bindParam(':MemberPhotoUrl',    $MemberPhotoUrl);
       $stmt->bindParam(':TeamID',    $TeamID);
       $stmt->bindParam(':MemberID',    $MemberID);

       $stmt->execute();
 
       echo '{"notice": {"text": "Member Details Successfully Updated!"}';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });

 //Delete Member
 $app->get('/api/member/delete/{MemberID}', function (Request $request, Response $response) {

    $MemberID = $request->getAttribute('MemberID');

   $sql = "DELETE FROM member
           WHERE MemberID = '$MemberID'
          ";
 
    try{
        $db = new db();
        $db = $db->connect();
 
       $stmt = $db->prepare($sql);
    
       $stmt->execute();
 
       echo '[{"notice": "Member Successfully Deleted!"}]';
        
    }catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
 });
