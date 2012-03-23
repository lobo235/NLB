<?php
/**
 * The PageTimerService class is a service class that provides function to help in determining page generation times
 */
class PageTimerService
{
	private $_start_time;
	private $_stop_time;
	private $_gen_time;
	private $round_to = 4;

	/**
	 * This function starts the timer
	 */
	function start()
	{
		$microstart = explode(' ', microtime());
		$this->_start_time = $microstart[0] + $microstart[1];
	}
	
	/**
	 * This function stops the timer
	 */
	function stop()
	{
		$microstop = explode(' ', microtime());
		$this->_stop_time = $microstop[0] + $microstop[1];
	}
	
	/**
	 * This function must be called after both the start() and stop() functions have been called. It returns the elapsed time
	 * @return float the rounded page generation time
	 */
	function getGenTime()
	{
		$this->_gen_time = round($this->_stop_time - $this->_start_time, $this->round_to);
		return $this->_gen_time;
	}
}
