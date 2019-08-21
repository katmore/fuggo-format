<?php
namespace FuggoFormat\Io;

use FuggoFormat\Lexer;

class FsStreamLineDispatcher implements Lexer\LineDispatcherInterface {
   /**
    * @var \FuggoFormat\Lexer\LineListenerProviderInterface
    */
   private $provider;
   
   use Lexer\LineDispatcherTrait;
   
   protected function getProvider(): Lexer\LineListenerProviderInterface {
      return $this->provider;
   }
   
   public function __construct(Lexer\LineListenerProviderInterface $provider, StreamInterface $stream) {
      $this->provider = $provider;
      while(false!==($buff = fgets($stream->getStreamHandle()))) {
         $this->dispatch(new Lexer\Line($buff));
      }
      unset($buff);
      if (!feof($stream->getStreamHandle())) {
         $streamData = stream_get_meta_data($stream->getStreamHandle());
         throw new StreamException("fgets failed");
      }
   }
}