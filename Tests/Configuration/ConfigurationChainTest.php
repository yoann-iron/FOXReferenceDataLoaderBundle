<?php

namespace FOX\ReferenceDataLoaderBundle\Tests\Unit\Configuration;

use FOX\ReferenceDataLoaderBundle\Configuration\CommonConfiguration;
use FOX\ReferenceDataLoaderBundle\Configuration\ConfigurationChain;
use FOX\ReferenceDataLoaderBundle\Configuration\ConfigurationInterface;

/**
 * Class ConfigurationChainTest
 */
class ConfigurationChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigurationChain
     */
    protected $configurationChain;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->configurationChain = new ConfigurationChain();
    }

    /**
     * DataProvider
     *
     * @return array
     */
    public function setConfigurationListDataProvider()
    {
        $configurationList = array(
            new CommonConfiguration(),
        );

        return array(
            array($configurationList),
        );
    }

    /**
     * @param ConfigurationInterface[] $configurationList
     *
     * @dataProvider setConfigurationListDataProvider
     */
    public function testSetConfigurationList($configurationList)
    {
        $configurationChainStub = $this->getMock(ConfigurationChain::class, array('addConfiguration'), array(), '', null);
        $configurationChainStub->expects($this->atLeastOnce(count($configurationList)))->method('addConfiguration');
        $configurationChainStub->setConfigurationList($configurationList);
    }

    /**
     * DataProvider
     *
     * @return array
     */
    public function getConfigurationDataProvider()
    {
        $commonConfiguration = new CommonConfiguration();

        return array(
            array($commonConfiguration, CommonConfiguration::CONFIGURATION_NAME, $commonConfiguration),
            array($commonConfiguration, 'toto',                                  null),
        );
    }

    /**
     * @param ConfigurationInterface      $configuration
     * @param string                      $identifier
     * @param ConfigurationInterface|null $expectedReturn
     *
     * @dataProvider getConfigurationDataProvider
     */
    public function testGetConfiguration($configuration, $identifier, $expectedReturn)
    {
        $this->configurationChain->addConfiguration($configuration);

        if (null === $expectedReturn) {
            $this->setExpectedException('FOX\ReferenceDataLoaderBundle\Exception\ConfigurationNotFoundException');
        }

        $this->assertEquals($expectedReturn, $this->configurationChain->getConfiguration($identifier));
    }
}
