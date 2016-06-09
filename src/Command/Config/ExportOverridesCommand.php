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
      ->addOption(
        'directory',
        null,
        InputOption::VALUE_OPTIONAL,
        $this->trans('commands.config.export.overrides.arguments.directory')
      );
  }
  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output)
  {
  }
  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $io = new DrupalStyle($input, $output);
  }
}
