JobQueue
========

Zend Server Feature JobQueue usage in Zend Framework 2.

JobQueue is basically a feature of Zend-Server. JobQueue Module is use to handle heavy background process. It helps to increase the Web App performance.  
* JobQueue service can be used for the following scenarios :
* Preparing data for the next request (pre-calculating)
* Pre-caching data
* Generating periodical reports 
* Sending e-mails 
* Cleaning temporary data or files 
* Communicating with external systems.

NOTE: Job Queue module is useful with Zend Server 5 (though not as part of the Community Edition).

Instruction to Use it in your Application:

1. Download the module and place it under vendor directory. 
2. Create database with table jobqueue_details. Install the DB SQL file from Application Demo/data directory named as jobqueue_db.sql
3. Include JobQueue module in your application.config.php file.
4. Now include the jobQueue code in your module controller as per below:
In your action function:
```php
      $this->jobQueueObj = new JobQueueLib($this);
      //Get Config params from config file, which is placed in your config dir whose name is module.config.php
      $config = $this->getServiceLocator ()->get ( 'config' );
    	
    	$host = $config['JobQueue'] ['BASE_URL'];
    	$jobUrl = $host. '/test-email/';  //JobQueue URL, or place your URL, place the entry in your module module.config.php
    	
    	//set time for the schedule time
    	//here, you can set the later date and time for the job queue execution
    	$scheduleTime = gmdate ( "Y-m-d H:i:s" );
    	
    	// IF, Want to Delete the Jobs from Job Queue
    	// $this->jobQueueObj->deleteJobQueue($this->jobQueueObj->getScheduleJobID('your-module-name', 1));
    	//end
    	
    	$jobQueueArray = array(
    		'url' => $jobUrl,
    		'scheduleTime' => $scheduleTime,
    		'module' => 'your-module-name',
    		'module_id' => 1,
    		'mail_body' => 'Hi Tarun,'				
    	);
    	
    	$this->jobQueueObj->setJobQueue($jobQueueArray); //Set the jobQueue
```
5. Now create action who will point for the jobQueue url 

```php
    /**
     * @desc : Test Mail
     */
    public function testEmailAction() {
    	$this->jobQueueObj = new JobQueueLib($this);
    	$params = \ZendJobQueue::getCurrentJobParams(); //Get params from JobQueue URL
    	$this->jobQueueObj->sendEmail($params, $this); //Call for the send email
    	exit();
    }
```
6. Now you will get the email.






