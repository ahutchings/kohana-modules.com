<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * STDERR log writer. Writes out messages to STDERR.
 *
 * @package    Kohana
 * @category   Logging
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Log_StdErr extends Log_Writer
{
  public function __construct ()
  {
    $this->_stream = defined('STDERR') ? STDERR : fopen('php://stderr', 'w');
  }

  /**
   * Writes each of the messages to STDERR.
   *
   *     $writer->write($messages);
   *
   * @param   array   $messages
   * @return  void
   */
  public function write(array $messages)
  {
    foreach ($messages as $message)
    {
      // Writes out each message
      fwrite($this->_stream, $this->format_message($message).PHP_EOL);
    }
  }
}
