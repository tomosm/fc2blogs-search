<?php
namespace EXAM0098\Testlib;

use Hamcrest\BaseMatcher;
use Hamcrest\Description;

/**
 * Is the value not the same object as another value?
 * In PHP terms, does $a !== $b?
 * @package EXAM0098\Testlib
 */
class IsNotSame extends BaseMatcher
{

    private $_object;

    public function __construct($object)
    {
        $this->_object = $object;
    }

    public function matches($object)
    {
        return ($object !== $this->_object) && ($this->_object !== $object);
    }

    public function describeTo(Description $description)
    {
        $description->appendText('sameNotInstance(')
            ->appendValue($this->_object)
            ->appendText(')');
    }

    /**
     * Creates a new instance of IsNotSame.
     *
     * @param mixed $object
     *   The predicate evaluates to true only when the argument is
     *   this object.
     *
     * @return \Hamcrest\Core\IsNotSame
     * @factory
     */
    public static function sameNotInstance($object)
    {
        return new self($object);
    }
}
