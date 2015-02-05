<?php

namespace FOX\ReferenceDataLoaderBundle\Command;

use FOX\ReferenceDataLoaderBundle\Configuration\CommonConfiguration;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadReferenceDataCommand
 */
class LoadReferenceDataCommand extends ContainerAwareCommand
{
    const DEFAULT_CONFIGURATION       = 'list';
    const ARGUMENT_CONFIGURATION_NAME = 'configuration';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $configurationIdentifier;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output                  = $output;
        $this->configurationIdentifier = $input->getArgument(self::ARGUMENT_CONFIGURATION_NAME);

    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('gp:reference-data:load')
            ->setDescription('Load GP reference data')
            ->setHelp(<<<EOT
Load SQL file into database for a project configuration.
EOT
            )
            ->addArgument(
                self::ARGUMENT_CONFIGURATION_NAME,
                InputArgument::OPTIONAL,
                'The configuration name',
                self::DEFAULT_CONFIGURATION
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->configurationIdentifier === self::DEFAULT_CONFIGURATION) {
            $this->generateList();
        } else {
            $this->loadReference($this->configurationIdentifier);
        }
    }

    /**
     * Generate list of configuration
     */
    protected function generateList()
    {
        $configurationList = $this->getContainer()->get('gp_domain.configuration.chain')->getConfigurationList();
        $table             = $this->getHelperSet()->get('table');
        $table->setHeaders(array('NAME', 'DESCRIPTION'));

        foreach ($configurationList as $configuration) {
            $table->addRow(
                array(
                    $configuration->getName(),
                    $configuration->getDescription()
                )
            );
        }

        $table->render($this->output);
    }

    /**
     * Load reference data
     *
     * @param string $configurationIdentifier
     */
    protected function loadReference($configurationIdentifier)
    {
        $this->output->writeln('<info>Loading Configuration : ' . $configurationIdentifier . '</info>');
        $reloadReferenceDataBehavior = $this->getContainer()->get('gp_domain.behavior.reload_reference_data');
        $reloadReferenceDataBehavior->reloadReferenceData($configurationIdentifier);

        $this->output->writeln('DONE');
    }
}
