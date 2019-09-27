<?php
namespace AdrienM\Logger;

class Logger
{
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
     * Name of the log file
     * @var string
     */
    protected $filename;

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
     * Logger constructor.
     * @param string $path
     * @param string $level
     * @param string $type
     */
    public function __construct(string $path, string $filename, string $level, string $type = null)
    {
        $this->setPath($path);
        $this->filename = $filename;
        $this->level = $level;
        $this->type = $type;
    }

    /**
     * Get instance of the Logger
     * @param string $path
     * @param string $level
     * @return Logger
     */
    public static function getInstance(string $path = null, string $level = self::LOG_DEBUG): Logger
    {
        if (null == $path) {
            $path = dirname(__DIR__, 4) . "/logs/";
        }

        return new self($path, date("d-m-Y") . ".log", $level);
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

            $type = (null != $this->type ? $this->type : "");

            if (strpos($this->filename, ".csv")) {
                $begin = "$date, $this->level, " . (null != $type ? "$type, " : "");
            } else {
                $begin = "[$date] [$this->level] " . (null != $type ? "[type: $type] " : "");
            }

            file_put_contents($this->path . "/" . $this->filename, $begin . $message . "\n", FILE_APPEND);
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
            $file = fopen($this->path . "/" . $this->filename, 'r');

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
        if (!is_dir($path)) {
            mkdir($path);
        }

        $this->path = $path;
    }

    /**
     * Get the name of the file
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Define the name of the file
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
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
