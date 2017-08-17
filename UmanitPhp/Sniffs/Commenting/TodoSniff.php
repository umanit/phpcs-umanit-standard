<?php

namespace UmanitPhp\Sniffs\Commenting;

/**
 * This sniff establishes a convention for the todo keyword in comments.
 *
 * It must be written as :
 *
 * <code>
 * @todo ABC this is the comment
 * </code>
 *
 * where ABC is the trigram of the developper who wrote it.
 *
 * @category  PHP
 * @package   \PHP_CodeSniffer
 * @author    Vincent Robic <vrobic@umanit.fr>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class UmanitPhpTodoSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return int[]
     */
    public function register()
    {
        return array(
            T_COMMENT, // single line comment
            T_DOC_COMMENT_STRING, // multi line comment
        );
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @todo Handle multiple todos in a single comment token.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file where the token was found.
     * @param int                         $stackPtr  The position in the stack where
     *                                               the token was found.
     *
     * @return void
     */
    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        $comment = $token['content'];

        $todoPos = stripos($comment, 'todo');

        // Is there a todo tag?
        if ($todoPos === false) {
            return;
        }

        // Is there something next to the todo tag that can't be trimmed?
        $hasSomethingBefore = isset($comment[$todoPos-1]) && !in_array(trim($comment[$todoPos-1]), array('', '@', '/'));
        $hasSomethingAfter = isset($comment[$todoPos+4]) && trim($comment[$todoPos+4]) !== '';

        // If not, then it's really a todo tag!
        if (!$hasSomethingBefore && !$hasSomethingAfter) {

            // Lowercase?
            $todoKeyword = substr($comment, $todoPos, 4);
            if (!preg_match('/todo/', $todoKeyword)) {
                $fix = $phpcsFile->addFixableWarning(
                    'Todo should be lowercase',
                    $stackPtr,
                    'UppercaseForbidden'
                );
                if ($fix) {
                    $fixedComment = str_ireplace('todo', 'todo', $comment);
                    $phpcsFile->fixer->replaceToken($stackPtr, $fixedComment);
                }
            }

            // @ prefix?
            if ($todoPos === 0 || $comment[$todoPos-1] !== '@') {
                $fix = $phpcsFile->addFixableError(
                    'Todo is missing @ prefix',
                    $stackPtr,
                    'MissingAtSignPrefix'
                );
                if ($fix) {
                    $fixedComment = str_ireplace('todo', '@todo', $comment);
                    $phpcsFile->fixer->replaceToken($stackPtr, $fixedComment);
                }
            }

            // Developer's trigram?
            $todo = '@' . substr($comment, $todoPos, 9);
            if (!preg_match('/@(todo|TODO) [A-Z]{3}\s/', $todo)) {
                $phpcsFile->addWarning(
                    'Todo should be followed by the uppercased developer\'s trigram. Expected something like "@todo ABC" where ABC is the trigram, but found "%s"',
                    $stackPtr,
                    'TrigramSuffix',
                    array(
                        $todo,
                    )
                );
            }
        }
    }
}
