<?php
namespace Tests;

use AdrienM\Collection\Collection;
use AdrienM\Logger\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    /**
     * @var $logger Logger
     */
    private $logger;

    /**
     * @before
     */
    public function setupInstance(): void
    {
        $this->logger = Logger::getInstance("logs");
    }

    /**
     * Test if the collection is empty
     */
    public function testWrite(): void
    {
        $this->logger->write("test");
    }
}
