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

//Abstration layer for the application. All template DB calls use this class.
//This class will have to be rewritten to implement your particular DB being usered.

class DB implements Database{
    private static $dbh;
    public static function Insert($sql = "", Array $insert = []) : bool
    {
        return true;
    }

    public static function Query($query, Array $subs = []) : Array
    {
        return [];
    }

    public static function DBCommand($command): bool
    {
        return true;
    }

    private static function dbHandle()
    {
        if (is_null(self::$dbh)) {
            self::$dbh = "";
        }
        return self::$dbh;
    }
}
