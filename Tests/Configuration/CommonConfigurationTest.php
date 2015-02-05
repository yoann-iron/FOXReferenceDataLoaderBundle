<?php

namespace FOX\ReferenceDataLoaderBundle\Tests\Unit\Configuration;

use FOX\ReferenceDataLoaderBundle\Configuration\CommonConfiguration;

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
