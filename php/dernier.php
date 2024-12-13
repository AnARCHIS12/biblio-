<?php

// Démarrage de la session, instruction a placer en tête de script

session_start();

?>

<!DOCTYPE html>

<html>

<body>

 

<?php

 

echo "Variable x : ".$x;

// Variable $x renseignée dans session1.php ... appel de session2.php : $x est inconnue

// (http protocole en mode déconnecté)

 

// Le tableau $_SESSION est 'super global', on y a accès partout (si session_start() fait)

// on pourra donc récupérer l'entrée 'x' de ce même tableau

echo "Entrée 'x' dans le tableau de Session : ".$_SESSION["x"];

 

?>

</body>

</html>

 