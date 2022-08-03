<?php 

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

use \Firebase\JWT\JWT;

use OpenApi\Annotations as OA;

class User {

    protected $collection;

    protected $generalFunctions; 

    public function __construct($connection) {
        try {
            $this->collection = $connection->connect_to_user();
            error_log("Connection to collection User");
            $this->generalFunctions = new GeneralFunctions();
        }
        catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
            error_log("Problem in connection with collection User".$e);
        }
    }
    
    /**
     * @OA\Get(
     *   path="/user/list",
     *   description="List departments",
     *   operationId="showUsers",
     *   tags={"User"},
     *   @OA\Response(
     *     response="200",
     *     description="A list with departments"
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Error"
     *   )
     * )
     */
    public function showUsers() {
        try {
            $result = $this->collection->find()->toArray();
            if (count($result)>0):
                return $this->generalFunctions->returnValue($result,true);
            else:
                return $this->generalFunctions->returnValue("",false);
            endif;
        }
        catch (MongoDB\Exception\UnsupportedException $e){
            error_log("Problem in find users \n".$e);
            return $this->generalFunctions->returnValue("",false);
        }
        catch (MongoDB\Driver\Exception\InvalidArgumentException $e){
            error_log("Problem in find users \n".$e);
            return $this->generalFunctions->returnValue("",false);
        }
        catch (MongoDB\Driver\Exception\RuntimeException $e){
            error_log("Problem in find users \n".$e);
            return $this->generalFunctions->returnValue("",false);
        };
    }

    public function showUser($id) {
        if( isset( $id )) {
            try {
                $result = $this->collection->findOne([
                    '_id'=>new MongoDB\BSON\ObjectId($id)
                ]);
                if ($result):
                    return $this->generalFunctions->returnValue($result,true);
                else:
                    return $this->generalFunctions->returnValue("",false);
                endif;
            }
            catch (MongoDB\Exception\UnsupportedException $e){
                error_log("Problem in findOne user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\InvalidArgumentException $e){
                error_log("Problem in findOne user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\RuntimeException $e){
                error_log("Problem in findOne user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
        } else 
            return $this->generalFunctions->returnValue("",false); 
    }

    public function createUser($data) {
        $username = $data->username;
        $password = $data->password;
        $user_category_identifier = $data->user_category->identifier;
        $user_category_name = $data->user_category->name;
        $name = $data->name;
        $surname = $data->surname;
        $email = $data->email;
        if( isset( $username ) && isset($password) && 
            isset($user_category_identifier) && isset($user_category_name) 
            && isset($name) && isset($surname) && isset($email) ) {
            try {
                $result = $this->collection->insertOne( [ 
                    'username' => $username,
                    'password' => $password,
                    'user_category' => [
                        'identifier' => $user_category_identifier,
                        'name' => $user_category_name
                    ],
                    'surname' => $surname,
                    'name' => $name,
                    'email' => $email,
                    'send_email' => false,
                    'verified' => false,
                    'roles' => [],
                    'subscription_list' => []
                ] );
                if ($result->getInsertedCount()==1)
                    return $this->generalFunctions->returnValue("",true);
                else 
                    return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\InvalidArgumentException $e){
                error_log("Problem in insert user category \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\BulkWriteException $e){
                error_log("Problem in insert user category \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\RuntimeException $e){
                error_log("Problem in insert user category \n".$e);
                return $this->generalFunctions->returnValue("",false);
            };
        } else 
            return $this->generalFunctions->returnValue("",false);
    }

    public function deleteUser($id) {
        if (isset( $id )){
            try {
                $result = $this->collection->deleteOne([
                    '_id'=>new MongoDB\BSON\ObjectId($id)
                ]);
                if ($result->getDeletedCount()==1)
                    return $this->generalFunctions->returnValue("",true);
                else 
                    return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Exception\UnsupportedException $e){
                error_log("Problem in delete user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\InvalidArgumentException $e){
                error_log("Problem in delete user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\BulkWriteException $e){
                error_log("Problem in delete user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\RuntimeException $e){
                error_log("Problem in delete user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            };
        } else 
            return $this->generalFunctions->returnValue("",false);
    }

    public function updateUser($data) {
        $id = $data->_id;
        $username = $data->username;
        $user_category_identifier = $data->user_category->identifier;
        $user_category_name = $data->user_category->name;
        $name = $data->name;
        $surname = $data->surname;
        $email = $data->email;
        $send_email = $data->send_email;
        $verified = $data->verified;

        if( isset( $id ) && isset( $username ) && 
            isset($user_category_identifier) && isset($user_category_name) && 
            isset($name) && isset($surname) && isset($email)) {
            try {
                $result = $this->collection->updateOne( 
                    [ '_id' => new MongoDB\BSON\ObjectId($id) ],
                    [ '$set' => [
                            'username' => $username,
                            'user_category' => [
                                'identifier' => $user_category_identifier,
                                'name' => $user_category_name
                            ],
                            'surname' => $surname,
                            'name' => $name,
                            'send_email' => $send_email,
                            'verified' => $verified
                        ]
                    ]
                );
                if ($result->getModifiedCount()==1)
                    return $this->generalFunctions->returnValue("",true);
                else 
                    return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\InvalidArgumentException $e){
                error_log("Problem in update user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\BulkWriteException $e){
                error_log("Problem in update user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\RuntimeException $e){
                error_log("Problem in update user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            };
        } else 
            return $this->generalFunctions->returnValue("",false);
    }

    /**
     * @OA\Post(
     *     path="/user/login",
     *     description="login a user",
     *     operationId="loginUser",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="username",type="string"),
     *                 @OA\Property(property="password",type="string"),
     *                example={"username": "akosta", "password": "1234"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retuns a json object with true or false value to field success",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(type="boolean")
     *             },
     *             @OA\Examples(example="False bool", value={"success": false}, summary="A false boolean value."),
     *             @OA\Examples(example="True bool", value={"success": true}, summary="A true boolean value."),
     *         )
     *     )
     * )
     */
    public function loginUser($data) {
        $username = $data->username;
        $password = $data->password;
        
        $findUser = $this->collection->findOne([
            'username'=> $username
        ]);

        // return json_encode($findUser->password);

        if( $findUser && isset( $password )){
            try {

                if ($password==$findUser->password) {
                    $data = json_encode(array(
                        "success" => true,
                        "username" => $username,
                        "permission" =>"editor",
                        "authorizations" => "xxxxx"
                    ));
                    return $data;
                    //return $this->generalFunctions->returnValue("",true);
                }   
                else 
                    return $this->generalFunctions->returnValue("x2",false);
            }
            catch (MongoDB\Driver\Exception\InvalidArgumentException $e){
                error_log("Problem in update user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\BulkWriteException $e){
                error_log("Problem in update user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            }
            catch (MongoDB\Driver\Exception\RuntimeException $e){
                error_log("Problem in update user \n".$e);
                return $this->generalFunctions->returnValue("",false);
            };
        } else 
            return $this->generalFunctions->returnValue("x1",false);
    }

    private function returnValue($result, $value){
        if ($value===true)
            return json_encode(array(
                'data' => json_encode($result),
                'success' => true
                )
            );
        else 
            return json_encode(array('success' => false));
    }
}
?>