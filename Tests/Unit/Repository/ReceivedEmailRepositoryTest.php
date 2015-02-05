<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Repository;
use GlobalPlatform\Bundle\DomainBundle\Entity\ReceivedEmail;

/**
 * Class ReceivedEmailRepositoryTest
 */
class ReceivedEmailRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ReceivedEmail
     */
    protected $receivedEmail;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->receivedEmail = new ReceivedEmail();
    }

    /**
     * Test status in receivedEmail
     */
    public function testStatus()
    {
        $this->receivedEmail->addStatus(ReceivedEmail::STATUS_READ);
        $this->assertEquals(1, $this->receivedEmail->getStatus());
        $this->receivedEmail->addStatus(ReceivedEmail::STATUS_TREATED);
        $this->assertEquals(3, $this->receivedEmail->getStatus());
        $this->receivedEmail->addStatus(ReceivedEmail::STATUS_REPLIED);
        $this->assertEquals(7, $this->receivedEmail->getStatus());
        $this->receivedEmail->addStatus(ReceivedEmail::STATUS_ARCHIVED);
        $this->assertEquals(15, $this->receivedEmail->getStatus());

        $this->receivedEmail->removeStatus(ReceivedEmail::STATUS_ARCHIVED);
        $this->assertEquals(7, $this->receivedEmail->getStatus());
        $this->receivedEmail->removeStatus(ReceivedEmail::STATUS_READ);
        $this->assertEquals(6, $this->receivedEmail->getStatus());

        $this->assertEquals(true, $this->receivedEmail->isStatus(ReceivedEmail::STATUS_REPLIED));
        $this->assertEquals(true, $this->receivedEmail->isStatus(ReceivedEmail::STATUS_REPLIED | ReceivedEmail::STATUS_TREATED));
        $this->assertEquals(false, $this->receivedEmail->isStatus(ReceivedEmail::STATUS_READ));
        $this->assertEquals(false, $this->receivedEmail->isStatus(ReceivedEmail::STATUS_READ | ReceivedEmail::STATUS_ARCHIVED));
    }
}
