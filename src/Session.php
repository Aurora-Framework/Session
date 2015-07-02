<?php

/**
 * Aurora - Framework
 *
 * Aurora is fast, simple, extensible Framework
 *
 * PHP version 6
 *
 * @category   Framework
 * @package    Aurora
 * @author     VeeeneX <veeenex@gmail.com>
 * @copyright  2015 Caroon
 * @license    MIT
 * @version    1.0
 * @link       http://caroon.com/Aurora
 *
 */

namespace Aurora;

class Session
{
   /**
    * $config Configuration
    * @var array
    */
   private $config;

   /**
    * __construct Constructor
    * @param \SessionHandlerInterface $Handler Handler for session
    * @param array $config Configuration for session
    *
    * @todo Fail back for session configuration
    */
   public function __construct(
		\SessionHandlerInterface $Handler = null,
		$config
	) {
      $this->config = $config;

      if($Handler !== null) {
         session_set_save_handler($Handler, true);
      }

		session_set_cookie_params(
         $this->config["lifetime"],
         $this->config["path"],
         $this->config["domain"],
         $this->config["secure"],
         $this->config["httponly"]
      );
      session_name($this->config['name']);
      register_shutdown_function([$this, "__destruct"]);
   }

   /**
    * start Start the session or thow an error
    * @return null Should be nicer in future
    */
	public function start()
	{
      try {
         session_start();
      } catch (\Exeption $Exeption){
         throw new \Exeption("Session was already created");
      }
   }

   public function destroy()
   {
      return session_destroy();
   }

   public function regenerate($keep = false)
   {
      return session_regenerate_id(!$keep);
   }

	public function id()
   {
      return session_id();
   }

	public function clear()
   {
      $_SESSION = [];
   }

	public function reset()
   {
      $_SESSION = [];
   }

	public function get($key, $default = null)
   {
      return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
   }

	public function has($key)
   {
      return isset($_SESSION[$key]);
   }

	public function delete($key)
   {
      unset($_SESSION[$key]);
   }

   public function remove($key)
   {
      unset($_SESSION[$key]);
   }

	public function set($key, $value)
   {
      $_SESSION[$key] = $value;
   }

	public function __destruct()
   {
	  session_write_close();
   }
}
