<?php

namespace RedefineLab\Config;

use Symfony\Component\Yaml\Yaml;

class Config
{

    /**
     * @var array The entire config array.
     */
    private $config;

    public function __construct()
    {
        $this->config = array();
    }

    public function addYamlConfig($file)
    {
        try
        {
            if (!is_file($file) || !is_readable($file))
            {
                return false;
            }

            $parsedFile = Yaml::parse($file);
            $this->config = $this->arrayMergeRecursive($this->config, $parsedFile);
            return true;
        } catch (\Exception $e)
        {
            return false;
        }
    }

    /**
     * Returns the entire config array. For debug / test purposes.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->config;
    }

    public function get($key)
    {
        $stack = explode('.', $key);

        $pointer = $this->config;
        foreach ($stack as $level)
        {
            if (!array_key_exists($level, $pointer))
            {
                return null;
            }

            $pointer = $pointer[$level];
        }

        return $pointer;
    }

    private function arrayMergeRecursive($oldArray, $newArray)
    {
        foreach ($newArray as $key => $value)
        {
            // Key does not exist in older array or
            // key exists in older array but value is not an array
            if (!array_key_exists($key, $oldArray) || !is_array($oldArray[$key]))
            {
                // We overwrite older value
                $oldArray[$key] = $value;
            } else
            {
                // Key exists in older array and value is an array
                // We recurse
                $oldArray[$key] = $this->arrayMergeRecursive($oldArray[$key], $newArray[$key]);
            }
        }

        return $oldArray;
    }

}