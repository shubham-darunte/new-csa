<?php

	include 'database.php';

	$StrFilename=$_FILES["file"]["name"];
	$StrExtension=substr($StrFilename,strrpos($StrFilename,"."),(strlen($StrFilename)-strrpos($StrFilename,".")));
	if($StrExtension==".csv")
	{	$StrFile = fopen($StrFilename, "r");
		while (($StrReview = fgetcsv($StrFile, 10000, ",")) != FALSE)
		{  	$SqlQuery = "INSERT into reviews1 (review_entity_type_id, comment, created_on) values('".$StrReview[0]."','".$StrReview[1]."', '".$StrReview[2]."')";
			$SqlResult = pg_query($SqlQuery) or die('Query failed: ' . pg_last_error()); 
        }
        fclose($file);
        header("Location:http://localhost:8080/Comment Sentiment Analysis System/web/reviews.php" );		
	}
	else {
	    	echo "Error: Please Upload only CSV File";
		}
?>