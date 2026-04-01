<?php
ob_start();
session_start();
require_once("classes/guestbook.class.php");
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Guestbook test</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="wrapper">
 
    <h1>Berichten!!</h1>

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

    <hr/>
    <h2>Contact Us</h2>
    
    <?php
    if (isset($_SESSION['contactMessage'])) {
        echo $_SESSION['contactMessage'];
        unset($_SESSION['contactMessage']);
    }
    ?>

    <form action="" method="post">
        <label for="contact_name">Name: </label>
        <br>
        <input type="text" name="contact_name" id="contact_name" placeholder="Your Name" value="<?= (isset($_SESSION['contact_name'])) ? $_SESSION['contact_name'] : "";?>"/>
        <br>
        <label for="contact_email">Email: </label>
        <br>
        <input type="email" name="contact_email" id="contact_email" placeholder="Your Email" value="<?= (isset($_SESSION['contact_email'])) ? $_SESSION['contact_email'] : "";?>"/>
        <br>
        <label for="contact_subject">Subject: </label>
        <br>
        <input type="text" name="contact_subject" id="contact_subject" placeholder="Subject" value="<?= (isset($_SESSION['contact_subject'])) ? $_SESSION['contact_subject'] : "";?>"/>
        <br>
        <label for="contact_message">Message: </label>
        <br>
        <textarea cols="40" rows="3" name="contact_message" id="contact_message" placeholder="Your Message"><?= (isset($_SESSION['contact_message'])) ? $_SESSION['contact_message'] : "";?></textarea>
        <br>
        <input type="submit" name="contactBtn" value="Send Contact"/>
    </form>

    <?php
    if (isset($_POST['contactBtn']))
    {
        $contact_name = $_POST['contact_name'];
        $contact_email = $_POST['contact_email'];
        $contact_subject = $_POST['contact_subject'];
        $contact_message = $_POST['contact_message'];
        
        $_SESSION['contact_name'] = $contact_name;
        $_SESSION['contact_email'] = $contact_email;
        $_SESSION['contact_subject'] = $contact_subject;
        $_SESSION['contact_message'] = $contact_message;

        if ((!empty($contact_name)) && (!empty($contact_email)) && (!empty($contact_subject)) && (!empty($contact_message)))
        {
            $messageLength = strlen($contact_message);
            if ($messageLength > 500) {
                $_SESSION['contactMessage'] = "<p class=\"redMessage\">Message moet max 500 karakters zijn</p>";
            }
            else{
                $to = "admin@example.com";
                $headers = "From: " . $contact_name . " <" . $contact_email . ">\r\n";
                $headers .= "Reply-To: " . $contact_email . "\r\n";
                
                if (mail($to, $contact_subject, $contact_message, $headers)) {
                    $_SESSION['contactMessage'] = "<p class=\"greenMessage\">Contact form submitted successfully</p>";
                    $_SESSION['contact_name'] = null;
                    $_SESSION['contact_email'] = null;
                    $_SESSION['contact_subject'] = null;
                    $_SESSION['contact_message'] = null;
                }
                else{
                    $_SESSION['contactMessage'] = "<p class=\"redMessage\">Something went wrong sending your message</p>";
                }
            }
        }
        else
        {
            $_SESSION['contactMessage'] = "<p class=\"redMessage\">All fields are required</p>";
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