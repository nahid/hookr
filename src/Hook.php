<?php

namespace Nahid\Hookr;

class Hook
{
    protected $hookActions = [];
    protected $hookFilters = [];

    public function action($name, $params = [])
    {
        if (isset($this->hookActions[$name])) {
            $actions = $this->makePriority($name);
            foreach ($actions as $callback) {
                $this->executeAction($callback['action'], $params);
            }
        }
    }

    public function bindAction($name, $callback, $priority = 0)
    {
        if (!isset($this->hookActions[$name])) {
            $this->hookActions[$name][] = [
                'action' => $callback,
                'priority' => $priority,
            ];
        } else {
            array_push($this->hookActions[$name], [
                'action' => $callback,
                'priority' => $priority,
            ]);
        }
    }

    public function filter($name, $data, $params = [])
    {
        if (isset($this->hookFilters[$name])) {
            $filters = $this->makePriority($name, 'filter');
            foreach ($filters as $callback) {
                $data = $this->executeFilter($callback['action'], $data, $params);
            }
        }

        return $data;
    }

    public function bindFilter($name, $callback, $priority = 0)
    {
        if (!isset($this->hookFilters[$name])) {
            $this->hookFilters[$name][] = [
                'action' => $callback,
                'priority' => $priority,
            ];
        } else {
            array_push($this->hookFilters[$name], [
                'action' => $callback,
                'priority' => $priority,
            ]);
        }
    }

    protected function newClassInstance($class, $params = [])
    {
        $reflection = new \ReflectionClass($class);

        return $reflection->newInstanceArgs($params);
    }

    protected function executeAction($action, $params)
    {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            $action = explode('@', $action);
            $func = $this->makeMethodParam($action[1]);
            $class = $this->makeMethodParam($action[0]);
            $instance = $this->newClassInstance($class['method'], $class['params']);
            if (count($action) > 1) {
                return call_user_func_array([$instance, $func['method']], $params);
            }
        }
    }

    protected function executeFilter($action, $data, $params = [])
    {
        if (is_callable($action)) {
            array_unshift($params, $data);

            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            $action = explode('@', $action);
            $func = $this->makeMethodParam($action[1]);
            array_unshift($params, $data);
            $class = $this->makeMethodParam($action[0]);
            $instance = $this->newClassInstance($class['method'], $class['params']);
            if (count($action) > 1) {
                return call_user_func_array([$instance, $func['method']], $params);
            }
        }
    }

    protected function makeMethodParam($method)
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

    protected function compare($value1, $value2)
    {
        if ($value1['priority'] == $value2['priority']) {
            return -1;
        }

        return ($value1['priority'] < $value2['priority']) ? -1 : 1;
    }

    protected function makePriority($name, $type = 'action')
    {
        if ($type == 'action') {
            usort($this->hookActions[$name], [$this, 'compare']);

            return $this->hookActions[$name];
        }

        if ($type == 'filter') {
            usort($this->hookFilters[$name], [$this, 'compare']);

            return $this->hookFilters[$name];
        }
    }
}
