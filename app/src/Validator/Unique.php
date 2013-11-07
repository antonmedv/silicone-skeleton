<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Unique extends Constraint
{
    public $fields = array();
    public $message = 'This value is already used.';
    public $repositoryMethod = 'findBy';

    public function getRequiredOptions()
    {
        return array('fields');
    }

    public function validatedBy()
    {
        return 'validator.unique';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getDefaultOption()
    {
        return 'fields';
    }
}