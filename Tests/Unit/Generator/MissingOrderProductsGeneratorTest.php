<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use GlobalPlatform\Bundle\DomainBundle\Generator\MissingOrderProductsGenerator;
use Phake;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class MissingOrderProductsGeneratorTest
 */
class MissingOrderProductsGeneratorTest extends \PHPUnit_Framework_TestCase
{
    protected $existingOrderProduct;
    protected $missingOrderProductsGenerator;
    protected $orderProductBuilder;
    protected $primarySubscription;
    protected $primarySubscriptionProduct;
    protected $secondarySubscription;
    protected $secondarySubscriptionProduct;
    protected $website;
    protected $websitePrimarySubscriptionProduct;
    protected $websiteSecondarySubscriptionProduct;
    protected $websiteSubscriptionProductRepository;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->existingOrderProduct                 = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\OrderProduct');
        $this->orderProductBuilder                  = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Builder\OrderProductBuilder');
        $this->primarySubscription                  = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\PrimarySubscription');
        $this->primarySubscriptionProduct           = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\SubscriptionProduct');
        $this->secondarySubscription                = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\SecondarySubscription');
        $this->secondarySubscriptionProduct         = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\SubscriptionProduct');
        $this->website                              = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteFrontAcquisition');
        $this->websitePrimarySubscriptionProduct    = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteSubscriptionProduct');
        $this->websiteSecondarySubscriptionProduct  = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\WebsiteSubscriptionProduct');
        $this->websiteSubscriptionProductRepository = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Repository\WebsiteSubscriptionProductRepository');

        Phake::when($this->primarySubscription)->getSecondarySubscription()->thenReturn($this->secondarySubscription);
        Phake::when($this->primarySubscription)->getAmount()->thenReturn(3900);
        Phake::when($this->secondarySubscription)->getAmount()->thenReturn(100);

        Phake::when($this->orderProductBuilder)->createFromWebsiteProduct(Phake::anyParameters())->thenReturn($this->existingOrderProduct);

        Phake::when($this->existingOrderProduct)->getWebsiteProduct()->thenReturn($this->websiteSecondarySubscriptionProduct);

        $this->missingOrderProductsGenerator = new MissingOrderProductsGenerator($this->websiteSubscriptionProductRepository, $this->orderProductBuilder);
    }

    /**
     * Test that nothing is created for an order without subscriptions
     */
    public function testCreateMissingOrderProductsForOrderWithoutSubscription()
    {
        $orderWithoutSubscription = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\Order');

        Phake::when($orderWithoutSubscription)->getSubscription()->thenReturn(null);
        Phake::when($orderWithoutSubscription)->getWebsite()->thenReturn($this->website);

        $generatedOrderProducts = $this->missingOrderProductsGenerator->createMissingOrderProductsForOrder($orderWithoutSubscription);
        Phake::verify($this->websiteSubscriptionProductRepository, Phake::never())->findOneBy(Phake::anyParameters());
        Phake::verify($this->orderProductBuilder, Phake::times(0))->createFromWebsiteProduct($this->websitePrimarySubscriptionProduct);
        $this->assertEquals(0, $generatedOrderProducts);
    }

    /**
     * Test that for an order with subscriptions the order products are created only if there is not any already
     * existing OrderProduct with the WebsiteProduct corresponding to the price of the subscription
     */
    public function testCreateMissingOrderProductsForOrderWithSubscriptions()
    {
        $orderWithSubscriptions = Phake::mock('GlobalPlatform\Bundle\DomainBundle\Entity\Order');

        Phake::when($orderWithSubscriptions)->getSubscription()->thenReturn($this->primarySubscription);
        Phake::when($orderWithSubscriptions)->getWebsite()->thenReturn($this->website);
        Phake::when($orderWithSubscriptions)->getOrderProducts()->thenReturn(new ArrayCollection([$this->existingOrderProduct]));

        Phake::when($this->websiteSubscriptionProductRepository)->findOneBy(array(
            'atiAmount' => $this->primarySubscription->getAmount(),
            'website'   => $orderWithSubscriptions->getWebsite(),
        ))->thenReturn($this->websitePrimarySubscriptionProduct);

        Phake::when($this->websiteSubscriptionProductRepository)->findOneBy(array(
            'atiAmount' => $this->secondarySubscription->getAmount(),
            'website'   => $orderWithSubscriptions->getWebsite(),
        ))->thenReturn($this->websiteSecondarySubscriptionProduct);

        $generatedOrderProducts = $this->missingOrderProductsGenerator->createMissingOrderProductsForOrder($orderWithSubscriptions);
        Phake::verify($this->websiteSubscriptionProductRepository, Phake::times(2))->findOneBy(Phake::anyParameters());
        Phake::verify($this->orderProductBuilder, Phake::times(1))->createFromWebsiteProduct($this->websitePrimarySubscriptionProduct, $this->primarySubscription);
        $this->assertEquals(1, $generatedOrderProducts);
    }
}
