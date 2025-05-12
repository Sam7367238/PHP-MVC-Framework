<?php


class Container {
    private $registry = [];

    public function set($name, $value) {
        $this -> registry[$name] = $value;
    }

    public function get($class_name) {

        if (array_key_exists($class_name, $this -> registry)) {
            return $this -> registry[$class_name]();
        }

        $reflector = new ReflectionClass($class_name);
        $constructor = $reflector -> getConstructor();

        if (is_null($constructor)) {
            return new $class_name;
        }

        $dependencies = [];

        foreach ($constructor -> getParameters() as $parameter) {
            $type = $parameter -> getType();
            $dependencies[] = $this -> get((string) $type);
        }

        return new $class_name(...$dependencies);
    }
}