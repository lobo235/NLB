<?php
// This class figures out page generation times
class PageTimer
{
	private $_start_time;
	private $_stop_time;
	private $_gen_time;
	private $round_to = 4;

	function start()
	{
		$microstart = explode(' ',microtime());
		$this->_start_time = $microstart[0] + $microstart[1];
	}
	function stop()
	{
		$microstop = explode(' ',microtime());
		$this->_stop_time = $microstop[0] + $microstop[1];
	}
	function getGenTime()
	{
		$this->_gen_time = round($this->_stop_time - $this->_start_time, $this->round_to);
		return $this->_gen_time;
	}
}
