<?php
/**
 * Created by PhpStorm.
 * User: aber
 * Date: 30.06.16
 * Time: 9:22
 */

namespace Logger\LoggerInterface;

interface LoggerInterface
{
    const NOTICE = 'Notice';
    const WARNING = 'Warning';
    const ERROR = 'Error';

    /**
     * Write notice.
     *
     * @param string $message
     *
     * @param string $type
     *
     * @return void
     */
    public function notice($message, $type);

    /**
     * Write warning.
     *
     * @param string $message
     *
     * @param string $type
     *
     * @return void
     */
    public function warning($message, $type);

    /**
     * Write error.
     *
     * @param string $message
     *
     * @param string $type
     *
     * @return void
     */
    public function error($message, $type);
}