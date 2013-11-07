<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $registry
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param object     $entity
     * @param Constraint $constraint
     *
     * @throws UnexpectedTypeException
     * @throws ConstraintDefinitionException
     */
    public function validate($entity, Constraint $constraint)
    {
        if(!($constraint instanceof Unique)) {
            return;
        }

        if (!is_array($constraint->fields) && !is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        $fields = (array) $constraint->fields;

        if (0 === count($fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        $em = $this->em;

        $className = $this->context->getClassName();
        $class = $em->getClassMetadata($className);
        /* @var $class \Doctrine\ORM\Mapping\ClassMetadataInfo */

        $criteria = array();
        foreach ($fields as $fieldName) {
            if (!$class->hasField($fieldName) && !$class->hasAssociation($fieldName)) {
                throw new ConstraintDefinitionException(sprintf("The field '%s' is not mapped by Doctrine, so it cannot be validated for uniqueness.", $fieldName));
            }

            $criteria[$fieldName] = $class->reflFields[$fieldName]->getValue($entity);


            if ($class->hasAssociation($fieldName)) {
                /* Ensure the Proxy is initialized before using reflection to
                 * read its identifiers. This is necessary because the wrapped
                 * getter methods in the Proxy are being bypassed.
                 */
                $em->initializeObject($criteria[$fieldName]);

                $relatedClass = $em->getClassMetadata($class->getAssociationTargetClass($fieldName));
                $relatedId = $relatedClass->getIdentifierValues($criteria[$fieldName]);

                if (count($relatedId) > 1) {
                    throw new ConstraintDefinitionException(
                        "Associated entities are not allowed to have more than one identifier field to be " .
                            "part of a unique constraint in: " . $class->getName() . "#" . $fieldName
                    );
                }
                $criteria[$fieldName] = array_pop($relatedId);
            }
        }

        $repository = $em->getRepository($className);
        $result = $repository->{$constraint->repositoryMethod}($criteria);

        /* If the result is a MongoCursor, it must be advanced to the first
         * element. Rewinding should have no ill effect if $result is another
         * iterator implementation.
         */
        if ($result instanceof \Iterator) {
            $result->rewind();
        }

        /* If no entity matched the query criteria or a single entity matched,
         * which is the same as the entity being validated, the criteria is
         * unique.
         */
        if (0 === count($result) || (1 === count($result) && $entity === ($result instanceof \Iterator ? $result->current() : current($result)))) {
            return;
        }

        $this->context->addViolationAt($fields[0], $constraint->message, array(), $criteria[$fields[0]]);
    }
}