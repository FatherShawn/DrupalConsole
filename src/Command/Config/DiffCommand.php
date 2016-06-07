<?php
/**
 * @file
 * Contains \Drupal\Console\Command\Config\DiffCommand.
 */

namespace Drupal\Console\Command\Config;

use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\StorageComparer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Drupal\Console\Command\Shared\ContainerAwareCommandTrait;
use Drupal\Console\Style\DrupalStyle;
use Drupal\Console\Utils\ChangeList;

class DiffCommand extends Command
{
    use ContainerAwareCommandTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('config:diff')
            ->setDescription($this->trans('commands.config.diff.description'))
            ->addArgument(
                'directory',
                InputArgument::OPTIONAL,
                $this->trans('commands.config.diff.arguments.directory')
            )
            ->addOption(
                'reverse',
                null,
                InputOption::VALUE_NONE,
                $this->trans('commands.config.diff.options.reverse')
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        global $config_directories;
        $io = new DrupalStyle($input, $output);

        $directory = $input->getArgument('directory');
        if (!$directory) {
            $directory = $io->choice(
                $this->trans('commands.config.diff.questions.directories'),
                array_keys($config_directories),
                CONFIG_SYNC_DIRECTORY
            );

            $input->setArgument('directory', $config_directories[$directory]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new DrupalStyle($input, $output);
        $directory = $input->getArgument('directory');
        $changes = $this->getChangelist($directory, $input->getOption('reverse'));
        if (!$changes->hasChanges) {
            $output->writeln($this->trans('commands.config.diff.messages.no-changes'));
            return;
        }
        $this->outputDiffTable($io, $changes->items);
    }

    /**
     * Outputs a table of configuration changes.
     *
     * @param DrupalStyle $io
     *   The io.
     * @param array $change_list
     *   The list of changes from the StorageComparer.
     */
    protected function outputDiffTable(DrupalStyle $io, array $change_list)
    {
        $header = [
            $this->trans('commands.config.diff.table.headers.collection'),
            $this->trans('commands.config.diff.table.headers.config-name'),
            $this->trans('commands.config.diff.table.headers.operation'),
        ];
        $rows = [];
        foreach ($change_list as $collection => $changes) {
            foreach ($changes as $operation => $configs) {
                foreach ($configs as $config) {
                    $rows[] = [
                        $collection,
                        $config,
                        sprintf("<$operation>%s</$operation>", $operation),
                    ];
                }
            }
        }
        $io->table($header, $rows);
    }

    /**
     * @param string $directory
     *   The directory of config files to diff against.
     * @param boolean $reverse
     *   Should the diff be reversed?
     *
     * @return \Drupal\Console\Utils\ChangeList
     */
    public function getChangelist($directory, $reverse = false)
    {
        $source_storage = new FileStorage($directory);
        $active_storage = $this->getDrupalService('config.storage');
        $config_manager = $this->getDrupalService('config.manager');

        if ($reverse) {
            $config_comparer = new StorageComparer($source_storage, $active_storage, $config_manager);
        } else {
            $config_comparer = new StorageComparer($active_storage, $source_storage, $config_manager);
        }
        $list = [];
        foreach ($config_comparer->getAllCollectionNames() as $collection) {
            $list[$collection] = $config_comparer->getChangelist(null, $collection);
        }
        return new ChangeList($config_comparer->createChangelist()->hasChanges(), $list);
    }
}
