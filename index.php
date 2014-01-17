<?php

require "facebook.php";
$contents;
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId' => 'xxxxxxxx',
  'secret' => 'xxxxxxxxxxxxxxxxxxxx',
));


echo 'Read Mailbox';
echo "<br/><br/><br/>";
$full =  array();//array to keep track of all messages



function puddinFile($stringy){
	$file = fopen("meep.csv","a") or die("can't open file");
	fwrite($file,$stringy);
	fclose($file);
}
function readMail($date,$facebook){
  $n = 0;
  while($n < 80000){//loop constantly (for some reason fb doesn't like it when you set while to 1)
    $fql = "SELECT created_time, message_id, body, author_id FROM message WHERE thread_id = 376659462449930 AND created_time > $date order by created_time asc limit 30 OFFSET $n";//offset is set to changing var
    try {
      $result = $facebook->api(array(
        'method' => 'fql.query',
        'query' =>$fql,
        ));
    }
    catch(FacebookApiException $e){
      $e_type = $e->getType();
      echo 'Got an ' . $e_type . ' while posting';
      echo $e;
      if($e == "Exception: Calls to this api have exceeded the rate limit."){
        echo "graceful exit";
        echo "<br>$n<br>";
        exit($full);
      }
  }
    $n+=30;//add 30 to offset
    $rSize = sizeof($result);
    for($i=0;$i<$rSize;$i++){//format all parts of returned array to write to string
      $author = $result[$i]['author_id'];
      $author = (string)$author;
      $message = $result[$i]['body'];
      $message = (string)$message;
      $id = $result[$i]['message_id'];
      $id = (string)$id;
      $search = array('"',"\n");
      $message = str_replace($search, ',', $message);
      $message = '"'.$message.'"';
      $date = $result[$i]['created_time'];
      $date = (string)$date;

      $fString = $id.",".$author.",".$message.",".$date."\n";//final string
      puddinFile($fString);//you will timeout before you writeout. and the try catch isn't always reliable as i've found.
				//but that may be just because of my shitty server, i dono.
    }
    $full = array_merge($full,$result);//not necessary but just for printing purposes
  }
}
//opens and reads the last line until the third \n which is the start of the last line.
//adds the date to the fql query that needs to start over.
$sFile = fopen("meep.csv","r");
fseek($sFile, -1, SEEK_END);
$pos = ftell($sFile);
$LastLine = "";
$secondLine=false;
$newliner = 0;
while(($secondLine == false) && ($pos > 0)){
  $C = fgetc($sFile);
  if($C=="\n"){
    $newliner +=1;
  }
  if($newliner==3){
    $secondLine=true;
  }
  $LastLine = $C.$LastLine;
  fseek($sFile,$pos--);
}
$fromDate = explode(',',$LastLine,4);//splode
$date = $fromDate[3];//get da date
readMail($date,$facebook);//loopit



echo "<pre>";
print_r($full); //for pretty printing if you desire it.
echo "</pre>";
?>
