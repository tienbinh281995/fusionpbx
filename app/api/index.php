<?php

	include "root.php";
	require_once "resources/require.php";

	require_once "resources/functions/restapi_functions.php";
	require 'resources/functions/restful_api.php';
	
	
	require  '../../vendor/autoload.php';
	use \Firebase\JWT\JWT;
	

				
	//$apikey = '670bc52e-889f-45cc-acec-380ac6c3eb94';
			// set request key value ready for call to check_auth
			// Kiểm tra xác thực API KEY lấy trong phần user 
	//$_REQUEST['key'] = $apikey;
	//require_once "resources/check_auth.php";	
	
class api extends restful_api {

	function __construct(){
		parent::__construct();
	}

	function webhook(){
		
	
			
		if ($this->method == 'GET'){
			// Hãy viết code xử lý LẤY dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
			
			
			
			$this->response(200, "OK CHAY ROI");
		}
		elseif ($this->method == 'POST'){
			// Hãy viết code xử lý THÊM dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
			
			
	
			$data = '{}';
			$this->response(200, $data);
		}
		elseif ($this->method == 'PUT'){
			// Hãy viết code xử lý CẬP NHẬT dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
		elseif ($this->method == 'DELETE'){
			// Hãy viết code xử lý XÓA dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
	}
	
	function binh(){
		if ($this->method == 'GET'){
			// Hãy viết code xử lý LẤY dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
			$this->response(200, "BINH OI ! OK CHAY ROI");
		}
		elseif ($this->method == 'POST'){
			// Hãy viết code xử lý THÊM dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
			$data = '{}';
			$this->response(200, $data);
		}
		elseif ($this->method == 'PUT'){
			// Hãy viết code xử lý CẬP NHẬT dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
		elseif ($this->method == 'DELETE'){
			// Hãy viết code xử lý XÓA dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
	}
	
	function dnc(){
		if ($this->method == 'GET'){
			// Hãy viết code xử lý LẤY dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
			$this->response(200, "BINH OI ! OK CHAY ROI");
		}
		elseif ($this->method == 'POST'){
			// Hãy viết code xử lý THÊM dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		
			
			//////////////////////////////////////////////////////////////////////////
			//// STEP 1 : XÁC THỰC TOKEN 								/////////////
			/////////////////////////////////////////////////////////////////////////
			try {
				//$jsondata = json_decode($this->params);
				//Authorization: Bearer ab0dde18155a43ee83edba4a4542b973
				// $matches = ab0dde18155a43ee83edba4a4542b973
				if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
				    $this->response(400,'Bad Request - not match');
				    
				    exit;
				}
				
				//$jwt = str_replace('"','',$matches[1]);
				$jwt = $matches[1];
				if (! $jwt) {
				    // No token was able to be extracted from the authorization header
				    $this->response(400,'Bad Request');
				    exit;
				}
				
				$VNCERT_IPADDR = '123.31.36.19';
				// Lấy token ra và giải mã
				
			
				
				$apikey = '670bc52e-889f-45cc-acec-380ac6c3eb94';
					
				$token = JWT::decode($jwt, $apikey,['HS256']);
				$jsondata = json_encode($token);
				//echo $token->msisdn;
				//echo $jwt;
				
				//$error = json_encode(array('error_code'=>'0','error_desc'=>'Good'));
				$error = array('error_code'=>'0','error_desc'=>'Good');
				//$this->response(200, $jsondata);
				if ($token->IPAddress != $VNCERT_IPADDR)
				{
					$this->response(400,'Bad Request');
					exit;
					
				}
			


			}
			catch (UnexpectedValueException $e) {
				 echo $e->getMessage();
			}
			//////////////////////////////////////////////////////////////////////////
			//// STEP 2 : XỬ LÝ DATA TRONG BODY DẠNG JSON				/////////////
			/////////////////////////////////////////////////////////////////////////
			/// ĐẾN PHẦN XỬ LÝ DATA TRONG BODY DẠNG JSON
			//{"telco":"01","mo_time":"18/03/2021 00:54:27","cmd_code":"DK","msisdn":"84932668446","shortcode":"5656","info":"DK DNC"}
			// Takes raw data from the request
			$json = file_get_contents('php://input');
			
			// Converts it into a PHP object
			$data = json_decode($json);
			
			
			// debug
			
			//$this->response(200, $data);
			
			/////////////////////////////////////////////////////////////////////////
			//// STEP 3 : GHI VÀO MỘT FILE TXT HAY CSV ; DNC SẼ IMPORT SAU /////////////
			/////////////////////////////////////////////////////////////////////////
			//save the database connection to a local variable

			
			
					
			$raw = str_replace("\\", "", $json);
			//$rrr = '{ "customer": "John Doe", "items": {"product": "Beer","qty": 6}}';
			//$sql = 'INSERT INTO v_dncjson (info) VALUES(\'' .$raw.'\')';
			$sql = "INSERT INTO v_dncjson (info) VALUES('$raw')";
			
			require_once "resources/classes/database.php";
			$database = new database;
			$database->connect();
			$db = $database->db;
			$prep_statement = $db->prepare(check_sql($sql));
			$prep_statement->execute();
			unset($prep_statement,$sql);
			
			//$this->response(200, $rrr);

			// Ket qua tra ve theo yeu cau cua VNCERT
			$error = array('error_code'=>'0','error_desc'=>'Good');
			$this->response(200, $error);
						
		}
		elseif ($this->method == 'PUT'){
			// Hãy viết code xử lý CẬP NHẬT dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
		elseif ($this->method == 'DELETE'){
			// Hãy viết code xử lý XÓA dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
	}
}


$user_api = new api();

/*
// https://pbxtest-blue.a2es.uk/app/api/contacts{121548741}/address
// string(27) "contacts{121548741}/address" 
//https://cloudpbx.ccas.vn/app/api/contacts{1234567}/api-key{1456765}

	if(isset($_REQUEST["rewrite_uri"])){
		$rewrite_uri = rtrim($_REQUEST["rewrite_uri"], '/');
		echo 'URL nhan duoc ' .$rewrite_uri;

		// vd: https://cloudpbx.ccas.vn/app/api/webhook
		// gia tri $rewrite_uri = webhook
		
		//vd : https://cloudpbx.ccas.vn/app/api/contacts{1234567}/api-key{1456765}
		// gia tri $rewrite_uri = contacts{1234567}/api-key{1456765}
		
	} else {
		send_access_denied();
	}

	$request_method = $_SERVER["REQUEST_METHOD"];
	$segments = explode('/', $rewrite_uri);

	foreach ($segments as $segment)
	{
		// vd : https://cloudpbx.ccas.vn/app/api/contacts{1234567}/api-key{1456765}
		// gia tri $rewrite_uri = contacts{1234567}/api-key{1456765}
		// Segment: contacts{121548741}
		// Segment: api-key{101010101}
    	echo "<br />Segment: " . $segment;
	}
	
	$endpoints = array();
	foreach($segments as $segment) {
		$ids = array();
		preg_match('/(.*){(.*)}/' , $segment , $ids);
		if(count($ids) == 3) {
			$endpoints[$ids[1]] = $ids[2];
		} else {
			$endpoints[$segment] = "";
		}
	}

*/	
	/* kiem ra xem cai api-key không , nếu không có thì báo lỗi
	if (!array_key_exists('api-key', $endpoints)) {
		send_access_denied();
	}
	*/
/*

// set request key value ready for call to check_auth
	$_REQUEST['key'] = $endpoints['api-key'];
	require_once "resources/check_auth.php";

	switch($request_method) {
		case "POST":
		
			break;
		case "GET":
		
			break;
		case "PUT":
		
			break;
		case "DELETE":
		
			break;
		default:
			send_access_denied();
}
*/


/*
// remove record Ids but keep placeholders
	$rewrite_uri = preg_replace('/{[^\/]*}/', '{}', $rewrite_uri);
// remove any refernce to the api key from uri that we will compare against the DB
	$rewrite_uri = preg_replace(array('/\/api-key{?}?/', '/^api-key{?}?\//'), '', $rewrite_uri);

	$sql = "select * from v_restapi where api_method = :api_method and api_uri = :api_uri and api_enabled = 'true' and (domain_uuid = :domain_uuid or domain_uuid is null) order by domain_uuid asc";

	$parameters['domain_uuid'] = $_SESSION['domain_uuid'];
	$parameters['api_method'] = $request_method;
	$parameters['api_uri'] = $rewrite_uri;

	$database = new database;

	$rows = $database->select($sql, $parameters, 'all');
	if (is_array($rows) && @sizeof($rows) != 0) {
		$api_sql = $rows[0]['api_sql'];
	} else {
		send_api_message(404, "API not found.");
	}

	unset ($parameters, $sql);

	if ($request_method == 'GET') {
		if (strpos($api_sql, ':domain_uuid') > 0){
			$parameters['domain_uuid'] = $_SESSION['domain_uuid'];
		}
		foreach($endpoints as $key => $value){
			if ($key == 'api-key') continue;
			if (strlen($value) > 0) {
				$parameters[$key] = $value;
			}
		}

		//var_dump($parameters);
		//echo "<br>\n";
		//exit;

		$rows = $database->select($api_sql, $parameters, 'all');
		if (is_array($rows) && @sizeof($rows) != 0) {
			send_data($rows);
		} else {
			send_api_message(200, "Empty result set.");
		}
	exit;
	}

	if ($request_method == 'POST') {
		$data = json_decode(file_get_contents("php://input"), TRUE);
		if (!permission_exists('restapi_domain_in_data')) {
			if (strpos($api_sql, ':domain_uuid') > 0){
				$data['domain_uuid'] = $_SESSION['domain_uuid'];
			}
		}
		if (!permission_exists('restapi_new_uuid_in_data')) {
			$data['new_uuid'] = uuid();
		}

		foreach($endpoints as $key => $value){
			if ($key == 'api-key') continue;
			if (strlen($value) > 0) {
				$data[$key] = $value;
			}
		}

		//var_dump($data);
		//echo "<br>\n".$api_sql."<br>\n";
		//exit;

		$database->execute($api_sql, $data, 'all');
		send_api_message($database->message['code'], $database->message['message']);
		//echo $database->message['error']['message']."\n";
		exit;
	}

	if ($request_method == 'PUT') {
		$data = json_decode(file_get_contents("php://input"), TRUE);
		if (!permission_exists('restapi_domain_in_data')) {
			if (strpos($api_sql, ':domain_uuid') > 0){
				$data['domain_uuid'] = $_SESSION['domain_uuid'];
			}
		}

		foreach($endpoints as $key => $value){
			if ($key == 'api-key') continue;
			if (strlen($value) > 0) {
				$data[$key] = $value;
			}
		}

		//var_dump($data);
		//echo "<br>\n".$api_sql."<br>\n";
		//exit;

		$database->execute($api_sql, $data, 'all');
		send_api_message($database->message['code'], $database->message['message']);
		//echo $database->message['error']['message']."\n";
		exit;
	}

	if ($request_method == 'DELETE') {

		if (strpos($api_sql, ':domain_uuid') > 0){
			$parameters['domain_uuid'] = $_SESSION['domain_uuid'];
		}
		foreach($endpoints as $key => $value){
			if ($key == 'api-key') continue;
			if (strlen($value) > 0) {
				$parameters[$key] = $value;
			}
		}

		//var_dump($data);
		//echo "<br>\n".$api_sql."<br>\n";
		//exit;

		$database->execute($api_sql, $parameters, 'all');
		send_api_message($database->message['code'], $database->message['message']);
		//echo $database->message['error']['message']."\n";
		exit;
	}
*/
exit;
?>

