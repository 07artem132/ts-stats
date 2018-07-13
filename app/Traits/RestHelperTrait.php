<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 02.07.2017
 * Time: 15:01
 */

namespace Api\Traits;

use App\Exceptions\UndefinedPOSTIndex;
use App\Exceptions\InvalidJSON;

trait RestHelperTrait
{
	/**
	 * @param $index
	 *
	 * @return mixed
	 * @throws UndefinedPOSTIndex
	 */
    protected function IssetPOST($index)
    {
        if (!isset($_POST[$index])) {
            throw new UndefinedPOSTIndex('Не передан обязательный параметр \'' . $index . '\' (POST)');
        }
        return $_POST[$index];
    }

	/**
	 * @param $json
	 *
	 * @return string
	 */
    protected function JsonFormatedPrint($json)
    {
        $result = '*';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '  ';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;

        for ($i = 0; $i <= $strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine.'*';
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }
                $result .='*';
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return '<pre>' . $result . '</pre>';
    }

	/**
	 * @param $string
	 * @param bool $assoc
	 *
	 * @return mixed
	 * @throws InvalidJSON
	 */
    protected function JsonDecodeAndValidate($string, $assoc = false)
    {
        // decode the JSON data
        $result = json_decode($string, $assoc);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'Достигнута максимальная глубина стека.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Неверный или не корректный JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Ошибка управляющего символа, возможно неверная кодировка.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Синтаксическая ошибка, не корректный JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Некорректные символы UTF-8, возможно неверная кодировка.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'Одна или несколько зацикленных ссылок в кодируемом значении.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'Одно или несколько значений NAN или INF в кодируемом значении.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'Передано значение с неподдерживаемым типом.';
                break;
            default:
                $error = 'Неизвестная ошибка при парсинге JSON.';
                break;
        }

        if ($error !== '') {
            throw new InvalidJSON($error);
        }

        // everything is OK
        return $result;
    }
}