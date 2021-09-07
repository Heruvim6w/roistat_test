<?php

class CalculateResults
{
    private $_views = 0;
    private $_traffic = 0;
    private $_urls = [];
    private $_codes = [];
    private $_crawlers = ['Google', 'Mediapartners', 'Yandex', 'Bing', 'Slurp', 'Mail.Ru', 'StackRambler'];
    private $_crawlerGoogle = 0;
    private $_crawlerYandex = 0;
    private $_crawlerBing = 0;
    private $_crawlerMailru = 0;
    private $_crawlerYahoo = 0;
    private $_crawlerRambler = 0;

    /**
     * @param array $parsedLine
     */
    public function handle(array $parsedLine)
    {
        $this->_views++;
        $this->_traffic += $parsedLine['bytes'];
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
            'traffic' => $this->_traffic,
            'urls' => $countOfUrls,
            'crawlers' => [
                'Google' => $this->_crawlerGoogle,
                'Yandex' => $this->_crawlerYandex,
                'Bing' => $this->_crawlerBing,
                'Yahoo' => $this->_crawlerYahoo,
                'Mail.Ru' => $this->_crawlerMailru,
                'Rambler' => $this->_crawlerRambler
            ],
            'statusCodes' => $this->_codes,
        ];
    }

    /**
     * @param string $crawlerFromLine
     * @return void
     */
    private function calculateCrawlers(string $crawlerFromLine)
    {
        foreach ($this->_crawlers as $crawler) {
            $lineBot = strpos($crawlerFromLine, $crawler);
            if ($lineBot) {
                switch ($crawler) {
                    case $this->_crawlers[0]:
                        $this->_crawlerGoogle++;
                        break;
                    case $this->_crawlers[1]:
                        $this->_crawlerGoogle++;
                        break;
                    case $this->_crawlers[2]:
                        $this->_crawlerYandex++;
                        break;
                    case $this->_crawlers[3]:
                        $this->_crawlerBing++;
                        break;
                    case $this->_crawlers[4]:
                        $this->_crawlerYahoo++;
                        break;
                    case $this->_crawlers[5]:
                        $this->_crawlerMailru++;
                        break;
                    case $this->_crawlers[6]:
                        $this->_crawlerRambler++;
                        break;
                }
            }
        }
    }
}