<?php
    session_start();
    include ('../nav/nav.php');
    require_once "../connect/connect.php";

    echo "<a href='regular/regular.html'>regular order</a> 
          <a href='quick/quick_wash.html'>quick wash</a>";
?>