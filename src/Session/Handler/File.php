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

namespace Aurora\Session\Handler;

class File implements \SessionHandlerInterface
{
   private $savePath;

   public function open($savePath, $sessionName)
   {
      $this->savePath = $savePath;
      if (!is_dir($this->savePath)) {
         mkdir($this->savePath, 0777);
      }

      return true;
   }

   public function close()
   {
     return true;
   }

   public function read($id)
   {
      $data = '';
      if(file_exists($this->savePath . '/' . $id) && is_readable($this->savePath . '/' . $id)) {
         $data = file_get_contents($this->savePath . '/' . $id);
      }

      return (string) $data;
   }

   public function write($id, $data)
   {
      if(is_writable($this->savePath)) {
         return file_put_contents($this->savePath . '/' . $id, $data) === false ? false : true;
      }

      return false;
   }

   public function destroy($id)
   {
      if(file_exists($this->savePath . '/' . $id) && is_writable($this->savePath . '/' . $id)) {
         return unlink($this->savePath . '/' . $id);
      }

      return false;
   }

   public function gc($maxlifetime)
   {
      $files = glob($this->savePath . '/*');

      if(is_array($files)) {
         foreach($files as $file) {
            if((filemtime($file) + $maxLifetime) < time() && is_writable($file)) {
               unlink($file);
            }
         }
      }

      return true;
   }
}
