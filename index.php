<?php

/**
 * main api serve in which is uses all POST request and respond in JSON.
 */

include('core.php');
$header = getallheaders();

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $request = json_decode(file_get_contents('php://input'),true);
}else if($_SERVER['REQUEST_METHOD']){
    $request = $_GET;
}else{
    $response = array(
        'responseCode' => 3,
        'responseMsg' => 'Invalid request parameters',
        'responseData' => ''
    );   
    return json_encode($response);
}

$action = $request['action'];

$callObj = new DemoApi($database);

switch(strtoupper($action)){
    case 'LOGIN' :
        if(isset($request['user_name']) && isset($request['password'])){
            $user = $request['user_name'];
            $pass = $request['password'];
            $response = $callObj->login($user,$pass);
            // print_r($response);
        }else{
            $response = array(
                'responseCode' => 3,
                'responseMsg' => 'Invalid request parameters',
                'responseData' => ''
            );    
        }
    break;

    case 'TRACK' :
        if(!isset($header['user_name']) || !isset($header['token'])){
            $response = array(
                'responseCode' => 504,
                'responseMsg' => 'Authentication headers required for this request',
                'responseData' => ''
            );
            break;
        }
        if($callObj->verify($header['user_name'],$header['token'])){
            $resData = array();
            if(isset($request['date'])){
                $resData = $callObj->track('DATE',$request['date']);
            }elseif(isset($request['order'])){
                $resData = $callObj->track('ORDER',$request['order']);
            }
            if(count($resData) <= 0){
                $response = array(
                    'responseCode' => 404,
                    'responseMsg' => 'No records found for given parameters',
                    'responseData' => ''
                );
            }else{
                $response = array(
                    'responseCode' => 0,
                    'responseMsg' => '',
                    'responseData' => $resData
                );
            }
        }else{
            $response = array(
                'responseCode' => 3,
                'responseMsg' => 'Authentication failes. Please try with right crendentials.',
                'responseData' => ''
            );                
        }
    break;

    default : 
        $response = array(
            'responseCode' => 1,
            'responseMsg' => 'Invalid action',
            'responseData' => ''
        );    
    break;
}
exit(json_encode($response));

class DemoApi{

    private $conn = null;
    function __construct($database){
        extract($database);
        $this->conn = new PDO("mysql:host=" . $HOST .";dbname=". $DBNAME, $USER, $PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function login($user,$pass){
        $response = array(
            'responseCode' => 1,
            'responseMsg' => 'Invalid request',
            'responseData' => ''
        );
        try{
            $sql = $this->conn->prepare("SELECT `id`,`user_name` AS username, `user_type` AS usertype FROM `inst_users` WHERE `user_name` = ?  AND `password` = PASSWORD(?)");
            $arg = array();
            array_push($arg,$user);
            array_push($arg,$pass);
            $sql->execute($arg);
            $res = $sql->fetch(PDO::FETCH_ASSOC);
            if($res && (count($res) > 0)){
                $token = date('Y') . $res['id'] . date('m') . date('d') . '##' . date('H:i:s');
                $token = md5($token);
                $upd = $this->conn->prepare('UPDATE `inst_users` SET `token` = ? WHERE `id` = ?');
                $upd->execute(array($token,$res['id']));    
                $data = array(
                    'username' => $res['username'],
                    'usertype' => $res['usertype'],
                    'token' => $token
                );
                $response = array(
                    'responseCode' => 1,
                    'responseMsg' => 'Logged in success',
                    'responseData' => $data
                );
            }else{
                $response = array(
                    'responseCode' => 1,
                    'responseMsg' => 'Aunthentication failed',
                    'responseData' => ''
                );
            }
        }catch(Eception $e){
            $response = array(
                'responseCode' => 1,
                'responseMsg' => 'Some error occured on server. Please try again after some time or contact customer care.',
                'responseData' => ''
            );
        }
        return $response;
    }


    function verify($user = '',$token = ''){
        try{
            $sql = $this->conn->prepare("SELECT `user_name`,`user_type`,`token` FROM `inst_users` WHERE `user_name` = ? ");
            $sql->execute(array($user));
            $res = $sql->fetch(PDO::FETCH_ASSOC);
            if($res && count($res) > 0){
                if(!strcmp($res['token'],$token)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }catch(Exception $e){
            print_r($e->getMessage());
            return false;
        }
    }

    function track($order,$data){
        try{
            if($order == 'DATE'){
                $condition = 'DATE(o.created_at)';
                $arg = DATE($data);
            }else if($order == 'ORDER'){
                $condition = 'o.order_id';
                $arg = $data;
            }
            $sql = $this->conn->prepare("SELECT o.order_id as order_id,o.created_at as order_date,u.id as customer_id,u.disp_name as customer_name,u.mobile as customer_mobile FROM `inst_orders` AS o INNER JOIN `inst_users` AS u ON o.user_id = u.id WHERE ". $condition ." = ?");
            $sql->execute(array($arg));
            $res = $sql->fetchAll(PDO::FETCH_ASSOC);
            $result = array();
            foreach($res as $key => $val){
                $det = $this->conn->prepare("SELECT item_name,quantity,unit_price FROM inst_orders_details where order_id = ?");
                $det->execute(array($val['order_id']));
                $details = $det->fetchAll(PDO::FETCH_ASSOC);
                $result[$key] = $val;
                $result[$key]['details'] = $details;
            }
            return $result;
        }catch(Exception $e){
            $response = array(
                'responseCode' => 504,
                'responseMsg' => 'Some error occured on server.',
                'responseData' => ''
            );
        }
    }

}

?>