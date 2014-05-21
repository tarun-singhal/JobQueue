<?php
/**
 * @desc This library is used to set/push the JobQueue in Zend-server
 * It uses the feature of zend-server JobQueue
 *  
 * @author Tarun Singhal
 * @date 15 April, 2014
 */
namespace JobQueue;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;
use Zend\Mime\Message;


class JobQueueLib {
	
	protected $adaptor;
	protected $jobQueueObj;
	
	public function __construct($con) {
		$this->adaptor = $con->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$this->jobQueueObj = new JobQueueModel($this->adaptor);
	}
	
	/**
	 * @desc To Set JobQueue
	 * @param Array $jobQueueArray
	 * @param Time $scheduleTime
	 */
	public function setJobQueue($jobQueueArray) {
		
		if (isset ( $jobQueueArray['scheduleTime'] ) && $jobQueueArray['scheduleTime'] != "") {
			// push email notification in zend job queue server
			$jobQueueId = $this->pushJobInQueue ( $jobQueueArray['url'], 
					array (
							'mailBody' => $jobQueueArray['mail_body'], //Passing variable to JobQueue
						), 
					array (
						'priority' => \ZendJobQueue::PRIORITY_NORMAL,
						'scheduleTime' => $jobQueueArray['scheduleTime']
						));
		
			if ($jobQueueId !== false) {
				$jobQueueArray = array (
					'module' => $jobQueueArray['module'],
					'module_id' => $jobQueueArray['module_id'],
					'jobqueue_id' => $jobQueueId
				);
				$this->jobQueueObj->saveJobQueue ( $jobQueueArray ); //Save in DB
			}
		}
	}
	
	/**
	 * @desc Push JobQueue in Zend-Server
	 * @param String $url
	 * @param Array $params
	 * @param Array $options
	 * @return number
	 */
	public function pushJobInQueue($url, $params = array(), $options = array()) {
		$url = str_replace ( "https://", "http://", $url );
		$queue = new \ZendJobQueue ();
		// check that Job Queue is running
		if ($queue->isJobQueueDaemonRunning ()) {
			if ($options ["priority"] == \ZendJobQueue::PRIORITY_NORMAL) {
				$options ["schedule_time"] = $options ["scheduleTime"];
			}
			 
			if (empty ( $options ['name'] )) {
				$options ['name'] = $url;
			}

			$jobID = $queue->createHttpJob ( $url, $params, $options );
			 
			return $jobID;
		}
	}
	
	
	/**
	 * @desc To send mail
	 * @param Array $mailOptions
	 * @param Object $con
	 * @throws Exception
	 * @return Boolean
	 */
	public function sendEmail($mailOptions = array(), $con = "") {
		$config = $con->getServiceLocator ()->get ( 'config' );
		$smtpName = $config ['JobQueue'] ['EMAIL'] ['SMTP_NAME'];
		$smtpHost = $config ['JobQueue'] ['EMAIL'] ['SMTP_HOST'];
		$smtpPort = $config ['JobQueue'] ['EMAIL'] ['SMTP_PORT'];
		$smtpConnectionClass = $config ['JobQueue'] ['EMAIL'] ['SMTP_CONNECTION_CLASS'];
		$smtpUsername = $config ['JobQueue'] ['EMAIL'] ['SMTP_USERNAME'];
		$smtpPassword = $config ['JobQueue'] ['EMAIL'] ['SMTP_PASSWORD'];
		$smtpSsl = $config ['JobQueue'] ['EMAIL'] ['SMTP_SSL'];
	
		$mailBody = $config ['JobQueue'] ['EMAIL'] ['BODY'];
		$mailFrom = $config ['JobQueue']['EMAIL'] ['FROM'];
		$mailSubject = $config ['JobQueue']['EMAIL'] ['SUBJECT'];
		$mailFromNickName = $config ['JobQueue']['EMAIL'] ['FROM_NICK_NAME'];
		$mailTo = $config ['JobQueue']['EMAIL'] ['TO'];
		$mailSenderType = $config ['JobQueue']['EMAIL'] ['SMTP_SENDER_TYPE'];
	
		if (array_key_exists ( 'mailTo', $mailOptions )) {
			$mailTo = $mailOptions ['mailTo'];
		}
		if (array_key_exists ( 'mailCc', $mailOptions )) {
			$mailCc = $mailOptions ['mailCc'];
		}
	
		if (array_key_exists ( 'mailFrom', $mailOptions )) {
			$mailFrom = $mailOptions ['mailFrom'];
		}
		if (array_key_exists ( 'mailFromNickName', $mailOptions )) {
			$mailFromNickName = $mailOptions ['mailFromNickName'];
		}
		if (array_key_exists ( 'mailSubject', $mailOptions )) {
			$mailSubject = $mailOptions ['mailSubject'];
		}
		if (array_key_exists ( 'mailBody', $mailOptions )) {
			$mailBody = $mailOptions ['mailBody'];
		}
		if (array_key_exists ( 'sender_type', $mailOptions )) {
			$mailSenderType = $mailOptions ['sender_type'];
		}
		
		
		$text = new Part ( $mailBody );
		$text->type = \Zend\Mime\Mime::TYPE_HTML;
		$mailBodyParts = new Message ();
		$mailBodyParts->addPart ( $text );
		if (! empty ( $mailOptions ['fileNames'] ) && ! empty ( $mailOptions ['filePaths'] )) {
			foreach ( $mailOptions ['filePaths'] as $key => $filePath ) {
				$file = new Part ( file_get_contents ( $filePath ) );
				$file->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
				$file->type = finfo_file ( finfo_open (), $filePath, FILEINFO_MIME_TYPE );
				$file->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
				$file->filename = $mailOptions ['fileNames'] [$key];
				$mailBodyParts->addPart ( $file );
			}
		}
	
		$options = new SmtpOptions ( array (
				"name" => $smtpName,
				"host" => $smtpHost,
				"port" => $smtpPort
		) );
	
		$mail = new Mail\Message ();
		$mail->setBody ( $mailBodyParts );
		$mail->setFrom ( $mailFrom, $mailFromNickName );
		$mail->addTo ( $mailTo );
		if (! empty ( $mailCc )) {
			$mail->addCc ( $mailCc );
		}
	
		$mail->setSubject ( $mailSubject );
		$transport = new SmtpTransport ();
		$transport->setOptions ( $options );
		$emailLogInfo = array (
				'email_to' => $mailTo,
				'email_from' => $mailFrom,
				'email_body' => $mailBody,
				'email_subject' => $mailSubject,
				'sender_type' => $mailSenderType
		);
		try {
			$transport->send ( $mail );
			$emailSend = 1;
		} catch ( \Exception $e ) {
			$emailSend = 0;
			$emailLogInfo ['email_error'] = $e->getMessage ();
			throw $e;
		}
		return $emailSend;
	}
	
	/**
	 * @desc Delete the Job from the JobQueue
	 * @param Integer $jobId
	 */
	public function deleteJobQueue($jobId) {
		$queue = new \ZendJobQueue ();
		if (is_array ( $jobId )) {
			foreach ( $jobId as $id ) {
				$queue->removeJob ( $id );
			}
		} elseif (is_numeric ( $jobId ) && $jobId != "") {
			$queue->removeJob ( $id );
		}
		return;
	}
	
	/**
	 * @desc Get the Sheduled Job ID for the module
	 *
	 * @param String $module
	 * @param Integer $id
	 * @return integer
	 */
	public function getScheduleJobID($module, $id) {
		$scheduledJobId = array ();
		$queue = new \ZendJobQueue ();

		$jobID = $this->jobQueueObj->getJobQueueDetails( array (
				"module" => $module,
				"module_id" => $id
		) );
		foreach ( $jobID as $jobDetails ) {
			$jobDetails = $queue->getJobInfo ( $jobDetails ['jobqueue_id'] );
			if (is_array ( $jobDetails )) {
				if ($jobDetails ['status'] == \ZendJobQueue::STATUS_SCHEDULED) {
					array_push ( $scheduledJobId, $jobDetails ['id'] );
				}
			}
		}
		return $scheduledJobId;
	}

}
