<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.07.2017
 * Time: 16:39
 */

namespace App\Exceptions;

use Exception;

/**
 * Class NotSpecified
 * @package Api\Exceptions
 */
class NotSpecified extends Exception
{
    /**
     * @var string
     */
    public $field;

    /**
     * NotSpecified constructor.
     * @param string $field
     */
    public function __construct(string $field)
    {
        $this->field = $field;

        parent::__construct($field);
    }
}