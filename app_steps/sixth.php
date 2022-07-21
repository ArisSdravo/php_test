<html>
    <body>
        <?php
        class MyClass {
            public $prop1 = "I'm a class property!";

            public function __construct() {
                echo 'The class "' ,__CLASS__,'" was initiated!<br />';
            }

            public function setProperty($newval) {
                $this->prop1 = $newval;
            }
            
            public function getProperty() {
                return $this ->prop1 . "<br />";
            }
        }
        $obj = new MyClass; // Create a new object
        echo $obj->getProperty(); // Get the value of $prop1
        // Output a message at the end of the file
        echo "End of file.<br />";
        ?>
    </body>
</html>