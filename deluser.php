<?php 
    require('cobdd.php');
    session_start();
if($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $username = filter_input(INPUT_POST, "username");
    $maRequete1 = $pdo->prepare("SELECT * FROM userinfo WHERE username = :username or email = :username");
    $maRequete1->execute([
        ":username" => $username
    ]);
    $maRequete1->setFetchMode(PDO::FETCH_ASSOC);
    $log = $maRequete1->fetch();

if ($log['username'] == $username or $log['email'] == $username and $username == $_SESSION["username"]){
    $maRequete = $pdo->prepare("DELETE FROM userinfo WHERE username = :username or email = :username");
    $maRequete->execute([
        ":username" => $username
    ]);
    $maRequete4 = $pdo->prepare("DELETE FROM schedule WHERE username = :username ");
    $maRequete4->execute([
        ":username" => $username
    ]);
    header('Location: index.php');
}elseif($log['username'] != $username or $username !=$_SESSION["username"]){
if($log['username'] != $username or $username !=$_SESSION["username"]){
    echo "<h2 style='color:red'> identifiant incorrect </h2>";
}
}
}
?>
