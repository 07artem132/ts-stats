<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 31.07.2017
 * Time: 12:46
 */

namespace App\Exceptions;

use Exception;

class TeamspeakInstancesNotAllowExceptions extends Exception
{
    private $server_id;

    function __construct($server_id)
    {
        $this->server_id = $server_id;
    }

}