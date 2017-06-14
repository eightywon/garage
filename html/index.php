<?php
 if ($_POST["p1"]=="interval") {
  if (shell_exec("/usr/bin/gpio read 0")==0) {
   echo "CLOSED";
  } else {
   echo "OPEN";
  }
  exit;
 } else if ($_POST["p1"]=="clicked") {
  shell_exec("/usr/bin/gpio mode 1 out");
  shell_exec("/usr/bin/gpio write 1 0");
  usleep(500000);
  shell_exec("/usr/bin/gpio write 1 1");
  sleep(15);
  echo("good");
  exit;
 }
?>
<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="http://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>
  <script type="text/javascript">
  </script>
  <script>
   $(function() {
    $('#toggle').click(function() {
     var obj=document.getElementById('toggle');
     if (obj.value=="Open Door") {
      obj.value="Opening...";
     } else {
      obj.value="Closing...";
     }
     obj.disabled=true;
     $.ajax({
      url: 'index.php',
      type: 'POST',
      data: 'p1=clicked',
      success: function(data) {
       obj.disabled=false;
       obj.value="";
      },
     });
    });
    var callAjax = function(){
     $.ajax({
      url:'index.php',
      type:'POST',
      data: 'p1=interval',
      success:function(data1) {
       if (document.getElementById('toggle').value!="Closing..." &&
           document.getElementById('toggle').value!="Opening...") {
        if (data1=="CLOSED") {
         document.getElementById('toggle').value="Open Door";
        } else {
         document.getElementById('toggle').value="Close Door";
        }
       }
      }
     });
    }
    setInterval(callAjax,200);
   });
  </script>
 </head>
 <body>
  <?php
   if (shell_exec("/usr/bin/gpio read 0")==0) {
    $val="Open Door";
   } else {
    $val="Close Door";
   }
  ?>
  <table width=100% height=100%>
   <tr>
    <td align=center valign=center>
     <input type="button" value="<?=$val?>" name="toggle" id="toggle" style="height:75px;width:150px">
    </td>
   </tr>
  </table>
 </body>
</html>
