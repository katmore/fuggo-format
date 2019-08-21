<?php
namespace FuggoFormat\PhpSource;

use FuggoFormat\PhpSourceInterface;

interface CommentHeaderInterpolatorInterface {
   abstract public function getCommentHeader(): string;
}