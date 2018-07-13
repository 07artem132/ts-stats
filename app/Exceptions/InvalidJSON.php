<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.06.2017
 * Time: 18:13
 */

namespace App\Exceptions;

use Exception;

/**
 * Class InvalidJSON
 * @package Api\Exceptions
 */
class InvalidJSON extends Exception
{
    /**
     * InvalidJSON constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);

    }
}