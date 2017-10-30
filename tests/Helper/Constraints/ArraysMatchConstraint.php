<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 30.10.2017
 * Time: 10:44
 */
namespace Heidelpay\Tests\PhpApi\Helper\Constraints;

use PHPUnit\Util\InvalidArgumentHelper;

class ArraysMatchConstraint extends \PHPUnit_Framework_Constraint
{
    /**
     * @var array
     */
    protected $value;

    /**
     * @var boolean
     */
    protected $strict;

    /**
     * @var bool
     */
    private $count;

    /**
     * @param array $value
     * @param bool $count
     * @param bool $strict
     *
     * @throws \PHPUnit\Framework\Exception
     */
    public function __construct($value, $count = false, $strict = false)
    {
        parent::__construct();

        if (!\is_array($value)) {
            throw InvalidArgumentHelper::factory(1, 'array');
        }

        if (!\is_bool($count)) {
            throw InvalidArgumentHelper::factory(2, 'boolean');
        }

        if (!\is_bool($strict)) {
            throw InvalidArgumentHelper::factory(3, 'boolean');
        }

        $this->value        = $value;
        $this->strict       = $strict;
        $this->count        = $count;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * This method can be overridden to implement the evaluation algorithm.
     *
     * @param mixed $other Value or object to evaluate.
     *
     * @return bool
     */
    public function matches($other)
    {
        if (!is_array($other)) {
            return false;
        }

        if ($this->count) {
            if (count($this->value) !== count($other)) {
                return false;
            }
        }

        foreach ($this->value as $key => $value) {
            if (!array_key_exists($key, $other)) {
                return false;
            }

            if ($this->strict) {
                if ($other[$key] !== $value) {
                    return false;
                }
            } else {
                /** @noinspection TypeUnsafeComparisonInspection */
                if ($other[$key] != $value) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'validate if array matches expected once';
    }
}
