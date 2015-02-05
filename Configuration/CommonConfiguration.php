<?php

namespace GlobalPlatform\Bundle\DomainBundle\Configuration;

/**
 * Class CommonConfiguration
 */
class CommonConfiguration extends BaseConfiguration
{
    const CONFIGURATION_NAME        = 'configuration.common';
    const CONFIGURATION_DESCRIPTION = 'Load base configuration of GP';

    /**
     * {@inheritDoc}
     */
    public function getSqlFileList()
    {
        $domainSQL = sprintf('%s/../Resources/sql/', __DIR__);
        $userSQL   = sprintf('%s/../../UserBundle/Resources/sql/', __DIR__);

        return array(
            sprintf('%sApiClientData.sql', $domainSQL),
            sprintf('%sAutomaticEmailTypeData.sql', $domainSQL),
            sprintf('%sOrderStatusData.sql', $domainSQL),
            sprintf('%sProductTypeData.sql', $domainSQL),
            sprintf('%sRefundTypeData.sql', $domainSQL),
            sprintf('%sUserData.sql', $userSQL),
        );
    }
}
