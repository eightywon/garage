<?php
 header("Content-type: application/json");
 $payload=file_get_contents("php://input");
 $data=@json_decode($payload,true);
 $dtstring=date('m/d/y \a\t G:i:s',$data['tst']);
 if (shell_exec("/usr/bin/gpio read 0")==0) {
  $status="C";
 } else {
  $status="O";
 }
 $logstring=$data['_type'];
 if ($data['event']!="") {
  $logstring=$logstring." (".$data['event']." ".$data['desc'].") ";
 }
 $logstring=$logstring." ".$data['lat'].",".$data['lon']." within ".$data['acc']."m ".$dtstring." (".$status.") (".$data['tid'].")\n";
 error_log($logstring,3,"/var/tmp/debug.log");
 if ($data["_type"]!="") {
  if ($data['_type']=="transition" &&
      $data['event']=="enter") {
   if ($status=="C") {
    error_log("!!!OPENING GARAGE DOOR!!!\n",3,"/var/tmp/garage.log");
    error_log("!!!OPENING GARAGE DOOR!!!\n",3,"/var/tmp/debug.log");
    shell_exec("/usr/bin/gpio mode 1 out");
    shell_exec("/usr/bin/gpio write 1 0");
    usleep(500000);
    shell_exec("/usr/bin/gpio write 1 1");
   }
  }
  //$logstring="Type:".$data['_type']."(".$data['event'].") Lat: ".$data['lat']." Lon: ".$data['lon']." Time: ".$dtstring."\n";
  //error_log($logstring,3,"/var/tmp/garage.log");
  if ($data['tid']=="el") {
   $logfile="/var/tmp/el.log";
  } else {
   $logfile="/var/tmp/63.log";
  }
  error_log($data['lat']."\t".$data['lon']."\tcircle1\tred\t\t".$dtstring." within ".$data['acc']." meters\n",3,$logfile);
  $response=array();
  print json_encode($response);
 }
?>
