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

    }

    public static function footer(Array $inserts = [])
    {
        echo
        '<div class="container-fluid">
            <div class="row bg-dark text-light border-top border-info" style="min-height:6vh">
                <div class="col text-center">
                    Application version:&#160;<i>',VERSION,'</i>&#160; &#169; Paul W. Lane
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