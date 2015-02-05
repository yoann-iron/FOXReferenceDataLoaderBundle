<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use GlobalPlatform\Bundle\DomainBundle\Entity\Business;
use GlobalPlatform\Bundle\DomainBundle\Entity\MerchantAccount;
use GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteFrontAcquisition;
use GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteFrontSav;
use GlobalPlatform\Bundle\DomainBundle\Factory\WebsiteFrontSavFactory;
use Phake;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class WebsiteFrontSavFactoryTest
 */
class WebsiteFrontSavFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function merchantAccountProvider()
    {
        $business1                      = Phake::mock(Business::class);
        $business2                      = Phake::mock(Business::class);
        $merchantAccountWithoutFrontSav = Phake::mock(MerchantAccount::class);
        $merchantAccountWithFrontSav    = Phake::mock(MerchantAccount::class);
        $websiteFrontAcquisition        = Phake::mock(WebsiteFrontAcquisition::class);
        $websiteFrontSav                = Phake::mock(WebsiteFrontSav::class);

        Phake::when($merchantAccountWithFrontSav)->getWebsiteFrontSav()->thenReturn($websiteFrontSav);
        Phake::when($websiteFrontSav)->getWebsiteFrontAcquisitions()->thenReturn(new ArrayCollection());
        Phake::when($merchantAccountWithoutFrontSav)->getWebsiteFrontSav($websiteFrontAcquisition)->thenReturn(null);

        Phake::when($merchantAccountWithoutFrontSav)->getUrl()->thenReturn("http://www.site.com");
        Phake::when($merchantAccountWithoutFrontSav)->getEmail()->thenReturn("contact@site.com");

        Phake::when($merchantAccountWithFrontSav)->getUrl()->thenReturn("http://www.site.com");
        Phake::when($merchantAccountWithFrontSav)->getEmail()->thenReturn("contact@site.com");

        Phake::when($merchantAccountWithFrontSav)->getBusinesses()->thenReturn(new ArrayCollection(array($business1, $business2)));
        Phake::when($merchantAccountWithoutFrontSav)->getBusinesses()->thenReturn(new ArrayCollection());

        Phake::when($business1)->getWebsites()->thenReturn([$websiteFrontAcquisition, $websiteFrontSav]);
        Phake::when($business2)->getWebsites()->thenReturn([]);

        $expectedWebsiteFrontSavCreated = new WebsiteFrontSav();
        $expectedWebsiteFrontSavUpdated = new WebsiteFrontSav();

        $expectedWebsiteFrontSavCreated->setBillType('');
        $expectedWebsiteFrontSavCreated->setSendMailAfterSubscription(false);
        $expectedWebsiteFrontSavCreated->setMerchantAccount($merchantAccountWithoutFrontSav);

        $expectedWebsiteFrontSavCreated->setUrl($merchantAccountWithoutFrontSav->getUrl());
        $expectedWebsiteFrontSavCreated->setContactEmail($merchantAccountWithoutFrontSav->getEmail());

        $expectedWebsiteFrontSavUpdated->setUrl($merchantAccountWithFrontSav->getUrl());
        $expectedWebsiteFrontSavUpdated->setContactEmail($merchantAccountWithFrontSav->getEmail());
        $expectedWebsiteFrontSavUpdated->addWebsiteFrontAcquisition($websiteFrontAcquisition);

        return array(
            array($merchantAccountWithoutFrontSav, $expectedWebsiteFrontSavCreated, false),
            array($merchantAccountWithFrontSav, $expectedWebsiteFrontSavUpdated, true),
        );
    }

    /**
     * @dataProvider merchantAccountProvider
     * @param MerchantAccount $merchantAccount
     * @param WebsiteFrontSav $expectedWebsiteFrontSav
     * @param boolean $isMock
     */
    public function testCreateWebsiteFrontSavForMerchantAccount(MerchantAccount $merchantAccount, WebsiteFrontSav $expectedWebsiteFrontSav, $isMock)
    {
        $entityManager = Phake::mock(EntityManager::class);
        $eventDispatcher = Phake::mock(EventDispatcherInterface::class);
        $websiteFrontSavFactory = new WebsiteFrontSavFactory($entityManager, $eventDispatcher);

        $generatedWebsiteFrontSav = $websiteFrontSavFactory->createWebsiteFrontSavForMerchantAccount($merchantAccount);
       
        $this->assertEquals($expectedWebsiteFrontSav->getBillType(), $generatedWebsiteFrontSav->getBillType());
        $this->assertEquals($expectedWebsiteFrontSav->getSendMailAfterSubscription(), $generatedWebsiteFrontSav->getSendMailAfterSubscription());
        $this->assertEquals($expectedWebsiteFrontSav->getMerchantAccount(), $generatedWebsiteFrontSav->getMerchantAccount());
        if ($isMock) {
            Phake::verify($generatedWebsiteFrontSav, \Phake::times(1))->setUrl(Phake::anyParameters());
            Phake::verify($generatedWebsiteFrontSav, \Phake::times(1))->setContactEmail(Phake::anyParameters());
            Phake::verify($generatedWebsiteFrontSav, \Phake::times(1))->addWebsiteFrontAcquisition(Phake::anyParameters());
        } else {
            $this->assertEquals($expectedWebsiteFrontSav->getUrl(), $generatedWebsiteFrontSav->getUrl());
            $this->assertEquals($expectedWebsiteFrontSav->getContactEmail(), $generatedWebsiteFrontSav->getContactEmail());
            $this->assertEquals($expectedWebsiteFrontSav->getWebsiteFrontAcquisitions(), $generatedWebsiteFrontSav->getWebsiteFrontAcquisitions());
        }
    }
}
