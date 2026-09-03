<?php

namespace Workbench\App\Abolaradev;

use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;
use function Orchestra\Sidekick\working_path;

class Helper
{
    /**
     * Indicates whether the helper file has been created.
     */
    public static bool $helperCreated = false;

    /**
     * Create the helper file if it does not already exist.
     *
     * @return self
     */
    public static function make(): self
    {
        $helper = working_path('src\\helpers\\helpers.php');

        if (!file_exists($helper)) {
            $path = str_replace('helpers.php', '', $helper);
            mkdir($path, 0775, true);
            file_put_contents($helper, "<?php ");
            info("Helper file created successfully.");
            self::$helperCreated = true;
        } else {
            warning("Helper file already exists.");
        }

        note("at: [$helper]");

        return new self();
    }

    /**
     * Add the helper file to Composer's autoload files configuration.
     *
     * @return self|null
     */
    public static function composer(): self
    {
        if (self::$helperCreated) {
            $composer = working_path('composer.json');
            $file = file_get_contents($composer);
            $content = '"files" : ["src/helpers/helpers.php"]';
            $search = '"files":[]';
            $replace = str_replace($search, $content, $file);
            file_put_contents($composer, $replace);
        }

         return new self();
    }

    /**
     * Regenerate Composer's autoloader.
     *
     * @return void
     */
    public static function dumpAutoLoad(): void
    {
        if (self::$helperCreated) {
            spin(
                function () {
                    exec("composer dump-autoload");
                },
                "Regenerating Composer autoload..."
            );
        }
    }
}
