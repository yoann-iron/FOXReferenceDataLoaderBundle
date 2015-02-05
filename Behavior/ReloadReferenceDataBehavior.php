<?php

namespace FOX\ReferenceDataLoaderBundle\Behavior;

use FOX\ReferenceDataLoaderBundle\Configuration\ConfigurationChainInterface;
use FOX\ReferenceDataLoaderBundle\Executor\ConfigurationExecutorInterface;
use FOX\ReferenceDataLoaderBundle\Configuration\ConfigurationInterface;

/**
 * Class ReloadReferenceDataBehavior
 */
class ReloadReferenceDataBehavior
{
    /**
     * @var ConfigurationChainInterface
     */
    protected $configurationChain;

    /**
     * @var ConfigurationExecutorInterface
     */
    protected $configurationExecutor;

    /**
     * @param ConfigurationChainInterface    $configurationChain
     * @param ConfigurationExecutorInterface $configurationExecutor
     */
    public function __construct(ConfigurationChainInterface $configurationChain, ConfigurationExecutorInterface $configurationExecutor)
    {
        $this->configurationChain    = $configurationChain;
        $this->configurationExecutor = $configurationExecutor;
    }

    /**
     * @param string $configurationIdentifier
     *
     * @throws \Exception
     */
    public function reloadReferenceData($configurationIdentifier)
    {
        $configuration = $this->getConfiguration($configurationIdentifier);

        try {
            $this->configurationExecutor->beginTransaction();
            /**
             * @var ConfigurationInterface $configuration
             */
            foreach ($configuration->getSqlFileList() as $sqlFile) {
                $this->configurationExecutor->execute($sqlFile);
            }

            $this->configurationExecutor->commit();
        } catch (\Exception $e) {
            $this->configurationExecutor->rollBack();

            throw $e;
        }
    }

    /**
     * @param string $configurationIdentifier
     *
     * @return ConfigurationChainInterface
     */
    protected function getConfiguration($configurationIdentifier)
    {
        return $this->configurationChain->getConfiguration($configurationIdentifier);
    }
}
