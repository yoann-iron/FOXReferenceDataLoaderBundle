<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Validator;

use GlobalPlatform\Bundle\DomainBundle\Entity\Customer;
use GlobalPlatform\Bundle\DomainBundle\Repository\CustomerRepository;
use GlobalPlatform\Bundle\DomainBundle\Validator\Constraints\UniqueEmail;
use GlobalPlatform\Bundle\DomainBundle\Validator\Constraints\UniqueEmailValidator;
use Symfony\Component\Validator\ExecutionContext;
use Phake;

/**
 * Class UniqueEmailValidatorTest
 */
class UniqueEmailValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var UniqueEmailValidator
     */
    protected $uniqueEmailValidator;
    
    /**
     * @var UniqueEmail
     */
    protected $uniqueEmailConstraint;

    /**
     * @var ExecutionContext
     */
    protected $context;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        $this->customerRepository = Phake::mock(CustomerRepository::class);
        $customerWithSameMail = Phake::mock(Customer::class);

        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('mainEmail1@example.org', Phake::anyParameters())->thenReturn([$customerWithSameMail]);
        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('mainEmail2@example.org', Phake::anyParameters())->thenReturn(null);
        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('mainEmail3@example.org', Phake::anyParameters())->thenReturn(null);
        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('mainEmail4@example.org', Phake::anyParameters())->thenReturn(null);

        Phake::when($this->customerRepository)->findOneByEmail('secondaryEmail1@example.org')->thenReturn(null);
        Phake::when($this->customerRepository)->findOneByEmail('secondaryEmail2@example.org')->thenReturn($customerWithSameMail);
        Phake::when($this->customerRepository)->findOneByEmail('secondaryEmail3@example.org')->thenReturn(null);
        Phake::when($this->customerRepository)->findOneByEmail('secondaryEmail4@example.org')->thenReturn(null);

        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('secondaryEmail1@example.org', Phake::anyParameters())->thenReturn(null);
        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('secondaryEmail2@example.org', Phake::anyParameters())->thenReturn(null);
        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('secondaryEmail3@example.org', Phake::anyParameters())->thenReturn([$customerWithSameMail]);
        Phake::when($this->customerRepository)->findOtherCustomersWithSecondaryEmail('secondaryEmail4@example.org', Phake::anyParameters())->thenReturn(null);

        $this->uniqueEmailValidator = new UniqueEmailValidator($this->customerRepository);

        $this->context = \Phake::mock(ExecutionContext::class);

        $this->uniqueEmailConstraint = new UniqueEmail();
        $this->uniqueEmailValidator->initialize($this->context);
    }
    
    
    /**
     * @param Customer $customer
     * @param int $expectedViolations
     *
     * @dataProvider customerProvider
     */
    public function testValidate(Customer $customer, $expectedViolations)
    {
        $this->uniqueEmailValidator->validate($customer, $this->uniqueEmailConstraint);
        Phake::verify($this->context, \Phake::times($expectedViolations))->addViolation(Phake::anyParameters());
    }

    /**
     * @return array
     */
    public function customerProvider()
    {
        $customer1 = Phake::mock(Customer::class);
        $customer2 = Phake::mock(Customer::class);
        $customer3 = Phake::mock(Customer::class);
        $customer4 = Phake::mock(Customer::class);

        Phake::when($customer1)->getEmail()->thenReturn('mainEmail1@example.org');
        Phake::when($customer2)->getEmail()->thenReturn('mainEmail2@example.org');
        Phake::when($customer3)->getEmail()->thenReturn('mainEmail3@example.org');
        Phake::when($customer4)->getEmail()->thenReturn('mainEmail4@example.org');

        Phake::when($customer1)->getSecondaryEmails()->thenReturn(['secondaryEmail1@example.org']);
        Phake::when($customer2)->getSecondaryEmails()->thenReturn(['secondaryEmail2@example.org']);
        Phake::when($customer3)->getSecondaryEmails()->thenReturn(['secondaryEmail3@example.org']);
        Phake::when($customer4)->getSecondaryEmails()->thenReturn(['secondaryEmail4@example.org']);


        return [
            [$customer1, 1],
            [$customer2, 1],
            [$customer3, 1],
            [$customer4, 0],
        ];
    }
}
