<?php
class ClassUser
{   
  private $IntID;
  private $StrURL;
  private $FloatScore;
  private $StrLabel;
  private $StrOfKeyword;
  public function setID($IntID) {
  $this->IntID = $IntID;
  }
  
  public function getID() {
    return $this->IntID;
  }

    
  public function setURL($StrURL) {
    $this->StrURL = $StrURL;
  }

  public function getURL() {
    return $this->StrURL;
  }

  public function setScore($FloatScore) {
    $this->FloatScore = $FloatScore;
  }

  public function getScore() {
    return $this->FloatScore;
  }

  public function setLabel($StrLabel) {
    $this->StrLabel = $StrLabel;
  }

  public function getLabel() {
    return $this->StrLabel;
  }

  public function setKeyword($StrKeyword) {
    $this->StrKeyword = $StrKeyword;
  }

  public function getKeyword() {
    return $this->StrKeyword;
  }
  
  public function setStrKeyword($StrOfKeyword) {
    $this->StrOfKeyword = $StrOfKeyword;
  }

  public function getStrKeyword() {
    return $this->StrOfKeyword;
  }
  

  public function AssignId($IntID)
  {
    include 'database.php';
    $PgQuery = "select * from reviews1 where id='".$IntID."';";
    $Result = pg_query($PgQuery);
    $RowResult = pg_fetch_row($Result);
    return $RowResult;
  }

  public function AnalyzeSentiment($RowResult,$StrURL){
    $JsonDecodedArray=$this->Analyze($RowResult,$StrURL,'sentiment');
    $this->setScore($JsonDecodedArray['sentiment']['document']['score']);
    $this->setLabel($JsonDecodedArray['sentiment']['document']['label']);
    $FloatScore=$this->getScore();
    $StrLabel=$this->getLabel();
    curl_close ($ch); 
  }
  public function AnalyzeKeyword($RowResult,$StrURL){
    $JsonDecodedArray=$this->Analyze($RowResult,$StrURL,'keywords');
    $this->setKeyword($JsonDecodedArray['keywords']);
    $StrKeyword=$this->getKeyword();
    foreach ($StrKeyword as $i => $value) {
      $StrText = $StrKeyword[$i]['text'];
      $StrOfKeyword.=$StrText.', ';
  }
    $this->setStrKeyword($StrOfKeyword);
    $StrOfKeyword=$this->getStrKeyword();
    echo $StrOfKeyword;
    curl_close ($ch);           
  }

  public function Analyze($RowResult,$StrURL,$StrAnalyzeType){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $StrURL );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"text\": \"$RowResult[2]; \",\n  \"features\": {\n    \"$StrAnalyzeType\": {}\n  }\n}");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, 'apikey' . ':' . 'NC5pFvhPQ1L0GnPXzcEhbtsc4FjJEQxBMvX8ZrQNY86A');
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $JsonResultArray = curl_exec($ch);
    if (curl_errno($ch)) { echo 'Error:' . curl_error($ch); }
    return $JsonDecodedArray=json_decode( $JsonResultArray, true );
  }
  public function database($FloatScore,$StrLabel,$StrOfKeyword,$IntID){
  $Time=time();
  $TimeStamp=date("Y-m-d",$Time);
  $query = "insert into sentiments(review_id, sentiment, label, keywords, created_on) values('".$IntID."','".$FloatScore."','".$StrLabel."','".$StrOfKeyword."','".$TimeStamp."');";
  $QueryExecute = pg_query($query);
  }
}
?>