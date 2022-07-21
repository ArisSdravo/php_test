<?php require('second.php');?>
<br>
<?php include('third.php');?>

<br>
<html>
    <body>
        <?php
            for ($i=1; $i<=5; $i++) {   
                echo "Hello World! <br/>";
            }
        ?>
        <br>
        <br>
        <?php 
            $x=array ("one", "two", "three");
            foreach ($x as $value ) {
                echo $value . "<br/>";
            }
        ?>
    </body>
</html>