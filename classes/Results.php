<?php

class Results
{
    private $_views = 0;
    private $_traffic = 0;
    private $_urls = [];
    private $_codes = [];
    private $_crawlers = ['Google' => 0, 'Bing' => 0, 'Baidu' => 0, 'Yandex' => 0];

    /**
     * @param array $parsedLine
     */
    public function handle(array $parsedLine)
    {
        $this->_views++;
        $this->calculateTraffic($parsedLine['bytes'], $parsedLine['statusCode']);
        $this->_urls[$parsedLine['path']] += 1;
        $this->_codes[$parsedLine['statusCode']] += 1;
        $this->calculateCrawlers($parsedLine['userAgent']);
    }

    /**
     * @return array
     */
    public function prepareResult(): array
    {
        $countOfUrls = count($this->_urls);
        return [
            'views' => $this->_views,
            'urls' => $countOfUrls,
            'traffic' => $this->_traffic,
            'crawlers' => $this->_crawlers,
            'statusCodes' => $this->_codes,
        ];
    }

    public function calculateTraffic($bytes, $status)
    {
        if ($status != 301) {
            $this->_traffic += $bytes;
        }
    }

    /**
     * @param string $crawlerFromLine
     * @return void
     */
    private function calculateCrawlers(string $crawlerFromLine)
    {
        $crawlers = array_keys($this->_crawlers);
        foreach ($crawlers as $crawler) {
            $lineBot = strpos($crawlerFromLine, $crawler);
            if ($lineBot !== false) {
                $this->_crawlers[$crawler] += 1;
            }
        }
    }
}