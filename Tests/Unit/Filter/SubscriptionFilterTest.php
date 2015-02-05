<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Filter;

use GlobalPlatform\Bundle\DomainBundle\Entity\Subscription;
use GlobalPlatform\Bundle\DomainBundle\Filter\SubscriptionFilter;
use Phake;
use Symfony\Component\Security\Core\SecurityContextInterface;
use GlobalPlatform\Bundle\DomainBundle\Entity\MerchantAccount;

/**
 * Class SubscriptionFilterTest
 */
class SubscriptionFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterSubscriptionForMerchantAccount()
    {

        $merchantAccountLabel1 = "easy-find.org";
        $merchantAccountLabel2 = "web-trust.co.uk";

        $subscription1 = Phake::mock(Subscription::class);
        $subscription2 = Phake::mock(Subscription::class);

        $merchantAccount1 = Phake::mock(MerchantAccount::class);
        $merchantAccount2 = Phake::mock(MerchantAccount::class);

        Phake::when($subscription1)->getMerchantAccount()->thenReturn($merchantAccount1);
        Phake::when($subscription2)->getMerchantAccount()->thenReturn($merchantAccount2);
        Phake::when($merchantAccount1)->getLabel()->thenReturn($merchantAccountLabel1);
        Phake::when($merchantAccount2)->getLabel()->thenReturn($merchantAccountLabel2);

        $subscriptions = [ $subscription1, $subscription2 ];

        $subscriptionFilter = new SubscriptionFilter();
        $this->assertEquals(
            $subscription2,
            $subscriptionFilter->filterSubscriptionForMerchantAccount($subscriptions, $merchantAccountLabel2)
        );
        $this->assertEquals(
            $subscription1,
            $subscriptionFilter->filterSubscriptionForMerchantAccount($subscriptions, $merchantAccountLabel1)
        );
        $this->assertNull($subscriptionFilter->filterSubscriptionForMerchantAccount($subscriptions, "dummylabel"));
    }
}
