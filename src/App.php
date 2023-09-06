<?php
/**
 * Copyright (C) 2023 Paul W. Lane <kc9eye@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace kc9eye;

define('DB_DCS','YOUR DATABASE CONNECTION STRING');
define('SESSION_NAME','247eb060-0cb7-4972-b45b-3d3f85488265');
define('AUTH_CALLBACK','last_request');
define('AUTH_FAILED','not_valid');
define('ROUTE_SEPARATOR','sigint');
define('HOST_ADDR','');
define('ROUTE_SWITCH','sigint');
define('NOBUFF',false);
define('DEBUG',false);
define('VERSION',1.0);

//Secure the session cookie
ini_set("session.use_only_cookies","1");
ini_set("session.cookie_httponly","1");
ini_set("session.cookie_secure","1");
ini_set("session.cookie_samesite","strict");
ini_set("session.use_strict_mode","1");

//Start the output buffer
ob_start("kc9eye\minOutput",0,PHP_OUTPUT_HANDLER_STDFLAGS);

//Set the error and exception handling
set_error_handler('kc9eye\errorHandler');
set_exception_handler('kc9eye\exceptionHandler');

//Set the session cookie and start the session
$params = session_get_cookie_params();
session_set_cookie_params($params['lifetime'],'/',$params['domain'],true,true);
session_name(SESSION_NAME);
session_start();

//Verify the session is valid and not hijacked
if (validSession())
{
    if (sessionHijacked())
    {
        $_SESSION = array();
        $_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['UserAgent'] = $_SERVER['HTTP_USER_AGENT'];
        regenerateSession();
    }
    elseif (rand(1,100) <= 10)
    {
        regenerateSession();
    }
}
else
{
    $_SESSION = array();
    session_destroy();
    session_start();
}

//Route the request
if (isset($_REQUEST[ROUTE_SWITCH]))
{
    $request = decodeQuery($_REQUEST[ROUTE_SWITCH]);
    if ($request['class'] === 'FILE')
    {
        require($request['method']);
        exit();
    }
    else
    {
        $class = 'kc9eye\\'.$request['class'];
        $method = $request['method'];
        //Check if the class exists
        if (!class_exists($class,true))
        {
            WebFunctions::notFound();
            exit();
        }
        //Check if the method exists
        else
        {
            if ($method === "__construct")
            {
                new $class();
                exit();
            }
            elseif (array_search($method,get_class_methods($class)) !== false)
            {
                (new $class())->$method();
                exit();
            }
            else
            {
                WebFunctions::notFound();
                exit();
            }
        }
        exit();
    }
}
elseif (WebFunctions::isValidUser())
{
    if (isset($_SESSION[AUTH_CALLBACK]))
    {
        header('Location: '.$_SESSION[AUTH_CALLBACK]);
        exit();
    }
    else
    {
        new Landing();
        exit();
    }

}
else
{
    new Login();
    exit;
}

/**
 * Encodes the given class and method into a valid url switch
 * @param String $class The class name to instantiate, if the class name is 'FILE',
 * it is assumed the `$method` parameter is a file path to require, execution then falls to that file.
 * @param String $method The method to invoke, else the '__construct' method of the given class is used; unless
 * the `$class` parameter has the value `FILE`
 * @param String $q_string is any additional query string required to pass. The proceeding `&` is added prior to '$q_string'
 * @return String A valid url query string to be decoded with decodeQuery()
 */
function encodeQuery($class,$method = '__construct',$q_string = null)
{
    $query = "{$class}".ROUTE_SEPARATOR."{$method}";
    if (!is_null($q_string)) return HOST_ADDR.'/?'.ROUTE_SWITCH.'='.base64_encode($query).'&'.$q_string;
    else return HOST_ADDR.'/?'.ROUTE_SWITCH.'='.base64_encode($query);
}

/**
 * Decodes url query based on one encoded with encodeQuery
 * @param String $query The encoded query string value
 * @return Array An indexed array containing the 'class' name and 'method' encoded in the query
 */
function decodeQuery($query)
{
    $query = base64_decode($query);
    if (strpos($query,ROUTE_SEPARATOR) === false)
    {
        $old = ob_get_clean();
        unset($old);
        WebFunctions::notFound();
        exit();
    }
    list($class,$method) = explode(ROUTE_SEPARATOR,$query);
    if (empty($class) || empty($method))
    {
        $old = ob_get_clean();
        unset($old);
        WebFunctions::notFound();
        exit();
    }
    return array('class'=>$class,'method'=>$method);
}

/**
 * Determines whether the session is valid
 *
 * @return Boolean True if valid, false otherwise
 */
function validSession()
{
    if (isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES'])) return false;
    if (isset($_SESSION['EXPIRES']) && ($_SESSION['EXPIRES'] < time())) return false;
    return true;
}

/**
 * Tests whether the session has been hijacked
 *
 * @return Boolean True if the session indicates a hijack, false otherwise
 */
function sessionHijacked()
{
    if (!isset($_SESSION['IPaddress']) || !isset($_SESSION['UserAgent'])) return true;
    if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR']) return true;
    if ($_SESSION['UserAgent'] != $_SERVER['HTTP_USER_AGENT']) return true;
    return false;
}

/**
 * Regenerates the session, keeping the last session for 10 seconds, but making it obsolete for further use.
 *
 * @return Void
 */
function regenerateSession()
{
    if (isset($_SESSION['OBSOLETE'])) return;
    $_SESSION['OBSOLETE'] = true;
    $_SESSION['EXPIRES'] = time() + 10;
    session_regenerate_id(false);
    $new = session_id();
    session_write_close();
    session_id($new);
    session_start();
    $_SESSION['regenerated'] = isset($_SESSION['regenerated']) ? $_SESSION['regenerated'] + 1: 1;
    unset($_SESSION['OBSOLETE']);
    unset($_SESSION['EXPIRES']);
}

/**
 * All script output first runs through this minimizer
 * @param String The buffer
 * @return String The minimized buffer
 */
function minOutput($buff)
{
    global $nobuff;
    if (NOBUFF)
    {
        return $buff;
    }
    elseif ($nobuff)
    {
        return $buff;
    }
    else
    {
        $nojunk = str_replace(["\n","\r","\f","\t"],'',$buff);
        $notagspace = preg_replace("/>(\s+)</","><",$nojunk);
        return $notagspace;
    }
}

/**
 * The application error handler
 *
 * Determines oh the error is handled based on the error code and the debug constant
 *
 * Operation depends on the constant `DEBUG`, if set to true the error is output to the buffer.
 * Also, further control is dependant on the constant `LOG_file`, if set to the string database
 * the error is logged to the database handle, otherwise `LOG_FILE` is assumed to be a file path
 * with writing permissions to log the error. *
 * @return Boolean
 */
function errorHandler ($code,$msg,$file,$line,$trace = [])
{
    $log =
        "CODE: {$code}\n".
        "MSG: {$msg}\n".
        "FILE: {$file}\n".
        "LINE: {$line}\n".
        "TRACE: ".print_r($trace,true)."\n--\n";

    if (DEBUG)
    {
        $buff = ob_get_clean();
        unset($buff);
        echo "<pre>{$log}</pre>";
        exit($code);
    }
    else
    {
        $insert = [
            ':id'=>uniqid(),
            ':code'=>$code,
            ':msg'=>(is_array($msg)) ? print_r($msg,true) : $msg,
            ':file'=>$file,
            ':line'=>$line,
            ':trace'=>print_r($trace,true)
        ];
        if (!DB::Insert("",$insert))
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            return true;
        }
        if ($code < 512) displayErrorScreen($code,$insert[':id']);
        return true;
    }
    return true;
}

/**
 * Handles exceptions by porting them to the error handler
 * @return Void
 */
function exceptionHandler($e)
{
    return \kc9eye\errorHandler(
        $e->getCode(),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTrace()
    );
}

/**
 * Self contained error display page for errors coded 511 and lower
 * @param Integer $code THe error code
 * @param String $errid The recorded errors ID for lookup
 * @return Void
 */
function displayErrorScreen($code,$errid)
{
    $old = ob_get_clean();
    unset($old);
    echo
"<!DOCTYPE html>
<html>
<head>
    <title>Fatal Exception</title>
    <style>
        html, body, .container {
            background-color:blue;
            color:white;
            font-size:22px;
            height:100%;
        }
        .container {
            position: relative;
        }
        .centered {
            position:absolute;
            top:33%;
            margin-left:33%;
            margin-right:33%;
        }
        a {
            color:white;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='centered'>
            <h1>Fatal Exception</h1>
            <b>ID:</b>&nbsp;{$errid}<br />
            <p>
                A fatal exception has occurred at: 0x".hexdec(time())."
                and the application can not continue.
                Please use the above reference number when contacting the
                adminstrator about the error. <br />
                <b>END OF LINE</b>
            </p>
            Contact your application adminstrator.
        </div>
    </div>
</body>
</html>";
    exit($code);
}
