<?php

class ApacheLogParser
{
    private $_pattern = "/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) (\".*?\") (\".*?\")$/";
    private $_file;

    /**
     * Parser constructor.
     * @param string $pathToFile
     * @throws Exception
     */
    public function __construct(string $pathToFile)
    {
        $this->_file = @fopen($pathToFile, 'r'); //*ToDo Вынести в собственную функцию, как line() и closeFile(), обернуть в try/catch, чтобы избавиться от @
        if (!$this->_file){
            throw new \Exception('File not found');
        }
    }

    /**
     * @return Generator
     */
    public function handle(): Generator
    {
        while ($line = $this->line()) {
            $parsedLine = $this->parseLine($line);
            if (!$parsedLine) {
                continue;
            }
            yield $parsedLine;
        }
        $this->closeFile($this->_file);
    }

    /**
     * https://gist.github.com/lstoll/45014
     *
     * @param string $line
     * @return array
     */
    private function parseLine(string $line): array
    {
        preg_match($this->_pattern, $line, $matches);

        if (!isset($matches[0])) {
            return [];
        }

        $formattedLog = [];
        $formattedLog['path'] = $matches[8] ?? null;
        $formattedLog['statusCode'] = $matches[10] ?? null; //*ToDo проверить типы элементов!!!
        $formattedLog['bytes'] = $matches[11];
        $formattedLog['userAgent'] = $matches[13] ?? null;

        return $formattedLog;
    }

    /**
     * @return string
     */
    private function line():string
    {
        return fgets($this->_file);
    }

    /**
     * @param resource $file
     * @return void
     */
    private function closeFile($file)
    {
        fclose($file);
    }
}