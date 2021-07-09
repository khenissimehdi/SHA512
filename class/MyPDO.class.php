<?php
   require_once 'MyPDO.template.class.php';

   MyPDO::setConfiguration('mysql:host=mysql;dbname=DB_NAME;charset=utf8', 'USERNAME', 'PASSWORD');
