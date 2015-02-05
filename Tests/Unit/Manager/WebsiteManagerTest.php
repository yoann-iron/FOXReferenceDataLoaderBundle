<?php

namespace GlobalPlatform\Bundle\DomainBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use GlobalPlatform\Bundle\DomainBundle\Entity\Website;
use GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteFrontAcquisition;
use GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteFrontSav;
use GlobalPlatform\Bundle\DomainBundle\Provider\AutomaticEmailTemplateProvider;
use GlobalPlatform\Bundle\DomainBundle\Repository\WebsiteRepository;
use Phake;

/**
 * Class WebsiteManagerTest
 */
class WebsiteManagerTest extends \PHPUnit_Framework_TestCase
{
    const EXPECTED_COUNT_1 = 3;
    const EXPECTED_COUNT_2 = 1;
    const EXPECTED_COUNT_3 = 0;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $websiteClass;

    /**
     * @var AutomaticEmailTemplateProvider
     */
    protected $automaticEmailTemplateProvider;

    /**
     * @var WebsiteRepository
     */
    protected $websiteRepository;

    /**
     * @var Website
     */
    protected $website1;

    /**
     * @var Website
     */
    protected $website2;

    /**
     * @var Website
     */
    protected $website3;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->entityManager                  = Phake::mock(EntityManager::class);
        $this->websiteClass                   = Website::class;
        $this->automaticEmailTemplateProvider = Phake::mock(AutomaticEmailTemplateProvider::class);
        $this->websiteRepository              = Phake::mock(WebsiteRepository::class);
        $this->website1                       = Phake::mock(WebsiteFrontAcquisition::class);
        $this->website2                       = Phake::mock(WebsiteFrontSav::class);
        $this->website3                       = Phake::mock(WebsiteFrontSav::class);

        Phake::when($this->website1)->getId()->thenReturn(1);
        Phake::when($this->website2)->getId()->thenReturn(2);
        Phake::when($this->website3)->getId()->thenReturn(3);
        Phake::when($this->automaticEmailTemplateProvider)->load($this->website1)->thenReturn(3);
        Phake::when($this->automaticEmailTemplateProvider)->load($this->website2)->thenReturn(1);
        Phake::when($this->automaticEmailTemplateProvider)->load($this->website3)->thenReturn(0);

        Phake::when($this->entityManager)->getRepository($this->websiteClass)->thenReturn($this->websiteRepository);
        Phake::when($this->websiteRepository)->findAll()->thenReturn(new ArrayCollection(array($this->website1, $this->website2, $this->website3)));
    }

    /**
     * @return array
     */
    public function expectedValuesForTestLoadMissingAutomaticEmailTemplates()
    {
        return array(array(self::EXPECTED_COUNT_1, self::EXPECTED_COUNT_2, self::EXPECTED_COUNT_3));
    }

    /**
     * Test load missing automaticEmailTemplates
     *
     * @dataProvider expectedValuesForTestLoadMissingAutomaticEmailTemplates
     *
     * @param array $expectedValues
     */
    public function testLoadMissingAutomaticEmailTemplates($expectedValues)
    {
        $index          = 0;
        $websiteManager = new WebsiteManager($this->entityManager, $this->websiteClass, $this->automaticEmailTemplateProvider);
        $websitesDone   = $websiteManager->loadMissingAutomaticEmailTemplates();

        foreach ($websitesDone as $count) {
            $this->assertEquals($expectedValues[$index], $count);
        }
    }

}
