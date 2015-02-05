<?php

namespace FOX\ReferenceDataLoaderBundle\Tests\Unit\Factory;

use Doctrine\ORM\EntityManager;
use FOX\ReferenceDataLoaderBundle\Builder\OrderProductBuilder;
use FOX\ReferenceDataLoaderBundle\Entity\Subscription;
use Phake;

/**
 * Class OrderProductBuilderTest
 */
class OrderProductBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $primarySubscriptionProduct;
    protected $websitePrimarySubscriptionProduct;
    protected $entityManager;
    protected $subscription;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->websitePrimarySubscriptionProduct = Phake::mock('FOX\ReferenceDataLoaderBundle\Entity\WebsiteSubscriptionProduct');
        $this->primarySubscriptionProduct        = Phake::mock('FOX\ReferenceDataLoaderBundle\Entity\SubscriptionProduct');
        $this->entityManager                     = Phake::mock(EntityManager::class);
        $this->subscription                      = Phake::mock(Subscription::class);

        Phake::when($this->websitePrimarySubscriptionProduct)->getAtiAmount()->thenReturn(3900);
        Phake::when($this->websitePrimarySubscriptionProduct)->getTaxFreeAmount()->thenReturn(3783);
        Phake::when($this->websitePrimarySubscriptionProduct)->getVat()->thenReturn(117);
        Phake::when($this->websitePrimarySubscriptionProduct)->getProduct()->thenReturn($this->primarySubscriptionProduct);
        Phake::when($this->primarySubscriptionProduct)->getLabel()->thenReturn('primary subscription product');

        $this->orderProductBuilder = new OrderProductBuilder($this->entityManager);
    }

    /**
     * Test Order Product creation from a given WebsiteProduct without a subscription
     */
    public function testCreateFromWebsiteProduct()
    {
        $generatedOrderProduct = $this->orderProductBuilder->createFromWebsiteProduct($this->websitePrimarySubscriptionProduct);

        $this->assertInstanceOf('FOX\ReferenceDataLoaderBundle\Entity\OrderProduct', $generatedOrderProduct);
        $this->assertEquals($this->websitePrimarySubscriptionProduct, $generatedOrderProduct->getWebsiteProduct());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getAtiAmount(), $generatedOrderProduct->getAtiAmount());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getTaxFreeAmount(), $generatedOrderProduct->getTaxFreeAmount());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getVat(), $generatedOrderProduct->getVat());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getProduct()->getLabel(), $generatedOrderProduct->getLabel());
    }

    /**
     * Test Order Product creation from a given WebsiteProduct with a subscription
     */
    public function testCreateFromWebsiteProductWithASubscription()
    {
        $generatedOrderProduct = $this->orderProductBuilder->createFromWebsiteProduct($this->websitePrimarySubscriptionProduct, $this->subscription);

        $this->assertInstanceOf('FOX\ReferenceDataLoaderBundle\Entity\OrderProduct', $generatedOrderProduct);
        $this->assertEquals($this->websitePrimarySubscriptionProduct, $generatedOrderProduct->getWebsiteProduct());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getAtiAmount(), $generatedOrderProduct->getAtiAmount());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getTaxFreeAmount(), $generatedOrderProduct->getTaxFreeAmount());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getVat(), $generatedOrderProduct->getVat());
        $this->assertEquals($this->websitePrimarySubscriptionProduct->getProduct()->getLabel(), $generatedOrderProduct->getLabel());

        Phake::verify($this->subscription, Phake::times(1))->setOrderProduct(Phake::anyParameters());
    }
}
