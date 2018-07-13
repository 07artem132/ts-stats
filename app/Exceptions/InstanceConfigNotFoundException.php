<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 05.07.2017
 * Time: 20:23
 */

namespace App\Exceptions;

use Exception;

/**
 * Class InstanceConfigNotFoundException
 * @package Api\Exceptions
 */
class InstanceConfigNotFoundException extends Exception
{
    /**
     * @var int
     */
    public $Instanse_ID;

    /**
     * InstanceConfigNotFoundException constructor.
     * @param int $Instanse_ID
     */
    public function __construct(int $Instanse_ID)
    {
        $this->Instanse_ID = $Instanse_ID;
        parent::__construct();

    }
}