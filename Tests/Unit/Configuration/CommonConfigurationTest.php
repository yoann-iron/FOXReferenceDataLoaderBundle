<?php

namespace GlobalPlatform\Bundle\DomainBundle\Tests\Unit\Configuration;

use GlobalPlatform\Bundle\DomainBundle\Configuration\CommonConfiguration;

/**
 * Class CommonConfigurationTest
 */
class CommonConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommonConfiguration
     */
    protected $commonConfiguration;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->commonConfiguration = new CommonConfiguration();
    }

    public function testGetSqlFileList()
    {
        $this->assertTrue(is_array($this->commonConfiguration->getSqlFileList()));
    }
}
