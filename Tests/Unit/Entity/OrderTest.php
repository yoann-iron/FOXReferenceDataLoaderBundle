<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Entity;

use GlobalPlatform\Bundle\DomainBundle\Entity\MerchantAccount;
use GlobalPlatform\Bundle\DomainBundle\Entity\Order;
use GlobalPlatform\Bundle\DomainBundle\Entity\OrderProduct;
use GlobalPlatform\Bundle\DomainBundle\Entity\Payment;
use GlobalPlatform\Bundle\DomainBundle\Entity\PrimarySubscription;
use GlobalPlatform\Bundle\DomainBundle\Entity\SecondarySubscription;
use GlobalPlatform\Bundle\DomainBundle\Entity\SubscriptionBilling;
use Phake;

/**
 * Class OrderTest
 */
class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var Payment
     */
    protected $acquisitionPayment;

    /**
     * @var Payment
     */
    protected $subscriptionPayment;

    public function setUp()
    {
        $this->order = new Order();

        $this->acquisitionPayment = Phake::mock(Payment::class);
        $this->subscriptionPayment = Phake::mock(Payment::class);
        $subscriptionBilling = Phake::mock(SubscriptionBilling::class);
        Phake::when($this->acquisitionPayment)->getSubscriptionBilling()->thenReturn(null);
        Phake::when($this->subscriptionPayment)->getSubscriptionBilling()->thenReturn($subscriptionBilling);
    }

    public function testSetPrimarySubscription()
    {
        $primary = Phake::mock(PrimarySubscription::class);

        $this->order->setSubscription($primary);

        $this->assertSame($primary, $this->order->getSubscription());
    }

    public function testSetSecondarySubscription()
    {
        $primary = new PrimarySubscription(1, 'aa');
        $secondary = new SecondarySubscription(14, 'aa');

        $this->order->setSubscription($primary);
        $this->order->setSubscription($secondary);

        $this->assertSame($secondary, $primary->getSecondarySubscription());
        $this->assertSame($primary, $this->order->getSubscription());
    }

    /**
     * @param string $class
     * @param int    $amount
     * @param int    $result
     *
     * @dataProvider provideClassAndAmount
     */
    public function testGetAmount($class, $amount, $result)
    {
        $websiteProduct = Phake::mock($class);
        Phake::when($websiteProduct)->getAtiAmount()->thenReturn($amount);

        $orderProduct = Phake::mock(OrderProduct::class);
        Phake::when($orderProduct)->getWebsiteProduct()->thenReturn($websiteProduct);

        $this->order->addOrderProduct($orderProduct);
        $this->order->addOrderProduct($orderProduct);

        $this->assertSame($result, $this->order->getAmount());
    }

    /**
     * Test the getAcquisitionPayment method
     */
    public function testGetAcquisitionPayment()
    {
        $this->order->addPayment($this->subscriptionPayment);
        $this->order->addPayment($this->acquisitionPayment);

        $this->assertSame($this->acquisitionPayment, $this->order->getAcquisitionPayment());
    }

    /**
     * Test the getMainMerchantAccount method
     */
    public function testGetMainMerchantAccount()
    {
        $this->order->addPayment($this->subscriptionPayment);
        $this->order->addPayment($this->acquisitionPayment);

        $merchantAccount = Phake::mock(MerchantAccount::class);
        Phake::when($this->acquisitionPayment)->getMerchantAccount()->thenReturn($merchantAccount);

        $this->assertSame($merchantAccount, $this->order->getMainMerchantAccount());
    }

    /**
     * @return array
     */
    public function provideClassAndAmount()
    {
        return array(
            array('GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteAcquisitionProduct', 10, 20),
            array('GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteSubscriptionProduct', 10, 0),
        );
    }
}
