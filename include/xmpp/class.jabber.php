<?php

/***************************************************************************

	Class.Jabber.PHP v0.1.3.1
	(c) 2002 Carlo "Gossip" Zottmann
	http://phpjabber.g-blog.net *** gossip@jabber.g-blog.net

	The FULL documentation and examples for this software can be found at
	http://phpjabber.g-blog.net (not many doc comments in here, sorry)
	
	last modified: 2002-10-09 21:57:20

	NOTE:
	If you want to write addons or extensions, please follow the coding style
	recommendations @ http://www.phpbuilder.net/columns/tim20010101.php3

 ***************************************************************************/

/***************************************************************************
 *
 * The Notice below must appear in each file of the Source Code of any copy
 * you distribute of the Licensed Product or any Modifications thereto.
 * Contributors to any Modifications may add their own copyright notices to
 * identify their own contributions.
 * 
 * License
 * 
 * The contents of this file are subject to the Jabber Open Source License
 * Version 1.0 (the "License").  You may not copy or use this file, in either
 * source code or executable form, except in compliance with the License.  You
 * may obtain a copy of the License at http://www.jabber.com/license/ or at
 * http://www.opensource.org/.  
 * 
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 * 
 * Copyrights
 * 
 * Portions created by or assigned to Jabber.com, Inc. are 
 * Copyright (c) 2000 Jabber.com, Inc.  All Rights Reserved.  Contact
 * information for Jabber.com, Inc. is available at http://www.jabber.com/.
 * 
 * Portions Copyright (c) 2002-present Carlo Zottmann,
 * http://phpjabber.g-blog.net
 *  
 * Other portions copyright their respective owners.
 * 
 * Acknowledgements
 * 
 * Special thanks to the Jabber Open Source Contributors for their
 * suggestions and support of Jabber.
 * 
 ***************************************************************************/

/*
	Jabber::Connect() 
	Jabber::Disconnect() 
	Jabber::SendAuth() 
	Jabber::AccountRegistration($reg_email {string}, $reg_name {string})
 
	Jabber::Listen() 
	Jabber::SendPacket($xml {string})
 
	Jabber::RosterUpdate() 
	Jabber::RosterAddUser($jid {string}, $id {string}, $name {string}) 
	Jabber::RosterRemoveUser($jid {string}, $id {string})
 
	Jabber::Subscribe($jid {string}) 
	Jabber::Unsubscribe($jid {string})
 
	Jabber::CallHandler($message {array}) 
	Jabber::CruiseControl([$seconds {number}])
 
	Jabber::SubscriptionApproveRequest($to {string}) 
	Jabber::SubscriptionDenyRequest($to {string})
 
	Jabber::GetFirstFromQueue() 
	Jabber::GetFromQueueById($packet_type {string}, $id {string})
 
	Jabber::SendMessage($to {string}, $id {number}, $type {string}, $content {array}[, $payload {array}])
 	Jabber::SendIq($to {string}, $type {string}, $id {string}, $xmlns {string}[, $payload {string}])
	Jabber::SendPresence($type {string}[, $to {string}[, $status {string}[, $show {string}[, $priority {number}]]]])
 
	Jabber::SendError($to {string}, $id {string}, $error_number {number}[, $error_message {string}]) 

	Jabber::GetInfoFromMessageFrom($message {array}) 
	Jabber::GetInfoFromMessageType($message {array}) 
	Jabber::GetInfoFromMessageId($message {array}) 
	Jabber::GetInfoFromMessageThread($message {array}) 
	Jabber::GetInfoFromMessageSubject($message {array}) 
	Jabber::GetInfoFromMessageBody($message {array}) 
	Jabber::GetInfoFromMessageError($message {array})
 
	Jabber::GetInfoFromIqFrom($message {array}) 
	Jabber::GetInfoFromIqType($message {array}) 
	Jabber::GetInfoFromIqId($message {array}) 
	Jabber::GetInfoFromIqKey($message {array})
 
	Jabber::GetInfoFromPresenceFrom($message {array}) 
	Jabber::GetInfoFromPresenceType($message {array}) 
	Jabber::GetInfoFromPresenceStatus($message {array}) 
	Jabber::GetInfoFromPresenceShow($message {array}) 
	Jabber::GetInfoFromPresencePriority($message {array})


	MakeXML::AddPacketDetails($string {string}[, $value {string/number}])
	MakeXML::BuildPacket([$array {array}])
*/



class Jabber
{
	var $server;
	var $port;
	var $username;
	var $password;
	var $resource;
	var $jid;

	var $connection;

	var $stream_id;
	var $roster;

	var $enable_logging;
	var $logfile;

	var $iq_sleep_timer;

	var $packet_queue;
	var $subscription_queue;
	
	var $iq_version_name;
	var $iq_version_os;
	var $iq_version_version;

	var $error_codes;
	
	var $CONNECTOR;



	function Jabber()
	{
		$this->server				= "localhost";
		$this->port					= "5222";

		$this->username				= "larry";
		$this->password				= "curly";
		$this->resource				= NULL;
		
		$this->enable_logging		= FALSE;
		$this->logfile				= array();

		$this->packet_queue			= array();
		$this->subscription_queue	= array();

		$this->iq_sleep_timer		= 1;

		$this->iq_version_name		= "Class.Jabber.PHP by Carlo 'Gossip' Zottmann, gossip@jabber.g-blog.net";
		$this->iq_version_version	= "0.1.3";
		$this->iq_version_os		= $_SERVER["SERVER_SOFTWARE"];

		$this->connection_class		= "CJP_StandardConnector";

		$this->error_codes			= array(400 => "Bad Request",
											401 => "Unauthorized",
											402 => "Payment Required",
											403 => "Forbidden",
											404 => "Not Found",
											405 => "Not Allowed",
											406 => "Not Acceptable",
											407 => "Registration Required",
											408 => "Request Timeout",
											409 => "Conflict",
											500 => "Internal Server Error",
											501 => "Not Implemented",
											502 => "Remove Server Error",
											503 => "Service Unavailable",
											504 => "Remove Server Timeout",
											510 => "Disconnected");
	}



	function Connect()
	{
		$this->CONNECTOR = new $this->connection_class;
		$this->connection = $this->CONNECTOR->OpenSocket($this->server, $this->port);

		if ($this->connection) {

	        socket_set_blocking($this->connection, 0);
			socket_set_timeout($this->connection, 31536000);

			$this->SendPacket("<?xml version='1.0' encoding='UTF-8' ?>\n");
			$this->SendPacket("<stream:stream to='" . $this->server . "' xmlns='jabber:client' xmlns:stream='http://etherx.jabber.org/streams'>\n");

			sleep(2);

			if ($this->_check_connected()) {

				return TRUE;

			} else {

				if ($this->enable_logging) {
					$this->logfile[] = "<b>Error:</b> Connect() #1";
				}

				return FALSE;
			}

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> Connect() #2";
			}

			return FALSE;
		}
	}



	function Disconnect()
	{
		$this->SendPacket("</stream:stream>");
		$this->CONNECTOR->CloseSocket($this->connection);
		
		if ($this->enable_logging) {
			echo "<h2>logging enabled, logged events below:</h2>\n";
			echo (count($this->logfile) > 0) ? implode("<br /><br />\n", $this->logfile) : "No logged events.";
		}
	}



	function SendAuth()
	{
		// Currently, we only support plaintext authentication. This ain't
		// perfect, but it works. I'll add <digest/> support later on...

		if ($this->resource) {
			$this->jid = $this->username . "@" . $this->server . "/" . $this->resource;
		} else {
			$this->jid = $this->username . "@" . $this->server;
		}

		$auth_id = "auth_" . time();

		$payload = "<username>" . $this->username . "</username>
					<password>" . $this->password . "</password>
					<resource>" . $this->resource . "</resource>";

		$packet = $this->SendIq(NULL, "set", $auth_id, "jabber:iq:auth", $payload);

		if ($this->GetInfoFromIqType($packet) == "result" && $this->GetInfoFromIqId($packet) == $auth_id) {

			return TRUE;

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> SendAuth() #1";
			}

			return FALSE;
		}
	}



	function AccountRegistration($reg_email = NULL, $reg_name = NULL)
	{
		$packet = $this->SendIq($this->server, "get", "reg_01", "jabber:iq:register");
		
		if ($packet) {
	
			$key = $this->GetInfoFromIqKey($packet);	// just in case a key was passed back from the server
			unset($packet);
		
			$payload = "<username>" . $this->username . "</username>
						<password>" . $this->password . "</password>
						<email>$reg_email</email>
						<name>$reg_name</name>\n";
			$payload .= ($key) ? "<key>$key</key>\n" : "";
		
			$packet = $this->SendIq($this->server, "set", "reg_01", "jabber:iq:register", $payload);
		
			if ($this->GetInfoFromIqType($packet) == "result") {
			
				if (isset($packet["iq"]["#"]["query"][0]["#"]["registered"][0]["#"])) {
					$return_code = 1;
				} else {
					$return_code = 2;
				}
		
				if ($this->resource) {
					$this->jid = $this->username . "@" . $this->server . "/" . $this->resource;
				} else {
					$this->jid = $this->username . "@" . $this->server;
				}

			} elseif ($this->GetInfoFromIqType($packet) == "error") {
		
				if (isset($packet["iq"]["#"]["error"][0]["#"])) {
					$return_code = "Error " . $packet["iq"]["#"]["error"][0]["@"]["code"] . ": " . $packet["iq"]["#"]["error"][0]["#"];
				}
			}
	
			return $return_code;
	
		} else {
		
			return 3;
	
		}
	}



	function SendPacket($xml)
	{
		$xml = trim($xml);

		if ($this->CONNECTOR->WriteToSocket($this->connection, $xml)) {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>SEND:</b> " . nl2br(htmlspecialchars($xml));
			}

			return TRUE;

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> SendPacket() #1";
			}

			return FALSE;
		}
	}



	function Listen()
	{
		unset($incoming);

		while ($line = $this->CONNECTOR->ReadFromSocket($this->connection, 4096)) {
			$incoming .= $line;
		}
		
		$incoming = trim($incoming);

		if ($this->enable_logging && $incoming != "") {
			$this->logfile[] = "<b>RECV:</b> " . nl2br(htmlspecialchars($incoming));
		}

		if ($incoming != "") {
			$temp = $this->_split_incoming($incoming);

			for ($a = 0; $a < count($temp); $a++) {
				$this->packet_queue[] = $this->xmlize($temp[$a]);
			}
		}

		return TRUE;
	}



	function StripJID($jid = NULL)
	{
		preg_match("/(.*)\/(.*)/Ui", $jid, $temp);
		return ($temp[1] != "") ? $temp[1] : $jid;
	}



	function SendMessage($to, $type = "normal", $id = NULL, $content = NULL, $payload = NULL)
	{
		if ($to && is_array($content)) {
		
			if (!$id) { $id = $type . "_" . time(); }

			$content = $this->_array_htmlspecialchars($content);

			$xml = "<message to='$to' type='$type' id='$id'>\n";

			if ($content["thread"]) {
				$xml .= "<thread>" . $content["thread"] . "</thread>\n";
			}
			
			$xml .= "<body>" . $content["body"] . "</body>\n";
			$xml .= $payload;
			$xml .= "</message>\n";


			if ($this->SendPacket($xml)) {

				return TRUE;

			} else {

				if ($this->enable_logging) {
					$this->logfile[] = "<b>Error:</b> SendMessage() #1";
				}

				return FALSE;
			}

		} else {
		
			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> SendMessage() #2";
			}

			return FALSE;
		}
	}



	function SendPresence($type = NULL, $to = NULL, $status = NULL, $show = NULL, $priority = NULL)
	{
		$xml = "<presence";
		$xml .= ($to) ? " to='$to'" : "";
		$xml .= ($type) ? " type='$type'" : "";
		$xml .= ($status || $show || $priority) ? ">\n" : " />\n";

		$xml .= ($status) ? "	<status>$status</status>\n" : "";
		$xml .= ($show) ? "	<show>$show</show>\n" : "";
		$xml .= ($priority) ? "	<priority>$priority</priority>\n" : "";

		$xml .= ($status || $show || $priority) ? "</presence>\n" : "";

		if ($this->SendPacket($xml)) {

			return TRUE;

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> SendPresence() #1";
			}

			return FALSE;
		}
	}



	function SendError($to, $id = NULL, $error_number, $error_message = NULL)
	{
		$xml = "<iq type='error' to='$to'";
		$xml .= ($id) ? " id='$id'" : "";
		$xml .= ">\n";
		$xml .= "	<error code='$error_number'>";
		$xml .= ($error_message) ? $error_message : $this->error_codes[$error_number];
		$xml .= "</error>\n";
		$xml .= "</iq>";

		$this->SendPacket($xml);
	}



	function RosterUpdate()
	{
		$roster_request_id = "roster_" . time();

		$incoming_array = $this->SendIq(NULL, "get", $roster_request_id, "jabber:iq:roster");

		if (is_array($incoming_array)) {
			
			if ($incoming_array["iq"]["@"]["type"] == "result"
				&& $incoming_array["iq"]["@"]["id"] == $roster_request_id
				&& $incoming_array["iq"]["#"]["query"]["0"]["@"]["xmlns"] == "jabber:iq:roster")
			{

				$number_of_contacts = count($incoming_array["iq"]["#"]["query"][0]["#"]["item"]);
				$this->roster = array();

				for ($a = 0; $a < $number_of_contacts; $a++) {

					$this->roster[$a] = array(	"jid"			=> $incoming_array["iq"]["#"]["query"][0]["#"]["item"][$a]["@"]["jid"],
												"name"			=> $incoming_array["iq"]["#"]["query"][0]["#"]["item"][$a]["@"]["name"],
												"subscription"	=> $incoming_array["iq"]["#"]["query"][0]["#"]["item"][$a]["@"]["subscription"],
												"group"			=> $incoming_array["iq"]["#"]["query"][0]["#"]["item"][$a]["#"]["group"][0]["#"]
											);
				}

				return TRUE;

			} else {

				if ($this->enable_logging) {
					$this->logfile[] = "<b>Error:</b> RosterUpdate() #1";
				}

				return FALSE;
			}

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> RosterUpdate() #2";
			}

			return FALSE;
		}
	}



	function RosterAddUser($jid = NULL, $id = NULL, $name = NULL)
	{
		$id = ($id) ? $id : "adduser_" . time();

		if ($jid) {
	
			$payload = "		<item jid='$jid'";
			$payload .= ($name) ? " name='" . htmlspecialchars($name) . "'" : "";
			$payload .= "/>\n";

			$packet = $this->SendIq(NULL, "set", $id, "jabber:iq:roster", $payload);

			if ($this->GetInfoFromIqType($packet) == "result") {

				$this->RosterUpdate();
				return TRUE;
			
			} else {

				if ($this->enable_logging) {
					$this->logfile[] = "<b>Error:</b> RosterAddUser() #2";
				}

				return FALSE;
			}

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> RosterAddUser() #1";
			}

			return FALSE;
		}
	}



	function RosterRemoveUser($jid = NULL, $id = NULL)
	{
		if ($jid && $id) {

			$packet = $this->SendIq(NULL, "set", $id, "jabber:iq:roster", "<item jid='$jid' subscription='remove'/>");

			if ($this->GetInfoFromIqType($packet) == "result") {

				$this->RosterUpdate();
				return TRUE;
			
			} else {

				if ($this->enable_logging) {
					$this->logfile[] = "<b>Error:</b> RosterRemoveUser() #2";
				}

				return FALSE;
			}

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> RosterRemoveUser() #1";
			}

			return FALSE;
		}
	}



	function GetFirstFromQueue()
	{
		reset($this->packet_queue);
		list($key, $value) = each($this->packet_queue);
		unset($this->packet_queue[$key]);

		return (is_array($value)) ? $value : FALSE;
	}



	function GetFromQueueById($packet_type, $id)
	{
		$found_message = FALSE;

		foreach ($this->packet_queue as $key => $value) {

			if ($value["$packet_type"]["@"]["id"] == $id) {

				$found_message = $value;
				unset($this->packet_queue[$key]);

				break;
			}
		}

		return (is_array($found_message)) ? $found_message : FALSE;
	}



	function CallHandler($packet = NULL)
	{
		$packet_type	= $this->_get_packet_type($packet);

		if ($packet_type == "message") {
		
			$type		= $packet["message"]["@"]["type"];
			$type		= ($type != "") ? $type : "normal";
			$funcmeth	= "Handler_message_$type";

		} elseif ($packet_type == "iq") {
		
			$this->TraverseXMLize($packet);

			$namespace	= $packet["iq"]["#"]["query"][0]["@"]["xmlns"];
			$namespace	= str_replace(":", "_", $namespace);
			$funcmeth	= "Handler_iq_$namespace";

		} elseif ($packet_type == "presence") {

			$type		= $packet["presence"]["@"]["type"];
			$type		= ($type != "") ? $type : "available";
			$funcmeth	= "Handler_presence_$type";

		}


		if ($funcmeth != "") {

			if (function_exists($funcmeth)) {

				call_user_func($funcmeth, $packet);

			} elseif(method_exists($this, $funcmeth)) {

				call_user_func(array(&$this, $funcmeth), $packet);

			} elseif ($this->enable_logging) {

				$this->Handler_NOT_IMPLEMENTED($packet);
				$this->logfile[] = "<b>Error:</b> CallHandler() #1 - neither method nor function $funcmeth() available";

			}
		}
	}



	function CruiseControl($seconds = -1)
	{
		$count = 0;

		while ($count != $seconds) {
			$this->Listen();

			do {
				$packet = $this->GetFirstFromQueue();
				$this->CallHandler($packet);
			} while (count($this->packet_queue) > 1);

			$count++;
			sleep(1);
		}
		
		return TRUE;
	}



	function SubscriptionAcceptRequest($to = NULL)
	{
		return ($to) ? $this->SendPresence("subscribed", $to) : FALSE;
	}



	function SubscriptionDenyRequest($to = NULL)
	{
		return ($to) ? /* still needs to be done */ TRUE : FALSE;
	}



	function Subscribe($to = NULL)
	{
		return ($to) ? $this->SendPresence("subscribe", $to) : FALSE;
	}



	function Unsubscribe($to = NULL)
	{
		return ($to) ? $this->SendPresence("unsubscribe", $to) : FALSE;
	}



	function SendIq($to = NULL, $type = "get", $id = NULL, $xmlns = NULL, $payload = NULL)
	{
		if (!preg_match("/^(get|set|result|error)$/", $type)) {
			unset($type);

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> SendIq() #2 - type must be 'get', 'set', 'result' or 'error'";
			}
			
			return FALSE;

		} elseif ($id && $xmlns) {

			$xml = "<iq type='$type' id='$id'";
			$xml .= ($to) ? " to='$to'" : "";
			$xml .= ">
						<query xmlns='$xmlns'>
							$payload
						</query>
					</iq>";

			$this->SendPacket($xml);
			sleep($this->iq_sleep_timer);
			$this->Listen();
			
			return (preg_match("/^(get|set)$/", $type)) ? $this->GetFromQueueById("iq", $id) : TRUE;

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> SendIq() #1 - to, id and xmlns are mandatory";
			}

			return FALSE;
		}
	}



	// ======================================================================
	// internal methods
	// ======================================================================



	function _listen_incoming()
	{
		unset($incoming);

		while ($line = $this->CONNECTOR->ReadFromSocket($this->connection, 4096)) {
			$incoming .= $line;
		}
		
		$incoming = trim($incoming);

		if ($this->enable_logging && $incoming != "") {
			$this->logfile[] = "<b>RECV:</b> " . nl2br(htmlspecialchars($incoming));
		}

		return $this->xmlize($incoming);
	}



	function _check_connected()
	{
		$incoming_array = $this->_listen_incoming();
		
		if (is_array($incoming_array)) {
			
			if ($incoming_array["stream:stream"]["@"]["from"] == $this->server
				&& $incoming_array["stream:stream"]["@"]["xmlns"] == "jabber:client"
				&& $incoming_array["stream:stream"]["@"]["xmlns:stream"] == "http://etherx.jabber.org/streams")
			{

				$this->stream_id = $incoming_array["stream:stream"]["@"]["id"];

				return TRUE;

			} else {

				if ($this->enable_logging) {
					$this->logfile[] = "<b>Error:</b> _check_connected() #1";
				}

				return FALSE;
			}

		} else {

			if ($this->enable_logging) {
				$this->logfile[] = "<b>Error:</b> _check_connected() #2";
			}

			return FALSE;
		}
	}



	function _get_packet_type($packet = NULL)
	{
		if (is_array($packet)) {
			reset($packet);
			$packet_type = key($packet);
		}

		return ($packet_type) ? $packet_type : FALSE;
	}



	function _split_incoming($incoming)
	{
		$temp = preg_split("/<(message|iq|presence|stream)/", $incoming, -1, PREG_SPLIT_DELIM_CAPTURE);
		$array = array();

		for ($a = 1; $a < count($temp); $a = $a + 2) {
			$array[] = "<" . $temp[$a] . $temp[($a + 1)];
		}

		return $array;
	}



	// _array_htmlspecialchars()
	// applies htmlspecialchars() to all values in an array

	function _array_htmlspecialchars($array)
	{
		if (is_array($array)) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					$v = $this->_array_htmlspecialchars($v);
				} else {
					$v = htmlspecialchars($v);
				}
			}
		}
		
		return $array;
	}



	// ======================================================================
	// <message/> parsers
	// ======================================================================



	function GetInfoFromMessageFrom($packet = NULL)
	{
		return (is_array($packet)) ? $packet["message"]["@"]["from"] : FALSE;
	}



	function GetInfoFromMessageType($packet = NULL)
	{
		return (is_array($packet)) ? $packet["message"]["@"]["type"] : FALSE;
	}



	function GetInfoFromMessageId($packet = NULL)
	{
		return (is_array($packet)) ? $packet["message"]["@"]["id"] : FALSE;
	}



	function GetInfoFromMessageThread($packet = NULL)
	{
		return (is_array($packet)) ? $packet["message"]["#"]["thread"][0]["#"] : FALSE;
	}



	function GetInfoFromMessageSubject($packet = NULL)
	{
		return (is_array($packet)) ? $packet["message"]["#"]["subject"][0]["#"] : FALSE;
	}



	function GetInfoFromMessageBody($packet = NULL)
	{
		return (is_array($packet)) ? $packet["message"]["#"]["body"][0]["#"] : FALSE;
	}



	function GetInfoFromMessageError($packet = NULL)
	{
		$error = preg_replace("/^\/$/", "", ($packet["message"]["#"]["error"][0]["@"]["code"] . "/" . $packet["message"]["#"]["error"][0]["#"]));
		return (is_array($packet)) ? $error : FALSE;
	}



	// ======================================================================
	// <iq/> parsers
	// ======================================================================



	function GetInfoFromIqFrom($packet = NULL)
	{
		return (is_array($packet)) ? $packet["iq"]["@"]["from"] : FALSE;
	}



	function GetInfoFromIqType($packet = NULL)
	{
		return (is_array($packet)) ? $packet["iq"]["@"]["type"] : FALSE;
	}



	function GetInfoFromIqId($packet = NULL)
	{
		return (is_array($packet)) ? $packet["iq"]["@"]["id"] : FALSE;
	}



	function GetInfoFromIqKey($packet = NULL)
	{
		return (is_array($packet)) ? $packet["iq"]["#"]["query"][0]["#"]["key"][0]["#"] : FALSE;
	}



	// ======================================================================
	// <presence/> parsers
	// ======================================================================



	function GetInfoFromPresenceFrom($packet = NULL)
	{
		return (is_array($packet)) ? $packet["presence"]["@"]["from"] : FALSE;
	}



	function GetInfoFromPresenceType($packet = NULL)
	{
		return (is_array($packet)) ? $packet["presence"]["@"]["type"] : FALSE;
	}



	function GetInfoFromPresenceStatus($packet = NULL)
	{
		return (is_array($packet)) ? $packet["presence"]["#"]["status"][0]["#"] : FALSE;
	}



	function GetInfoFromPresenceShow($packet = NULL)
	{
		return (is_array($packet)) ? $packet["presence"]["#"]["show"][0]["#"] : FALSE;
	}



	function GetInfoFromPresencePriority($packet = NULL)
	{
		return (is_array($packet)) ? $packet["presence"]["#"]["priority"][0]["#"] : FALSE;
	}



	// ======================================================================
	// <message/> handlers
	// ======================================================================



	function Handler_message_normal($packet)
	{
		$from = $packet["message"]["@"]["from"];
		$this->logfile[] = "<b>message</b> (type normal) from $from";
	}



	function Handler_message_chat($packet)
	{
		$from = $packet["message"]["@"]["from"];
		$this->logfile[] = "<b>message</b> (type chat) from $from";
	}



	function Handler_message_groupchat($packet)
	{
		$from = $packet["message"]["@"]["from"];
		$this->logfile[] = "<b>message</b> (type groupchat) from $from";
	}



	function Handler_message_headline($packet)
	{
		$from = $packet["message"]["@"]["from"];
		$this->logfile[] = "<b>message</b> (type headline) from $from";
	}



	function Handler_message_error($packet)
	{
		$from = $packet["message"]["@"]["from"];
		$this->logfile[] = "<b>message</b> (type error) from $from";
	}



	// ======================================================================
	// <iq/> handlers
	// ======================================================================



	// application version updates
    function Handler_iq_jabber_iq_autoupdate($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:autoupdate</b> from $from";
	}



	// interactive server component properties
    function Handler_iq_jabber_iq_agent($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:agent</b> from $from";
	}



	// method to query interactive server components
    function Handler_iq_jabber_iq_agents($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:agents</b> from $from";
	}



	// simple client authentication
	function Handler_iq_jabber_iq_auth($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:auth</b> from $from";
	}



	// out of band data
	function Handler_iq_jabber_iq_oob($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:oob</b> from $from";
	}



	// method to store private data on the server
	function Handler_iq_jabber_iq_private($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:private</b> from $from";
	}



	// method for interactive registration
	function Handler_iq_jabber_iq_register($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:register</b> from $from";
	}



	// client roster management
	function Handler_iq_jabber_iq_roster($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:roster</b> from $from";
	}



	// method for searching a user database
	function Handler_iq_jabber_iq_search($packet)
	{
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		
		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>jabber:iq:search</b> from $from";
	}



	// method for requesting the current time
	function Handler_iq_jabber_iq_time($packet)
	{
		$type	= $this->GetInfoFromIqType($packet);
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		$id		= ($id != "") ? $id : "time_" . time();

		if ($type == "get") {

			$payload = "<utc>" . gmdate("Ydm\TH:i:s") . "</utc>
						<tz>" . date("T") . "</tz>
						<display>" . date("Y/d/m h:i:s A") . "</display>";
	
			$this->SendIq($from, "result", $id, "jabber:iq:time", $payload);
		}

		$this->logfile[] = "<b>jabber:iq:time</b> (type $type) from $from";
	}



	// method for requesting version
	function Handler_iq_jabber_iq_version($packet)
	{
		$type	= $this->GetInfoFromIqType($packet);
		$from	= $this->GetInfoFromIqFrom($packet);
		$id		= $this->GetInfoFromIqId($packet);
		$id		= ($id != "") ? $id : "version_" . time();

		if ($type == "get") {

			$payload = "<name>" . $this->iq_version_name . "</name>
						<os>" . $this->iq_version_os . "</os>
						<version>" . $this->iq_version_version . "</version>";

			$this->SendIq($from, "result", $id, "jabber:iq:version", $payload);
		}

		$this->logfile[] = "<b>jabber:iq:version</b> (type $type) from $from";
	}



	// ======================================================================
	// <presence/> handlers
	// ======================================================================



    function Handler_presence_available($packet)
	{
		$from = $this->GetInfoFromPresenceFrom($packet);

		$show_status = $this->GetInfoFromPresenceStatus($packet) . " / " . $this->GetInfoFromPresenceShow($packet);
		$show_status = ($show_status != " / ") ? " ($addendum)" : "";

		$this->logfile[] = "<b>Presence:</b> (type: available) - $from is available $show_status";
	}



	function Handler_presence_unavailable($packet)
	{
		$from = $this->GetInfoFromPresenceFrom($packet);

		$show_status = $this->GetInfoFromPresenceStatus($packet) . " / " . $this->GetInfoFromPresenceShow($packet);
		$show_status = ($show_status != " / ") ? " ($addendum)" : "";

		$this->logfile[] = "<b>Presence:</b> (type: unavailable) - $from is unavailable $show_status";
	}



    function Handler_presence_subscribe($packet)
	{
		$from = $this->GetInfoFromPresenceFrom($packet);
		$this->subscription_queue[] = $from;
		$this->RosterUpdate();

		$this->logfile[] = "<b>Presence:</b> (type: subscribe) - Subscription request from $from, was added to \$this->subscription_queue, roster updated";
	}



    function Handler_presence_subscribed($packet)
	{
		$from = $this->GetInfoFromPresenceFrom($packet);
		$this->RosterUpdate();

		$this->logfile[] = "<b>Presence:</b> (type: subscribed) - Subscription allowed by $from, roster updated";
	}



	function Handler_presence_unsubscribe($packet)
	{
		$from = $this->GetInfoFromPresenceFrom($packet);
		$this->SendPresence("unsubscribed", $from);
		$this->RosterUpdate();

		$this->logfile[] = "<b>Presence:</b> (type: unsubscribe) - Request to unsubscribe from $from, was automatically approved, roster updated";
	}



	function Handler_presence_unsubscribed($packet)
	{
		$from = $this->GetInfoFromPresenceFrom($packet);
		$this->RosterUpdate();

		$this->logfile[] = "<b>Presence:</b> (type: unsubscribed) - Unsubscribed from $from's presence";
	}



	// ======================================================================
	// Generic handlers
	// ======================================================================



	// Generic handler for unsupported requests
	function Handler_NOT_IMPLEMENTED($packet)
	{
		$packet_type	= $this->_get_packet_type($packet);
		$from			= call_user_func(array(&$this, "GetInfoFrom" . ucfirst($packet_type) . "From"), $packet);
		$id				= call_user_func(array(&$this, "GetInfoFrom" . ucfirst($packet_type) . "Id"), $packet);

		$this->SendError($from, $id, 501);
		$this->logfile[] = "<b>Unrecognized &lt;$packet_type/&gt;</b> from $from";
	}



	// ======================================================================
	// Third party code
	// m@d pr0ps to the coders ;)
	// ======================================================================



	// xmlize()
	// (c) Hans Anderson / http://www.hansanderson.com/php/xml/
	
	function xmlize($data) {
		$vals = $index = $array = array();
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $data, $vals, $index);
		xml_parser_free($parser);
	
		$i = 0; 
	
		$tagname = $vals[$i]['tag'];
		$array[$tagname]["@"] = $vals[$i]["attributes"];
		$array[$tagname]["#"] = $this->_xml_depth($vals, $i);
	
		return $array;
	}
	
	
	
	// _xml_depth()
	// (c) Hans Anderson / http://www.hansanderson.com/php/xml/
	
	function _xml_depth($vals, &$i) { 
		$children = array(); 

		if ($vals[$i]['value']) {
			array_push($children, trim($vals[$i]['value']));
		}
	
		while (++$i < count($vals)) { 
	
			switch ($vals[$i]['type']) { 
	
				case 'cdata': 
					array_push($children, trim($vals[$i]['value'])); 
	 				break; 
	
				case 'complete': 
					$tagname = $vals[$i]['tag'];
					$size = sizeof($children["$tagname"]);
					$children[$tagname][$size]["#"] = trim($vals[$i]['value']);
					if ($vals[$i]["attributes"]) {
						$children[$tagname][$size]["@"] = $vals[$i]["attributes"];
					}
					break; 
	
				case 'open': 
					$tagname = $vals[$i]['tag'];
					$size = sizeof($children["$tagname"]);
					if ($vals[$i]["attributes"]) {
						$children["$tagname"][$size]["@"] = $vals[$i]["attributes"];
						$children["$tagname"][$size]["#"] = $this->_xml_depth($vals, $i);
					} else {
						$children["$tagname"][$size]["#"] = $this->_xml_depth($vals, $i);
					}
					break; 
	
				case 'close':
					return $children; 
					break;
			} 
		} 
	
		return $children;
	
	}
	
	
	
	// TraverseXMLize()
	// (c) acebone@f2s.com, a HUGE help!
	
	function TraverseXMLize($array, $arrName = "array", $level = 0) {
		if ($level == 0) {
			echo "<pre>";
		}
	
		while (list($key, $val) = @each($array)) {
			if (is_array($val)) {
				$this->TraverseXMLize($val, $arrName . "[" . $key . "]", $level + 1);
			} else {
				echo '$' . $arrName . '[' . $key . '] = "' . $val . "\"\n";
			}
		}
	
		if ($level == 0) {
			echo "</pre>";
		}
	}

}	



class MakeXML extends Jabber
{
	var $nodes;


	function MakeXML()
	{
		$nodes = array();
	}



	function AddPacketDetails($string, $value = NULL)
	{
		if (preg_match("/\(([0-9]*)\)$/i", $string)) {
			$string .= "/[\"#\"]";
		}

		$temp = @explode("/", $string);
		
		for ($a = 0; $a < count($temp); $a++) {
			$temp[$a] = preg_replace("/^[@]{1}([a-z0-9_]*)$/i", "[\"@\"][\"\\1\"]", $temp[$a]);
			$temp[$a] = preg_replace("/^([a-z0-9_]*)\(([0-9]*)\)$/i", "[\"\\1\"][\\2]", $temp[$a]);
			$temp[$a] = preg_replace("/^([a-z0-9_]*)$/i", "[\"\\1\"]", $temp[$a]);
		}

		$node = implode("", $temp);
		
		// Yeahyeahyeah, I know it's ugly... get over it. ;)
		echo "\$this->nodes$node = \"" . htmlspecialchars($value) . "\";<br/>";
		eval("\$this->nodes$node = \"" . htmlspecialchars($value) . "\";");
	}



	function BuildPacket($array = NULL)
	{

		if (!$array) {
			$array = $this->nodes;
		}

		if (is_array($array)) {

			array_multisort($array, SORT_ASC, SORT_STRING);

			foreach ($array as $key => $value) {
	
				if (is_array($value) && $key == "@") {

					foreach ($value as $subkey => $subvalue) {
						$subvalue = htmlspecialchars($subvalue);
						$text .= " $subkey='$subvalue'";
					}

					$text .= ">\n";

				} elseif ($key == "#") {

					$text .= htmlspecialchars($value);

				} elseif (is_array($value)) {

					for ($a = 0; $a < count($value); $a++) {

						$text .= "<$key";

						if (!$this->_preg_grep_keys("/^@/", $value[$a])) {
							$text .= ">";
						}

						$text .= $this->BuildPacket($value[$a]);

						$text .= "</$key>\n";
					}

				} else {

					$value = htmlspecialchars($value);
					$text .= "<$key>$value</$key>\n";

				}

			}

			return $text;
		}
	}



	function _preg_grep_keys($pattern, $array)
	{
		while (list($key, $val) = each($array)) {
			if (preg_match($pattern, $key)) {
				$newarray[$key] = $val;
			}
		}
		return (is_array($newarray)) ? $newarray : FALSE;
	}
}



class CJP_StandardConnector
{
	function OpenSocket($server, $port)
	{
		return fsockopen($server, $port);
	}



	function CloseSocket($connection)
	{
		return fclose($connection);
	}


	function WriteToSocket($connection, $data)
	{
		return fwrite($connection, $data);
	}



	function ReadFromSocket($connection, $chunksize)
	{
		return fread($connection, $chunksize);
	}
}



?>