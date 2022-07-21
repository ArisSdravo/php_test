<html>
    <body>
        <?php
            $a=1;
            $b=16;
            function add($x, $y) {
                $total = $x + $y;
                return $total;
            }
            echo $a."+".$b."=".add($a,$b);
        ?>

        <br><br>

        <?php
            function xx($x, $y) {
                global $z;  // Global variable.
                $total = $x + $y + $z;
                return $total;
            }
            $z = 5;
            echo "1 + 16"." + ".$z." = " . xx(1,16);
        ?>

        <br><br>

        
        <?php
        class SimpleClass
        { 
            // property declaration
            public $var = 'a default value';

            // method declaration
            public function displayVar() {
                echo $this->var;
            }
        }
        ?>
    </body>
</html>