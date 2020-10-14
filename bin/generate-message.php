<?php

use longlang\phpkafka\Generator\MessageGenerator;

require_once dirname(__DIR__) . '/vendor/autoload.php';

define('KAFKA_PROTOCOL_MESSAGE_PATH', dirname(__DIR__) . '/res/message');
define('KAFKA_PROTOCOL_DEST_PATH', dirname(__DIR__) . '/src/Protocol');

// run
(function () {
    // messages
    $generators = [];
    foreach (new FilesystemIterator(KAFKA_PROTOCOL_MESSAGE_PATH, FilesystemIterator::SKIP_DOTS) as $jsonFile) {
        if ('json' !== $jsonFile->getExtension()) {
            continue;
        }
        $pathname = $jsonFile->getPathname();
        var_dump($pathname);
        $data = json5_decode(file_get_contents($pathname));
        $generator = new MessageGenerator($data);
        $generators[$generator->getApiKey()] = $generator;
        $generator->generate();
    }
    // AbstractApiKeys
    generateAbstractApiKeys($generators);
    // Format
    echo 'php-cs-fixer:', \PHP_EOL;
    $cmd = 'php-cs-fixer fix "' . KAFKA_PROTOCOL_DEST_PATH . '"';
    echo `{$cmd}`;
})();

/**
 * @param MessageGenerator[] $generators
 */
function generateAbstractApiKeys(array $generators): void
{
    $consts = $map = '';
    $apiKeys = [];
    ksort($generators);
    foreach ($generators as $generator) {
        $apiKey = $generator->getApiKey();
        if (-1 === $apiKey || isset($apiKeys[$apiKey])) {
            continue;
        }
        $apiKeys[$apiKey] = true;
        $name = strtoupper(toUnderScoreCase($generator->getApiName()));
        $consts .= <<<CODE
public const PROTOCOL_{$name} = {$apiKey};

CODE;
        $map .= <<<CODE
self::PROTOCOL_{$name} => '{$generator->getApiName()}',

CODE;
    }
    file_put_contents(KAFKA_PROTOCOL_DEST_PATH . '/AbstractApiKeys.php', <<<CODE
<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

abstract class AbstractApiKeys
{
    {$consts}

    public const PROTOCOL_MAP = [
        {$map}
    ];

    private function __construct()
    {
    }
}
CODE
    );
}

function toUnderScoreCase(string $name): string
{
    return trim(preg_replace('/[A-Z]/', '_\0', $name), '_');
}
