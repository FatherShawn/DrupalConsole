<?php
/**
 * Created by PhpStorm.
 * User: shawnduncan
 * Date: 6/10/16
 * Time: 3:12 PM
 */

namespace Drupal\Console\Utils;

use Drupal\Console\Command\Shared\ContainerAwareCommandTrait;
use Drupal\Console\Utils\ChangeList;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\StorageComparer;

class ConfigCompare
{
    use ContainerAwareCommandTrait;

    /**
     * @var \Drupal\Core\Config\CachedStorage
     */
    protected $activeStorage;

    /**
     * @var \Drupal\Core\Config\CachedStorage
     */
    protected $configManager;

    function __construct()
    {
        $this->activeStorage = $this->getDrupalService('config.storage');
        $this->configManager = $this->getDrupalService('config.manager');
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

        if ($reverse) {
            $config_comparer = new StorageComparer($source_storage, $this->activeStorage, $this->configManager);
        } else {
            $config_comparer = new StorageComparer($this->activeStorage, $source_storage, $this->configManager);
        }
        $config_comparer->createChangelist();
        $list = [];
        foreach ($config_comparer->getAllCollectionNames() as $collection) {
            $list[$collection] = $config_comparer->getChangelist(null, $collection);
        }
        return new ChangeList($config_comparer->hasChanges(), $list);
    }
}