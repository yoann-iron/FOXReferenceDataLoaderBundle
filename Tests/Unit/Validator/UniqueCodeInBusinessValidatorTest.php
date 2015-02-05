<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Validator;

use GlobalPlatform\Bundle\DomainBundle\Entity\Business;
use GlobalPlatform\Bundle\DomainBundle\Entity\Product;
use GlobalPlatform\Bundle\DomainBundle\Repository\ProductRepository;
use GlobalPlatform\Bundle\DomainBundle\Validator\Constraints\UniqueCodeInBusiness;
use GlobalPlatform\Bundle\DomainBundle\Validator\Constraints\UniqueCodeInBusinessValidator;
use Symfony\Component\Validator\ExecutionContext;
use Phake;

/**
 * Class UniqueCodeInBusinessValidatorTest
 */
class UniqueCodeInBusinessValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var UniqueCodeInBusinessValidator
     */
    protected $uniqueCodeInBusinessValidator;
    
    /**
     * @var UniqueCodeInBusiness
     */
    protected $uniqueCodeInBusinessConstraint;

    /**
     * @var ExecutionContext
     */
    protected $context;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->productRepository = Phake::mock(ProductRepository::class);
        $this->uniqueCodeInBusinessValidator = new UniqueCodeInBusinessValidator($this->productRepository);

        $this->context = Phake::mock(ExecutionContext::class);

        $this->uniqueCodeInBusinessConstraint = new UniqueCodeInBusiness();
        $this->uniqueCodeInBusinessValidator->initialize($this->context);
    }

    /**
     * Test if the validator does not add any violation when a product has a unique code in its business
     */
    public function testValidate()
    {
        $product  = Phake::mock(Product::class);
        $business = Phake::mock(Business::class);

        Phake::when($product)->getCode()->thenReturn('NEW_CODE');
        Phake::when($product)->getBusiness()->thenReturn($business);

        Phake::when($this->productRepository)->findBy(Phake::anyParameters())->thenReturn([]);

        $this->uniqueCodeInBusinessValidator->validate($product, $this->uniqueCodeInBusinessConstraint);
        Phake::verify($this->context, Phake::never())->addViolationAt(Phake::anyParameters());
    }

    /**
     * Test if the validator doesn't add any violation when updating a product.
     */
    public function testValideWhenUpdating()
    {
        $product  = Phake::mock(Product::class);
        $business = Phake::mock(Business::class);

        Phake::when($product)->getCode()->thenReturn('ALREADY_USED_CODE');
        Phake::when($product)->getBusiness()->thenReturn($business);
        Phake::when($product)->getId()->thenReturn(2);

        $productWithSameCode = Phake::mock(Product::class);
        Phake::when($productWithSameCode)->getId()->thenReturn(2);

        Phake::when($this->productRepository)->findBy(Phake::anyParameters())->thenReturn([$productWithSameCode]);

        $this->uniqueCodeInBusinessValidator->validate($product, $this->uniqueCodeInBusinessConstraint);
        Phake::verify($this->context, Phake::never())->addViolationAt(Phake::anyParameters());
    }

    /**
     * Test if the validator adds any violation when a product does not have a unique code in its business
     */
    public function testAddViolation()
    {
        $product  = Phake::mock(Product::class);
        $business = Phake::mock(Business::class);

        Phake::when($product)->getCode()->thenReturn('ALREADY_USED_CODE');
        Phake::when($product)->getBusiness()->thenReturn($business);
        Phake::when($product)->getId()->thenReturn(2);

        $productWithSameCode = Phake::mock(Product::class);
        Phake::when($productWithSameCode)->getId()->thenReturn(3);

        Phake::when($this->productRepository)->findBy(Phake::anyParameters())->thenReturn([$productWithSameCode]);

        $this->uniqueCodeInBusinessValidator->validate($product, $this->uniqueCodeInBusinessConstraint);
        Phake::verify($this->context, Phake::times(1))->addViolationAt(Phake::anyParameters());
    }
}
