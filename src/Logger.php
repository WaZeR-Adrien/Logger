<?php
namespace AdrienM\Logger;

class Logger
{
    const DEFAULT_PATH = __DIR__ . "/logs/";
    
    const LOG_DEBUG = "DEBUG";
    const LOG_INFO = "INFO";
    const LOG_ALERT = "ALERT";
    const LOG_CRITICAL = "CRITICAL";
    const LOG_ERROR = "ERROR";
    const LOG_WARNING = "WARNING";


    /**
     * Path for the log folder
     * @var string
     */
    protected $path;

    /**
     * Level of the log (DEBUG, CRITICAL, ERROR...)
     * @var string
     */
    protected $level;

    /**
     * Type of the log
     * @var string
     */
    protected $type;

    /**
     * ApiLogger constructor.
     * @param string $path
     * @param string $level
     */
    public function __construct(string $path, string $level = self::LOG_DEBUG)
    {
        $this->path = $path;
        $this->level = $level;
    }

    /**
     * Get instance of the Logger with default path
     * @param string $level
     * @return Logger
     */
    public static function getInstance(string $level = self::LOG_DEBUG): Logger
    {
        return new self(Logger::DEFAULT_PATH . date("d-m-Y") . ".log", $level);
    }

    /**
     * Save a new log
     * @param string $message
     * @throws LogException
     */
    public function write(string $message): void
    {
        try {
            $date = date("d/m/Y H:i:s");

            if (strpos($this->path, ".csv")) {
                $begin = "$date, $this->level, ";
            } else {
                $begin = "[$date] [$this->level] ";
            }

            file_put_contents($this->path, $begin . $message . "\n", FILE_APPEND);
        } catch (\Exception $e) {
            throw new LogException($e->getMessage(), LogException::ERROR_DURING_PUT_IN_FILE);
        }
    }

    /**
     * Get all logs
     */
    public function getAll(): array
    {
        $logs = self::parse();

        return $logs;
    }

    /**
     * Parse the log file to retrieve the content
     * @return array
     * @throws LogException
     */
    private function parse(): array
    {
        try {
            $file = fopen($this->path, 'r');

            // lines
            $logs = [];
            while (($line = fgets($file)) !== FALSE) {
                $logs[] = $line;
            }

            fclose($file);
        } catch (\Exception $e) {
            throw new LogException("The file can't be parsed", LogException::CANT_PARSE_FILE);
        }

        return $logs;
    }

    /**
     * Get the current path
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Define the path for the log folder
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Get the current level
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Define the level for the log
     * @param string $level
     */
    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    /**
     * Get the current type
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Define the type for the log
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
