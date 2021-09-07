<?php

class ApacheLogParser
{
    /**
     * https://gist.github.com/lstoll/45014
     */
    private $_pattern = "/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) (\".*?\") (\".*?\")$/";
    private $_file;

    /**
     * Parser constructor.
     * @param string $pathToFile
     * @throws Exception
     */
    public function __construct(string $pathToFile)
    {
        $this->_file = fopen($pathToFile, 'r');
        if (!$this->_file){
            throw new \Exception('Wrong path to file');
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
        $formattedLog['ip'] = $matches[1];
        $formattedLog['identity'] = $matches[2];
        $formattedLog['user'] = $matches[2];
        $formattedLog['date'] = $matches[4];
        $formattedLog['time'] = $matches[5];
        $formattedLog['timezone'] = $matches[6];
        $formattedLog['method'] = $matches[7];
        $formattedLog['path'] = $matches[8];
        $formattedLog['protocol'] = $matches[9];
        $formattedLog['statusCode'] = $matches[10];
        $formattedLog['bytes'] = $matches[11];
        $formattedLog['referer'] = $matches[12];
        $formattedLog['userAgent'] = $matches[13];

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