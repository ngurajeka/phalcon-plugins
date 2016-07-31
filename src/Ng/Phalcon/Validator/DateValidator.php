<?php
/**
 * Date Validator
 *
 * PHP Version 5.4.x
 *
 * @category Library
 * @package  Library
 * @author   Ady Rahmat MA <adyrahmatma@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/ngurajeka/phalcon-plugins
 */
namespace Ng\Phalcon\Validator;


use Phalcon\Validation;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

/**
 * Date Validator
 *
 * @category Library
 * @package  Library
 * @author   Ady Rahmat MA <adyrahmatma@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/ngurajeka/phalcon-plugins
 */

class DateValidator extends Validator implements ValidatorInterface
{
    const VAL_TYPE = "Date";

    protected $defaultFormat = "d-m-y";

    public function validate(Validation $validator, $attribute)
    {
        if ($this->getOption('format')) {
            $format = $this->getOption('format');
        } else {
            $format = $this->defaultFormat;
        }

        $date = date_parse_from_format($format, $validator->getValue($attribute));

        if (($date['error_count'] + $date['warning_count']) > 0) {
            if ($this->getOption('message')) {
                $message = $this->getOption('message');
            } else {
                $message = sprintf(
                    'The date entered was in valid, must be in format %s',
                    $format
                );
            }

            $validator->appendMessage(
                new \Phalcon\Validation\Message(
                    $message, $attribute, self::VAL_TYPE
                )
            );

            return false;
        }

        return true;
    }
}