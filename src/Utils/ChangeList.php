<?php
/**
 * @file
 *  A Utility class for passing around config change list data.
 */

namespace Drupal\Console\Utils;


/**
 * Class ChangeList: A Utility class for passing around config change list data.
 */
class ChangeList
{
    /**
     * @var boolean
     */
    protected $hasChanges;

    /**
     * @var array
     */
    protected $items;

    /**
     * ChangeList constructor.
     *
     * @param boolean $changes
     * @param array $list
     */
    function __construct($changes, $list)
    {
        $this->hasChanges = $changes;
        $this->items = $list;
    }

    /**
     * Using __get() to create readonly properties.
     *
     * @param $name
     * The property name.
     *
     * @return array|bool
     */
    function __get($name)
    {
        switch ($name) {
            case 'hasChanges':
                return $this->hasChanges;
            case 'items':
                return $this->items;
            default:
                throw new \InvalidArgumentException;
        }
    }
}