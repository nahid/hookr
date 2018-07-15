<?php

namespace Nahid\Hookr;

class Hook
{
    protected static $hookActions = [];
    protected static $hookFilters = [];
    protected static $instance = null;

    /**
     * make singleton
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * declare action hook
     *
     * @param string $name
     * @param array $params
     */
    public static function action($name, $params = [])
    {
        if (isset(static::$hookActions[$name])) {
            $actions = static::makePriority($name);
            foreach ($actions as $callback) {
                static::executeAction($callback['action'], $params);
            }
        }
    }

    /**
     * bind action with hook
     *
     * @param   string  $name
     * @param   callable $callback
     * @param   int $priority
     */
    public static function bindAction($name, $callback, $priority = 0)
    {
        if (!isset(static::$hookActions[$name])) {
            static::$hookActions[$name][] = [
                'action' => $callback,
                'priority' => $priority,
            ];
        } else {
            array_push(static::$hookActions[$name], [
                'action' => $callback,
                'priority' => $priority,
            ]);
        }

    }

    /**
     * declare filter hook
     *
     * @param   string $name
     * @param   mixed $data
     * @param   array $params
     * @return  mixed
     */
    public static function filter($name, $data, $params = [])
    {
        if (isset(static::$hookFilters[$name])) {
            $filters = static::makePriority($name, 'filter');
            foreach ($filters as $callback) {
                $data = static::executeFilter($callback['action'], $data, $params);
            }
        }

        return $data;
    }

    /**
     * bind filter with hook
     *
     * @param     string $name
     * @param     callable $callback
     * @param     int $priority
     */
    public static function bindFilter($name, $callback, $priority = 0)
    {
        if (!isset(static::$hookFilters[$name])) {
            static::$hookFilters[$name][] = [
                'action' => $callback,
                'priority' => $priority,
            ];
        } else {
            array_push(static::$hookFilters[$name], [
                'action' => $callback,
                'priority' => $priority,
            ]);
        }
    }

    /**
     * make class from string with array param
     *
     * @param       string $class
     * @param       array $params
     * @return object
     */
    protected static function newClassInstance($class, $params = [])
    {
        $reflection = new \ReflectionClass($class);

        return $reflection->newInstanceArgs($params);
    }

    /**
     * apply action from hook
     *
     * @param callable $action
     * @param array $params
     * @return mixed
     */
    protected static function executeAction($action, $params)
    {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            $action = explode('@', $action);
            $func = static::makeMethodParam($action[1]);
            $class = static::makeMethodParam($action[0]);
            $instance = static::newClassInstance($class['method'], $class['params']);
            if (count($action) > 1) {
                return call_user_func_array([$instance, $func['method']], $params);
            }
        }
    }

    /**
     * @param       callable $action
     * @param       mixed $data
     * @param       array $params
     * @return      mixed
     */
    protected static function executeFilter($action, $data, $params = [])
    {
        if (is_callable($action)) {
            array_unshift($params, $data);

            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            $action = explode('@', $action);
            $func = static::makeMethodParam($action[1]);
            array_unshift($params, $data);
            $class = static::makeMethodParam($action[0]);
            $instance = static::newClassInstance($class['method'], $class['params']);
            if (count($action) > 1) {
                return call_user_func_array([$instance, $func['method']], $params);
            }
        }
    }

    /**
     * make param from input string
     *
     * @param string $method
     * @return array
     */
    protected static function makeMethodParam($method)
    {
        $methods = explode(':', $method);
        $param = [];
        $method = $methods[0];
        if (isset($methods[1])) {
            $param = explode(',', $methods[1]);
        }

        return [
            'method' => $method,
            'params' => $param,
        ];
    }

    /**
     * compare two input
     *
     * @param int $value1
     * @param int $value2
     * @return int
     */
    protected static function compare($value1, $value2)
    {
        if ($value1['priority'] == $value2['priority']) {
            return -1;
        }

        return ($value1['priority'] < $value2['priority']) ? -1 : 1;
    }

    /**
     * make action/filter priority
     *
     * @param        string $name
     * @param        string $type
     * @return       mixed
     */
    protected static function makePriority($name, $type = 'action')
    {
        if ($type == 'action') {
            usort(static::$hookActions[$name], [new self, 'compare']);

            return static::$hookActions[$name];
        }

        if ($type == 'filter') {
            usort(static::$hookFilters[$name], [new self, 'compare']);

            return static::$hookFilters[$name];
        }
    }
}
