<?php
// ============================================================================
// Module      : imenu.php
// Version     : 3.0R0.0
// PHP version : PHP 7+
//
// Author      : Denis Patrice <denispatrice@yahoo.com>
// Copyright   : Copyright (c) Denis Patrice Dipl.-Ing. 2010-2025
//               All rights reserved
//
// Application : imenu
// Description : imenu engine
//
// Date+Time of change   By     Description
// --------------------- ------ ----------------------------------------------
// 20-Jan-25 00:00 WIT   Denis  Deployment V. 2025 "Raymond Chandler"
//
// ============================================================================

function imenu_get_node($service_id, $node_id="")
{
	$result = NULL;

	$db = new TDatabase("radiahub");
	if ($db->connect()) {

		$service = $db->locate("imenu_services", array("service_id"=>$service_id));
		if ($service !== NULL) {
			$json = json_decode($service["description"],TRUE);

			if (strlen($node_id) > 0) {
				for($i = 0; $i < count($json["nodes"]); $i++) {
					if ($json["nodes"][$i]["node_id"] === $node_id) {
						$result = $json["nodes"][$i];
						break;
					}
				}
			}
			else {
				$result = $json["nodes"][0];
			}

		}

		$db->disconnect();
	}

	return $result;
}

function imenu_get_init_node($service_id)
{
	return imenu_get_node($service_id, "");
}

function imenu_current_node_id($service_id, $user_id)
{
	$result = "";
	if (array_significant(array($service_id, $user_id), TRUE)) {
		$db = new TDatabase("radiahub");
		if ($db->connect()) {
			$session = $db->locate("imenu_sessions", array("service_id"=>$service_id, "user_id"=>$user_id));
			if ($session !== NULL) {
					$result = $session["node_id"];
			}
			$db->disconnect();
		}
	}
	return $result;
}

function imenu_session_variable($service_id, $user_id, $variable)
{
	$result = "";
	if (array_significant(array($service_id, $user_id, $variable), TRUE)) {
		$db = new TDatabase("radiahub");
		if ($db->connect()) {
			$rec = $db->locate("imenu_session_variables", array("service_id"=>$service_id, "user_id"=>$user_id, "variable" => $variable));
			if ($rec !== NULL) {
				$result = $rec["value"];
			}
		$db->disconnect();
		}
	}
	return $result;
}

function imenu_store_session($service_id, $user_id, $node_id)
{
	$errno = (array_significant(array($service_id, $user_id), TRUE)) ? 1000 : 1004;

	if ($errno === 1000) {
		$db = new TDatabase("radiahub");
		if ($db->connect()) {
			$loc = array("service_id"=>$service_id, "user_id"=>$user_id);
			$row = $db->locate("imenu_sessions", $loc);
			if ($row !== NULL) {
				$update = array("node_id"=>$node_id);
				if ($db->update("imenu_sessions", $update, $loc) === FALSE) {
					$errno = 1011;
				}          
			}
			else {
				$insert = array(
				"service_id"=> $service_id,
				"user_id"   => $user_id,
				"node_id"   => $node_id
				);
				if ($db->insert("imenu_sessions", $insert) === FALSE) {
					$errno = 1011;
				}          
			}
			$db->disconnect();
		}
		else {
			$errno = 1015;
		}
	}

	return $errno;    
}

function imenu_delete_session($service_id, $user_id)
{
	$errno = (array_significant(array($service_id, $user_id), TRUE)) ? 1000 : 1004;
	if ($errno === 1000) {
		$db = new TDatabase("radiahub");
		if ($db->connect()) {
			$service = $db->locate("imenu_services", array("service_id"=>$service_id));
			if ($service !== NULL) {
				$loc = array("service_id"=>$service_id, "user_id"=>$user_id);
				$row = $db->locate("imenu_sessions", $loc);
				if ($row !== NULL) {
					$db->delete("imenu_sessions", $loc);
				}
			}
			else {
				$errno = 1009;
			}
			$db->disconnect();
		}
		else {
			$errno = 1015;
		}
	}
	return $errno;  
}

function imenu_initialize_service($service_id, $user_id)
{
	$errno = (array_significant(array($service_id, $user_id), TRUE)) ? 1000 : 1004;
	if ($errno === 1000) {
		$db = new TDatabase("radiahub");
		if ($db->connect()) {
			$service = $db->locate("imenu_services", array("service_id"=>$service_id));
			if ($service !== NULL) {
				imenu_delete_session($service_id, $user_id);
				$node  = imenu_get_init_node($service_id);
				$errno = imenu_goto_node_id ($service_id, $user_id, $node["node_id"]);
			}
			else {
				$errno = 1009;
			}
			$db->disconnect();
		}
		else {
			$errno = 1015;
		}
	}
	return $errno;  
}
    
function imenu_on_user_input($service_id, $user_id, $input_value)
{
	$errno = 1000;

	$db = new TDatabase("radiahub");
	if ($db->connect()) {

		$node = imenu_get_node(imenu_current_node_id($service_id, $user_id));
		if ($node !== NULL) {

			$okcont = TRUE;

			if (isset($node["menu"])) {
				for ($i = 0; $i < count($node["menu"]); $i++) {
					if ($node["menu"][$i]["order"] === $input_value) {
						$node_id = $node["menu"][$i]["target_node_id"];
						if (strlen($node_id) > 0) {
							$okcont = FALSE;
							$errno  = imenu_goto_node_id($service_id, $user_id, $node_id);
						}
						break;
					}
				}
			}        

			if ($okcont and (isset($node["input"]))) {
				$variable = $node["input"]["variable"];
				$loc = array (
					"service_id" => $service_id,
					"user_id"    => $user_id,
					"node_id"    => $node_id,
					"variable"   => $variable
				);
				$rec = $db->locate("imenu_session_variables", $loc);
				if ($rec !== NULL) {
					$update = array ("value" => $input_value);
					if ($db->update("imenu_session_variables", $update, $loc) === FALSE) {
						$errno = 1011;
					}
				}
				else {
					$insert = array (
						"service_id" => $service_id,
						"user_id"    => $user_id,
						"node_id"    => $node_id,
						"variable"   => $variable,
						"value"      => $input_value
					);
					if ($db->insert("imenu_session_variables", $insert) === FALSE) {
						$errno = 1011;
					}
				}
				if ($errno === 1000) {
					$node_id = $node["input"]["target_node_id"];
					if (strlen($node_id) > 0) {
						$errno = imenu_goto_node_id($service_id, $user_id, $node_id);
					}
				}
			}
		}
		else {
			$node = imenu_get_init_node($service_id);
			$node_id = $node["node_id"];
			$errno = imenu_goto_node_id($service_id, $user_id, $node_id);
		}

		$db->disconnect();
	}
	else {
	$errno = 1015;
	}    

	return $errno;
}

function imenu_goto_node_id($service_id, $user_id, $node_id)
{
	$errno = (array_significant(array($service_id, $user_id), TRUE)) ? 1000 : 1004;
	if ($errno === 1000) {
		$db = new TDatabase("radiahub");
		if ($db->connect()) {

			$node = imenu_get_node($service_id, $node_id);
			if ($node !== NULL) {
				if (isset($node["input"])) {
					$variable = $node["input"]["variable"];
					$value = imenu_session_variable($service_id, $user_id, $variable);
					$node["input"]["value"] = $value;
				};
				$dataType = "IMENU_FORM";
				$data = array("service_id" => $service_id, "node" => $node);

				$res = fcm_push("", $user_id, $datatype, $data);

				$errno = $res["errno"];
				if ($errno === 1000) {
					$errno = $imenu_store_session($service_id, $user_id, $node_id);
				}
			}
			else {
				$errno = 1009;
			}

			$db->disconnect();
		}
		else {
			$errno = 1015;
		}
	}

	return $errno;    
}
    
function imenu($service_id, $user_id, $value="")
{
	$errno = (array_significant(array($service_id, $user_id), TRUE)) ? 1000 : 1004;
	if ($errno === 1000) {
		if (strlen($value) === 0) {
			$errno = imenu_initialize_service($service_id, $user_id);
		}
		else {
			$errno = imenu_on_user_input($service_id, $user_id, $value);
		}
	}
	return $errno;
}


// End of file: imenu.php
// ============================================================================
?>