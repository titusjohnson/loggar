<?php
/**
 * Loggar is a simple class for writing events to log file.
 *
 * Every log written contains the following information:
 * ----------------------------------------------------------------
 * level    - Level of the error.
 * time     - The current time in M/D/Y H:M:S.
 * user     - IP of the user that triggered this error.
 * location - The location in the code that generated this message.
 * message  - A plain text description of what happened.
 * ----------------------------------------------------------------
 *
 * @copyright 2012 Titus Johnson
 * @author Titus Johnson [tgj] <amishforkfight@gmail.com>
 * @license MIT
 *
 * @package Loggar
 * @link https://github.com/titusjohnson/loggar
 *
 * @changes     0.2.1
 * @changes     0.2     Rewrote initial idea into a simpler class [tgj, 2012-9-12]
 * @changes     0.1     The initial idea [tgj, 2012-9-11]
 */
class loggar {

    /**
     *  Where we store log files
     *  @var $folder
     */
    private $folder = "";
    
    /**
     *  The default file we will save events into
     *  @var $filename
     */
    private $file = "eventlog";

    /**
     *  A default for $file. After logging we reset
     *  $file back to this constant for consistency.
     */
    const file = "eventlog";

    /**
     *  The default log level
     *  @var string
     */
    var $level = "INFO";

    /**
     *  A default for $level. After logging we reset
     *  $level back to this constant for consistency.
     */
    const level = "INFO";

    /**
     *  The time the next log is written at
     *  @var string
     */
    var $time = "";

    /**
     *  The user that triggered this error.
     *  @var string
     */
    var $user = "";

    /**
     * The location in the file that this log was
     * instantiated at.
     * @var string
     */
    var $location = "";

    /**
     * A user-friendly message of what went wrong.
     * @var string
     */
    var $message = "";

    /**
     * All of the error messages we may toss. For cleanliness's sake.
     * @var array
     */
    private $errors = array(
        "bad_directory"         => "The log directory specified, '%s', does not exist and could not be created.",
        "unwritable_directory"  => "The log folder, '%s', is not writable.",
        "unfound_method"        => "The method '%s' does not exist.",
        "file_write_error"      => "Error writing the log file '%s'!"
    );

    /**
     * Test the provided path for write permissions.
     * @param $path is an absolute file path to the log folder
     * @throws loggarException if the file can't be written
     */
    function __construct($path) {
        if( ! is_dir($path)) {
            if( ! mkdir($path)) {
                throw new loggarException(sprintf($this->errors["bad_directory"], $path));
            }
        }

        if( ! is_writable($path)) {
            throw new loggarException(sprintf($this->errors["unwritable_directory"], $path));
        }

        $this->folder = $path;
    }

    /**
     *  Specify a custom log file
     *  @param  string $type a shortname of the file to write to
     *  @return object $this for method chaining
     */
    function file($type) {
        $this->file = $type;
        return $this;
    }

    /**
     *  Overload method
     *  @param  string $name name of the log type we are writing
     *  @param  string $message error message we want to type
     *  @throws loggarException if we try to overload without prefixing 'log'
     */
    function __call($name, $message) {
        if(substr($name, 0, 3) !== "log") {
            throw new loggarException(sprintf($this->errors["unfound_method"], $name));
        }
        $backtrace      = debug_backtrace();

        $this->level    = strtoupper(substr_replace($name, '', 0, 3));
        $this->message  = $message[0];
        $this->time     = date("m/d/j h:i:s", time());
        $this->user     = $_SERVER["REMOTE_ADDR"];
        $this->location = $backtrace[1]["file"].":".$backtrace[1]["line"];
        $this->save();

        // Reset some defaults
        $this->level    = $this::level;
        $this->file     = $this::file;
        $this->message  = "";
    }

    /**
     * Save the log information to file
     * @throws Exception on the off-chance that the file isn't writable.
     */
    private function save() {
        $log_line = sprintf(
            "[%s]\t%s\t%s\t%s\t%s\n",
            $this->time,
            $this->level,
            $this->user,
            $this->location,
            $this->message
        );
        $file  = $this->folder.$this->file.".log";

        $write = file_put_contents($file, $log_line, FILE_APPEND);
        if(!$write) {
            throw new Exception($this->errors["file_write_error"], $file);
        }
    }
}
class loggarException extends Exception {}