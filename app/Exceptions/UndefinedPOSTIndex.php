<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.06.2017
 * Time: 18:23
 */

namespace App\Exceptions;

use Exception;


class UndefinedPOSTIndex extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);

    }
}