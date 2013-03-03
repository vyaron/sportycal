<?php
   //$dateSrc = '2007-04-19 12:50 GMT';
   $dateSrc = '2007-04-19 07:50 Asia/Jerusalem';



   # Using second function.
   $dateTime = new DateTime($dateSrc);

   echo "Original Time is: " . $dateTime->format('Y-m-d H:i:s');
   echo "<br/>";
   
   $DateTimeZone = timezone_open ( 'America/Chicago' );
   $dateTime->setTimezone( $DateTimeZone );
   $NewDateTimeZone = $dateTime->getTimezone ();
   echo 'New timeZone is '. timezone_name_get ($NewDateTimeZone) . ": ";
   echo "Time is: " . $dateTime->format('Y-m-d H:i:s');
   echo "<br/>";

   
?> 


