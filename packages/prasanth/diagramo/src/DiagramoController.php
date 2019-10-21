<?php
namespace prasanth\diagramo;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

// use prasanth\diagramo\delegate;
include('delegate.php');
// include('png.php');



class DiagramoController extends Controller
{
	//

	public function login()
	{
		$delegate = new Delegate();

		$VERSION = $delegate->settingsGetByKeyNative('VERSION');


		// $delegate = new Delegate();
		$WEBADDRESS = $delegate->settingsGetByKeyNative('WEBADDRESS');
		// echo 'yesssssss';exit;

		return view('diagramo::login', compact('VERSION','WEBADDRESS','delegate'));
	}

	public function index($diagramId = null)
	{
		// print_r($_SESSION);exit;
		$delegate = new Delegate();
		$WEBADDRESS = $delegate->settingsGetByKeyNative('WEBADDRESS');

		return view('diagramo::index', compact('WEBADDRESS','diagramId'));
	}

	public function controller()
	{
		// print_r($_REQUEST);exit;


		/*
		Copyright [2014] [Diagramo]

		Licensed under the Apache License, Version 2.0 (the "License");
		you may not use this file except in compliance with the License.
		You may obtain a copy of the License at

			http://www.apache.org/licenses/LICENSE-2.0

		Unless required by applicable law or agreed to in writing, software
		distributed under the License is distributed on an "AS IS" BASIS,
		WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
		See the License for the specific language governing permissions and
		limitations under the License.
		*/

		// require_once dirname(__FILE__) . '/delegate.php';

		session_start();
		################################################################################
		###   REQUEST   ################################################################
		// Collect the data (from POST or GET)
		// print_r($_REQUEST['action']);exit;
		// $_SESSION['userId'] = 1;
		if (empty($_REQUEST['action'])) {
			$action = $_REQUEST['action'] = 'load';
		}else{
			$action = $_REQUEST['action'];
		}
		// print_r($_SESSION);//exit;
		// echo $action;exit;


		switch ($action) {

			case 'info':
				self::info();
				break;

			case 'logoutExe':
				self::logoutExe();
				break;

			case 'loginExe':
				self::loginExe();
				break;

			case 'forgotPasswordExe':
				self::forgotPasswordExe();
				break;

			case 'resetPassword':
				self::resetPassword();
				break;

			case 'resetPasswordExe':
				self::resetPasswordExe();
				break;

			case 'saveSettingsExe':
				self::saveSettingsExe();
				break;


			case 'save':
				// echo 'tessssssssssss save';break;
				self::save();
				break;

			case 'saveAs':
				self::saveAs();
				break;

			case 'saveSvg':
				self::saveSvg();
				break;

			case 'newDiagramExe':
				self::newDiagramExe();
				break;

			case 'editDiagramExe':
				self::editDiagramExe();
				break;

			case 'firstSaveExe':
				self::firstSaveExe();
				break;

			case 'load':
				self::load();
				break;
			
			case 'loadTemp':
				self::loadTemp();
				break;
			
			case 'loadQuickStart':
				self::loadQuickStart();
				break;

			case 'getUploadedImageFileNames':
				self::getUploadedImageFileNames();
				break;

			case 'insertImage':
				self::insertImage();
				break;
			
			case 'importDiagramExe':
				self::importDiagramExe();
				break;

			case 'deleteDiagramExe':
				self::deleteDiagramExe();
				break;
			/*************************** */
			/*********QUICK start****** */
			/*************************** */
			case 'closeQuickStart':
				self::closeQuickStart();
				break;
			
			/*************************** */
			/*********COLABORATORS****** */
			/*************************** */
			case 'addUserExe':
				self::addUserExe();
				break;
			
			case 'cancelInvitationExe':
				self::cancelInvitationExe();
				break;
			
			case 'acceptInvitationExe':
				self::acceptInvitationExe();
				break;
			
			case 'declineInvitationExe':
				self::declineInvitationExe();
				break;

			case 'removeUser':
				self::removeUser();
				break;
			

			case 'removeMeFromDiagram':
				self::removeMeFromDiagram();
				break;
			
			/*************************** */
			/*********USERS****** */
			/*************************** */
		//	case 'addUserExe':
		//		addUserExe();
		//		break;
			
			case 'registerExe':
				self::registerExe();
				break;
				
			case 'editUserExe':
				self::editUserExe();
				break;
			
			
		//	/****************LICENSE--------------*/
		//	case 'saveLicense':
		//		saveLicense();
		//		break;
		}




	}


	public function loginExe() {
		$email = trim($_REQUEST['email']);
		$password = trim($_REQUEST['password']);
// echo $email;exit;

		// Validate data
		if (validateString($email, 'Empty email or bad email syntax')) {
			#print "Wrong email";
		}
		if (validateString($password, 'Empty password')) {
			#print "Wrong password";
		}


		if (errors ()) {
			#print "Errors"; exit(0);
			//outer site
			redirect("login");
			exit(0);
		}

		$delegate = new Delegate();
		$user = $delegate->userGetByEmailAndPassword($email, $password);
		if (is_object($user)) {
			$_SESSION['userId'] = $user->id;

			//remember me option
			if ($_REQUEST['rememberMe'] === 'true') {
				$userCookie = packer(array('email' => $email, 'password' => md5($password)), PACKER_PACK);
				setcookie('biscuit', $userCookie, time() + ((60 * 60 * 24) * 5), '/');
			}

			$user->lastLoginDate = now();
			$user->lastLoginIp = $_SERVER['REMOTE_ADDR'];
			$user->lastBrowserType = $_SERVER['HTTP_USER_AGENT'];
			//$delegate->userUpdate($user);

			if($user->tutorial){
				redirect("index");
			}
			else{
				redirect("index");
			}
			exit(0);		
		} else {
			addError("Authetication failed");
			//outer site
			redirect("login");
			exit(0);
		}
	}

	/**
	 * Logout
	 */
	public function logoutExe() {
		if (is_numeric($_SESSION['userId'])) {
			unset($_SESSION['userId']);

			// Clear the user cookie
			setcookie('biscuit', null, time() - ((60 * 60 * 24) * 5), '/');


			session_destroy();
		}

		addMessage("You were logged out!");

		//up to the outer site
		redirect("login");
	}

	/**
	 */
	public function forgotPasswordExe() {	
		$email = trim($_POST['email']);


		// Validate data
		if (!validateString($email, 'Empty email or bad email syntax')) {
			print "Wrong email: " . $email;
			exit();
		}
		
		if($_REQUEST['captcha'] != $_SESSION['captcha']){
			addError("Text was wrong. try again!");		
		}


		if (errors ()) {
		   // print "Errors"; exit(0);
			redirect("../forgot-password.php");
			exit(0);
		}

		$delegate = new Delegate();
		$WEBADDRESS = $delegate->settingsGetByKeyNative('WEBADDRESS');
		$user = $delegate->userGetByEmail($email);
		if (is_object($user)) {
			$url = $WEBADDRESS . '/editor/common/controller.php?action=resetPassword&k=' . $user->password . '&i=' . $user->id;
			$body =
					"<html>
					<head>
					<title>Reset your password</title>
					</head>
				 <body>
					Hello, <p/>
					Here is your request to reset your password. Please click the link to reset your password.
					<a href=\"${url}\">${url}</a>
				 </body>
				 </html>";
			if (sendEmail($user->email, 'no-reply@diagramo.com', "Password reset", $body)) {
				addMessage("Reset email sent!");
			} else {
				addError("Reset email NOT sent!");
			}

			#outer site
			redirect("../forgot-password.php");
			exit(0);
		} else {
			addError("Email not present in DB");
			redirect("../forgot-password.php");
			exit(0);
		}
	}

	/* * Resets a password */

	public function resetPassword() {
		$id = trim($_GET['i']); //get user Id
		$key = trim($_GET['k']); //get user's encrypted password :D


		// Validate data
		if (validateString($id, 'Wrong i param')) {
			#print "Wrong email";
		}

		if (validateString($key, 'Wrong k param')) {
			#print "Wrong email";
		}


		if (errors ()) {
			#print "Errors"; exit(0);
			redirect("../forgot-password.php");
			exit(0);
		}

		$delegate = new Delegate();
		$user = $delegate->userGetByIdAndEncryptedPassword($id, $key);
		#print_r($user);
		#exit();
		if (is_object($user)) {
			$_SESSION['userId'] = $user->id;

			redirect("../resetPassword.php");
			exit(0);
		} else {
			addError("User/Email not present in DB");
			redirect("../forgot-password.php");
			exit(0);
		}
	}

	/* * Resets a password */

	public function resetPasswordExe() {

		if (!is_numeric($_SESSION['userId'])) {
			addError("Not permited");
			redirect("../editor.php");
			exit(0);
		}

		$password = trim($_POST['password']);

		// Validate data
		if (validateString($password, 'Password should have at least 4 characters', 4)) {
			#print "Wrong email";
		}



		if (errors ()) {
			#print "Errors"; exit(0);
			redirect("../resetPassword.php");
			exit(0);
		}

		$delegate = new Delegate();
		$user = $delegate->userGetById($_SESSION['userId']);
		$user->password = md5($password);
		#print_r($user);
		#exit();
		if ($delegate->userUpdate($user)) {
			//we will skip this message
			//addMessage("Password changed!");

			redirect("../editor.php");
			exit(0);
		} else {
			addError("Password not changed");
			redirect("../resetPassword.php");
			exit(0);
		}
	}

	/* * Resets a password */

	public function saveSettingsExe() {

		if (!isset($_SESSION['userId']) || !is_numeric($_SESSION['userId'])) {
			addError("Not permited");
			redirect("../editor.php");
			exit(0);
		}

		$delegate = new Delegate();
		$user = $delegate->userGetById($_SESSION['userId']);
	   // print_r($user);
	   // exit();

	   // $name = trim($_POST['name']);
		$currentPassword = trim($_POST['currentPassword']);
		$newPassword = trim($_POST['newPassword']);


		if (!strlen($newPassword) >= 4) {
			addError("New password too short or empty");
		}

		if (md5($currentPassword) != $user->password) {
			addError("Current password is wrong");
		} else {
			$user->password = md5($newPassword);
		}




		if (errors ()) {
			#print "Errors"; exit(0);
			redirect("../settings.php");
			exit(0);
		}


		if ($delegate->userUpdate($user)) {
			addMessage("Settings saved!");

			redirect("../settings.php");
			exit(0);
		} else {
			addError("Settings not saved (or nothing to save)");
			redirect("../settings.php");
			exit(0);
		}
	}

	/* * Save currently edited diagram
	 * We have 3 cases:
	 * 1. there is no account present  (once time)
	 * 2. account is present but this is the first save (seldomly)
	 * 3. account is pressent and this is not the first save (the most common)
	 */

	public function save() {

		if (isset($_REQUEST['diagramId']) &&  is_numeric($_REQUEST['diagramId'])) { //we have a current working diagram
			//print($_POST['svg']);

			$delegate = new Delegate();


			$currentDiagramId = $_REQUEST['diagramId'];
			$nowIsNow = now();
			// $nowIsNow = date('Y-m-d H:i:s');

			// 1 - update the Dia file
			// echo 'yesssssssssss';exit;
			$diaData = $delegate->diagramdataGetByDiagramIdAndType($currentDiagramId, Diagramdata::TYPE_DMO);
			// print_r($diaData);exit;

			$fh = fopen(getStorageFolder() . '/' . $currentDiagramId . '.dmo', 'w');
			// $fh = fopen(dirname(__FILE__) . '/../diagrams/' . $currentDiagramId . '.dmo', 'w');

			   // $diaFile = dirname(__FILE__) . '/../diagrams/' . $_REQUEST['diagramId'] . '.dmo';
			$diaSize = fwrite($fh, $_POST['diagram']);
			fclose($fh);

			$diaData->fileSize = $diaSize;
			$diaData->lastUpdate = $nowIsNow;
			$delegate->diagramdataUpdate($diaData);
			//end update Dia file
			
			
			/*SVG support discontinued
			//2 - update the SVG file
			$svgData = $delegate->diagramdataGetByDiagramIdAndType($currentDiagramId, Diagramdata::TYPE_SVG);

			$fh = fopen(getStorageFolder() . '/' . $currentDiagramId . '.svg', 'w');
			$svgSize = fwrite($fh, $_POST['svg']);
			fclose($fh);

			$svgData->fileSize = $svgSize;
			$svgData->lastUpdate = $nowIsNow;
			$delegate->diagramdataUpdate($svgData);
			//end update the SVG file
			//update the Diagram
			$diagram = $delegate->diagramGetById($currentDiagramId);
			$diagram->size = $diaSize;
			$diagram->lastUpdate = $nowIsNow;
			*/
			
			//3 - update the PNG file
			$pngData = $delegate->diagramdataGetByDiagramIdAndType($currentDiagramId, Diagramdata::TYPE_PNG);

			$fh = fopen(getStorageFolder() . '/' . $currentDiagramId . '.png', 'wb');
			$data = substr($_POST['png'], strpos($_POST['png'], ','));
			$imgData = base64_decode($data);
			$pngSize = fwrite($fh, $imgData);
			fclose($fh);

			$pngData->fileSize = $pngSize;
			$pngData->lastUpdate = $nowIsNow;
			$delegate->diagramdataUpdate($pngData);
			//end update the SVG file
			
			
			//update the link map 
			$csvData = $delegate->diagramdataGetByDiagramIdAndType($currentDiagramId, Diagramdata::TYPE_CSV);
			$fh = fopen(getStorageFolder() . '/' . $currentDiagramId . '.csv', 'w');
			$data = $_POST['linkMap'];
			$csvSize = fwrite($fh, $data);
			fclose($fh);

			$csvData->fileSize = $csvSize;
			$csvData->lastUpdate = $nowIsNow;
			$delegate->diagramdataUpdate($csvData);
			//end update the link map
			
			
			
			//update the Diagram
			$diagram = $delegate->diagramGetById($currentDiagramId);
			$diagram->size = $diaSize;
			$diagram->lastUpdate = $nowIsNow;
			// print_r($diagram);exit;

			if ($delegate->diagramUpdate($diagram)) {
				print "saved";
			} else {
				print 'diagramdata not saved';
			}
			exit();
		} else { //no current working diagram
			$_SESSION['tempDiagram'] = $_POST['diagram'];
			$_SESSION['tempSVG'] = $_POST['svg'];
			$_SESSION['tempPNG'] = $_POST['png'];
			$_SESSION['tempLinkMap'] = $_POST['linkMap'];
			print "firstSave";
			exit();
		}
	}

	/* * Save currently edited diagram. We always have an user logged
	 * We have 2 cases:
	 * 1. there is no account present  (do nothing)
	 * 2. account is present so store diagram in session and redirect (from JavaScript) to save Diabram form
	 */

	public function saveAs() {
		
		if (!is_numeric($_SESSION['userId'])) { //no user logged
			$_SESSION['tempDiagram'] = $_POST['diagram'];
			$_SESSION['tempSVG'] = $_POST['svg'];
			$_SESSION['tempPNG'] = $_POST['png'];
			$_SESSION['tempLinkMap'] = $_POST['linkMap'];
			print "noaccount";
			exit();
		} else { //user is logged
			$_SESSION['tempDiagram'] = $_POST['diagram'];
			$_SESSION['tempSVG'] = $_POST['svg'];
			$_SESSION['tempPNG'] = $_POST['png'];
			$_SESSION['tempLinkMap'] = $_POST['linkMap'];
			print "step1Ok";
			exit();
		}
	}

	/* * Save currently SVG-ed diagram
	 */

	public function saveSvg() {

		if (!empty($_POST['svg'])) { //no user logged
			$_SESSION['svg'] = $_POST['svg'];
			print "svg_ok";
			exit();
		} else { //user is not logged
			print "svg_failed";
			exit();
		}
	}

	public function newDiagramExe() {
	   // if(!is_numeric($_SESSION['userId'])) { //no user logged
	   //	 print "wrong turn";
	   //	 exit();
	   // }
		//reset ay temporary diagram
		$_SESSION['tempDiagram'] = null;
		unset($_SESSION['tempDiagram']);

		redirect('../editor.php');
	}

	public function editDiagramExe() {
		if (!is_numeric($_SESSION['userId'])) { //no user logged
			print "Not allowed";
			exit();
		}

		if (!is_numeric($_REQUEST['diagramId'])) { //no diagram specified
			print "No diagram";
			exit();
		}

		$d = new Delegate();
		$diagram = $d->diagramGetById($_REQUEST['diagramId']);

		$diagram->title = trim($_REQUEST['title']);
		$diagram->description = trim($_REQUEST['description']);
		$diagram->public = ($_REQUEST['public'] == true) ? 1 : 0;
		$diagram->lastUpdate = now();

		if ($d->diagramUpdate($diagram)) {
			addMessage("Diagram updated");
		} else {
			addError("Diagram not updated");
		}

		redirect('../myDiagrams.php');
	}

	/* * We already have the temporary diagram saved in session */
	public function firstSaveExe() {
	   // print_r($_SESSION);
	   // exit();
		
		// if (!is_numeric($_SESSION['userId'])) {
		// 	print "Wrong way";
		// 	exit();
		// }

		//store current time
		// $nowIsNow = now();
		$nowIsNow = date('Y-m-d H:i:s');

		//save Diagram
		$diagram = new Diagram();
		$diagram->title = trim($_REQUEST['title']);
		$diagram->description = trim($_REQUEST['description']);
		$diagram->public = (isset($_REQUEST['public']) && $_REQUEST['public'] == 'true') ? 1 : 0;
		$diagram->createdDate = $nowIsNow;
		$diagram->lastUpdate = $nowIsNow;
		$diagram->size = strlen($_SESSION['tempDiagram']); //TODO: it might be not very accurate

		$delegate = new Delegate();

	   // $token = '';
	   // do {
	   //	 $token = generateRandom(6);
	   // } while ($delegate->diagramCountByHash($token) > 0);
	
	   // $diagram->hash = $token;
		$diagramId = $delegate->diagramCreate($diagram);
		//end save Diagram
		
		//create Dia file
		$diagramdata = new Diagramdata();
		$diagramdata->diagramId = $diagramId;
		$diagramdata->type = Diagramdata::TYPE_DMO;
		$diagramdata->fileName = $diagramId . '.dmo';

		$fh = fopen(getStorageFolder() . '/' . $diagramId . '.dmo', 'w');
		$size = fwrite($fh, $_SESSION['tempDiagram']);
		fclose($fh);

		$diagramdata->fileSize = $size;
		$diagramdata->lastUpdate = $nowIsNow;

		$delegate->diagramdataCreate($diagramdata);
		//end Dia file
		
		/*
		//create SVG file
		$diagramdata = new Diagramdata();
		$diagramdata->diagramId = $diagramId;
		$diagramdata->type = Diagramdata::TYPE_SVG;
		$diagramdata->fileName = $diagramId . '.svg';

		$fh = fopen(getStorageFolder() . '/' . $diagramId . '.svg', 'w');
		$size = fwrite($fh, $_SESSION['tempSVG']);
		fclose($fh);

		$diagramdata->fileSize = $size;
		$diagramdata->lastUpdate = $nowIsNow;
		$delegate->diagramdataCreate($diagramdata);
		//end SVG file
		*/
		
		
		//create PNG file
		$diagramdata = new Diagramdata();
		$diagramdata->diagramId = $diagramId;
		$diagramdata->type = Diagramdata::TYPE_PNG;
		$diagramdata->fileName = $diagramId . '.png';

		$fh = fopen(getStorageFolder() . '/' . $diagramId . '.png', 'wb');
		$data = substr($_SESSION['tempPNG'], strpos($_SESSION['tempPNG'], ','));
		$imgData = base64_decode($data);
		$size = fwrite($fh, $imgData);
		fclose($fh);

		$diagramdata->fileSize = $size;
		$diagramdata->lastUpdate = $nowIsNow;

		$delegate->diagramdataCreate($diagramdata);
		//end PNG file
		
		
		//create CSV file
		$diagramdata = new Diagramdata();
		$diagramdata->diagramId = $diagramId;
		$diagramdata->type = Diagramdata::TYPE_CSV;
		$diagramdata->fileName = $diagramId . '.csv';

		$fh = fopen(getStorageFolder() . '/' . $diagramId . '.csv', 'w');
		$size = fwrite($fh, $_SESSION['tempLinkMap']);
		fclose($fh);

		$diagramdata->fileSize = $size;
		$diagramdata->lastUpdate = $nowIsNow;

		$delegate->diagramdataCreate($diagramdata);
		//end CSV file
		
		
		//clean temporary diagram
		unset($_SESSION['tempDiagram']);
		unset($_SESSION['tempSVG']);
		unset($_SESSION['tempPNG']);
		unset($_SESSION['tempLinkMap']);

// echo 'yesssssssssss';exit;

		// redirect("../editor.php?diagramId=" . $diagramId);
		redirect("index/" . $diagramId);
	}


	/**Loads a diagram*/
	public function load() {
		// echo $_REQUEST['diagramId'] = 11;
		if (!is_numeric($_REQUEST['diagramId'])) {
			print "Wrong diagram id : " . $_REQUEST['diagramId'];
			exit();
		}

		// echo $_REQUEST['diagramId'];exit;
		$d = new Delegate();
		// echo 'yesssssssssss';exit;
		$diagram = $d->diagramGetById($_REQUEST['diagramId']);
// echo 'yesssssssssssssssssssss';exit;
		$allow = false;
		if($diagram->public){
			$allow = true;
		}
		else{ //no public so only logged users can see it
			$_SESSION['userId'] = 1;
			if (!is_numeric($_SESSION['userId'])) {
				print "Wrong user id";
				exit();
			}

			$allow = true;
		}

		if($allow){
			// echo 'yes+++++++++++';
			$diagramdata = $d->diagramdataGetByDiagramIdAndType($_REQUEST['diagramId'], Diagramdata::TYPE_DMO);

			$diaFile = getStorageFolder() . '/' . $_REQUEST['diagramId'] . '.dmo';

			/**When switching from Linux to Windows some files might get corrupt so we will use file_get_contents*/
		   // $fh = fopen($diaFile, 'r');
		   // $data = fread($fh, $diagramdata->fileSize);
		   // fclose($fh);
			$data = file_get_contents($diaFile);
			// print_r($data);exit;

			print $data;
		}		
	}


	/**Loads a temporary diagram*/
	public function loadTemp() {

		if (!is_numeric($_SESSION['userId'])) {
			print "Wrong way";
			exit();
		}
		
		$tempName = $_REQUEST['tempName'];
		$diaFile = getStorageFolder() . '/' . $tempName;
		$data = file_get_contents($diaFile);
		print $data;
	}


	/**Loads quick start diagram*/
	public function loadQuickStart() {

		if (!is_numeric($_SESSION['userId'])) {
			print "Wrong way";
			exit();
		}
		
		$diaFile = getDataFolder() . '/quickstart.dmo';
		$data = file_get_contents($diaFile);
		print $data;
	}


	/** Returns filenames array for uploaded images.
	*  @author Artyom Pokatilov <artyom.pokatilov@gmail.com>
	*/
	public function getUploadedImageFileNames() {
		// get folder of uploaded images
		$dir = getUploadedImageFolder() . "/";
		// $dir = dirname(__FILE__) . '/data/diagrams/';

		// index of uploaded image
		$i = 0;

		// print filenames that matched to images pattern
		echo "[";
		// find images in upload directory
		foreach(glob($dir . "*.{jpg,jpeg,png,gif,bmp}", GLOB_BRACE) as $filename){
			if (!is_dir($filename)) {
				$filename = str_replace($dir, "", $filename);
				// added comma before every filename after first
				if ($i > 0) {
					echo ",";
				}
				echo "\"" . $filename . "\"";
				$i++;
			}
		}
		echo "]";
	}


	/**Insert image inside diagram:
		1) Check: get image from URL or get uploaded one.
		2) Save image on server.
		3) Return path to it.

	*  @author Artyom Pokatilov <artyom.pokatilov@gmail.com>
	*/
	public function insertImage() {
		$_SESSION['userId'] = 1;
		if (!is_numeric($_SESSION['userId'])) { //no user logged
			print "Not allowed";
			exit();
		}

		$imgSource = $_REQUEST['image-group'];


		switch ($imgSource) {

			case 'URL':
				$imageURL = $_REQUEST['imageURL'];
				$fileName = basename($imageURL);
				$ext = pathinfo($fileName, PATHINFO_EXTENSION);
				$imageFile = get($imageURL);
				$imagePath = getUploadedImageFolder() . '/' . $fileName;
				if ($imageFile !== false && strlen($imageFile) > 0  
						&& ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif" || $ext == "bmp")) { //file is image

					$fh = fopen($imagePath, 'w');
					$size = fwrite($fh, $imageFile);
					fclose($fh);

				} else {
					// can't upload image from URL

					// call insert image function and send error message
					print '<script type="text/javascript">'
						. 'window.top.window.insertImage("", "Error uploading image from URL. Check typed URL. Max allowed size is 5MB." )'
						. '</script>';

					exit();
				}
				break;

			case 'Upload':
				$imageFile = $_FILES['imageFile']['tmp_name'];
				$fileName = $_FILES["imageFile"]["name"];
				$ext = pathinfo($fileName, PATHINFO_EXTENSION);
				if (is_uploaded_file($imageFile) && filesize($imageFile) > 0  
						&& ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif" || $ext == "bmp")) { //file is image

					$imagePath = getUploadedImageFolder() . '/' . $fileName;
					if (!move_uploaded_file($imageFile, $imagePath)) {
						// can't move uploaded file

						// call insert image function and send error message
						print '<script type="text/javascript">'
							. 'window.top.window.insertImage("", "Error uploading image. Check chosen file." )'
							. '</script>';

						exit();
					}

				} else {
					// file isn't uploaded

					// call insert image function and send error message
					print '<script type="text/javascript">'
						. 'window.top.window.insertImage("", "Error uploading image. Check chosen file. " )'
						. '</script>';

					exit();
				}
				break;

			case 'Reuse':
				$fileName = $_REQUEST['reuseImageFile'];
				break;

		}

		// call insert image function and send saved image path to it
		print '<script type="text/javascript">'
				. 'window.top.window.insertImage("' . $fileName . '")'
				. '</script>';
	}


	/**Imports a Diagram from a file*/
	public function importDiagramExe() {

		if (!is_numeric($_SESSION['userId'])) { //no user logged
			print "Not allowed";
			exit();
		}

		$d = new Delegate();
		if (is_uploaded_file($_FILES['diagramFile']['tmp_name']) && filesize($_FILES['diagramFile']['tmp_name']) > 0) { //file is fine
		   // $size = filesize($_FILES['diagramFile']['tmp_name']);
				   
		   // //create entry in 'diagram' table
		   // $nd = new Diagram();
		   // $nd->title = "Imported on " + now();
		   // $nd->public = 0;
		   // $nd->createdDate = now();
		   // $nd->lastUpdate = now();
		   // $nd->size = $size;
		   
		   // $diagramId = $d->diagramCreate($nd);
		   
		   // //create entry in 'diagramdata' table
		   // $ndd = new Diagramdata();
		   // $ndd->diagramId = $diagramId;
		   // $ndd->type = Diagramdata::TYPE_DIA;
		   // $ndd->fileName = $diagramId . '.dmo';
		   // $ndd->fileSize = $size;
		   // $ndd->lastUpdate = now();
		   
		   // $d->diagramdataCreate($ndd);
			
			//compute destination file 
			$newFileName = 'tmp' . time() . '.dmo';
			$destFile = dirname(__FILE__) . '/data/diagrams/' . $newFileName;
			if (move_uploaded_file($_FILES['diagramFile']['tmp_name'], $destFile)) {
				redirect('../editor.php?diagramId=' . $newFileName);
			}
			else{
				redirect('../myDiagrams.php?error=Could not import diagram');
			}
			
		}  
	}



	public function deleteDiagramExe() {
		if (!is_numeric($_SESSION['userId'])) {
			print "Wrong way";
			exit();
		}

		if (!is_numeric($_REQUEST['diagramId'])) {
			print "Wrong diagram id : " . $_REQUEST['diagramId'];
			exit();
		}

		//TODO: usually ONLY the author can delete the diagram
		$d = new Delegate();
		
	   // print_r($_REQUEST);
	   // exit();

		//delete diagramdata
		$diagramDatas = $d->diagramdataGetByDiagramId($_REQUEST['diagramId']);
		$storageFolder = getStorageFolder();
		foreach($diagramDatas as $diagramData){
			//TODO: we can make more tests here
			unlink($storageFolder . '/' . $diagramData->fileName);
			$d->diagramdataDeleteByDiagramIdAndType($diagramData->diagramId, $diagramData->type);
		}

		//delete diagram
		if ($d->diagramDelete($_REQUEST['diagramId'])) {
			addMessage("Diagram deleted");
		} else {
			addError("Diagram could not be deleted from database");
		}

		redirect('../myDiagrams.php');
	}


	public function closeQuickStart(){
		if (!is_numeric($_SESSION['userId'])) {
			print "Wrong way";
			exit();
		}
		
		$delegate = new Delegate();
		
		$user = $delegate->userGetById($_SESSION['userId']);
		
		$user->tutorial = 0;
			
		$delegate->userUpdate($user);
		
		newDiagramExe();
	}


	/**Invite a collaborator to a diagram.
	 * There are 2 kind of invitations:
	 *  1. outside people - when you send an email with invitation and they will 
	 *	  first create an account and then accept the invitation
	 *  2. known people - You already know those people and you invite them.
	 *	  They will get an email + an "accept invitation" link on main page.
	 */
	public function addUserExe() {

		if (!is_numeric($_SESSION['userId'])) {
			print "Wrong way";
			exit();
		}

		if (empty($_REQUEST['email'])) {
			print "Email is empty";
			exit();
		}
		
		if (empty($_REQUEST['password'])) {
			print "Password is empty";
			exit();
		}

		$d = new Delegate();
		$loggedUser = $d->userGetById($_SESSION['userId']);
		
		$user = new User();
		$user->email = trim($_REQUEST['email']);
		$user->password = md5(trim($_REQUEST['password']));
		$user->createdDate = now();
		if($d->userCreate($user)){
			addMessage("User added");
		}
		else{
			addError("User not added");;
		}
		
			
		//refirect back to collaborators
		redirect('../users.php');
	}


	/**Delete an invitation*/
	public function cancelInvitationExe() {

		if (!is_numeric($_SESSION['userId'])) {
			print "Wrong way";
			exit();
		}

		if (empty($_REQUEST['invitationId'])) {
			print "Invitation id is wrong";
			exit();
		}

		$d = new Delegate();
		$loggedUser = $d->userGetById($_SESSION['userId']);
		$invitation = $d->invitationGetById($_REQUEST['invitationId']);
		$diagram = $d->diagramGetById($invitation->diagramId);

		//are u allocated?
		$userdiagram = $d->userdiagramGetByIds($loggedUser->id, $diagram->id);
		if (!is_object($userdiagram)) {
			addError("Not working on that diagram.");
			redirect('../myDiagrams.php');
			exit();
		}
		
		if($userdiagram->level != Userdiagram::LEVEL_AUTHOR){
			addError("You have no rights.");
			redirect('../myDiagrams.php');
			exit();
		}
		
		if($d->invitationDelete($invitation->id)){
			addMessage("Invitation deleted");
		} 
		else{
			addError("Invitation NOT deleted");
		}		
		
		redirect('../colaborators.php?diagramId=' . $diagram->id);
	}


	/**
	 * Remove a colaborator
	 */
	public function removeUser(){

	   // print_r($_REQUEST);
	   // exit();
		
		if (!is_numeric($_SESSION['userId'])) {
			print("Wrong way");
			exit();
		}
		

		if(!is_numeric($_REQUEST['userId'])){
			print("Wrong user");
			exit();
		}

		$delegate = new Delegate();
		if($delegate->userDeleteById($_REQUEST['userId'])){
			addMessage("User deleted");
		}
		else{
			addError("User not deleted");
		}
		
		redirect('../users.php');
		
	}


	/**
	 * The collaborator remove itself from diagram
	 */
	public function removeMeFromDiagram(){

		if (!is_numeric($_SESSION['userId'])) {
			print("Wrong way");
			exit();
		}

		if(!is_numeric($_REQUEST['diagramId'])){
			print("No diagram");
			exit();
		}


		$delegate = new Delegate();
		$loggedUser = $delegate->userGetById($_SESSION['userId']);
		
		$userdiagram = $delegate->userdiagramGetByIds($loggedUser->id, $_REQUEST['diagramId']);

		if ($userdiagram) {
			/**author can not remove itself. he has to delete the diagram*/
			if($userdiagram->level == Userdiagram::LEVEL_AUTHOR){
				addError("Author can not remove itself from a diagram");
				redirect('../myDiagrams.php');
				exit();
			}
			
			if ($delegate->userdiagramDelete($loggedUser->id, $_REQUEST['diagramId'])) {
				addMessage("Removed from diagram");
				//TODO:  notify author ?			 
			} else {
				addError("You were not removed from diagram");
			}

			redirect('../myDiagrams.php');
		}
		else{
			print('No rights');
			exit();
		}
	}


	public function info() {
		phpinfo();
	}


	public function registerExe(){
		if(!validateEmail($_REQUEST['email'])){
			addError("Email is wrong");
		}
		
		$d = new Delegate();
		
		$existingUser = $d->userGetByEmail(trim($_REQUEST['email']));
		if(is_object($existingUser)){
			addError("An user with same email already present.");
		}
		
		if(!validateString($_REQUEST['password'])){
			addError("Password is not ok");
		}
		
		if($_REQUEST['password'] != $_REQUEST['password2']){
			addError("Passwords do not match");
		}
		
		if( !isset ($_REQUEST['invitationToken']) ){
			if($_REQUEST['captcha'] != $_SESSION['captcha']){
				addError("Code was incorrect");
			}
		}
		
			
		if(errors()){
			redirect('../../register.php');
			exit(0);
		}
		
		
		$user = new User();
		$user->email = trim($_REQUEST['email']);
		$user->password = md5($_REQUEST['password']);
		$user->createdDate = now();	
		$user->lastLoginDate = now();
		$user->lastLoginIp = $_SERVER['REMOTE_ADDR'];
		$user->lastBrowserType = $_SERVER['HTTP_USER_AGENT'];
		
		
		$userId = $d->userCreate($user);
		if(is_numeric($userId)){
			addMessage("You were registered");
			
			$_SESSION['userId'] = $userId;	
			
			$_SESSION['captcha'] = null;
			unset($_SESSION['captcha']);
			
			//TODO: if we have a temp diagram we will redirect to save page
			if( isset ($_SESSION['tempDiagram']) ){
				redirect('../saveDiagram.php');
			} 
			else if( isset($_REQUEST['invitationToken']) ){
				$invitation = $d->invitationGetByToken($_REQUEST['invitationToken']);
				if(is_object($invitation)){
					//find the diagram
					$diagram = $d->diagramGetById($invitation->diagramId);
					
					//create userdiagram
					$userdiagram = new Userdiagram();
					$userdiagram->diagramId = $diagram->id;
					$userdiagram->invitedDate = $invitation->createdDate;
					$userdiagram->level = Userdiagram::LEVEL_EDITOR;
					$userdiagram->status = Userdiagram::STATUS_ACCEPTED;
					$userdiagram->userId = $userId;
					
					if(!$d->userdiagramCreate($userdiagram)){
						addError("Could not add you to the diagram");
						redirect('../editor.php');
						exit();
					}
					
					//delete invitation
					$d->invitationDelete($invitation->id);
					
					//all is fine, redirect to the diagram
					redirect('../editor.php?diagramId=' . $diagram->id);
					
				}
				else{
				}
				redirect('../editor.php');
			}
			else{
				redirect('../editor.php');
			}
			
			exit(0);
		}
		else{
			addError("User not added ");
			redirect('../../register.php');
			exit(0);
		}	   
	}



	//public function saveLicense(){
	//	print_r($_REQUEST);
	//	
	//	$serial = $_REQUEST['serial'];
	//	$host = $_REQUEST['host'];
	//	
	//	$d = new Delegate();
	//	
	//	$DIAGRAMO = $d->settingsGetByKeyNative('DIAGRAMO');
	//	
	//	$url = $DIAGRAMO . "/dcentral/activator.php?serial=$serial&host=$host";
	//	#print 'URL: ' . $url;
	//	
	//	
	//	$license = get($url);
	//	
	//	#print 'License: ' . $license;
	//	#exit();
	//	
	//	$d->settingsSaveNative('LICENSE', $license);
	//	
	//	addMessage("App activated");
	//	redirect('../license.php');
	//	exit(0);
	//}



	//public function editUserExe(){
	//		
	//	if (!is_numeric($_SESSION['userId'])) {
	//		print("Wrong way");
	//		exit();
	//	}
	//	
	//	if(!is_numeric($_REQUEST['userId'])){
	//		print("Wrong user");
	//		exit();
	//	}
	//	
	//	#exit('here');
	//	$d = new Delegate();
	//	
	//	$loggedUser = $d->userGetById($_SESSION['userId']);
	//	
	//	
	//	
	//	if(errors()){
	//		redirect('../users.php');
	//		exit(0);
	//	}
	//	
	//	$user = $d->userGetById($_REQUEST['userId']);
	//	
	//	if(strlen($_REQUEST['password']) > 0 ){
	//		$user->password = md5($_REQUEST['password']) ;
	//		if($d->userUpdate($user)){
	//			addMessage("User updated");
	//		}
	//		else{
	//			addError("User NOT updated");
	//		}	
	//	}
	//	
	//	
	//	redirect('../users.php');
	//}


	public function acceptInvitationExe(){
		if (!is_numeric($_SESSION['userId'])) {
			print("Wrong way");
			exit();
		}
		if( !isset ($_REQUEST['invitationId']) ){
			print("Wrong Invitation");
			exit();
		}
		
		$d = new Delegate();
		$loggedUser = $d->userGetById($_SESSION['userId']);
		$invitation = $d->invitationGetById($_REQUEST['invitationId']);
		
		
		if($invitation->email == $loggedUser->email){ //a match made in stars...how lovely :)
			
			
			$diagram = $d->diagramGetById($invitation->diagramId);
					
			//create userdiagram
			$userdiagram = new Userdiagram();
			$userdiagram->diagramId = $diagram->id;
			$userdiagram->invitedDate = $invitation->createdDate;
			$userdiagram->level = Userdiagram::LEVEL_EDITOR;
			$userdiagram->status = Userdiagram::STATUS_ACCEPTED;
			$userdiagram->userId = $loggedUser->id;

			//store it in DB
			$d->userdiagramCreate($userdiagram);

			//delete invitation
			$d->invitationDelete($invitation->id);
			
			addMessage("Invitation accepted");
			redirect('../editor.php?diagramId=' . $diagram->id);
		}
		else{
			
			addError("Nope");
			redirect('../myDiagrams.php');
		}
		
		
	}
		
	public function declineInvitationExe(){
		if (!is_numeric($_SESSION['userId'])) {
			print("Wrong way");
			exit();
		}
		if( !isset ($_REQUEST['invitationId']) ){
			print("Wrong Invitation");
			exit();
		}
		
		$d = new Delegate();
		$loggedUser = $d->userGetById($_SESSION['userId']);
		$invitation = $d->invitationGetById($_REQUEST['invitationId']);
		
		
		if($invitation->email == $loggedUser->email){ //a match made in stars...how lovely :)
			$d->invitationDelete($invitation->id);
			addMessage("Invitation declined.");
		}
		
		redirect('../myDiagrams.php');
	}

	public function save_diagram()
	{
		$delegate = new Delegate();
		$WEBADDRESS = $delegate->settingsGetByKeyNative('WEBADDRESS');

		return view('diagramo::save_diagram', compact('WEBADDRESS'));
	}

	public function work_flow_view($diagramId = null)
	{
		$delegate = new Delegate();

		$diagram = $delegate->diagramGetById($diagramId);
		$diagramdata = $delegate->diagramdataGetByDiagramIdAndType($diagramId, Diagramdata::TYPE_CSV);



		return view('diagramo::work_flow_view', compact('diagramId','diagram','diagramdata'));
	}

	public function png($diagramId = null)
	{
		if (is_numeric($diagramId)) {
		    $d = new Delegate();
		    
		    $diagram = $d->diagramGetById($diagramId);    
		    $d->close();
		    
		    if ( (isset($_SESSION['userId']) && is_numeric($_SESSION['userId'])) || $diagram->public) {
		        // $filePath = dirname(__FILE__) . '/data/diagrams/' . $diagramId . '.png';
		        $filePath = dirname(__FILE__) . '/../../../../public/diagramo_assets/data/diagrams/' . $diagramId . '.png';
		        // echo $filePath;exit;


		        if (file_exists($filePath)) {
		            $fileSize = filesize($filePath);

		            $fh = fopen($filePath, 'rb');
		            $data = fread($fh, $fileSize);
		            fclose($fh);


		            header('Content-Type: image/png');
		            header('Content-Transfer-Encoding: binary');
		            header('Expires: 0');
		            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		            // header('Content-Disposition: attachment; filename="' . $diagramId . '.png' . '"');
		            header('Pragma: public');
		            header('Content-Length: ' . $fileSize);

		            print $data;
		            //print 'Alex';
		            flush();
		        } else {
		            print "No file";
		        }
		    }
		}

	}

}

	









