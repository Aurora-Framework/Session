<?php

namespace Aurora;

use Aurora\Session\Exception\SessionAlreadyExistsException;
use SessionHandlerInterface;
use Exception;

class Session
{
   /**
    * $config Configuration
    * @var array
    */
   private $config;

   private $default = [
      "lifetime" => 86400,
      "path" => "/",
      "domain" => null,
      "secure" => true,
      "httpOnly" => true
   ];

   /**
    * __construct Constructor
    * @param \SessionHandlerInterface $Handler Handler for session
    * @param array $config Configuration for session
    */
   public function __construct(
		SessionHandlerInterface $Handler = null,
		$config
	) {
      $config = array_merge($this->default, $config);

      $this->config = (array) $config;

      if ($Handler !== null) {
         session_set_save_handler($Handler, true);
      }

		session_set_cookie_params(
         $this->config["lifetime"],
         $this->config["path"],
         $this->config["domain"],
         $this->config["secure"],
         $this->config["httpOnly"]
      );
      session_name($this->config['name']);
      register_shutdown_function([$this, "__destruct"]);
   }

	public function start()
	{
      try {
         session_start();
      } catch (Exeption $Exeption){
         throw new SessionAlreadyExistsException("Session was already created");
      }

      return $this;
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

      return $this;
   }

	public function reset()
   {
      $_SESSION = [];

      return $this;
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

      return $this;
   }

   public function remove($key)
   {
      unset($_SESSION[$key]);

      return $this;
   }

	public function set($key, $value)
   {
      $_SESSION[$key] = $value;

      return $this;
   }

	public function __destruct()
   {
	  session_write_close();
   }
}
