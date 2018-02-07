<?php

namespace Training\Bundle\UserNamingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * IMPORTANT!!! This command is added just to demonstrate how to use integration data - please, don't follow this
 * approach in production code. Proper way to run import using an integration is automatic sync via cron command or
 * manual call of CLI command oro:cron:integration:sync with an appropriate parameters
 */
class NamingTypeIntegrationImportCommand extends ContainerAwareCommand
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('training:naming-type:integration:import')
            ->setDescription('Import all naming types using active naming type integrations');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importConnector = $this->container->get('training_user_naming.connector.naming_type_import');

        $statistics = $importConnector->importNameTypes();

        $output->writeln(sprintf('Invalid user naming types: %s.', $statistics['invalid']));
        $output->writeln(sprintf('Skipped user naming types: %s.', $statistics['skipped']));
        $output->writeln(sprintf('Added user naming types: %s.', $statistics['added']));
    }
}
