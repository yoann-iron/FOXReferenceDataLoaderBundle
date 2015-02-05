<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use GlobalPlatform\Bundle\DomainBundle\Entity\Chargeback;
use GlobalPlatform\Bundle\DomainBundle\Entity\Payment;
use Phake;

/**
 * Class ChargebackTest
 */
class ChargebackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Chargeback
     */
    protected $entity;

    /**
     * set up the test
     */
    public function setUp()
    {
        $this->entity = new Chargeback();
    }

    /**
     * Test add one payment
     */
    public function testAddPossiblePaymentWithOnePayment()
    {
        $payment = Phake::mock(Payment::class);

        $this->entity->addPossiblePayment($payment);

        $this->assertSame($payment, $this->entity->getPossiblePayments()->first());
        $this->assertEquals(1, $this->entity->getPossiblePayments()->count());
    }

    /**
     * @param int $time
     *
     * @dataProvider provideTimes
     */
    public function testAddPossiblePaymentWithOnePaymentMultipleTime($time)
    {
        $payment = Phake::mock(Payment::class);

        for ($i = 0; $i < $time; $i++) {
            $this->entity->addPossiblePayment($payment);
        }

        $this->assertSame($payment, $this->entity->getPossiblePayments()->first());
        $this->assertEquals(1, $this->entity->getPossiblePayments()->count());
    }

    /**
     * @return array
     */
    public function provideTimes()
    {
        return array(
            array(1),
            array(3),
            array(6),
            array(10),
        );
    }

    /**
     * test add multiple payment
     */
    public function testAddMultiplePossiblePayment()
    {
        $payment1 = Phake::mock(Payment::class);
        $payment2 = Phake::mock(Payment::class);

        $this->entity->addPossiblePayment($payment1);
        $this->entity->addPossiblePayment($payment2);

        $this->assertSame($payment1, $this->entity->getPossiblePayments()->first());
        $this->assertSame($payment2, $this->entity->getPossiblePayments()->next());
        $this->assertEquals(2, $this->entity->getPossiblePayments()->count());
    }
}
