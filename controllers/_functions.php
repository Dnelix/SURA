<?php
##########################################
# BACK-END FUNCTIONS
##########################################
//RESPONSES
  // 500 - server error
  // 400 - invalid data
  // 401 - unauthorized
  // 404 - not found
  // 405 - invalid request
  // 409 - data conflict
  // 200 - success
  // 201 - record created

function responseServerException($e, $message){
  error_log("Connection error - ".$e, 0);

  $response = new Response();
  $response -> setHttpStatusCode(200); //change back to 500 for prod.
  $response -> setSuccess(false);
  //$response -> addMessage($message);
  $response -> addMessage($e); //optional. Remove for prod.
  $response -> send();
  exit(); 
}

function sendResponse($code, $state, $message = null, $returnData = null, $toCache = false){
  $code = 200; //Just to stop browser suppression. remove this line in production.
  $response = new Response();
  $response -> setHttpStatusCode($code);
  $response -> setSuccess($state);
  $response -> toCache($toCache);
  (($message !== null) ? $response -> addMessage($message) : false);
  (($returnData !== null) ? $response -> setData($returnData) : false);
  $response -> send();
  exit();
}

// AUTH
function generateTokens() {
  $accesstoken = base64_encode((bin2hex(openssl_random_pseudo_bytes(24))).time());
  $refreshtoken = base64_encode((bin2hex(openssl_random_pseudo_bytes(16))).time());
  $accesstoken_expiry = 12000; //20mins
  $refreshtoken_expiry = 1209600; //14 days (2 weeks)
  
  return array(
    'access_token' => $accesstoken,
    'refresh_token' => $refreshtoken,
    'access_token_expiry' => $accesstoken_expiry,
    'refresh_token_expiry' => $refreshtoken_expiry
  );
}

// JSON
function validateJsonRequest() {
  if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
    sendResponse(400, false, 'Content type header not set to JSON');
    exit();
  }
  //else get the json data
  $rawPostData = file_get_contents('php://input');
  if (!$jsonData = json_decode($rawPostData)) {
    sendResponse(400, false, 'Invalid JSON data in request body');
    exit();
  }

  return $jsonData;
}

// Get JSON as either php object(false) or array(true)
function retrieveDataFrom($url='php://input', $stat = false){
  if($stat === true){
    return json_decode(file_get_contents($url), true);
  } else {
    return json_decode(file_get_contents($url));
  }
}

// Random Password Generator
function getRandomPassword($length = 12) {
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
  $password = substr(str_shuffle($chars), 0, $length);
  return $password;
}

// Get User Device
function getuserDevice($userAgent){
  $browser = '';

  switch (true) {
    case (strpos($userAgent, 'MSIE') !== false):
      preg_match('/MSIE\s([0-9]+)/', $userAgent, $matches);
      $browser = 'Internet Explorer - ' . $matches[1];
      break;
    case (strpos($userAgent, 'Firefox') !== false):
      preg_match('/Firefox\/([0-9]+)/', $userAgent, $matches);
      $browser = 'Firefox - ' . $matches[1];
      break;
    case (strpos($userAgent, 'Chrome') !== false):
      preg_match('/Chrome\/([0-9]+)/', $userAgent, $matches);
      $browser = 'Chrome - ' . $matches[1];
      break;
    case (strpos($userAgent, 'Safari') !== false):
      preg_match('/Version\/([0-9]+)/', $userAgent, $matches);
      $browser = 'Safari - ' . $matches[1];
      break;
    case (strpos($userAgent, 'Opera') !== false):
      preg_match('/(OPR\/[\d.]+)/', $userAgent, $matches);
      $browser = 'Opera - ' . $matches[1];
      break;
    default:
      $browser = substr($userAgent, 0, 20); //list first 20 characters in the string
  }

  return $browser;
}

//* Make JSON API call: Opt 1&2
function callJSONAPI($url, $data=[], $accesstoken=null, $type='POST'){
  $ch = curl_init(); //initialize a session
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Authorization: Bearer ' . $accesstoken
  ));
  if (curl_error($ch)) {
    $response = curl_error($ch);
  } else {
    $response = curl_exec($ch);
  }

  return $response;
  curl_close($ch);
}
function callJSONAPI2($url, $accesstoken=null){
  $postFields = '{"from":{"email":"mailtrap@rinotradefx.com","name":"Mailtrap Test"},"to":[{"email":"domainbuy101@gmail.com"}]}'; // for test. This should be dynamic array

  $curl = curl_init();
  curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $postFields,
      CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer ' . $accesstoken,
          'Content-Type: application/json'
      ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return $response;
}

//Set JSON Request Headers
function setJSONRequestHeaders($jsonData){
  $headers = array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
  );

  // Create the stream context with headers
  $context = stream_context_create(array(
      'http' => array(
          'method' => 'POST',
          'header' => $headers,
          'content' => $jsonData
      )
  ));

  return $context;
}

//* Send data to controller
function sendToController($data, $controllerURL){
  $jsonData = json_encode($data);               // Convert the data to JSON
  $context = setJSONRequestHeaders($jsonData);  // set request headers

  global $c_website;
  $fileURL = $c_website . $controllerURL;

  $feedback = file_get_contents($fileURL, false, $context);  // Send data to controller file
  //$feedback = file_get_contents($fileURL . '?data=' . urlencode($jsonData));  //GET method

  //return $feedback;
  $cleanOutput = json_decode($feedback);
  return $cleanOutput->messages[0];
}

// Send any Email
function sendEmail($type, $subject, $to_mail, $to_name='', $message='', $sender=''){
    $data = array(
      'type'    => $type,
      'subject' => $subject,
      'to_mail' => $to_mail,
      'to_name' => $to_name,
      'message' => $message,
      'sender'  => $sender,
    );

    $result = sendToController($data, 'controllers/_email.php');

    return $result;
}

// Manage time displays
function time_elapsed_string($time) {
  $diff = time() - $time;
  
  if ($diff < 60) {
      return 'just now';
  } elseif ($diff < 3600) {
      $mins = floor($diff / 60);
      $suffix = ($mins > 1) ? 'mins' : 'min';
      return $mins . ' ' . $suffix . ' ago';
  } elseif ($diff < 86400) {
      $hours = floor($diff / 3600);
      $suffix = ($hours > 1) ? 'hours' : 'hour';
      return $hours . ' ' . $suffix . ' ago';
  } else {
      $days = floor($diff / 86400);
      $suffix = ($days > 1) ? 'days' : 'day';
      return $days . ' ' . $suffix . ' ago';
  }
}

function calculateTimeLeft($futureDate) {
  $currentTime = time();
  $futureTime = strtotime($futureDate); //this is not working. Try using time()
  
  $timeDifference = $futureTime - $currentTime;
  
  if ($timeDifference < 0) {
    return "Date has already passed.";
  }
  
  $secondsInADay = 24 * 60 * 60;
  $secondsInAWeek = 7 * $secondsInADay;
  $secondsInAMonth = 30 * $secondsInADay;
  
  $daysLeft = floor($timeDifference / $secondsInADay);
  $hoursLeft = floor(($timeDifference % $secondsInADay) / 3600);
  $weeksLeft = floor($timeDifference / $secondsInAWeek);
  $monthsLeft = floor($timeDifference / $secondsInAMonth);
  
  $result = "";
  
  if ($monthsLeft > 0) {
    $result .= "$monthsLeft month" . ($monthsLeft > 1 ? "s" : "") . " ";
  }
  
  if ($weeksLeft > 0) {
    $result .= "$weeksLeft week" . ($weeksLeft > 1 ? "s" : "") . " ";
  }
  
  if ($daysLeft > 0) {
    $result .= "$daysLeft day" . ($daysLeft > 1 ? "s" : "") . " ";
  }
  
  if ($hoursLeft > 0) {
    $result .= "$hoursLeft hour" . ($hoursLeft > 1 ? "s" : "");
  }
  
  if ($result === "") {
    $result = "Less than an hour";
  }
  
  return $result . " to go";
}

// add any number of days to a given date
function addtoDate($givenDate, $num, $unit='day') {

  $secondsInADay = 24 * 60 * 60;

  if ($unit === 'week'){
    $secondsInAWeek = 7 * $secondsInADay;
    $totalsecs = $secondsInAWeek;
  } else if ($unit === 'month'){
    $secondsInAMonth = 30 * $secondsInADay;
    $totalsecs = $secondsInAMonth;
  } else {
    $totalsecs = $secondsInADay;
  }

  $daysToAdd = $num * $totalsecs;
  $dateString = time($givenDate) + $daysToAdd; //this actually just adds to the current date. How do we evaluate for a given date?
  $newDate = date('d/m/Y H:i', $dateString);
  
  return $newDate;
}

//* Process Image Upload
function processImageUpload($inputName, $targetDirectory) {
  // Check if the file was uploaded without errors
  if ($_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
    $tempFilePath = $_FILES[$inputName]['tmp_name'];
    $originalFileName = $_FILES[$inputName]['name'];

    // Generate a unique filename to prevent overwriting existing files
    $uniqueFileName = uniqid() . '_' . $originalFileName;

    // Build the target path for storing the uploaded image
    $targetPath = $targetDirectory . '/' . $uniqueFileName;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($tempFilePath, $targetPath)) {
      // File successfully uploaded
      return $targetPath;
    } else {
      // Error while moving the uploaded file
      return null;
    }
  } else {
    // Error in the uploaded file
    return null;
  }
}

##########################################
# DATABASE CALLS
##########################################
function getuserDataById($data, $id){
  global $c_website;
  $users = retrieveDataFrom($c_website.'controllers/users.php?userid='.$id);
  $userdata = (($users -> data !== null) ? $users->data->$data : null);
  return $userdata;
}

//GET COUNTRIES, STATES & CITIES
function country_state_city(){
  global $c_website;
  return retrieveDataFrom($c_website.'models/databases/city-state-country.json', true); //read file from JSON DB
}

function getCountries(){
  $data = country_state_city();
  $country_list = array_column($data['Countries'], 'CountryName');
  return $country_list;
}

function getStates($targetCountry){
  $data = country_state_city();
  $states = [];
  foreach ($data['Countries'] as $country) {
      if ($country['CountryName'] === $targetCountry) {
          $states = array_column($country['States'], 'StateName');
          break;
      }
  }
  return $states;
}

function getCities($targetState){
  $data = country_state_city();
  $cities = [];
  foreach ($data['Countries'] as $country) {
    foreach ($country['States'] as $state) {
      if ($state['StateName'] === $targetState) {
          $cities = $state['Cities'];
          break;
      }
    }
  }
  return $cities;
}

##########################################
# FRONT-END FUNCTIONS
##########################################
function showMsg($type='', $msg=''){
    if ($type === 'danger'){ $icon = "fa-lightbulb"; $title = "ERROR!"; }
    if ($type === 'warning'){ $icon = "fa-chart-pie"; $title = "WARNING!"; }
    if ($type === 'info'){ $icon = "fa-mail-bulk"; $title = "INFO"; }
    if ($type === 'primary'){ $icon = "fa-lightbulb"; $title = "NOTE"; }
    if ($type === 'success'){ $icon = "fa-check-square"; $title = "SUCCESS!"; }

    $html = '
    <div class="alert alert-dismissible bg-light-'. $type .' d-flex flex-column flex-sm-row p-5 mb-10">
        <i class="fas '. $icon .' fs-2x text-'.$type.' me-5 mb-5 mb-sm-0"></i>
        <div class="d-flex flex-column pe-0 pe-sm-10">
            <h4 class="fw-bold">' . $title . '</h4>
            <span>' . $msg . '</span>
        </div>
        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
            <i class="fa fa-times text-'.$type.'"></i>
        </button>
    </div>
    ';
    echo $html;
}

function getInitials($fullname) {
  $names = explode(" ", $fullname);
  $last_name = $names[0];

  if (count($names) >= 2) {
    $first_name = $names[1];
    // Get the first letter of each name and concatenate them
    $initials = substr($last_name, 0, 1) . substr($first_name, 0, 1);
  } else{
    $initials = substr($last_name, 0, 1);
  }
  
  return strToUpper($initials);
}

function showCustomerIcon($id='', $initials='', $active='0', $size='small'){
    switch ($id % 5) {
        case 0:
          $color_type = 'danger';
          break;
        case 1:
          $color_type = 'warning';
          break;
        case 2:
          $color_type = 'success';
          break;
        case 3:
          $color_type = 'primary';
          break;
        default:
          $color_type = 'info';
          break;
      }
    
    $onlineIcon = "";
    if($active=='1'){ $onlineIcon = '<div class="bg-'. $color_type .' position-absolute h-8px w-8px rounded-circle translate-middle start-100 top-100 ms-n1 mt-n1"></div>';}

    if($size == 'small'){
      $html = '<div class="symbol symbol-35px symbol-circle"><span class="symbol-label bg-light-'. $color_type .' text-'. $color_type .' fw-bold">'. $initials .'</span></div>'. $onlineIcon ;
    } else if($size == 'large'){
      $html = '<div class="symbol symbol-100px symbol-circle"><span class="symbol-label bg-light-'. $color_type .' text-'. $color_type .' fw-bold"><font size="36pt">'. $initials .'</font></span></div>'. $onlineIcon ;
    }

    echo $html;
}

function showProjectIcon($category, $size='small'){
    switch ($category) {
      case 'image':
        $color_type = 'danger';
        $icon = '<img src="assets/media/stock/ecommerce/211.gif" class="w-100px ms-n1 me-1" alt="">';
        break;
      case 'top':
        $color_type = 'danger';
        $icon = 'las la-tshirt';
        break;
      case 'trouser':
        $color_type = 'warning';
        $icon = 'las la-street-view';
        break;
      case 'skirt':
        $color_type = 'success';
        $icon = 'fa fa-vest';
        break;
      case 'gown':
        $color_type = 'primary';
        $icon = 'las la-restroom';
        break;
      case 'suit':
        $color_type = 'primary';
        $icon = 'fa fa-vest';
        break;
      case 'headwear':
        $color_type = 'primary';
        $icon = 'las la-hat-cowboy-side';
        break;
      case 'footwear':
        $color_type = 'primary';
        $icon = 'las la-shoe-prints';
        break;

      default:
        $color_type = 'info';
        $icon = 'las la-user-tie';
        break;
    }
    
    if ($size === 'small'){
      $html = '<div class="symbol symbol-50px w-50px bg-light"><span class="symbol-label bg-light-'.$color_type.'"><i class="'.$icon.' text-'.$color_type.' fs-3x"></i></span></div>';
    } else {    
      $html = '<div class="symbol symbol-70px symbol-circle me-5"><span class="symbol-label bg-light-'.$color_type.'"><i class="'.$icon.' text-'.$color_type.' fs-5x"></i></span></div>';
    }

    echo $html;
}

function showStatus($status){
  $status =  ucWords($status);
  switch ($status) {
    case 'Not Started':
      $state = 'light-warning';
      break;
    case 'In Progress':
      $state = 'light-primary';
      break;
    case 'Delayed':
      $state = 'light-danger';
      break;
    case 'Completed':
      $state = 'light-success';
      break;
    case 'Active':
      $state = 'success';
      break;
    case 'Inactive':
      $state = 'secondary';
      break;
    default:
      $state = 'warning';
      break;
  }

  $html = '<span class="badge badge-'.$state.' fw-bolder me-auto px-4 py-3">'.$status.'</span>';

  echo $html;
}

function readableDateTime($timeString, $show=null) {
  $dateTime = DateTime::createFromFormat('d/m/Y H:i', $timeString);
  if (!$dateTime) {
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $timeString);
  }

  $formattedDate = $dateTime->format('jS M Y'); // Format date with ordinal suffix
  $formattedTime = $dateTime->format('g:ia'); // Format time with 12-hour clock and am/pm
  // Combine formatted date and time
  if ($show==='dateonly'){
    $formattedDateTime = $formattedDate;
  } else if ($show==='timeonly'){
    $formattedDateTime = $formattedTime;
  } else {
    $formattedDateTime = $formattedDate . ' at ' . $formattedTime;
  }

  return $formattedDateTime;
}

function displayLoadingIcon($btn_txt){
  $html = '
  <span class="indicator-label">' . $btn_txt . '</span>
  <span class="indicator-progress">Please wait... 
  <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
  ';

  echo $html;
}

function limit_text($text, $limit) {
  if (str_word_count($text, 0) > $limit) {
      $words = str_word_count($text, 2);
      $pos = array_keys($words);
      $text = substr($text, 0, $pos[$limit]) . '...';
  }
  return $text;
}

function formatNumber($number) {
  $suffixes = array('', 'K', 'M', 'B', 'T'); // Suffixes for thousand, million, billion, trillion, etc.

  $suffixIndex = 0;
  while ($number >= 1000 && $suffixIndex < count($suffixes) - 1) {
      $number /= 1000;
      $suffixIndex++;
  }

  $formattedNumber = number_format($number, 3);   // Format number to maximum three decimal places

  // Remove trailing zeroes and decimal point if not needed
  $formattedNumber = rtrim($formattedNumber, '0');
  $formattedNumber = rtrim($formattedNumber, '.');

  // Append the appropriate suffix
  $formattedNumber .= $suffixes[$suffixIndex];

  return $formattedNumber;
}

?>