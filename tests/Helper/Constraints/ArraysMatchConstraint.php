<?php
/**
 * Created by PhpStorm.
 * User: Simon.Gabriel
 * Date: 30.10.2017
 * Time: 10:44
 */
namespace Heidelpay\Tests\PhpPaymentApi\Helper\Constraints;

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

    /** @var string $failureMessage */
    private $failureMessage;

    /**
     * @param array $value
     * @param bool  $count
     * @param bool  $strict
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

        foreach ($this->value as $key => $value) {
            if (!array_key_exists($key, $other)) {
                $this->failureMessage = "Expected key: '" . $key . "' " . 'is missing';
                return false;
            }

            $keys_match = true;

            if ($this->strict) {
                if ($other[$key] !== $value) {
                    $keys_match = false;
                }
            } else {
                /** @noinspection TypeUnsafeComparisonInspection */
                if ($other[$key] != $value) {
                    $keys_match =  false;
                }
            }

            if (!$keys_match) {
                $this->failureMessage = "Key: '" . $key . "' => '" . $other[$key] . "' " .
                    "does not match expected value: '" . $value . "'";
                return false;
            }
        }

        if ($this->count) {
            $diff = array_diff_key($other, $this->value);
            if (count($diff) > 0) {
                $this->failureMessage = 'The array contains the following key/s, which is/are not expected: '
                    . implode(', ', array_keys($diff));
                return false;
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
        $ret_val = 'matches expected Array (';

        foreach ($this->value as $key => $value) {
            $ret_val .= "\n\t '" . $key . " => '" . $value . "'";
        }

        $ret_val .= "\n]";

        if (!empty($ret_val)) {
            $ret_val .= "\n" . $this->failureMessage;
        }

        return  $ret_val;
    }
}
