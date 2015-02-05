<?php

namespace FOX\ReferenceDataLoaderBundle\Configuration;

/**
 * Class AutomaticEmailConfiguration
 */
class AutomaticEmailConfiguration extends BaseConfiguration
{
    const CONFIGURATION_NAME        = 'configuration.automatic_email';
    const CONFIGURATION_DESCRIPTION = 'Load base configuration of automatic email template and reassign old';

    /**
     * {@inheritDoc}
     */
    public function getSqlFileList()
    {
        $domainSQL = sprintf('%s/../Resources/sql/AutomaticEmailType/', __DIR__);

        return array(
            sprintf('%sAutomaticEmailTypeData.sql', $domainSQL),
            sprintf('%sRelationalAutomaticEmailTemplateWithType.sql', $domainSQL),
        );
    }
}
