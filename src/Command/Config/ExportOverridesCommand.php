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
   * A static array map of operations -> color strings.
   *
   * @see http://symfony.com/doc/current/components/console/introduction.html#coloring-the-output
   *
   * @var array
   */
  protected static $operationColours = [
    'delete' => '<fg=red>%s</fg=red>',
    'update' => '<fg=yellow>%s</fg=yellow>',
    'create' => '<fg=green>%s</fg=green>',
    'default' => '%s',
  ];
  
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
