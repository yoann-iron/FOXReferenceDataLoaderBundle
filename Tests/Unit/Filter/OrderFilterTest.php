<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Filter;

use GlobalPlatform\Bundle\ApiBundle\Security\Authentication\Token\OAuth2Token;
use GlobalPlatform\Bundle\DomainBundle\Entity\Website;
use GlobalPlatform\Bundle\DomainBundle\Model\WebsiteTokenInterface;
use Phake;
use GlobalPlatform\Bundle\DomainBundle\Entity\Order;
use GlobalPlatform\Bundle\DomainBundle\Filter\OrderFilter;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * TODO: Must be refactored, this test is not Unit !
 */
class OrderFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrderFilter
     */
    protected $orderFilter;

    protected $securityContext;
    protected $token;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->token = Phake::mock(WebsiteTokenInterface::class);

        $this->securityContext = Phake::mock(SecurityContextInterface::class);
        Phake::when($this->securityContext)->getToken()->thenReturn($this->token);

        $this->orderFilter = new OrderFilter($this->securityContext);
    }
    /**
     * @param array  $websiteArray
     * @param array  $orders
     * @param array  $expectedResult
     *
     * @dataProvider filterOrdersForCurrentUserWebsiteProvider
     */
    public function testfilterOrdersForCurrentUserWebsite($websiteArray, $orders, $expectedResult)
    {
        Phake::when($this->securityContext)->isGranted('ROLE_ADMIN')->thenReturn(false);
        Phake::when($this->token)->getWebsites()->thenReturn($websiteArray);

        $result = $this->orderFilter->filterOrdersForCurrentUserWebsite($orders);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @param array  $websiteArray
     * @param array  $orders
     * @param array  $expectedResult
     *
     * @dataProvider filterOrdersForCurrentUserWebsiteProvider
     */
    public function testfilterOrdersForAdmin($websiteArray, $orders, $expectedResult)
    {
        Phake::when($this->securityContext)->isGranted('ROLE_ADMIN')->thenReturn(true);
        Phake::when($this->token)->getWebsites()->thenReturn($websiteArray);

        $result = $this->orderFilter->filterOrdersForCurrentUserWebsite($orders);

        $this->assertSame($orders, $result);
        Phake::verify($this->securityContext, Phake::never())->getToken();
        Phake::verify($this->token, Phake::never())->getWebsites();
    }

    /**
     * @return array
     */
    public function filterOrdersForCurrentUserWebsiteProvider()
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

        $order1 = Phake::mock(Order::class);
        Phake::when($order1)->getWebsite()->thenReturn($website1);
        $order2 = Phake::mock(Order::class);
        Phake::when($order2)->getWebsite()->thenReturn($website2);
        $order3 = Phake::mock(Order::class);
        Phake::when($order3)->getWebsite()->thenReturn($website3);

        $websiteArray = array($website1, $website2);

        return array(
            array($websiteArray, array($order1), array($order1)),
            array($websiteArray, array($order1, $order2), array($order1, $order2)),
            array($websiteArray, array($order1, $order2, $order3), array($order1, $order2)),
            array($websiteArray, array($order3), array()),
        );
    }
}
