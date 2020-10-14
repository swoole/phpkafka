<?php

declare(strict_types=1);

namespace longlang\phpkafka\Generator;

use longlang\phpkafka\Protocol\Type\TypeRelation;

abstract class AbstractGenerator
{
    /**
     * @var MessageGenerator
     */
    protected $messageGenerator;

    /**
     * @var \stdClass
     */
    protected $data;

    /**
     * @var string
     */
    protected $apiName;

    /**
     * @var int
     */
    protected $maxSupportVersion;

    /**
     * @var int[]
     */
    protected $validVersions;

    public function getMessageGenerator(): MessageGenerator
    {
        return $this->messageGenerator;
    }

    public function getMessageData(): \stdClass
    {
        return $this->messageData;
    }

    public function getData(): \stdClass
    {
        return $this->data;
    }

    public function getApiName(): string
    {
        return $this->apiName;
    }

    public function getMaxSupportVersion(): int
    {
        return $this->maxSupportVersion;
    }

    public function getValidVersions(): array
    {
        return $this->validVersions;
    }

    public function generateCode(): array
    {
        $classProperties = $constructProtocolField = $taggedFieldses = $methods = '';
        foreach ($this->data->fields as $field) {
            $isArray = '[]' === substr($field->type, 0, 2);
            if ($isArray) {
                $type = substr($field->type, 2);
            } else {
                $type = $field->type;
            }
            if (isset(TypeRelation::TYPE_RELATION[$type])) {
                [$phpType] = TypeRelation::TYPE_RELATION[$type];
                $typeWithNamespace = "'{$type}'";
            } elseif (class_exists($type)) {
                $phpType = $type;
                $typeWithNamespace = "'{$type}'";
            } else {
                $phpType = null;
                $hasGenerated = $this->messageGenerator->hasGenerated($type);
                if (!$hasGenerated && !isset($field->fields)) {
                    throw new \RuntimeException(sprintf('Unsupport type %s', $type));
                } elseif (!$hasGenerated) {
                    $this->messageGenerator->generateStruct($type, $field);
                }
                $typeWithNamespace = $type . '::class';
            }
            $propertyName = lcfirst($field->name);
            $ucPropertyName = ucfirst($propertyName);
            $about = $field->about ?? '';
            if ($isArray) {
                $phpCommentType = ($phpType ?? $type) . '[]';
                $phpType = 'array';
            } else {
                $phpCommentType = $phpType;
            }
            if ($allowNull = isset($field->nullableVersions)) {
                $phpType = '?' . $phpType;
                $phpCommentType .= '|null';
            }
            if ('array' === $phpType) {
                $defaultValue = ' = []';
            } else {
                $defaultValue = ' = ' . $this->parseDefaultValue($phpType, $field->default ?? null, $allowNull);
            }
            $classProperties .= <<<CODE
/**
 * {$about}
 * 
 * @var {$phpCommentType}
 */
protected \${$propertyName}{$defaultValue};


CODE;
            if (isset($field->flexibleVersions)) {
                $flexibleVersions = $this->parseVersionsToArray($field->flexibleVersions, $this->messageGenerator->getMaxSupportVersion());
            } else {
                $flexibleVersions = $this->messageGenerator->getFlexibleVersions();
            }
            $fieldDefine = sprintf("new ProtocolField('%s', %s, %s, %s, %s, %s, %s, %s)," . \PHP_EOL, $propertyName, $typeWithNamespace, var_export($isArray, true), json_encode($this->parseVersionsToArray($field->versions, $this->maxSupportVersion)), json_encode($flexibleVersions), isset($field->nullableVersions) ? json_encode($this->parseVersionsToArray($field->nullableVersions, $this->maxSupportVersion)) : '[]', isset($field->taggedVersions) ? json_encode($this->parseVersionsToArray($field->taggedVersions, $this->maxSupportVersion)) : '[]', $field->tag ?? 'null');
            if (isset($field->tag)) {
                $taggedFieldses .= $fieldDefine;
            } else {
                $constructProtocolField .= $fieldDefine;
            }
            $methods .= <<<CODE
/**
 * @return {$phpCommentType}
 */
public function get{$ucPropertyName}(): {$phpType}
{
    return \$this->{$propertyName};
}

/**
 * @param {$phpCommentType} \${$propertyName}
 * 
 * @return self
 */
public function set{$ucPropertyName}({$phpType} \${$propertyName}): self
{
    \$this->{$propertyName} = \${$propertyName};

    return \$this;
}

CODE;
        }

        $constructMethod = <<<CODE
public function __construct()
{
    if (!isset(self::\$maps[self::class])) {
        self::\$maps[self::class] = [
            {$constructProtocolField}
        ];
        self::\$taggedFieldses[self::class] = [
            {$taggedFieldses}
        ];
    }
}
CODE;

        return [$classProperties, $constructMethod, $methods];
    }

    protected function save(string $classContent)
    {
        $fileName = $this->getSaveFileName();
        $dir = \dirname($fileName);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($fileName, $classContent);
    }

    protected function parseVersionsToArray(string $versions, int $maxVersion = null): array
    {
        if ('none' === $versions) {
            return [];
        }
        $matches = null;
        if (preg_match('/^((?<single>\d+)|((?<plus>\d+)\+)|((?<from>\d+)-(?<to>\d+)))$/', $versions, $matches) <= 0) {
            throw new \RuntimeException(sprintf('Invalid version %s', $versions));
        }
        if ('' !== ($matches['single'] ?? '')) {
            return [(int) $matches['single']];
        }
        if ('' !== ($matches['plus'] ?? '')) {
            return range($matches['plus'], $maxVersion);
        }
        if ('' !== ($matches['from'] ?? '') && '' !== ($matches['to'] ?? '')) {
            return range($matches['from'], $matches['to']);
        }
        throw new \InvalidArgumentException(sprintf('Invalid versions %s', $versions));
    }

    protected function parseDefaultValue(string $phpType, $default, bool $allowNull): string
    {
        if (null === $default) {
            if ($allowNull) {
                return 'null';
            }
            switch ($phpType) {
                case 'bool':
                    return 'false';
                case 'float':
                case 'int':
                    return '0';
                case 'string':
                    return "''";
                default:
                    return 'null';
            }
        } elseif ('null' === $default) {
            return 'null';
        } else {
            switch ($phpType) {
                case 'bool':
                case 'float':
                case 'int':
                    return (string) $default;
                default:
                    return "'{$default}'";
            }
        }
    }

    abstract public function getSaveFileName(): string;

    abstract public function generate(): void;
}
