<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Utils;

use GlobalPlatform\Bundle\DomainBundle\Entity\Subscription;
use GlobalPlatform\Bundle\DomainBundle\Utils\Unsubscriber;
use Doctrine\ORM\EntityManager;

/**
 * Test unsubscriber
 */
class UnsubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testUnsubscribe()
    {
        $subscription = \Phake::mock(Subscription::class);
        $em = \Phake::mock(EntityManager::class);

        $unsubscriber = new Unsubscriber($em);
        $unsubscriber->unsubscribe($subscription);

        \Phake::verify($em, \Phake::times(1))->persist(\Phake::anyParameters());
    }
}
