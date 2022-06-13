<?php     

require('cobdd.php');
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username");
    $email = filter_input(INPUT_POST, "email");
    $username = str_replace(' ', '_', $username);
    $maRequete2 = $pdo->prepare("SELECT * FROM userinfo WHERE username = :username or email = :email");
    $maRequete2->execute(array(
        'username' => $username,
        'email' => $email
    ));
    $verifuse = $maRequete2->fetch(); 
    if (!$verifuse){
    $maRequete = $pdo->prepare("INSERT INTO userinfo (username,email) VALUES(:username,:email)");
    $maRequete->execute(array(
        'username' => $username,
        'email' => $email
    ));
    header('Location: index.php');
    }elseif($verifuse){
        echo "<h2 style='color:red'>Ce nom d'utilisateur ou ce mail sont déja utilisé veuiller choisir un autre combo.</h2>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="shortcut icon" href="calendar.png" type="image/png">

</head>
<body>
    <form method="post">
        <label for="username">username</label>
        <input type="text" name="username">
        <label for="username">username</label>
        <input type="email" name="email">
        <button type="submit">Submit</button>
    </form>
</body>
</html>