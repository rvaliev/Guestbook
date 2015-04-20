<?php
ob_start();
session_start();
require_once("classes/guestbook.class.php");
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Guestbook</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="wrapper">

    <h1>Berichten</h1>

    <?php
    $postsObj = new Guestbook();
    $posts = $postsObj->getAllMessages();


    foreach ($posts as $post) {
    ?>
    <ul>
        <li><b>Auteur:</b> <?= $post['auteur'];?></li>
        <li><b>Tijd:</b> <?= $post['datum'];?></li>
        <li><b>Bericht:</b> <i><?= $post['boodschap'];?></i></li>
    </ul>
    <hr/>
    <?php
    }

    ?>

    <form action="../guestbook/index.php" method="post">
        <label for="author">Auteur: </label>
        <br>
        <input type="text" name="author" placeholder="Auteur" value="<?= (isset($_SESSION['author'])) ? $_SESSION['author'] : "";?>"/>
        <br>
        <label for="message">Boodschap:</label>
        <br>
        <textarea cols="40" rows="3" name="message" placeholder="Boodschap"><?= (isset($_SESSION['message'])) ? $_SESSION['message'] : "";?></textarea>
        <br>
        <input type="submit" name="postBtn" value="Send"/>
    </form>

    <?php
    if (isset($_SESSION['errorMessage'])) {
        echo $_SESSION['errorMessage'];
    }

    if (isset($_POST['postBtn']))
    {
        $author = $_POST['author'];
        $message = $_POST['message'];
        $_SESSION['author'] = $author;
        $_SESSION['message'] = $message;



        if ((!empty($author)) && (!empty($message)))
        {
            $messageLength = strlen($message);
            if ($messageLength > 200) {
                $_SESSION['errorMessage'] = "<p class=\"redMessage\">Boodschap moet max 200 karakters zijn</p>";
            }
            else{
                if ($postsObj->insertPost($author, $message)) {
                    $_SESSION['errorMessage'] = "<p class=\"greenMessage\">Nieuwe bericht is toegevoegd</p>";
                    $_SESSION['author'] = null;
                    $_SESSION['message'] = null;
                }
                else{
                    $_SESSION['errorMessage'] = "<p class=\"redMessage\">Something went wrong</p>";
                }
            }
        }
        else
        {
            $_SESSION['errorMessage'] = "<p class=\"redMessage\">Auteur en boodschap zijn verplicht</p>";
        }

        header("Refresh:0");

    }
    ?>


</div>
</body>
</html>

<?php
ob_flush();
?>