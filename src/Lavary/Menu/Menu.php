<?php

namespace Lavary\Menu;

class Menu
{
    /**
     * Menu collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * List of menu builders.
     *
     * @var []Builder
     */
    protected $menu = [];

    /**
     * Initializing the Menu manager
     */
    public function __construct()
    {
        // creating a collection for storing menu builders
        $this->collection = new Collection();
    }

    /**
     * Check if a menu builder exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name)
    {
        return array_key_exists($name, $this->menu);
    }

    /**
     * Create a new menu builder instance.
     *
     * @param string   $name
     * @param callable $callback
     *
     * @return Builder
     */
    public function makeOnce($name, $callback)
    {
        if ($this->exists($name)) {
            return null;
        }

        return $this->make($name, $callback);
    }

    /**
     * Create a new menu builder instance.
     *
     * @param string   $name
     * @param callable $callback
     * @param array    $config
     *
     * @return Builder
     */
    public function make($name, $callback, $config = [])
    {
        if (!is_callable($callback)) {
            return null;
        }

        if (!array_key_exists($name, $this->menu)) {
            $this->menu[$name] = new Builder($name, array_merge($this->loadConf(), $config));
        }

        // Registering the items
        call_user_func($callback, $this->menu[$name]);

        // Storing each menu instance in the collection
        $this->collection->put($name, $this->menu[$name]);

        // Make the instance available in all views
        \View::share($name, $this->menu[$name]);

        return $this->menu[$name];
    }

    /**
     * Loads and merges configuration data.
     *
     * @return array
     */
    public function loadConf()
    {
        return config('menu');
    }

    /**
     * Return Menu builder instance from the collection by key.
     *
     * @param string $key
     *
     * @return Builder
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * Return Menu builder collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Alias for getCollection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->collection;
    }
}
