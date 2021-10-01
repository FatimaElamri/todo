<?php
if(!isset($_SESSION)) {
    session_start();
}
include('src/PHP_Files/connexion_db.php');
if (isset($_GET['choix'])) {
    $choix = $_GET['choix'];
} else {
    $choix = 'se_connecter';
}
switch ($choix) {
    case 'authentification_start':
        include('src/PHP_Files/login_start.php');
        break;
}
?>
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
            integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="public/css/tailwind.css"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>ToDo List</title>
</head>

<body class="bg-gradient-to-tr from-green-300 to-red-300" id="body">

<?php switch ($choix) {
    case 'authentification_stop' :
        include('src/PHP_Files/logout.php');
        break;
    case 'todos' :
        include('src/PHP_Files/todos.php');
        break;
    case 'new_member_formulaire' :
        include('src/PHP_Files/signup.php');
        break;
    case 'se_connecter' :
        include('src/PHP_Files/login.php');
        break;
    default :
        include('src/PHP_Files/login.php');
}
?>
<div class="container">
    <h1><span>ToDo</span> List</h1>

    <!--TO DO-->
<!--    <div class="">-->
       <?php if (isset($_SESSION['login'])) { ?>
           <form id="deconnexion" class=""
                 action="index.php?choix=authentification_stop" method="POST">
               <a href="index.php?choix=authentification_stop" class="nav-link">Deconnexion</a>
           </form>
            <div class="form-container">
                <form action="" method="POST" class="form_task">
                    <input type="text" class="tache" name="tache" id="tache" maxlength="80"
                           placeholder="Thing to remember" autocomplete="off" required><span
                            class="countChar">0/80</span>
                    <button class="btn-form">Let's remember!</button>
                </form>
            </div>
            <div id="displaydata"><?php include('src/PHP_Files/select_tache.php'); ?></div>
            <?php } ?>
    </div>
<script>
    $(document).ready(function () {
        // $(function () {
        //     $('body').css('visibility', 'visible');
        // });

        if (!<?php echo isset($_SESSION['login'])?'true':'false'; ?>) {
            $(".container").hide();
            console.log("not logged")
        } else {
            $(".div_log").show();
            $(".container").show();
            checkCrossOut();
        }

        // ADD NEW TO DO INTO DATABASE
        $('.btn-form').click(function (e) {
            e.preventDefault();
            var tache = $('#tache').val();

            if (tache !== "") {
                $.ajax({
                    type: "POST",
                    url: "src/PHP_Files/insert_tache.php",
                    data: {
                        tache: tache
                    },
                    success: function () {
                        $('.form_task')[0].reset();
                    }
                });
                // DISPLAY TO DO
                selectTask = tache;
                $.ajax({
                    type: "GET",
                    url: "src/PHP_Files/select_tache.php",
                    contentType: "application/json",
                    data: {
                        tache : selectTask
                    },
                    datatype: 'json',
                    success: function (data) {
                        $('#displaydata').html(data);
                        checkCrossOut();
                    }
                });
            } else {
                alert("Nothing to do?");
            }
            $(".countChar").html("0/80");
        });

        //NB CHAR INPUT
        $('.tache').keydown(function (e) {
            var tache = $(".tache").val();

            if (e.keyCode !== 8) {
                if (this.value.length >= 80) {
                    return false;
                } else {
                    $(".countChar").html((tache.length + 1) + "/80");
                }
            } else {
                if (this.value.length > 0 && this.value.length <= 80) {
                    $(".countChar").html((tache.length - 1) + "/80");
                }
            }
        });

        // LOGIN
        // $(".login").click(function (){
        //     $(".div_log").hide();
        //     $(".container").hide();
        //     $(".login_window").removeClass("hidden");
        //     $(".login_window").show();
        // });
        // Close login form
        // $(".close").click(function (){
        //     $(".div_log").show();
        //     $(".container").show();
        //     $(".login_window").addClass("hidden");
        //     $(".login_window").hide();
        // });
    })

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Code pour afficher le mot de passe à la connexion
    function fonction_voir_mdp() {
        var mdp = document.getElementById("password");
        if (mdp.type === "password") {
            mdp.type = "text";
        } else {
            mdp.type = "password";
        }
    }
    //Code pour afficher le mot de passe à la connexion à l'inscription au moment de la confirmation
    function fonction_voir_mdp_inscription() {
        var mdp = document.getElementById("password_conf");
        if (mdp.type === "password") {
            mdp.type = "text";
        } else {
            mdp.type = "password";
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

</script>
</body>
</html>