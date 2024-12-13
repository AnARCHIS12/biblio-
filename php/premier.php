
<form action="welcome.php" method="post">

Name: <input type="text" name="txtName"><br>

E-mail: <input type="text" name="txtEmail"><br>

<input type="submit">

</form>

 

</body>

</html>
<html>

<body>

 

Welcome <?php echo $_POST["txtName"]; ?><br>

Your email address is: <?php echo $_POST["txtEmail"]; ?>

 

</body>

</html>

<?php

echo $_SERVER['PHP_SELF']; // URL courante

echo "<br>";

echo $_SERVER['SERVER_NAME'];

echo "<br>";

echo $_SERVER['HTTP_HOST'];

echo "<br>";

echo $_SERVER['HTTP_REFERER'];

echo "<br>";

echo $_SERVER['HTTP_USER_AGENT'];

echo "<br>";

echo $_SERVER['SCRIPT_NAME'];

?>