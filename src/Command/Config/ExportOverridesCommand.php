<?php
/**
 * @file
 * Contains \Drupal\Console\Command\Config\ExportCommand.
 */

namespace Drupal\Console\Command\Config;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\Shared\ContainerAwareCommandTrait;
use Drupal\Console\Style\DrupalStyle;

class ExportOverridesCommand extends Command
{
    use ContainerAwareCommandTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('config:export:overrides')
            ->setDescription($this->trans('commands.config.export.overrides.description'))
            ->addArgument(
                'directory',
                null,
                InputArgument::REQUIRED
            );
    }

//    /**
//     * {@inheritdoc}
//     */
//    protected function interact(InputInterface $input, OutputInterface $output)
//    {
//    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        global $config_directories;
        $io = new DrupalStyle($input, $output);
        $config_diff = $this->get('config_compare');
        // The directory argument is the target directory for storing the overrides.
        $directory = $input->getArgument('directory');
        // We always diff against the 'sync' directory, which is considered base config.
        $changes = $config_diff->getChangelist($config_directories[CONFIG_SYNC_DIRECTORY], false);
        if (!$changes->hasChanges) {
            $output->writeln($this->trans('commands.config.diff.messages.no-changes'));
            return;
        }
        // Iterate changes.
    }
}
