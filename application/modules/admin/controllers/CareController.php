<?php

class CareController extends Zend_Controller_Action
{
	protected $_redirector = null;
	protected $_dbAdapter = null;
    
	public function init()
    {
		$registry = Zend_Registry::getInstance();
		$this->_dbAdapter = $registry->dbAdapter;
        $this->_redirector = $this->_helper->getHelper('Redirector');

        if (empty($this->view->adminidentity)) {
			$this->_redirector->gotoSimple('index','auth','admin');
			exit;
	    } 	
	}

    public function indexAction()
    {
		#Getting Request Info
		$request = $this->getRequest();
		$_GET = $request->getParams();
		$_POST = $request->getPost(); 
		
		#Getting Objects
		$commonobj = new Default_Models_Common();
		
		$IsSick = rand(1,2);
		$wellnessinfo = $commonobj->getwellnessdata($IsSick);
		$wellnessinfo['userid'] = $this->view->adminidentity->userId;
		$commonobj->insertwellness($wellnessinfo);
		
		#Setting data to view page
		$this->view->msg = $_GET['msg'];		
		$this->view->wellnessinfo = $commonobj->getwellnessMessage($wellnessinfo);
		$this->view->employeesinfo = $commonobj->getEmployees(" AND adminid = '".$this->view->adminidentity->userId."'");		
    }	
	
    public function historyAction()
    {
		#Getting Request Info
		$request = $this->getRequest();
		$_GET = $request->getParams();
		$_POST = $request->getPost(); 
		
		#Getting Objects
		$commonobj = new Default_Models_Common();
		
		$where = " AND userid = '".$this->view->adminidentity->userId."'";
		$wellnessinfo = $commonobj->getwellness($where);
		
		#Setting data to view page
		$this->view->msg = $_GET['msg'];		
		$this->view->wellnessinfo = $wellnessinfo;		
    }	

    public function statustestAction()
    {
        #Getting Request Info
		$request = $this->getRequest();
		$_GET = $request->getParams();
		$_POST = $request->getPost(); 
		
		#Getting Objects
		$commonobj = new Default_Models_Common();
		$IsSick = rand(1,2);
		$wellnessinfo = $commonobj->getwellnessdata($IsSick);
		
		$wellnessinfo['userid'] = $this->view->adminidentity->userId;
		$commonobj->insertwellness($wellnessinfo);
		
		$wellnessinfo = $commonobj->getwellnessMessage($wellnessinfo);
		
		$loggedemployeesinfo = $commonobj->getEmployees(" AND adminid = '".$this->view->adminidentity->userId."'");	
		
		$smsmstr = "Your Status##BBT:".$wellnessinfo['bodytemp']."##BP:".$wellnessinfo['bp1']."##BG:".$wellnessinfo['Glucose']."##HR:".$wellnessinfo['hartrate']."##O2sat:".$wellnessinfo['oxygen']."##Asthma:".$wellnessinfo['Asthma'];
    $smsinfo = array();
	$smsinfo[] = array("mobile" =>  $loggedemployeesinfo[0]->mobile, "message"=>$smsmstr);
	if($loggedemployeesinfo[0]->otmobile) {
	    $smsinfo[] = array("mobile" =>  $loggedemployeesinfo[0]->otmobile, "message"=>$smsmstr);
	}
	echo "<pre>"; print_r($smsinfo);
	$output = $commonobj->sendtestsms($smsinfo);
	echo $output;
        exit;
    }

    public function statusAction()
    {
		#Getting Request Info
		$request = $this->getRequest();
		$_GET = $request->getParams();
		$_POST = $request->getPost(); 
		
		#Getting Objects
		$commonobj = new Default_Models_Common();
		$IsSick = rand(1,2);
		$wellnessinfo = $commonobj->getwellnessdata($IsSick);
		
		$wellnessinfo['userid'] = $this->view->adminidentity->userId;
		$commonobj->insertwellness($wellnessinfo);
		
		$wellnessinfo = $commonobj->getwellnessMessage($wellnessinfo);
		
		$loggedemployeesinfo = $commonobj->getEmployees(" AND adminid = '".$this->view->adminidentity->userId."'");	
		
		if($wellnessinfo['sickcount'] >= 2) {
	//Sendemail
	$emailinfo = array(
	"fromemail" => 'ihjeeva@gmail.com',
	"fromname" => 'Admin',
	"toemail" => $loggedemployeesinfo[0]->email,# $this->view->testemail,#
	"subject" =>"Employee Current Status at ".date('d-m-Y h:i A'),
	"texthtml" =>"Dear Employee,<br><br>Below are the current status of <b>".$loggedemployeesinfo[0]->firstname." ".$loggedemployeesinfo[0]->lastname."</b><br><br>
						<strong>Body Temperature :</strong>".$wellnessinfo['bodytemp']."<br>
						<strong>Blood Pressure :</strong> ".$wellnessinfo['bp1']."<br>
						<strong>Glucose :</strong> ".$wellnessinfo['Glucose']."<br>
						<strong>Heart Rate :</strong> ".$wellnessinfo['hartrate']."<br>
						<strong>Oxygen Saturation :</strong> ".$wellnessinfo['oxygen']."<br>
						<strong>Asthma :</strong> ".$wellnessinfo['Asthma']."<br><br>
						Regards,<br>
						<strong>Employee Wellness Care</strong>",
	);
	$output = $commonobj->sendtestemail($emailinfo);
    
	if($loggedemployeesinfo[0]->otemail) {
		//Sendemail
		$emailinfo = array(
		"fromemail" => 'ihjeeva@gmail.com',
		"fromname" => 'Admin',
		"toemail" => $loggedemployeesinfo[0]->otemail,# $this->view->testemail,#
		"subject" =>"Employee Current Status at ".date('d-m-Y h:i A'),
		"texthtml" =>"Dear Employee,<br><br>Below are the current status of <b>".$loggedemployeesinfo[0]->firstname." ".$loggedemployeesinfo[0]->lastname."</b><br><br>
							<strong>Body Temperature :</strong>".$wellnessinfo['bodytemp']."<br>
							<strong>Blood Pressure :</strong> ".$wellnessinfo['bp1']."<br>
							<strong>Glucose :</strong> ".$wellnessinfo['Glucose']."<br>
							<strong>Heart Rate :</strong> ".$wellnessinfo['hartrate']."<br>
							<strong>Oxygen Saturation :</strong> ".$wellnessinfo['oxygen']."<br>
							<strong>Asthma :</strong> ".$wellnessinfo['Asthma']."<br><br>
							Regards,<br>
							<strong>Employee Wellness Care</strong>",
		);
		$output = $commonobj->sendtestemail($emailinfo);
	}
	
	$smsmstr = "Your Status##BBT:".$wellnessinfo['bodytemp']."##BP:".$wellnessinfo['bp1']."##BG:".$wellnessinfo['Glucose']."##HR:".$wellnessinfo['hartrate']."##O2sat:".$wellnessinfo['oxygen']."##Asthma:".$wellnessinfo['Asthma'];
    $smsinfo = array();
	$smsinfo[] = array("mobile" =>  $loggedemployeesinfo[0]->mobile, "message"=>$smsmstr);
	if($loggedemployeesinfo[0]->otmobile) {
	    $smsinfo[] = array("mobile" =>  $loggedemployeesinfo[0]->otmobile, "message"=>$smsmstr);
	}
	#$output = $commonobj->sendtestsms($smsinfo);
	
}
		
		echo json_encode($wellnessinfo);
		exit;
    }	
	
	public function docslistAction()
    {
		#Getting Request Info
		$request = $this->getRequest();
		$_GET = $request->getParams();
		$_POST = $request->getPost(); 
		
		#Getting Objects
		$commonobj = new Default_Models_Common();
		
		#POST Process
		$searchTerm = $where = '';
		if($this->getRequest()->isPost()) {
			#$searchTerm = trim($_POST['searchTerm']);
		}		
		
		#Getting employees list
		$where = " AND userid = '".$this->view->adminidentity->userId."' ";
		$docsfile =  $commonobj->getdocsfile($where);
		
		#Setting data to view page
		$this->view->docsfile = $docsfile;
		$this->view->userid = $this->view->adminidentity->userId;
		$this->view->searchTerm = $searchTerm;
		$this->view->msg = $_GET['msg'];		
    }
	
	public function docsAction()
    {	
		#Getting Request Info
		$request = $this->getRequest();
		$_GET = $request->getParams();
		$_POST = $request->getPost();
		
		#Getting Objects
		$commonobj = new Default_Models_Common();
		
		#variables
		$errormsg = '';
		$info = array();
		
		#POST Process
		if($this->getRequest()->isPost()) {	
			$info = $_POST;	
			$info['upfile'] = '';
			if($_FILES["imagepath"]["error"] == 1) {
				$errormsg = "Error in image try another image";
			}
			if(!empty($_FILES["imagepath"]["tmp_name"])) {
				#Image Storing
				$target_filename = date('YmdHis').'_'.rand(100,999).".png";
				$target_path = $this->view->uploadpath.'images/category/'.$target_filename;
				$imageFileType = $_FILES['imagepath']['type'];
				//echo "<pre>"; print_r($_FILES); exit;
				#Check file size
				if ($_FILES["imagepath"]["error"] == 1) {
					$errormsg = "Error in image try another image";
				} else if ($_FILES["imagepath"]["size"] > 500000) {
					$errormsg = "Sorry, your file is too large";
				} else if($imageFileType != "image/png" && $imageFileType != "image/jpeg" && $imageFileType != "image/jpg" ) {
					$errormsg = "Sorry, only JPG, JPEG & PNG files are allowed";
				} else {
					if (move_uploaded_file($_FILES["imagepath"]["tmp_name"], $target_path)) {
						$info['upfile'] = $target_filename;
					} else {	
						$errormsg = "Sorry, there was an error uploading your file";
					}
				}
			} else {
				$errormsg = "Upload file before submit";
			}
			if(empty($errormsg)) {
				$info['userid'] = $this->view->adminidentity->userId;
				if($commonobj->insertdocsfile($info)) {
					$this->_redirector->gotoSimple('docslist','care','admin',  array('msg' => 'File_added_successfully'));
				} else {
					$errormsg = "Error in category create";
				}
			}
		}
		
		#Setting data to view page
		$this->view->profileaction = "add";
		$this->view->errormsg = $errormsg;
		$this->view->info = $info;
    }
	public function notifylistAction()
    {
		#Getting Request Info
		$request = $this->getRequest();
		$_GET = $request->getParams();
		$_POST = $request->getPost(); 
		
		#Getting Objects
		$commonobj = new Default_Models_Common();
		
		#POST Process
		$searchTerm = $where = '';
		if($this->getRequest()->isPost()) {
			#$searchTerm = trim($_POST['searchTerm']);
		}		
		
		#Getting employees list
		$where = " AND (userid = '' or userid is null) ";
		$docsfile =  $commonobj->getnotifications($where);
		
		#Setting data to view page
		$this->view->docsfile = $docsfile;
		$this->view->searchTerm = $searchTerm;
		$this->view->msg = $_GET['msg'];		
    }
}