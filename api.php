<?php
  /*
    api.php - Client Server Model Programming exercise
    version 1.0
    creation date: 10-01-2018
  */

  define("LOGGING", true);

  include("opendb.php");
  $log = array(date('d-m-Y'));
  $log[] = serialize($_REQUEST);

  $reply = array("id" => null, "status" => 'success');

  if(!isset($_REQUEST["mykey"])) error("No key provided.");
  $action = empty($_REQUEST["action"]) ? "" : strtolower($_REQUEST["action"]);

  switch($action){

      case "read":
          if(!isset($_REQUEST["id"])) error('No ID provided.');
          $stmt = $mysqli->prepare("SELECT value FROM data WHERE mykey = ? AND id = ?");
          $stmt->bind_param('si', $_REQUEST["mykey"], $_REQUEST["id"]);
          $stmt->execute();
          $stmt->bind_result($value);
          if(!$stmt->fetch()) error("No results found.");
          $reply["id"] = $_REQUEST["id"];
          $reply["value"] = $value;
          output();

      case "write":
          if(!isset($_REQUEST["value"])) error('No value provided.');
          $stmt = $mysqli->prepare("INSERT INTO `data` (`value`, `mykey`) VALUES (?, ?)");
          $stmt->bind_param('ss', $_REQUEST["value"], $_REQUEST["mykey"]);
          if(!$stmt->execute()) error($mysqli->error);
          $reply["id"] = $mysqli->insert_id;
          output();

      default: error('Invalid action.');
  }

  function error($msg){
      global $log, $reply;
      $log[] = $msg;
      $reply['status'] = 'error';
      $reply['error'] = $msg;
      output();
  }

  function output(){
      global $log, $reply;
      if(LOGGING){
          file_put_contents('log/read_write_exercise.log',  implode(PHP_EOL, $log), FILE_APPEND) ;
      }
      //header('Content-Type: application/json');
      //echo json_encode($reply, JSON_PRETTY_PRINT);
      print_r($reply);
      exit;
  }
?>
