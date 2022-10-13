<?php

namespace App\Service;

class File
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}

class Directory
{
    /**
     * @param (File|Directory)[] $children
     */
    public function __construct(
        public readonly string $name,
        public readonly array $children,
    ) {
    }
}

class VisitFiles
{
    /**
     * Traverse Files & Directories.
     *
     * Return a list of every files filtered by given function.
     *
     * @param array $root list of files or sub directories
     */
    public function visitFiles(array $root, callable $filterFn): array
    {
        return array_filter($root, fn ($k) => $filterFn($k));
    }

    public function usageExemple(): void
    {
        $this->visitFiles(
            [
                'bin/',
                'etc/',
                'usr/',
                'opt/',
                'var/',
            ],
            function ($file) {
                $name = $file->name;
                for ($i = 0; $i < floor(strlen($name)); ++$i) {
                    if ($name[$i] != $name[strlen($name) - $i - 1]) {
                        return false;
                    }
                }

                return true;
            }
        );
    }
}
