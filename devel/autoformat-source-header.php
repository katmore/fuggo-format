#!/usr/bin/env php
<?php
use FuggoFormat\Io;
use FuggoFormat\Lexer;
use FuggoFormat\PhpSource;

/*
 * adds header comment to a PHP source file
 */
new class() {
   /**
    * @var string name of this script
    */
   const ME = 'source-headers.php';

   /**
    * @var string autoformat.d root dir
    */
   const TEMPLATE_ROOT = __DIR__ . '/autoformat.d';

   /**
    * @var string default template basename
    */
   const DEFAULT_TEMPLATE = 'source-header.template';

   /**
    * @var int exit status
    * @private
    */
   private $exitStatus = 0;

   /**
    * concatenates template path
    * @param string $pathOrBasename
    *           template path or template basename
    * @return string template path
    */
   private static function concatTemplatePath(string $pathOrBasename = null): string {
      if (empty($pathOrBasename)) return static::TEMPLATE_ROOT . "/" . static::DEFAULT_TEMPLATE;
      if (substr($pathOrBasename,0,1) === DIRECTORY_SEPARATOR || file_exists($pathOrBasename)) return $pathOrBasename;
      return static::TEMPLATE_ROOT . "/$pathOrBasename";
   }

   /**
    * serializes usage message
    * @return string usage message
    */
   private static function serializeUsageMessage(): string {
      /*@formatter:off*/
      return
         "Usage:\n".
         "  ".static::ME." [--help] | [OPTIONS...] [PHP-SOURCE]\n".
         "  ".static::ME." [OPTIONS...] [PHP-SOURCE]\n".
         "  ".static::ME." [--save=PATH | --overwrite,-i][--template PATH](=".static::serializeTemplatePath().") [PHP-SOURCE]\n"
      ;
      /* @formatter:on */
   }

   /**
    * serializes help message
    * @return string help message
    */
   private static function serializeHelpMessage(): string {
      /*@formatter:off*/
      return
         "Options:\n".
         "  --help : display help message and exit\n".
         "  --overwrite, -i : overwrite the PHP-SOURCE instead of sending output to stdout\n".
         "  --save-file=PATH : save to PATH instead of sending output to stdout\n".
         "  --template=PATH : header template path, default: ".static::serializeTemplatePath()."\n".
         "\n".
         "Operands:\n".
         "  PHP-SOURCE : optional path to php source file, otherwise, php source is read from stdin\n"
      ;
      /* @formatter:on */
   }

   /**
    * serializes about message
    * @return string about message
    */
   private static function serializeAboutMessage(): string {
      /*@formatter:off*/
      return
         static::ME."\n".
         "re-formats a PHP source file to have a particular comment header\n"
      ;
      /* @formatter:on */
   }
   public function __destruct() {
      exit($this->exitStatus);
   }
   public function __construct() {
      if (!isset($GLOBALS['argv'])) {
         throw new RuntimeException("missing argv global: this script must be executed in cli mode");
      }

      $argv = $GLOBALS['argv'];

      $offset = null;

      /*
       * parse command-line options
       */
      $option = getopt("huia",[
         'about',
         'help',
         'usage',
         'save-file:',
         'overwrite',
         'template:',
      ],$offset);

      if (isset($option['about']) || isset($option['a']) || isset($option['usage']) || isset($option['u']) ||
            isset($option['help']) || isset($option['h'])) {
         echo static::serializeAboutMessage();
         if (isset($option['about']) || isset($option['a'])) {
            return;
         }
         echo "\n";
         echo static::serializeUsageMessage();
         if (isset($option['usage']) || isset($option['u'])) {
            return;
         }
         echo "\n";
         echo static::serializeHelpMessage();
         return;
      }

      $interpolatedLineListenerProvider = new Lexer\LineListenerProvider();
      $originalLineListenerProvider = new Lexer\LineListenerProvider();
      
      new PhpSource\CommentHeaderInterpolator($interpolatedLineListenerProvider, $originalLineListenerProvider);
      //new PhpSource\CommentHeaderInterpolator
      
      $interpolatedLineListenerProvider->addListener(function (Lexer\Line $line) {
         echo "interpolated line: $line\n";
      });

      exit(4);

      /**
       * @var string[] $sourceHeaderTemplate array of each line of source header template
       * @see self::SOURCE_HEADER_TEMPLATE_PATH
       */
      $sourceHeaderTemplatePath = static::concatTemplatePath(isset($option['template']) ? $option['template'] : null);
      if (false === ($sourceHeaderTemplate = file($sourceHeaderTemplatePath,FILE_IGNORE_NEW_LINES))) {
         fwrite(STDERR,"file failed on source header template: $sourceHeaderTemplatePath\n");
         return $this->exitStatus = 1;
      }

      /*
       * apply option parse offset
       */
      $argv = array_slice($argv,$offset);

      /*
       * apply PHP-SOURCE operand
       */
      if (null === ($path = array_shift($argv))) {

         $source = [];
         while ($line = fgets(STDIN)) {
            $source[] = substr($line,0,-1);
         }
         unset($line);

         if (!feof(STDIN)) {
            fwrite(STDERR,"fgets failed on STDIN\n");
            return $this->exitStatus = 1;
         }
      } else {
         /*
          * replace tilde with $HOME in PHP-SOURCE
          */
         !empty($_SERVER['HOME']) && $path = preg_replace('/^~/',$_SERVER['HOME'],$path);

         /**
          * @var string[] $source array of each line of PHP-SOURCE
          */
         if (false === ($source = file($path,FILE_IGNORE_NEW_LINES))) {
            fwrite(STDERR,"file failed on PHP-SOURCE: $path\n");
            return $this->exitStatus = 1;
         }
      }

      /**
       * @var int[] $token array of each php token in PHP-SOURCE
       */
      $token = token_get_all(implode("\n",$source));

      if (!isset($token[0])) {
         fwrite(STDERR,"empty source: $path\n");
         return $this->exitStatus = 1;
      }
      var_dump($token);
      echo "debug\n";
      exit(4);


      /**
       * @var int|null $namespaceLine line number of namespace declaration in PHP-SOURCE
       */
      $namespaceLine = null;

      /**
       * @var int[] $commentBeforeNamespace line numbers any comments before namespace declaration in PHP-SOURCE
       */
      $commentBeforeNamespace = [];
      array_walk($token,function ($tdata) use (&$namespaceLine, &$commentBeforeNamespace) {
         if ($namespaceLine === null && is_array($tdata)) {
            if ($tdata[0] === T_NAMESPACE) {
               $namespaceLine = $tdata[2];
            } else if ($tdata[0] === T_DOC_COMMENT || $tdata[0] === T_COMMENT) {
               $start = $tdata[2];
               $commentBeforeNamespace[] = [
                  'start' => $start,
                  'stop' => $start + substr_count($tdata[1],"\n"),
               ];
            }
         }
      });



      /*
       * deal with any comments before namespace declaration
       */
      if (count($commentBeforeNamespace)) {
         $headerOffset = 0;
         array_walk($commentBeforeNamespace,function (array $data) use (&$token, &$source, &$headerOffset) {
            $sourceBefore = array_slice($source,0,$data['start'] - 1 - $headerOffset);
            $sourceAfter = array_slice($source,$data['stop'] - $headerOffset);
            $headerOffset += $data['stop'] - $data['start'] + 1;
            $source = $sourceBefore;
            $source = array_merge($source,$sourceAfter);
            $token = token_get_all(implode("\n",$source));
         });
      }

      /*
       * re-determine namespace line and empty lines before namespace declaration
       * (having dealt with comments before namespace declaration)
       */
      $namespaceLine = null;
      /**
       * @var int[] $emptyLineBeforeNamespace line numbers of empty lines before namespace declaration
       */
      $emptyLineBeforeNamespace = [];
      array_walk($token,function ($tdata) use (&$namespaceLine, &$emptyLineBeforeNamespace) {

         if ($namespaceLine === null && is_array($tdata)) {

            if ($tdata[0] === T_NAMESPACE) {

               $namespaceLine = $tdata[2];
            } else {
               if (ctype_space($tdata[1])) {
                  for($i = 0;$i < substr_count($tdata[1],"\n");$i ++) {
                     $emptyLineBeforeNamespace[] = $i + $tdata[2];
                  }
               }
            }
         }
      });



      /*
       * re-order source according to namespace declaration offset, etc.
       */
      if ($namespaceLine !== null) {
         if (count($emptyLineBeforeNamespace)) {
            $headerOffset = 0;
            array_walk($emptyLineBeforeNamespace,function (int $sourceno) use (&$token, &$source, &$headerOffset) {
               $sourceBefore = array_slice($source,0,$sourceno - 1 - $headerOffset);
               $sourceAfter = array_slice($source,$sourceno - $headerOffset);
               $headerOffset ++;
               $source = $sourceBefore;
               $source = array_merge($source,$sourceAfter);
               $token = token_get_all(implode("\n",$source));
            });
         }
      }

      var_dump($token);
      var_dump($source);
      var_dump($namespaceLine);
      exit(4);

      /**
       * @var int $startLine line to start source output
       */
      $startLine = null;

      /*
       * determine $startLine:
       * should be namespace declaration line, if it exists
       */
      array_walk($token,function ($tdata) use (&$startLine) {
         if ($startLine === null) {
            if ($tdata[0] === T_NAMESPACE) {
               $startLine = $tdata[2];
            }
         }
      });

      /*
       * determine $startLine:
       * if namespace declaration did not exist, should be same as first open-tag
       */
      if ($startLine == null) {
         array_walk($token,function ($tdata) use (&$startLine) {
            if ($startLine === null) {
               if ($tdata[0] === T_OPEN_TAG) {
                  $startLine = $tdata[2];
               }
            }
         });
      }

      /**
       * @var string[] $sourceBefore top half of source
       */
      $sourceBefore = array_slice($source,0,$startLine - 1);

      /**
       * @var string[] $sourceAfter bottom half of source
       */
      $sourceAfter = array_slice($source,$startLine - 1);

      /*
       * re-serialize $source array from $sourceBefore
       */
      $source = $sourceBefore;

      /*
       * append newline to $source
       */
      $source[] = '';

      /*
       * append $sourceHeaderTemplate (comment header) to $source
       */
      $source = array_merge($source,$sourceHeaderTemplate);

      /*
       * append newline to $source
       */
      $source[] = '';

      /*
       * append $sourceAfter (bottom half of source) to $source
       */
      $source = array_merge($source,$sourceAfter);

      /*
       * output $source
       */
      array_walk($source,function (string $line) {
         echo "$line\n";
      });
   }
};