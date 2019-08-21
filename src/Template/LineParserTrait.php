<?php
namespace FuggoFormat\Template;

trait LineParserTrait {
   
   protected static function parseTemplateLine(string $line, callable $paramHandler) : string {
      $startParamOffset = 0;
      while (false !== ($startParamPos = strpos($line,static::SYMBOL_PARAM_START,$startParamOffset))) {
         $endParamOffset = $startParamOffset = $startParamPos;
         if (false !== ($endParamPos = strpos($line,static::SYMBOL_PARAM_END,$endParamOffset))) {
            $paramToken = substr($line,$startParamPos + strlen(static::SYMBOL_PARAM_START),$endParamPos -
                  $startParamPos -
                  strlen(static::SYMBOL_PARAM_START .
                        static::SYMBOL_PARAM_END));
            
         } else {
            throw new LineParserException('missing end param symbol', $endParamOffset);
         }
      }
   }
   
}