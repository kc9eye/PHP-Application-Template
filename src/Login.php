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
class Login {
    public function __construct()
    {
        $this->main();
    }

    public function main()
    {
        ViewWidgets::header("Login");
        ViewWidgets::banner();
        echo
        '<div class="fluid-container">
            <div class="row" style="min-height:82vh">
                <div class="col-md-4"></div>
                <div class="col-md-4 col-sm">
                    <div style="margin-top:10vmax" class="p-2 bg-dark text-light border border-danger rounded">
                        <form method="post" action="'.encodeQuery("WebFunctions","authenticate").'">
                            <h3>Authenticate</h3>';
                            if (isset($_SESSION[AUTH_FAILED])) {
                                echo '<small class="text-danger">Authentication Failed!</small>';
                                unset($_SESSION[AUTH_FAILED]);
                            }

                echo
                '            <div class="form-group">
                                <label for="username">Username:</label>
                                <input class="form-control" type="email" name="username" required />
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input class="form-control" type="password" name="password" required />
                            </div>
                            <label for="persistent">
                                Remain Logged in: <input type="checkbox" name="persistent" value="true" />
                                <span class="text-muted small"><i>Not recommended on public computers.</i></span>
                            </label><br>
                            <button type="submit" class="btn btn-outline-secondary">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>';
        ViewWidgets::footer();
    }
}