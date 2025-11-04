<?php

namespace Marcuwynu23\PHPInspector;

use ReflectionClass;
use Psr\Log\LoggerInterface;

class PHPInspector
{
    /**
     * Optional PSR-3 logger
     */
    private static ?LoggerInterface $logger = null;

    /**
     * Set a PSR-3 logger (optional)
     */
    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * Log class or object details in a pretty JSON format.
     *
     * @param string|object $input Class name or object instance
     * @param bool $toStdErr If true, logs to STDERR; otherwise, STDOUT
     */
    public static function log($input, bool $toStdErr = false): void
    {
        if (is_object($input)) {
            $ref = new ReflectionClass($input);
            $type = 'object';
        } elseif (is_string($input) && class_exists($input)) {
            $ref = new ReflectionClass($input);
            $type = 'class';
        } else {
            self::writeLog(print_r($input, true), $toStdErr);
            return;
        }

        $parent = $ref->getParentClass();
        $properties = $ref->getProperties();

        $data = [
            'type' => $type,
            'class' => $ref->getName(),
            'parent' => $parent ? $parent->getName() : null,
            'interfaces' => $ref->getInterfaceNames(),
            'traits' => $ref->getTraitNames(),
            'constants' => $ref->getConstants(),
            'methods' => array_map(fn($m) => $m->getName(), $ref->getMethods()),
            'properties' => [],
        ];

        foreach ($properties as $prop) {
            $prop->setAccessible(true);
            $data['properties'][$prop->getName()] = $type === 'object' ? self::serializeValue($prop->getValue($input)) : null;
        }

        self::writeLog(json_encode($data, JSON_PRETTY_PRINT), $toStdErr);
    }

    /**
     * Serialize property values recursively for logging
     */
    private static function serializeValue($value)
    {
        if (is_object($value)) {
            return [
                'class' => get_class($value),
                'properties' => (new ReflectionClass($value))->getProperties() ? '...object...' : 'empty object'
            ];
        } elseif (is_array($value)) {
            $result = [];
            foreach ($value as $k => $v) {
                $result[$k] = self::serializeValue($v);
            }
            return $result;
        }
        return $value;
    }

    /**
     * Write log to PSR-3 logger, or fallback to STDOUT/STDERR
     */
    private static function writeLog(string $message, bool $toStdErr = false): void
    {
        if (self::$logger) {
            self::$logger->debug($message);
        } else {
            $stream = $toStdErr ? 'php://stderr' : 'php://stdout';
            file_put_contents($stream, $message . PHP_EOL);
        }
    }
}
