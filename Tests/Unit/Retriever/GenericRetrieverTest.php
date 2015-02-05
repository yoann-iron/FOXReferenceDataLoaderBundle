<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Retriever;

use GlobalPlatform\Bundle\DomainBundle\Entity\Order;
use GlobalPlatform\Bundle\DomainBundle\Entity\Payment;
use GlobalPlatform\Bundle\DomainBundle\Entity\Refund;
use GlobalPlatform\Bundle\DomainBundle\Entity\Subscription;
use GlobalPlatform\Bundle\DomainBundle\Entity\Chargeback;
use GlobalPlatform\Bundle\DomainBundle\Entity\Unsubscription;
use GlobalPlatform\Bundle\DomainBundle\Retriever\GenericRetriever;

/**
 * Test generic retriever
 */
class GenericRetrieverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $orders
     * @param array $expectedSubscriptions
     *
     * @dataProvider retrieveSubscriptionsForCustomerProvider
     */
    public function testRetrieveSubscriptionsFromOrders($orders, $expectedSubscriptions)
    {
        $genericRetriever = new GenericRetriever();
        $result = $genericRetriever->retrieveSubscriptionsFromOrders($orders);
        $this->assertEquals($expectedSubscriptions, $result);
    }

    /**
     * @return array
     */
    public function retrieveSubscriptionsForCustomerProvider()
    {
        $order1 = \Phake::mock(Order::class);
        $order2 = \Phake::mock(Order::class);
        $order3 = \Phake::mock(Order::class);

        $subscription1 = \Phake::mock(Subscription::class);
        $subscription2 = \Phake::mock(Subscription::class);

        \Phake::when($order1)->getSubscription()->thenReturn($subscription1);
        \Phake::when($order2)->getSubscription()->thenReturn($subscription2);
        \Phake::when($order3)->getSubscription()->thenReturn(null);

        return array(
            array(array($order1, $order2, $order3), array($subscription1, $subscription2)),
            array(array($order2), array($subscription2)),
            array(array($order3), array()),
        );
    }

    /**
     * @param array $payments
     * @param array $expectedRefunds
     *
     * @dataProvider retrieveRefundsFromPaymentsProvider
     */
    public function testRetrieveRefundsFromPayments($payments, $expectedRefunds)
    {
        $genericRetriever = new GenericRetriever();
        $result = $genericRetriever->retrieveRefundsFromPayments($payments);
        $this->assertEquals($expectedRefunds, $result);
    }

    /**
     * @return array
     */
    public function retrieveRefundsFromPaymentsProvider()
    {
        $payment1 = \Phake::mock(Payment::class);
        $refund1 = \Phake::mock(Refund::class);
        \Phake::when($payment1)->getRefund()->thenReturn($refund1);

        $payment2 = \Phake::mock(Payment::class);
        $refund2 = \Phake::mock(Refund::class);
        \Phake::when($payment2)->getRefund()->thenReturn($refund2);

        $payment3 = \Phake::mock(Payment::class);

        return array(
            array(array($payment1), array($refund1)),
            array(array($payment1, $payment2), array($refund1, $refund2)),
            array(array($payment1, $payment2, $payment3), array($refund1, $refund2))
        );
    }

    /**
     * @param array $subscriptions
     * @param array $expectedUnsubscriptions
     *
     * @dataProvider retrieveUnsubscriptionsFromSubscriptionsProvider
     */
    public function testRetrieveUnsubscriptionsFromSubscriptions($subscriptions, $expectedUnsubscriptions)
    {
        $genericRetriever = new GenericRetriever();
        $result = $genericRetriever->retrieveUnsubscriptionsFromSubscriptions($subscriptions);
        $this->assertEquals($expectedUnsubscriptions, $result);
    }

    /**
     * @return array
     */
    public function retrieveUnsubscriptionsFromSubscriptionsProvider()
    {
        $subscription1 = \Phake::mock(Subscription::class);
        $unsubscription1 = \Phake::mock(Unsubscription::class);
        \Phake::when($subscription1)->getUnsubscription()->thenReturn($unsubscription1);

        $subscription2 = \Phake::mock(Subscription::class);
        $unsubscription2 = \Phake::mock(Unsubscription::class);
        \Phake::when($subscription2)->getUnsubscription()->thenReturn($unsubscription2);

        $subscription3 = \Phake::mock(Subscription::class);

        return array(
            array(array($subscription1), array($unsubscription1)),
            array(array($subscription1, $subscription2), array($unsubscription1, $unsubscription2)),
            array(array($subscription1, $subscription2, $subscription3), array($unsubscription1, $unsubscription2))
        );
    }

    /**
     * @param array $payments
     * @param array $expectedChargebacks
     *
     * @dataProvider retrieveChargebacksFromPaymentsProvider
     */
    public function testRetrieveChargebacksFromPayments($payments, $expectedChargebacks)
    {
        $genericRetriever = new GenericRetriever();
        $result = $genericRetriever->retrieveChargebacksFromPayments($payments);
        $this->assertEquals($expectedChargebacks, $result);
    }

    /**
     * @return array
     */
    public function retrieveChargebacksFromPaymentsProvider()
    {
        $payment1 = \Phake::mock(Payment::class);
        $chargeback1 = \Phake::mock(Chargeback::class);
        \Phake::when($payment1)->getChargeback()->thenReturn($chargeback1);

        $payment2 = \Phake::mock(Payment::class);
        $chargeback2 = \Phake::mock(Chargeback::class);
        \Phake::when($payment2)->getChargeback()->thenReturn($chargeback2);

        $payment3 = \Phake::mock(Payment::class);

        return array(
            array(array($payment1), array($chargeback1)),
            array(array($payment1, $payment2), array($chargeback1, $chargeback2)),
            array(array($payment1, $payment2, $payment3), array($chargeback1, $chargeback2))
        );
    }
}
