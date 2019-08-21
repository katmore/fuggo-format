<?php
namespace FuggoFormat;

use Serializable;
use RuntimeException;
use InvalidArgumentException;

class LineStream implements Serializable, LineIteratorInterface {

   use LineIteratorTrait;

   /**
    * @var resource|null
    */
   private $handle;

   /**
    * @var string|null
    */
   private $path;

   /**
    * @param resource|string $source
    *           a stream resource handle or a path/uri
    */
   public function __construct($source) {
      if (is_resource($source) && get_resource_type($source) === 'stream') {
         if (null === ($streamInfo = stream_get_meta_data($source))) {
            throw new RuntimeException("stream_get_meta_data failed");
         }
         $this->handle = $source;
         $this->path = $streamInfo['uri'];
         return;
      }
      if (is_string($source)) {
         $this->unserialize($source);
         return;
      }
      throw new InvalidArgumentException('source must be a stream resource handle or a path/uri');
   }

   /**
    * @return resource|null
    */
   public function getHandle() {
      return $this->handle;
   }
   /**
    * @return string path/uri of stream
    */
   public function serialize(): string {
      return (string) $this->path;
   }
   /**
    * @param string $serialized
    *           a path/uri
    */
   public function unserialize(string $serialized) {
      if (false === ($handle = fopen($serialized,'r'))) {
         throw new RuntimeException("fopen failed on path: $serialized");
      }
      $this->handle = $handle;
   }
   public function nextLine(): ?string {
      if (false===($buffer = fgets($this->handle))) {
         if (!feof($this->handle)) {
            throw new RuntimeException("fgets failed");
         }
         return null;
      }
      return $buffer;
   }
   public function endOfLines(): bool {
      return feof($this->handle);
   }
}