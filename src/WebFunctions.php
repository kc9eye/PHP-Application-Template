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
class WebFunctions {
    public static function notFound()
    {
        ViewWidgets::header("Not Found");
        ViewWidgets::banner();
        echo
        '<div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="m-3 p-3 border border-secondary rounded">
                        <h1>Resource Not Found</h1>
                        <span class="text-muted">Unable to find the resource located at:<i>',$_REQUEST[ROUTE_SWITCH],'</i></span>
                    </div>
                </div>
            </div>
        </div>';
        ViewWidgets::footer();
        exit();
    }

    public static function isValidUser() : bool
    {
        return false;
    }
}