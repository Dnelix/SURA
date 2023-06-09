<?php
    // a safe action is to delay a session request by 1 sec to slow down hacking attempts
    sleep(1); //delay by 1 sec

    //check if the content is JSON and retrieve
    $jsonData = validateJsonRequest();

    //data validation checks. We are only sending username and password to create a session
    if(!isset($jsonData->username) || !isset($jsonData->password)){
        $message = (!isset($jsonData->username) ? 'Email/Username not set' : false);
        $message .= (!isset($jsonData->password) ? 'Password not set' : false);
        sendResponse(400, false, $message);
        exit(); 
    }

    //2. check if the strings are empty or have values above the DB limits
    if(strlen($jsonData->username) < 1 || strlen($jsonData->username) > 100 || strlen($jsonData->password) < 1 || strlen($jsonData->password) > 255){
        
        $message = (strlen($jsonData->username) < 1 ? 'Email/Username cannot be blank' : false);
        $message .= (strlen($jsonData->username) > 100 ? 'Email/Username cannot be greater than 100 characters' : false);
        $message .= (strlen($jsonData->password) < 1 ? 'Password cannot be blank' : false);
        $message .= (strlen($jsonData->password) > 255 ? 'Password cannot be greater than 255 characters' : false);
        
        sendResponse(400, false, $message);
        exit(); 
    }

    //3. Check if user exists
    try{

        $username = $jsonData->username;
        $password = $jsonData->password;
        $all = 'id,username,password,email,active,lastlogin,loginattempts,role';

        $query = $writeDB -> prepare('SELECT '. $all .' FROM tbl_users WHERE username = :username OR email = :email');
        $query -> bindParam(':username', $username, PDO::PARAM_STR);
        $query -> bindParam(':email', $username, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query -> rowCount();

        if($rowCount === 0 || $rowCount > 1){
            sendResponse(401, false, 'User not found or invalid!');
        }
        
        //no need for while statement since it's only going to be a single record
        $row = $query -> fetch(PDO::FETCH_ASSOC);

        $ret_id = $row['id'];
        $ret_username = $row['username'];
        $ret_password = $row['password'];
        $ret_email = $row['email'];
        $ret_active = $row['active'];
        $ret_loginattempts = $row['loginattempts'];
        $ret_lastlogin = $row['lastlogin'];
        $ret_role = $row['role'];

        // Hash Password
        $hash_pass = password_hash($password, PASSWORD_DEFAULT); //hash using the standard PHP hashing

        // Data Validations - check if user is still active
        if($ret_active !== "1"){    
            sendResponse(401, false, 'User account is not active!');
        }

        // if login attempts have exceeded 3...
        if($ret_loginattempts >= $max_loginattempts){
            sendResponse(401, false, 'Number of attempts exceeded! User account have been locked out.');
        }

        // validate the password (using the password_verify() functn)
        if(!password_verify($password, $ret_password)) { 
            // increment login attempts
            $query = $writeDB->prepare('UPDATE tbl_users set loginattempts = loginattempts+1 WHERE id = :id');
            $query -> bindParam(':id', $ret_id, PDO::PARAM_INT);
            $query -> execute();

            // send response
            sendResponse(401, false, 'Password is incorrect! Your account will be locked if you enter an incorrect password '. $max_loginattempts .' times');
        }

        //else login is successful
        // generate accesstoken and refreshtoken
        $tokens = generateTokens();
        $accessToken = $tokens['access_token'];
        $refreshToken = $tokens['refresh_token'];
        $accessTokenExpiry = $tokens['access_token_expiry'];
        $refreshTokenExpiry = $tokens['refresh_token_expiry'];
        
        //end of checks
    }
    catch (PDOException $e){
        responseServerException($e, 'Problem with logging in. Please try again');
    }

    //use a separate try and catch for the login updates so that we can perform a rollback in case of failure.
    // Three db functions are involved in the rollback: beginTransaction(); commit(); rollback();
    try{

        $writeDB -> beginTransaction(); //rollback to this point if an error is found

        $lastlogin = date('d/m/Y H:i'); //current date&time
        $user_ip = $_SERVER['REMOTE_ADDR']; //user IP address
        $user_agent = getuserDevice($_SERVER['HTTP_USER_AGENT']); //user device

        $query = $writeDB -> prepare('UPDATE tbl_users SET loginattempts = 0, lastlogin = STR_TO_DATE(:lastlogin, '. $write_dateformat .') WHERE id = :id');
        $query -> bindParam(':id', $ret_id, PDO::PARAM_INT);
        $query -> bindParam(':lastlogin', $lastlogin, PDO::PARAM_STR);
        $query -> execute();

        //Insert login record into sessions table
        //we use the date_add() SQL functn to get the current time and add to it the number of seconds before expiry. I.e.: date_add(currentTime, INTERVAL xxx SECOND)
        $query = $writeDB -> prepare('INSERT INTO tbl_sessions (userid, login_time, accesstoken, a_tokenexpiry, refreshtoken, r_tokenexpiry, device, ip) 
            VALUES (:id, STR_TO_DATE(:logintime, '. $write_dateformat .'), :accesstoken, date_add(NOW(), INTERVAL :a_tokenexpiry SECOND), :refreshtoken, date_add(NOW(), INTERVAL :r_tokenexpiry SECOND), :device, :ip)');
        $query -> bindParam(':id', $ret_id, PDO::PARAM_INT);
        $query -> bindParam(':logintime', $lastlogin, PDO::PARAM_STR);
        $query -> bindParam(':accesstoken', $accessToken, PDO::PARAM_STR);
        $query -> bindParam(':a_tokenexpiry', $accessTokenExpiry, PDO::PARAM_INT);
        $query -> bindParam(':refreshtoken', $refreshToken, PDO::PARAM_STR);
        $query -> bindParam(':r_tokenexpiry', $refreshTokenExpiry, PDO::PARAM_INT);
        $query -> bindParam(':device', $user_agent, PDO::PARAM_STR);
        $query -> bindParam(':ip', $user_ip, PDO::PARAM_STR);
        $query -> execute();

        $lastSessionID = $writeDB -> lastInsertId();

        $writeDB -> commit(); //if everything is fine so far, commit to database.

        $rowCount = $query -> rowCount();

        if($rowCount === 0){
            sendResponse(401, false, 'Some error occurred with login');
        }

        // store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = intval($lastSessionID);
        $_SESSION["userid"] = $ret_id;
        $_SESSION["username"] = $ret_username;
        $_SESSION["accesstoken"] = $accessToken;

        $returnData = array();
        $returnData['session_id'] = intval($lastSessionID); //cast as integer
        $returnData['accesstoken'] = $accessToken;
        $returnData['access_token_expires_in'] = $accessTokenExpiry;
        $returnData['refreshtoken'] = $refreshToken;
        $returnData['refresh_token_expires_in'] = $refreshTokenExpiry;
        $returnData['user_device'] = $user_agent;
        $returnData['user_ip'] = $user_ip;

        sendResponse(201, true, 'User successfully logged in', $returnData);
    }
    catch(PDOException $e){
        
        $writeDB -> rollBack(); // rollback to beginning and return the db to former values if any error is caught in the processing

        responseServerException($e, 'There was an issue with logging in. Please try again');
    
    }

?>