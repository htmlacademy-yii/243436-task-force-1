<?php
namespace Taskforce\Importer;

use SplFileObject;
use Taskforce\Exception\SourceFileException;
use Taskforce\Exception\FileFormatException;
use Taskforce\Exception\RuntimeException;

class ImporterSpl
{
    private $filename;
    private $columns;
    private $fileObject;

    private $result = [];

    public function __construct(string $filename, array $columns)
    {
        $this->filename = $filename;
        $this->columns = $columns;
    }

    /**
     * @return void Проверка файла на корректность
     */
    public function import() : void
    {
        if (!$this->validateColumns($this->columns)) {
            throw new FileFormatException("Заданы неверные заголовки столбцов");
        }

        if (!file_exists($this->filename)) {
            throw new SourceFileException("Файл не существует");
        }

        try {
            $this->fileObject = new SplFileObject($this->filename);
        } catch (RuntimeException $e) {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }

        $header_data = $this->getHeaderData();

        if ($header_data !== $this->columns) {
            throw new FileFormatException("Исходный файл не содержит необходимых столбцов");
        }

        foreach ($this->getNextLine() as $line) {
            $this->result[] = $line;
        }
    }

    /**
     * @param array $columns колонки файла
     *
     * @return bool Валидация колонок файла
     */
    private function validateColumns(array $columns) : bool
    {
        $result = true;

        foreach ($columns as $column) {
            if (!is_string($column)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @return array|null Заголовки таблицы
     */
    private function getHeaderData() : ?array
    {
        $this->fileObject->rewind();
        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    /**
     * @return iterable|null Объект файла
     */
    private function getNextLine() : ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return $result;
    }

    /**
     * @return array Данные таблицы
     */
    public function getData() : array
    {
        return $this->result;
    }

    /**
     * @param string $name имя таблицы
     * @param string $file имя файла
     *
     * Создает запись в SQL-файле
     */
    public function SQLFormat(string $name, string $file)
    {
        $columns = implode(', ', $this->columns);

        $data = '';

        foreach ($this->getData() as $key => $value) {
            $a =  '(';
            $b = '';
            foreach ($value as $val) {
                if (preg_match('/[A-Za-zА-Яа-я.,-]|([0-9]){10,}/', $val)) {
                    $c = "'".$val."'".', ';
                } else {
                    $c = $val.', ';
                }
                $b .= $c;
            }
                $b = mb_substr($b, 0, -2);
            $d = '), ';

            $data .= $a.$b.$d;
        }

        $data = mb_substr($data, 0, -2);

        $insert = "INSERT INTO $name($columns) VALUES $data;";

        $fp = fopen($file, 'w');

        fwrite($fp, $insert);

        fclose($fp);
    }
}
