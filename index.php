<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CALENDRIER</title>
        <link rel="shortcut icon" href="calendar.png" type="image/png">
        <link rel="stylesheet" href="style.css">
        <link rel="manifest" href="manifest.webmanifest">
        <script src="index.js" defer></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    </head>

    <header class="headbar">
        <button class="ubtn" class="add-button"><span class="material-symbols-outlined">download</span></button>
        <script>   
            let date = new Date().toDateString();
            document.write(date)
        </script>
        <button  class="dnmode" onclick="myFunction()"><span id="dark" class="material-symbols-outlined">dark_mode</span></button>
        <script>
            function myFunction() {
            let element = document.body;
            element.classList.toggle("dark-mode");

            if (document.getElementById('dark').innerText=='light_mode' or getCookie("cmode") == 'light_mode'){
                document.cookie = "cmode = dark_mode";
                document.getElementById('dark').innerHTML = 'dark_mode';
            }else if (document.getElementById('dark').innerText=='dark_mode' getCookie("cmode") == 'dark_mode'){
                document.cookie = "cmode = ligh_mode";
                document.getElementById('dark').innerHTML = 'light_mode';
            }
            }
        </script>
    </header>

    <body>

    <?php
    session_start();
    require('cobdd.php');
    if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['signin']))) {
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
            }else if($verifuse){
                echo "<center><h2 style='color:red'>Ce nom d'utilisateur ou ce mail sont déja utilisé veuiller choisir un autre combo.</h2></center>";
            }
        }
    }else if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['login']))){
        $username = filter_input(INPUT_POST, "username");
        $maRequete = $pdo->prepare("SELECT * FROM userinfo WHERE username = :username or email = :username ");
        $maRequete->execute([
            ":username" => $username
        ]);
        $maRequete->setFetchMode(PDO::FETCH_ASSOC);
        $log = $maRequete->fetch();
        if (($log['username'] == $username or $log['email'] == $username)){
            $_SESSION["connecte"] = True;
            $_SESSION["username"] = $username;
            http_response_code(302);
            $username = $_SESSION['username'];
            ?>
            <!-- today -->
            
<?php 
                    
}elseif(($log['username'] != $username or $log['email'] != $username)){
    echo "<center><span style='color:red'>identifiant indisponible, veuillez ressayer</span></center> ";
}
}
if(!isset($_SESSION['connecte'])){  ?>
        <section class="login-box"> 
        <h2>LOGIN</h2>
            <form method="post" >
                <div class="user-box">
                    <input type="text" name="username"required>
                    <label>Username</label>
                </div>
                <button name="login" type="submit">
                <span></span>
      <span></span>
      <span></span>
      <span></span>
      Submit
    </button>
            </form>
            <br><h2>-OR-</h2>
            <br><h2>SIGNIN</h2>
            <form method="post" class="form-signin">
                <div class="user-box">
                    <input type="text" name="username"required>
                    <label>Username</label>
                </div>
                <div class="user-box">
                    <input type="email" name="username"required>
                    <label>Email</label>
                </div>
                <button  name="signin" type="submit">
                <span></span>
      <span></span>
      <span></span>
      <span></span>
      Submit
    </button>
            </form>
        </section>
        <?php }
    elseif($_SESSION['connecte']==True){?>
        <section class="today" id="stoday">
                <table>
                    <tbody >
                        <script>
                            var heure = new Date().getHours();
                                for (let i = 0; i < 24; i++){
                                    if (i<10){
                                        if(i==heure){document.write("<tr class='trr'><td>0"+i+"H00</td><td>No events scheduled.</td></tr>")}
                                        else{document.write("<tr><td>0"+i+"H00</td><td>No events scheduled.</td></tr>")}
                                    }else{
                                        if(i==heure){document.write("<tr class='trr'><td>"+i+"H00</td><td>No events scheduled.</td></tr>")}
                                        else{document.write("<tr><td>"+i+"H00</td><td>No events scheduled.</td></tr>")}
                                    }
                                }
                        </script>
                    </tbody>
                </table>
            </section>
                    
            <!-- schedule -->
            <section class="schedule" id="sschedule">
                <script>
                    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    const weekNames = ["Mond", "Tues", "Wedn", "Thur", "Frid", "Satu", "Sund"];
                    var jour = new Date().getDate();
                    var mois = new Date().getMonth()+1;
                    var ans = new Date().getFullYear();
                    let semaine=0
                    let l = 0
                    function getDaysInMonth(year, month) {
                        return new Date(year, month, 0).getDate();
                    }
                    function getSemaine(js){
                        if(js == 3){return 0}
                        else if(js == 4){return 0}
                        else if(js == 5){return 1}
                        else if(js == 6){return 2}
                        else if(js == 0){return 3}
                        else if(js == 1){return 4}
                        else if(js == 2){return 5}
                        else if(js == 3){return 6}
                    }
                    for(let p=0;p<12;p++){
                        l=0
                        document.write("<center><h1 '>"+monthNames[p]+"</h1></center>")
                        document.write("<div  class='calendardiv' ")
                        for(let t =0;t<7;t++){
                            document.write("<span style='grid-row:1;grid-column:"+(t+1)+";'>"+weekNames[t]+"</span>")
                        }
                        if(p==0){
                            js = new Date(ans,p+1,1)
                            semaine = getSemaine(js.getDay())
                        }
                        for (let i = 1; i < getDaysInMonth(ans, p+1)+1; i++){
                            semaine=semaine+1
                            if(i==jour & mois==p+1){document.write("<div class='trr'  style='display: flex;align-items: center;width:100%;height:100%;justify-content: center;grid-row:"+(l+2)+";grid-column:"+(semaine)+"'>"+i+"</div>")}
                            else{document.write("<div class='hspan' id='mbt' style='display: flex;align-items: center;width:100%;height:100%;justify-content: center;grid-row:"+(l+2)+";grid-column:"+(semaine)+"'>"+i+"</div>")}
                            if(semaine==7){semaine=0;l=l+1}
                        }
                        document.write("</div>")
                    }
                    
                </script>
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <p>Some text in the Modal..</p>
                        </div>
                    </div>
                    <script>
                    var modal = document.getElementById("myModal");
                    var btn = document.getElementById("mbt");
                    var span = document.getElementsByClassName("close")[0];
                    btn.onclick = function() {modal.style.display = "block";}
                    span.onclick = function() {modal.style.display = "none";}
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                </script>   
            </section>
        
            <!-- setting -->
            <section class="setting" id="ssetting">
                <form action="">
                    <label for="">Utilisateur Actuel : </label>
                    <br><label for="">Notifications : </label>
                    <br><label for="">Couleur d'accentuation light : </label>
                    <br><label for="">Couleur d'accentuation dark : </label>
                </form>
                <form action="deluser.php"><button type="submit" style="color:red">Delete account</button></form>
                <form action="logout.php"><button type="submit" style="color:orange">Log-out</button></form>
            </section>
            
                    
        </body>
        <footer>
        <nav class="navbar">
            <button id="today" class="nbuton"><span class="material-symbols-outlined">today</span> <span  id="hdt">Today</span></button>
            <button id="schedule" class="nbuton"><span class="material-symbols-outlined">calendar_month</span> <span  id="hdt">Schedule</span></button>
            <button id="setting" class="nbuton"><span class="material-symbols-outlined">settings</span> <span  id="hdt">Setting</span></button>
        </nav>
        <script>
            let bt1 = document.getElementById("today");
            let bt2 = document.getElementById("schedule");
            let bt3 = document.getElementById("setting");
            let s1 = document.getElementById("stoday");
            let s2 = document.getElementById("sschedule");
            let s3 = document.getElementById("ssetting");
            bt1.style.boxShadow = "0px -5px 0px 0px orange"
            bt1.addEventListener("click", () => {
                s1.style.display = "block";
                bt1.style.boxShadow = "0px -5px 0px 0px orange"
                bt2.style.boxShadow = "0px -5px 0px 0px var(--colortwo)"
                bt3.style.boxShadow = "0px -5px 0px 0px var(--colortwo)"
                s2.style.display = "none";
                s3.style.display = "none";
                s1.style.animation = "moveToRight 0.5s ease-in-out"
            })
            bt2.addEventListener("click", () => {
                s2.style.display = "block";
                bt2.style.boxShadow = "0px -5px 0px 0px orange"
                bt3.style.boxShadow = "0px -5px 0px 0px var(--colortwo)"
                bt1.style.boxShadow = "0px -5px 0px 0px var(--colortwo)"
                s1.style.display = "none";
                s3.style.display = "none";
                s2.style.animation = "moveToRight 0.5s ease-in-out"
                
            })
            bt3.addEventListener("click", () => {
                s3.style.display = "block";
                bt3.style.boxShadow = "0px -5px 0px 0px orange"
                bt2.style.boxShadow = "0px -5px 0px 0px var(--colortwo)"
                bt1.style.boxShadow = "0px -5px 0px 0px var(--colortwo)"
                s2.style.display = "none";
                s1.style.display = "none";
                s3.style.animation = "moveToRight 0.5s ease-in-out"
            })
        </script>
    </footer>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('Service Worker: Registered', registration);
            });
        }
    </script>
</html>

       <?php } ?>

      

