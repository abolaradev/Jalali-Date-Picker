<?php

namespace Workbench\App\Abolaradev;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function Laravel\Prompts\alert;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\warning;
use function Orchestra\Sidekick\working_path;
use function Orchestra\Testbench\workbench_path;

class Asset
{
    /**
     * Create the package asset directories.
     *
     * Creates the default asset directories inside the working path.
     * Existing directories will not be recreated.
     *
     * @param array $except Asset directories that should not be created.
     *
     * @return self
     */
    public static function make(array $except = []): self
    {
        $dist = working_path('resources\\dist\\');

        $assets = Arr::exceptValues(
            ['js', 'css', 'fonts', 'images'],
            $except
        );

        $assetsPaths = Arr::map($assets, function ($value) use ($dist) {
            return $dist . $value;
        });

        $assetsPaths = array_combine($assets, $assetsPaths);

        if (count($assetsPaths) > 0) {
            intro('Creating asset directories...');

            foreach ($assetsPaths as $key => $value) {
                if (!is_dir($value)) {
                    mkdir($value, 0775, true);

                    note("$key asset created: [$value]");
                }else{
                    warning("$key asset already exists: [$value]");
                }
            }

            info('Asset directory setup completed.');
        } else {
            alert('No asset directories to create.');
        }

        return new self();
    }

    /**
     * Create a junction link for the package assets.
     *
     * Creates a Windows junction from the Workbench public directory
     * to the package asset directory.
     *
     * @return self
     */
    public static function link(): void
    {
        $target = working_path('resources\\dist');

        if (is_dir($target)) {
            $link = workbench_path('public');

            exec("mklink /J \"$link\" \"$target\"") ? info("Assets linked successfully: [$link]")
                                                    : error("Failed to link assets: [$link]");
                                                    
        }else {
            alert("No asset directory exists to link: [$target]");
        }
    }

    /**
     * Create and append the package asset helper function to the helpers file.
     *
     * @return void
     */
    public static function createhelperAsset() :void
    {
        $helper=working_path('src\\helpers\\helpers.php');
        $helperFile=file_get_contents($helper);
        
        if(Str::contains($helperFile,'package_asset')==false){
            $assetHelper =self::normalizeIndentation(' 

            function package_asset(string $path)
            {
                $asset = asset("vendor/skeleton/$path");
                $path = file_exists($asset) ? $asset
                                            : url("assets/$path");
                
                return $path;
            }');
         
            file_put_contents($helper,$assetHelper,FILE_APPEND);

            outro("Also package_asset() helper added.");
            note("at: [$helper]");
        }
    }


    /**
     * Normalize the indentation of a multiline string.
     *
     * @param string $content
     * @return string
     */
    private static function normalizeIndentation(string $content): string
    {
        $lines = preg_split('/\r\n|\r|\n/', $content);

        $lines = array_values(array_filter(
            $lines,
            fn ($line) => trim($line) !== ''
        ));

        if (empty($lines)) {
            return '';
        }

        $indentations = array_map(
            fn ($line) => strlen($line) - strlen(ltrim($line)),
            $lines
        );

        $indentation = min($indentations);

        return implode("\n", array_map(
            fn ($line) => substr($line, $indentation),
            $lines
        ));
    }
}

