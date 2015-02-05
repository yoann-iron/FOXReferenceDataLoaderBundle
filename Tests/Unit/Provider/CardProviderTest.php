<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Provider;

use GlobalPlatform\Bundle\DomainBundle\Entity\Card;
use GlobalPlatform\Bundle\DomainBundle\Provider\CardProvider;
use GlobalPlatform\Bundle\DomainBundle\Repository\CardRepository;
use Phake;

/**
 * Class CardProviderTest
 */
class CardProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CardProvider
     */
    protected $cardProvider;

    /**
     * @var CardRepository
     */
    protected $cardRepositoryMock;

    public function setUp()
    {
        parent::setUp();

        $this->cardRepositoryMock   = Phake::mock(CardRepository::class);
        $this->cardProvider = new CardProvider($this->cardRepositoryMock);
    }

    public function loadByCardHashDataProvider()
    {
        return array(
            array('toto', new Card()),
            array('tata', null),
        );
    }

    /**
     * @dataProvider loadByCardHashDataProvider
     * @param string $cardHash
     * @param Card $card
     */
    public function testLoadByCardHash($cardHash, $card)
    {
        Phake::when($this->cardRepositoryMock)->findOneBy(array('cardHash' => $cardHash))->thenReturn($card);

        if (null === $card) {
            $this->setExpectedException('GlobalPlatform\Bundle\DomainBundle\Exception\CardNotFoundException');
        }

        $this->assertSame($card, $this->cardProvider->loadByCardHash($cardHash));
    }
}
