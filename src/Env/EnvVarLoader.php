<?php

declare(strict_types=1);

namespace Atoolo\Resource\Env;

use Symfony\Component\DependencyInjection\EnvVarLoaderInterface;

class EnvVarLoader implements EnvVarLoaderInterface
{
    private readonly string $baseDir;

    public function __construct(
        string $baseDir = null
    ) {
        $this->baseDir = $baseDir ?? (getcwd() ?: '');
    }

    /**
     * @return array{
     *     RESOURCE_ROOT?: non-empty-string,
     * }
     */
    public function loadEnvVars(): array
    {
        $env = [];

        $resourceRoot = $_SERVER['RESOURCE_ROOT'] ?? '';
        if (!is_string($resourceRoot) || empty($resourceRoot)) {
            $resourceRoot = $this->determineResourceRootForCliCall();
            if (!empty($resourceRoot)) {
                $env['RESOURCE_ROOT'] = $resourceRoot;
                // other EnvVarLoader needs this value
                $_SERVER['RESOURCE_ROOT'] = $resourceRoot;
            }
        }
        return $env;
    }

    /**
     * If the call was made via a CLI command, an attempt is made to
     * the resource root via the path of the `bin/console` script.
     * to determine the resource root.
     * This is successful if the script is called via the absolute
     * path to the `app` folder below the host directory.
     *
     * E.G.
     * /var/www/example.com/www/app/bin/console
     *
     */
    private function determineResourceRootForCliCall(): ?string
    {
        /** @var string[] $directories */
        $directories = [
            $this->baseDir
        ];

        $filename = $_SERVER['SCRIPT_FILENAME'] ?? null;
        if (is_string($filename)) {
            $binDir = dirname($filename);
            $appDir = dirname($binDir);
            $hostDir = dirname($appDir);
            $directories[] = $hostDir;
        }

        foreach ($directories as $dir) {
            $realpath = realpath($dir);
            if ($realpath === false) {
                continue;
            }

            if (is_file($realpath . '/resources/context.php')) {
                return $realpath . '/resources';
            }
            if (is_file($realpath . '/context.php')) {
                return $realpath;
            }
            if (is_file($realpath . '/WEB-IES/context.php')) {
                return $realpath;
            }
        }

        return null;
    }
}
