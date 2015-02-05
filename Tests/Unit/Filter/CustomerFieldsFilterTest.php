<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Filter;

use GlobalPlatform\Bundle\DomainBundle\Entity\CustomerField;
use GlobalPlatform\Bundle\DomainBundle\Entity\Website;
use GlobalPlatform\Bundle\DomainBundle\Filter\CustomerFieldsFilter;
use GlobalPlatform\Bundle\DomainBundle\Model\WebsiteTokenInterface;
use Phake;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class CustomerFieldsFilterTest
 */
class CustomerFieldsFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $securityContext;
    protected $filter;
    protected $token;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->token = Phake::mock(WebsiteTokenInterface::class);

        $this->securityContext = Phake::mock(SecurityContextInterface::class);
        Phake::when($this->securityContext)->getToken()->thenReturn($this->token);

        $this->filter = new CustomerFieldsFilter($this->securityContext);
    }

    /**
     * @param array $websiteArray
     * @param array $customerFields
     * @param array $expected
     *
     * @dataProvider provideCustomerFieldsAndWebsites
     */
    public function testFilterCustomerFieldsForCurrentUserWebsite($websiteArray, $customerFields, $expected)
    {
        Phake::when($this->securityContext)->isGranted('ROLE_ADMIN')->thenReturn(false);
        Phake::when($this->token)->getWebsites()->thenReturn($websiteArray);

        $result = $this->filter->filterFieldsForCurrentUserWebsite($customerFields);

        $this->assertSame($expected, $result);
    }

    /**
     * @param array $websiteArray
     * @param array $customerFields
     * @param array $expected
     *
     * @dataProvider provideCustomerFieldsAndWebsites
     */
    public function testFilterCustomerFieldsForAdmin($websiteArray, $customerFields, $expected)
    {
        Phake::when($this->securityContext)->isGranted('ROLE_ADMIN')->thenReturn(true);
        Phake::when($this->token)->getWebsites()->thenReturn($websiteArray);

        $result = $this->filter->filterFieldsForCurrentUserWebsite($customerFields);

        $this->assertSame($customerFields, $result);
        Phake::verify($this->securityContext, Phake::never())->getToken();
        Phake::verify($this->token, Phake::never())->getWebsites();
    }

    /**
     * @return array
     */
    public function provideCustomerFieldsAndWebsites()
    {
        $url1 = 'url1';
        $website1 = Phake::mock(Website::class);
        Phake::when($website1)->getUrl()->thenReturn($url1);

        $url2 = 'url2';
        $website2 = Phake::mock(Website::class);
        Phake::when($website2)->getUrl()->thenReturn($url2);

        $url3 = 'url3';
        $website3 = Phake::mock(Website::class);
        Phake::when($website3)->getUrl()->thenReturn($url3);

        $field1 = Phake::mock(CustomerField::class);
        Phake::when($field1)->getWebsite()->thenReturn($website1);

        $field2 = Phake::mock(CustomerField::class);
        Phake::when($field2)->getWebsite()->thenReturn($website2);

        $field3 = Phake::mock(CustomerField::class);
        Phake::when($field3)->getWebsite()->thenReturn($website3);

        $field4 = Phake::mock(CustomerField::class);
        Phake::when($field4)->getWebsite()->thenReturn(null);

        $websiteArray = array($website1, $website2);

        return array(
            array($websiteArray, array($field1), array($field1)),
            array($websiteArray, array($field1, $field2), array($field1, $field2)),
            array($websiteArray, array($field1, $field2, $field3), array($field1, $field2)),
            array($websiteArray, array($field3), array()),
            array($websiteArray, array($field4), array($field4)),
            array($websiteArray, array($field1, $field2, $field4), array($field1, $field2, $field4)),
            array($websiteArray, array($field4, $field3), array($field4)),
        );
    }
}
