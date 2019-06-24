<?php

include 'ClassUser.php';

$ObjUser = new ClassUser;

if(isset($_GET["index"]))
{   
  $ObjUser->setID(intval($_GET['index']));
  
  $IntID=$ObjUser->getID();
  $RowResult=$ObjUser->AssignId($IntID);
  
  $ObjUser->setURL('https://gateway-lon.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2018-09-21');
  $StrURL=$ObjUser->getURL();

  $ObjUser->AnalyzeSentiment($RowResult,$StrURL);
  $ObjUser->AnalyzeKeyword($RowResult,$StrURL);
  
  $FloatScore=$ObjUser->getScore();
  $StrLabel=$ObjUser->getLabel();
  $StrOfKeyword=$ObjUser->getStrKeyword();
  
  $ObjUser->database($FloatScore,$StrLabel,$StrOfKeyword,$IntID);
}
header("Location:http://localhost:8080/Comment Sentiment Analysis System/web/sentiment.php");
?>
