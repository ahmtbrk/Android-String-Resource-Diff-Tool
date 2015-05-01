<?php
set_time_limit(0);
//error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
$firstFilePath = "res1/strings.xml";
$secondFilePath = "res2/strings.xml";

$firstFile = fopen($firstFilePath,'r') or die("First resource file not found");
$secondFile = fopen($secondFilePath,'r') or die("Second resource file not found");;

$firstFileDatas = fread($firstFile,filesize($firstFilePath));
$secondFileDatas = fread($secondFile,filesize($secondFilePath));

preg_match_all("#<string name=\"(?:.*?)\">(?:.*?)</string>|<string-array name=\"(?:.*?)\">(?:.*?)</string-array>|<integer-array name=\"(?:.*?)\">(?:.*?)</integer-array>#si",$firstFileDatas,$firstFileResult);
preg_match_all("#<string name=\"(?:.*?)\">(?:.*?)</string>|<string-array name=\"(?:.*?)\">(?:.*?)</string-array>|<integer-array name=\"(?:.*?)\">(?:.*?)</integer-array>#si",$secondFileDatas,$secondFileResult);

$firstFileDiff = findDiff($firstFileResult[0],$secondFileResult[0]);
$secondFileDiff = findDiff($secondFileResult[0],$firstFileResult[0]);

diffToXML($firstFileDiff,"res1/diff.xml");
diffToXML($secondFileDiff,"res2/diff.xml");


function diffToXML($difArray,$filePath){
	$diffFile = fopen($filePath,"w");
	for($i=0; $i<count($difArray); $i++){
		fwrite($diffFile,$difArray[$i]."\r\n");
	}
	fclose($diffFile);
}

function findDiff($firstResArray,$secondResArray){
	$firstFileDiff;
	$k = 0;
	for($i=0;$i<count($firstResArray);$i++){
			preg_match('/name="([^"]*)/i',$firstResArray[$i],$firstResResult);
			$firstResName = array_pop($firstResResult);
			for($j=0;$j<count($secondResArray);$j++){
				preg_match('/name="([^"]*)/i',$secondResArray[$j],$secondResResult);
				$secondResName = array_pop($secondResResult);
				if($firstResName == $secondResName){
					break;
				}
				if($j == count($secondResArray)-1){
					$firstFileDiff[$k] = $firstResArray[$i];
					$k++;
				}
			}
	}
	return $firstFileDiff;
}

?>
