<?php

// header("Access-Control-Allow-Origin: *");
// header("Content-Type: application/json; charset=utf-8");

require dirname(__FILE__,2).'/vendor/autoload.php';

echo $_SERVER['DOCUMENT_ROOT']."<br>";
echo dirname(__FILE__,2)."<br>";
echo __DIR__."<br>";

// Uncomment for localhost running
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

$MDB_USER=$_ENV['MDB_USER'];
$MDB_PASS=$_ENV['MDB_PASS'];
$ATLAS_CLUSTER_SRV=$_ENV['ATLAS_CLUSTER_SRV'];

$client= new MongoDB\Client('mongodb+srv://'.$MDB_USER.':'.$MDB_PASS.'@'.$ATLAS_CLUSTER_SRV.'/?retryWrites=true&w=majority');
// $client= new MongoDB\Client(`mongodb+srv://admin:<password>@cluster0.2dc6x.mongodb.net/test`);
$db = $client->announcements;

$collection = $db->department;

$result = $collection->find()->toArray();

print(json_encode($result));

?>
<hr>
<?php print_r($result); ?>