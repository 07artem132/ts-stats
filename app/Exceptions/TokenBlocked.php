<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.07.2017
 * Time: 15:45
 */

namespace App\Exceptions;

use Exception;

/**
 * Class TokenBlocked
 * @package Api\Exceptions
 */
class TokenBlocked extends Exception
{
    /**
     * TokenBlocked constructor.
     */
    public function __construct()
    {
        //
    }
}