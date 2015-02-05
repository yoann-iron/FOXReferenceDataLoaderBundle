<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Provider;

use GlobalPlatform\Bundle\DomainBundle\Entity\AutomaticEmailTemplate;
use GlobalPlatform\Bundle\DomainBundle\Entity\AutomaticEmailType;
use GlobalPlatform\Bundle\DomainBundle\Entity\Website;
use GlobalPlatform\Bundle\DomainBundle\Provider\AutomaticEmailTemplateProvider;
use GlobalPlatform\Bundle\DomainBundle\Factory\AutomaticEmailTemplateFactory;
use GlobalPlatform\Bundle\DomainBundle\Repository\AutomaticEmailTypeRepository;
use GlobalPlatform\Bundle\DomainBundle\Repository\WebsiteRepository;
use Phake;

/**
 * Class AutomaticEmailTemplateProviderTest
 */
class AutomaticEmailTemplateProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AutomaticEmailTemplateProvider
     */
    protected $automaticEmailTemplateProvider;

    /**
     * @var AutomaticEmailTemplateFactory
     */
    protected $automaticEmailTemplateFactoryMock;

    /**
     * @var AutomaticEmailTypeRepository
     */
    protected $automaticEmailTypeRepositoryMock;

    /**
     * @var WebsiteRepository
     */
    protected $websiteRepositoryMock;

    /**
     * @var Website
     */
    protected $websiteMock;

    /**
     * @var AutomaticEmailType
     */
    protected $automaticEmailType;

    /**
     * @var AutomaticEmailType
     */
    protected $automaticEmailTypeToAdd;

    /**
     * @var AutomaticEmailTemplate
     */
    protected $automaticEmailTemplateOne;

    /**
     * @var AutomaticEmailTemplate
     */
    protected $automaticEmailTemplateTwo;

    /**
     * @var array
     */
    protected $collectionAutomaticEmailTemplate;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->websiteMock                      = Phake::mock(Website::class);
        $this->automaticEmailTemplateOne        = new AutomaticEmailTemplate();
        $this->automaticEmailTemplateTwo        = new AutomaticEmailTemplate();
        $this->automaticEmailType               = new AutomaticEmailType();
        $this->automaticEmailTypeToAdd          = new AutomaticEmailType();
        $this->collectionAutomaticEmailTemplate = array($this->automaticEmailTemplateOne, $this->automaticEmailTemplateTwo);
        $this->automaticEmailTypeRepositoryMock = Phake::mock(AutomaticEmailTypeRepository::class);
        $this->websiteRepositoryMock            = Phake::mock(WebsiteRepository::class);
        $this->automaticEmailTemplateFactoryMock    = Phake::mock(AutomaticEmailTemplateFactory::class);

        $this->automaticEmailTemplateProvider = new AutomaticEmailTemplateProvider($this->automaticEmailTypeRepositoryMock, $this->automaticEmailTemplateFactoryMock, $this->websiteRepositoryMock);

        $this->automaticEmailType->setId(40);
        $this->automaticEmailType->setType(AutomaticEmailType::TYPE_FRONT);
        $this->automaticEmailType->setLabel('unechaine');
        $this->automaticEmailTypeToAdd->setId(41);
        $this->automaticEmailTypeToAdd->setType(AutomaticEmailType::TYPE_FRONT);
        $this->automaticEmailTypeToAdd->setLabel('toadd');
        $this->automaticEmailTemplateOne->setType('deprecated');
        $this->automaticEmailTemplateOne->setContent('Content');
        $this->automaticEmailTemplateOne->setSubject('Subject');
        $this->automaticEmailTemplateOne->setWebsite($this->websiteMock);
        $this->automaticEmailTemplateOne->setAutomaticEmailType($this->automaticEmailType);
        $this->automaticEmailTemplateTwo->setType('deprecated');
        $this->automaticEmailTemplateTwo->setContent('Content');
        $this->automaticEmailTemplateTwo->setSubject('Subject');
        $this->automaticEmailTemplateTwo->setWebsite($this->websiteMock);
        $this->automaticEmailTemplateTwo->setAutomaticEmailType($this->automaticEmailType);
    }

    /**
     * Test if function load, call every methods required to generate missing automatic email templates for a website
     */
    public function testAutomaticEmailTemplateAddedLoad()
    {
        Phake::when($this->websiteRepositoryMock)->searchWithJoinById(1)->thenReturn($this->websiteMock);
        Phake::when($this->websiteMock)->getAutomaticEmailTemplates()->thenReturn($this->collectionAutomaticEmailTemplate);
        Phake::when($this->automaticEmailTypeRepositoryMock)->searchByTypeNotIn(Phake::anyParameters())->thenReturn(array($this->automaticEmailTypeToAdd));
        Phake::when($this->automaticEmailTemplateFactoryMock)->createAutomaticEmailTemplatesForWebsite(Phake::anyParameters())->thenReturn(3);

        $nb = $this->automaticEmailTemplateProvider->load(1);
        Phake::verify($this->websiteRepositoryMock, Phake::times(1))->searchWithJoinById(Phake::anyParameters());
        Phake::verify($this->automaticEmailTypeRepositoryMock, Phake::times(1))->searchByTypeNotIn(Phake::anyParameters());
        Phake::verify($this->automaticEmailTemplateFactoryMock, Phake::times(1))->createAutomaticEmailTemplatesForWebsite(Phake::anyParameters());
        $this->assertEquals(3, $nb);
    }
}
