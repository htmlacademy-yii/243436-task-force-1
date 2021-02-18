<?php

namespace Taskforce\Importer;

use Taskforce\Exception\SourceFileException;
use Taskforce\Exception\FileFormatException;

class SQLGenerator
{
    public function Generator(string $file, array $columns)
    {
        $name = new ImporterSpl("data/$file.csv", $columns);

        try {
            $name->import();
        } catch(SourceFileException $e) {
            echo $e->getMessage();
        } catch(FileFormatException $e) {
            echo $e->getMessage();
        }

        $name->SQLFormat($file, "shemasql/$file.sql");
    }
}
