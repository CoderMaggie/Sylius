<?php

namespace AppBundle\Generator;

use Sylius\Component\Sequence\Model\SequenceSubjectInterface;
use Sylius\Component\Sequence\Number\AbstractGenerator;
use Sylius\Component\Sequence\Number\GeneratorInterface;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class OrderNumberGenerator extends AbstractGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var int
     */
    private $startNumber;

    /**
     * @param string $prefix
     * @param int $startNumber
     */
    public function __construct($prefix, $startNumber)
    {
        $this->prefix = $prefix;
        $this->startNumber = $startNumber;
    }

    /**
     * {@inheritdoc}
     */
    protected function generateNumber($index, SequenceSubjectInterface $subject)
    {
        $number = $this->startNumber + $index;

        return sprintf('%s%d', $this->prefix, $number);
    }
}
