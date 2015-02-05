<?php

namespace GlobalPlatform\Bundle\DomainBundle\Factory;

use GlobalPlatform\Bundle\DomainBundle\Entity\AutomaticEmailTemplate;
use GlobalPlatform\Bundle\DomainBundle\Entity\AutomaticEmailType;
use GlobalPlatform\Bundle\DomainBundle\Entity\Website;
use Doctrine\ORM\EntityManager;
use GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteFrontAcquisition;
use GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteFrontSav;
use Phake;

/**
 * Class AutomaticEmailTemplateFactory
 */
class AutomaticEmailTemplateFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Email data
     */
    const EMAIL_SUBJECT_PATTERN = 'Subject of the %s email for the website %s';
    const EMAIL_CONTENT_PATTERN = 'Content of the %s email for the website %s';
    const EMAIL_TYPE_PATTERN    = 'automatic_email_template.%s';

    const URL_WEBSITE_1      = 'website-front-url.com';
    const URL_WEBSITE_2      = 'website-sav-url.com';
    const URL_WEBSITE_3      = 'website-sav-bis-url.com';
    const EMAIL_TYPE_LABEL_1 = 'automatic_email.type_one';
    const EMAIL_TYPE_LABEL_2 = 'automatic_email.type_two';
    const EMAIL_TYPE_LABEL_3 = 'automatic_email.type_three';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->entityManager = Phake::mock(EntityManager::class);
    }

    /**
     * @return array
     */
    public function websiteAndAutomaticEmailTypeProvider()
    {
        $website1                      = Phake::mock(WebsiteFrontAcquisition::class);
        $website2                      = Phake::mock(WebsiteFrontSav::class);
        $website3                      = Phake::mock(WebsiteFrontSav::class);
        $automaticEmailType1           = Phake::mock(AutomaticEmailType::class);
        $automaticEmailType2           = Phake::mock(AutomaticEmailType::class);
        $automaticEmailType3           = Phake::mock(AutomaticEmailType::class);

        Phake::when($website1)->getUrl()->thenReturn(self::URL_WEBSITE_1);
        Phake::when($automaticEmailType1)->getLabel()->thenReturn(self::EMAIL_TYPE_LABEL_1);
        Phake::when($automaticEmailType1)->getType()->thenReturn(AutomaticEmailType::TYPE_FRONT);
        Phake::when($automaticEmailType2)->getLabel()->thenReturn(self::EMAIL_TYPE_LABEL_2);
        Phake::when($automaticEmailType2)->getType()->thenReturn(AutomaticEmailType::TYPE_FRONT);

        Phake::when($website2)->getUrl()->thenReturn(self::URL_WEBSITE_2);
        Phake::when($automaticEmailType3)->getLabel()->thenReturn(self::EMAIL_TYPE_LABEL_3);
        Phake::when($automaticEmailType3)->getType()->thenReturn(AutomaticEmailType::TYPE_SAV);

        Phake::when($website3)->getUrl()->thenReturn(self::URL_WEBSITE_3);

        return array(
            array($website1, array($automaticEmailType1, $automaticEmailType2), 2),
            array($website2, array($automaticEmailType3), 1),
            array($website3, array(), 0),
        );
    }

    /**
     * @return array
     */
    public function websiteAndAutomaticEmailTypeForCreateProvider()
    {
        $website1                      = Phake::mock(WebsiteFrontAcquisition::class);
        $automaticEmailType1           = Phake::mock(AutomaticEmailType::class);

        Phake::when($website1)->getUrl()->thenReturn(self::URL_WEBSITE_1);
        Phake::when($automaticEmailType1)->getLabel()->thenReturn(self::EMAIL_TYPE_LABEL_1);
        Phake::when($automaticEmailType1)->getType()->thenReturn(AutomaticEmailType::TYPE_FRONT);

        return array(
            array($website1, $automaticEmailType1, self::URL_WEBSITE_1, self::EMAIL_TYPE_LABEL_1),
        );
    }

    /**
     * Test if we call the creation function for the number of automaticEmailType provided in the $automaticEmailTemplateTypeList
     *
     * @dataProvider       websiteAndAutomaticEmailTypeProvider
     * @param Website      $website
     * @param array|null   $automaticEmailTemplateTypeList
     * @param integer      $nbCreation
     */
    public function testCreateAutomaticEmailTemplatesForWebsite(Website $website, array $automaticEmailTemplateTypeList = null, $nbCreation)
    {
        $automaticEmailTemplateFactory = new AutomaticEmailTemplateFactory($this->entityManager);

        $automaticEmailTemplateCreationCount = $automaticEmailTemplateFactory->createAutomaticEmailTemplatesForWebsite($website, $automaticEmailTemplateTypeList);

        $this->assertEquals($nbCreation, $automaticEmailTemplateCreationCount);
    }


    /**
     * Test if the values when creating a new AutomaticEmailTemplate are correct.
     *
     * @dataProvider                  websiteAndAutomaticEmailTypeForCreateProvider
     * @param Website                 $website
     * @param AutomaticEmailType      $automaticEmailType
     * @param string                  $websiteUrl
     * @param string                  $automaticEmailTypeLabel
     *
     * @return AutomaticEmailTemplate $automaticEmailTemplate
     */
    public function testCreate(Website $website, AutomaticEmailType $automaticEmailType, $websiteUrl, $automaticEmailTypeLabel)
    {
        $automaticEmailTemplateFactory = new AutomaticEmailTemplateFactory($this->entityManager);
        $automaticEmailTemplate        = $automaticEmailTemplateFactory->create($website, $automaticEmailType);

        $this->assertEquals(
            sprintf(
                AutomaticEmailTemplateFactory::EMAIL_SUBJECT_PATTERN,
                $automaticEmailTypeLabel,
                $websiteUrl),
            $automaticEmailTemplate->getSubject()
        );

        $this->assertEquals(
            sprintf(
                AutomaticEmailTemplateFactory::EMAIL_CONTENT_PATTERN,
                $automaticEmailTypeLabel,
                $websiteUrl),
            $automaticEmailTemplate->getContent()
        );

        $this->assertEquals($automaticEmailTemplate->getWebsite()->getUrl(), $websiteUrl);
        $this->assertEquals($automaticEmailTemplate->getAutomaticEmailType()->getLabel(), $automaticEmailTypeLabel);
    }
}
