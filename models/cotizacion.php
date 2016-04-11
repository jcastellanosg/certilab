<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/17/2015
 * Time: 4:23 PM
 */


defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/models/crear.php';
JLoader::registerNamespace('Respect', JPATH_COMPONENT . '/helpers/validator');
use Respect\Validation\Validator as v;

class CertilabModelCotizacion extends CertilabModelCrear
{

public $mcontext ;
public $mtable;
public $misNew;


    public function save($data)
    {
        $dispatcher = JEventDispatcher::getInstance();
        $table      = $this->getTable();
        $context    = $this->option . '.' . $this->name;

        if ((!empty($data['tags']) && $data['tags'][0] != ''))
        {
            $table->newTags = $data['tags'];
        }

        $key = $table->getKeyName();
        $pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
        $isNew = true;

        // Include the plugins for the save events.
        JPluginHelper::importPlugin($this->events_map['save']);

        // Allow an exception to be thrown.
        try
        {
            // Load the row if saving an existing record.
            if ($pk > 0)
            {
                $table->load($pk);
                $isNew = false;
            }

            // Bind the data.
            if (!$table->bind($data))
            {
                $this->setError($table->getError());

                return false;
            }

            // Prepare the row for saving
            $this->prepareTable($table);

            // Check the data.
            if (!$table->check())
            {
                $this->setError($table->getError());

                return false;
            }

            // Trigger the before save event.
            $result = $dispatcher->trigger($this->event_before_save, array($context, $table, $isNew));

            if (in_array(false, $result, true))
            {
                $this->setError($table->getError());

                return false;
            }

            // Store the data.
            if (!$table->store())
            {
                $this->setError($table->getError());

                return false;
            }

            // Clean the cache.
            $this->cleanCache();

            // Trigger the after save event.
            $this->mcontext = $context;
            $this->mtable = $table;
            $this->misNew = $isNew;
            $dispatcher->trigger($this->event_after_save, array($context, $table, $isNew));

        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());

            return false;
        }

        if (isset($table->$key))
        {
            $this->setState($this->getName() . '.id', $table->$key);
        }

        $this->setState($this->getName() . '.new', $isNew);

        return true;
    }

}
