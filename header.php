<!--------------------------------------
Gretar Ævarsson
© 2016 gretar80@gmail.com
---------------------------------------->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>VM Skiregistrering</title>
        <style>
            html,body {margin:0;padding:0;background-color:white;font:small/1.3 Arial, Helvetica, sans-serif;height:100%;min-width:700px}
            section {min-height:100%;position:relative;}
            #nav {width:100%;float:left;margin:0 0 3em 0;padding:0;list-style:none;background-color:#f2f2f2;border-bottom:1px solid #ccc;border-top:1px solid #ccc;}
            #nav li {float:left;}
            #nav li a {display:block;padding:8px 15px;text-decoration:none;font-weight:bold;color:#069;border-right:1px solid #ccc;}
            #nav li a:hover {color:#c00;background-color:#fff;}
            header {margin:0 auto;background-color:#444;}
            h1 {font-size:2.5em;text-shadow:1px 1px 0 rgb(0,0,0); padding:20px 20px;color:white;margin:0;}
            article {background-color:white;padding:20px 20px 50px;padding-bottom:50px;}
            footer {background:#444;border-top:2px solid black;color:white;width:100%;height:20px;position:absolute;bottom:0;left:0;padding:10px 0px 10px 0px;}
            table td {padding:0px 5px 0px 5px;}
            #loggut{text-align: right; }
            /*input[type="submit"] {width:10em;}*/
        </style>
    </head>
    <body>
        <section>
            <header>
            <h1>VM Skiregistrering</h1>
            <ul id="nav">
                <li><a href="./index.php">Oversikt</a></li>
                <li><a href="./event.php">Ny øvelse</a></li>
                <li><a href="./endre.php">Endre øvelse</a></li>
                <li><a href="./publikum.php">Publikum</a></li>
                <li><a href="./contestant.php">Utøvere</a></li>
                <li><a href="./regadmin.php">Admin</a></li>
                <?php if( empty($_SESSION['loggetInn']) || !$_SESSION['loggetInn'] ) echo '<li><a href="./loginn.php">Logg inn</a></li>'; else echo '<li><a href="./clear.php">Logg ut</a></li>';?>
                
            </ul>
            </header>
