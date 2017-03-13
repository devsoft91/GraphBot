<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once ('jpgraph-4.0.1/src/jpgraph.php');
require_once ('jpgraph-4.0.1/src/jpgraph_line.php');

define('BOT_TOKEN', '<your_bot_token>');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

// read incoming info and grab the chatID
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["chat"]["id"];

switch($message){
	case "/<your_command>@<your_bot>": sendGraph($chatID);
	break;
	case "/<your_command>": sendGraph($chatID);
	break;
	default: register_traffic($chatID);
}

function connect_db() {

  $host = 'localhost';
  $user = '<db_user>';
  $pwd = '<db_password>';
  $dbname = '<db_name>';
  $error = "Impossible connect to database $dbname! ";

  $connect = mysqli_connect($host, $user, $pwd, $dbname) or die($error.mysqli_connect_error());
  
  return $connect;
}

function register_traffic($ID){

	$connect = connect_db();

	$hour = date('G', time());
	$hour = intval($hour);
	
	$query = "CALL add_stats($ID,$hour)";

	$send = mysqli_query($connect, $query) or die("Query add_stats fallita. ".mysqli_error($connect));

}

function sendGraph($ID){

	$url = API_URL."sendPhoto?chat_id=".$ID;

	$post_fields = array('chat_id' => $ID,'photo' => new CURLFile(realpath("/<path_to_bot_folder>/image.png")));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

	makeGraph($ID);

	$output = curl_exec($ch);
}

function makeGraph($ID){

	$connect = connect_db();

	$query = "SELECT * FROM `statistics` WHERE `chat_id` = $ID ORDER BY `hour`";

	$send = mysqli_query($connect, $query) or die("Query add_stats failed. ".mysqli_error($connect));

	$ydata = array();

	$hour = date('G', time());
	$hour = intval($hour);

	//fetch and order data
	$array = array();
	while($row = mysqli_fetch_assoc($send)){
		array_push($array, $row['counter']);
	}

	for($i=$hour+1;$i<24;$i++){
		$ydata[$i] = $array[$i];
	}
	for($i=0;$i<$hour+1;$i++){
		$ydata[$i] = $array[$i];
	}

	$orderedYdata = array();
	for($i=$hour+1;$i<24;$i++){
		array_push($orderedYdata, $ydata[$i]);
	}
	for($i=0;$i<$hour+1;$i++){
		array_push($orderedYdata, $ydata[$i]);
	}

	// Setup the graph
	$graph = new Graph(1000,500);
	$graph->SetScale("textlin");

	$theme_class = new UniversalTheme;

	$graph->SetTheme($theme_class);
	$graph->img->SetAntiAliasing(false);
	//$graph->title->Set('Last 24h statistics');
	$graph->SetBox(false);

	$graph->img->SetAntiAliasing();

	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);

	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle("solid");
	$graph->xaxis->SetTickLabels(array_keys($ydata));
	$graph->xgrid->SetColor('#E3E3E3');

	// Create the first line
	$p1 = new LinePlot($orderedYdata);
	$graph->Add($p1);
	$p1->SetColor("#6495ED");

	$graph->legend->SetFrameWeight(1);

	// Output line
	$graph->Stroke('image.png');

}

?>