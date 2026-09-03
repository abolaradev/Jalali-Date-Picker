<?php

namespace Workbench\App\Abolaradev;

use Illuminate\Support\Str;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Orchestra\Sidekick\working_path;
use function Orchestra\Testbench\workbench_path;

class Livewire
{
    private static $path;

    private static bool $layout = false;

    private static bool $bladeComponent = false;

    /**
     * Set the base path for package Livewire views.
     *
     * @return self
     */
    public static function setPath(): self
    {
        self::$path = working_path('resources\\views\\');

        return new self();
    }

    /**
     * Set the directory where the component will be created.
     *
     * Dot notation is converted to the directory separator.
     *
     * @param string $fullpath
     * @return self
     */
    public static function atDirectory(string $fullpath = ''): self
    {
        $fullpath = Str::of($fullpath)
            ->replace('.', DIRECTORY_SEPARATOR)
            ->lower()
            ->prepend(self::$path)
            ->finish(DIRECTORY_SEPARATOR);

        if (! is_dir($fullpath)) {
            mkdir(
                directory: $fullpath,
                permissions: 0775,
                recursive: true
            );
        }

        self::$path = $fullpath;

        return new self();
    }

    /**
     * Set the name of the Livewire component to create.
     *
     * @param string $name
     * @return self
     */
    public static function component(string $name): self
    {
        $name = Str::of($name)
                    ->kebab()
                    ->finish('.blade.php');

        self::$path .= $name;

        return new self();
    }

    /**
     * Mark the component as a Livewire layout.
     *
     * @return self
     */
    public static function isLayout(): self
    {
        self::$layout = true;

        return new self();
    }

    /**
     * Mark the component as a Blade component.
     *
     * @return self
     */
    public static function isBladeComponent(): self
    {
        self::$bladeComponent = true;

        return new self();
    }

    /**
     * Create the component using the appropriate template.
     *
     * Returns false if a component already exists at the target path.
     *
     * @return bool
     */
    public static function create(): bool
    {
        if (file_exists(self::getPath())) {
            return false;
        }

        $componentType = self::$layout
            ? 'layout'
            : (self::$bladeComponent
                ? 'blade'
                : 'livewire');

        $componentPath = Str::of(workbench_path("app\\Abolaradev\\Livewire\\$componentType"))
                            ->finish('.blade.php');

        $content = file_get_contents($componentPath);

        file_put_contents(self::getPath(), $content);

        return true;
    }

    /**
     * Get the current component path.
     *
     * @return string
     */
    public static function getPath(): string
    {
        return self::$path;
    }

    /**
     * Display the result of the component creation operation.
     *
     * Displays a success message when the operation succeeds
     * and an alert when it fails, followed by the target path.
     *
     * @param bool $result
     * @param string $success
     * @param string $fail
     * @return void
     */
    public static function consoleOutput(
        bool $result,
        string $success,
        string $fail
    ): void {
        $result
            ? info($success)
            : alert($fail);

        note('at: ' . self::getPath());
    }
}