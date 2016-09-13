<?php

namespace EXAM0098\Lib;

/**
 * Logger シングルトンクラス.
 *
 * @package EXAM0098\Lib
 */
class Logger
{
    private static $instance = null;
    private $logLevel = null;

    private function __construct($logLevel)
    {
        $this->logLevel = strtoupper($logLevel);
    }

    /**
     * 当クラスの唯一のインスタンスを返す。
     * ログレベルはデフォルト"ERROR"。
     *
     * @param string $logLevel ログレベル("DEBUG"|"INFO"|"ERROR"|"FATAL")
     * @return Logger 唯一のインスタンス
     */
    public static function getInstance($logLevel = "ERROR")
    {
        if (is_null(self::$instance)) {
            self::$instance = new Logger($logLevel);
        }
        return self::$instance;
    }

    /**
     * @param string $logLevel ログレベル("DEBUG"|"INFO"|"ERROR"|"FATAL")
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    private function isLogLevel($value)
    {
        $value = strtoupper($value);
        switch ($this->logLevel) {
            case "FATAL":
                return $this->logLevel === $value;
            case "ERROR":
                return $this->logLevel === $value || "FATAL" === $value;
            case "INFO":
                return $this->logLevel === $value || "ERROR" === $value || "FATAL" === $value;
        }
        return true;
    }

    private function output($type, $messages, $useStdout = true)
    {
        $type = strtoupper($type);
        if ($messages instanceof \Exception) {
            $e = $messages;
            $messages = "Exception occurred.\n";
            $messages .= 'Message    : ' . $e->getMessage() . "\n";
            $messages .= "StackTrace :\n";
            $messages .= $e->getTraceAsString() . "\n";
        } else {
            if (is_array($messages)) {
                $messages = json_encode($messages);
            }
            if ($type === "FATAL" || $type === "ERROR") {
                $messages = $messages . "\n" . json_encode(debug_backtrace());
            }
        }

        $caller = $this->getCallerFileName();
        if ($useStdout) print sprintf("%s|%s\n", $caller, $messages);
        if (ini_get("log_errors")) error_log(sprintf("[%s] %s %s|%s\n", date("Y-m-d H:i:s"), str_pad($type, 5), $caller, $messages));
    }

    private function getCallerFileName()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit = 3);
        return @pathinfo($trace[count($trace) - 1]['file'], PATHINFO_FILENAME);
    }

    /**
     * ログレベルが FAIL 時にメッセージを出力する。
     *
     * @param string|\Exception $messages メッセージ
     * @param bool $useStdout true: 標準出力する / false: 標準出力しない
     */
    public function fatal($messages, $useStdout = true)
    {
        if ($this->isLogLevel("FATAL")) {
            $this->output("FATAL", $messages, $useStdout);
        }
    }

    /**
     * ログレベルが ERROR 時にメッセージを出力する。
     *
     * @param string|\Exception $messages メッセージ
     * @param bool $useStdout true: 標準出力する / false: 標準出力しない
     */
    public function error($messages, $useStdout = true)
    {
        if ($this->isLogLevel("ERROR")) {
            $this->output("ERROR", $messages, $useStdout);
        }
    }

    /**
     * ログレベルが DEBUG 時にメッセージを出力する。
     *
     * @param string|\Exception $messages メッセージ
     * @param bool $useStdout true: 標準出力する / false: 標準出力しない
     */
    public function debug($messages, $useStdout = true)
    {
        if ($this->isLogLevel("DEBUG")) {
            $this->output("DEBUG", $messages, $useStdout);
        }
    }

    /**
     * ログレベルが INFO 時にメッセージを出力する。
     *
     * @param string|\Exception $messages メッセージ
     * @param bool $useStdout true: 標準出力する / false: 標準出力しない
     */
    public function info($messages, $useStdout = true)
    {
        if ($this->isLogLevel("INFO")) {
            $this->output("INFO", $messages, $useStdout);
        }
    }

}
