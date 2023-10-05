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
namespace Application;
class ViewWidgets {
    public static function header($title = "TITLE HERE", Array $inserts = [])
    {
        echo
        '<!DOCTYPE html>
        <html lang="EN-US">
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="mobile-web-app-capable" content="yes" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">';
        echo '<title>'.$title.'</title>';
        if (!empty($inserts)) {
            foreach($inserts as $insert) {
                echo $insert,"\n";
            }
        }
        echo '</head>';
    }

    public static function banner()
    {
        echo
        '<body>
            <div class="container-fluid">
                <div class="row bg-dark text-light border-bottom border-info p-2">
                    <div class="col" style="min-height:10vh">
                        <a href="/" style="text-decoration:none" class="text-light"><h1>Application Title</h1></a>
                        <small><i>Catchy Phrase</i></small>
                    </div>
                </div>
            </div>';
    }

    public static function navbar()
    {
        $user = new User($_SESSION[VALID_USER]);
        echo
        '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>&#160;',$user->GetUsername(),'
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="',encodeQuery("WebFunctions","logout"),'">Log Out</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">Replace Me</a></li>
                    </ul>
                </div>
            </div>
        </nav>';
    }

    public static function footer(Array $inserts = [])
    {
        echo
        '<div class="container-fluid">
            <div class="row bg-dark text-light border-top border-info" style="min-height:6vh">
                <div class="col text-center">
                    &#169;',COPYRIGHT_YEAR,'&#160;<i>',COPYRIGHT,'</i>&#160;-&#160;Version:&#160;<i>',VERSION,'</i>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>';
        if (!empty($inserts)) {
            foreach($inserts as $insert) {
                echo $insert,"\n";
            }
        }
        echo
        '</body>
        </html>';
    }
}
