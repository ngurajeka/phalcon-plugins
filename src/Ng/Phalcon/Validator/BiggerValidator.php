<?php
/**
 * Bigger Value Validator
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
 * Bigger Value Validator
 *
 * @category Library
 * @package  Library
 * @author   Ady Rahmat MA <adyrahmatma@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/ngurajeka/phalcon-plugins
 */

class BiggerValidator extends Validator implements ValidatorInterface
{
    const VAL_TYPE = "Bigger";

    protected $useBiggerEquals = false;

    public function validate(Validation $validator, $attribute)
    {
        if ($this->getOption('useBiggerEquals')) {
            $opt = $this->getOption('useBiggerEquals');
            if (is_bool($opt)) {
                $this->useBiggerEquals = $opt;
            }
        }

        if (is_null($this->getOption('biggerThan'))) {
            $this->addMessage($validator, $attribute);
            return false;
        }

        $value      = $validator->getValue($attribute);
        $compared   = $this->getOption('biggerThan');
        if ($this->useBiggerEquals) {
            if ($value <= $compared) {
                $this->addMessage($validator, $attribute);
                return false;
            }
        } else {
            if ($value < $compared) {
                $this->addMessage($validator, $attribute);
                return false;
            }
        }

        return true;
    }

    protected function getOptionMessage()
    {
        if ($this->getOption('message')) {
            return $this->getOption('message');
        }

        return 'Invalid Input';
    }

    protected function addMessage(Validation &$validator, $attr)
    {
        $validator->appendMessage(
            new \Phalcon\Validation\Message($this->getOptionMessage(), $attr, self::VAL_TYPE)
        );
    }
}
