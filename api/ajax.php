<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once $_SERVER["DOCUMENT_ROOT"].'/ashish_rest/init.php';
 //echo $_REQUEST["username"];exit;
$data = array();
$success = "N";
$narr = "Something went wrong !";

$post_data = json_decode(file_get_contents('php://input'), true);
$action = !empty($_REQUEST["action"]) ? $_REQUEST["action"] : "";

$errors = [];
if ($action == "login") {
	
	if(empty($post_data["username"]) || empty($post_data["password"]))
	{
		$errors[]= "`username` and `password` are required.";
		$success = "N";
	    $narr = "Validation errors.";		
	}
	if(empty($errors))
	{
		// get posted params
		$username = $post_data["username"];
		$password = $post_data["password"];

		// check user exist in db or not
		$login_sql = "select * from ".TBL_USER." where user_name='".$username."' and user_pwd = '".$password."'";
	    $login_result = $db->get_row($login_sql);
	    /*print_R($login_result);exit; */  
	 
	    if(empty($login_result))
	    {
	    	$errors[]= "Invalid `username` or `password`.";
		    $success = "N";
		    $narr = "Authentication failed.";
	    }else{	    	
	    	$success = "Y";
	    	$narr = "Authentication success !";
	    	$_SESSION['logged_in_user']= $login_result;
	    }
	}
    
}
else if($action == "get_user_works"){
	if(!empty($_SESSION['logged_in_user']))
	{
		$user_works_sql = "select * from ".TBL_WORK." where user_id= ".$_SESSION['logged_in_user']["user_id"];
		$data = $user_works_result = $db->get_results($user_works_sql);
			    	
	    $success = "Y";
	    $narr = "User works retrieved successfully.";
	}

}
else if($action == "logout"){
	//if(isset($_SESSION['logged_in_user'])){
		 unset($_SESSION['logged_in_user']);
		 header ("Location: ".SITE_URL."/login.php");
		 exit;
	//}
	
}
	$response = array();
	$response["success"] = $success;
	$response["narr"] = $narr;
	if(!empty($data))
	$response["data"] = $data;
	if(!empty($errors))
	$response["errors"] = $errors;
	if($success == "Y")
		http_response_code(200);
	else
		http_response_code(401);
	echo json_encode($response);
	exit;
?>

