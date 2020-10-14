<?php

declare(strict_types=1);

namespace longlang\phpkafka\Generator;

class SubStructGenerator extends AbstractGenerator
{
    /**
     * @var string
     */
    protected $structName;

    public function __construct(MessageGenerator $messageGenerator, \stdClass $fieldData)
    {
        $this->messageGenerator = $messageGenerator;
        $this->data = $data = $fieldData;
        $this->apiName = $messageGenerator->getApiName();
        $this->validVersions = $this->parseVersionsToArray($data->versions, $messageGenerator->getMaxSupportVersion());
        $this->maxSupportVersion = max(0, ...$this->validVersions);
        if (isset($this->data->type)) {
            $this->structName = $this->data->type;
            if ('[]' === substr($this->structName, 0, 2)) {
                $this->structName = substr($this->structName, 2);
            }
        } else {
            $this->structName = $this->data->name;
        }
    }

    public function getSaveFileName(): string
    {
        return $this->messageGenerator->getDirName() . '/' . $this->structName . '.php';
    }

    public function generate(): void
    {
        [$classProperties, $constructMethod, $methods] = $this->generateCode();
        $extendsClassName = 'AbstractStruct';
        $flexibleVersionsStr = json_encode($this->messageGenerator->getFlexibleVersions());
        $constructMethod .= <<<CODE

public function getFlexibleVersions(): array
{
    return {$flexibleVersionsStr};
}

CODE;
        $classContent = <<<CODE
<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\\{$this->apiName};

use longlang\phpkafka\Protocol\\{$extendsClassName};
use longlang\phpkafka\Protocol\ProtocolField;

class {$this->structName} extends {$extendsClassName}
{
    {$classProperties}

    {$constructMethod}

    {$methods}
}
CODE;
        $this->save($classContent);
    }
}
