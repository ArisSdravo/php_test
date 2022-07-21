<html>
    <body>
        <?php
            $i=0;
            while ($i <= 5) {
                echo "Number : " . $i . "<br/>";
                $i++;
            }
        ?>
        <br>
        <br>
        <?php
            $i=1;
            do {
                $i++;
                echo "The number is " . $i . "<br/>";
            } while ($i <= 5);
        ?>
    </body>
</html>