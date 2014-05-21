<?php 
return array(
	'JobQueue' => array(
        	'BASE_URL' => 'http://'.$_SERVER ["SERVER_NAME"],
			"EMAIL" => array("SMTP_SENDER_TYPE" => "user",
					"SMTP_NAME" => "localhost", "SMTP_HOST" => "localhost",
					"SMTP_PORT" => "25", "SMTP_CONNECTION_CLASS" => "plain",
					"SMTP_USERNAME" => "", "SMTP_PASSWORD" => "",
					"SMTP_SSL" => "", "BODY" => "Hello there!",
					"FROM" => "noreply@complysight.com", "TO" => "tarun.singhal@osscube.com",
					"MAIL_FROM_NICK_NAME" => "OSS", "SUBJECT" => "JobQueue : Hello Dude !!",
					"FROM_NICK_NAME" => "JobQueue Mail"),
        ),	
);