<?php

/**
 * This sniff forces the use of a space at the beginning
 * of a double slash single line comment.
 *
 * An example of a bad comment is :
 *
 * <code>
 * //This is a bad comment, which is prohibited.
 * </code>
 *
 * Whereas an example of a good comment is :
 *
 * <code>
 * // This is a good comment.
 * </code>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Vincent Robic <vrobic@umanit.fr>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class UmanitPhp_Sniffs_Commenting_SingleLineCommentSniff implements PHP_CodeSniffer_Sniff
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
        );
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        $comment = $token['content'];
        $isDoubleSlashComment = substr($comment, 0, 2) === '//';

        if ($isDoubleSlashComment) {
            $hasLeadingSpace = $tokens[$stackPtr]['content'][2] === ' ';
            if ($hasLeadingSpace) {
                $hasMoreThanOneLeadingSpace = $tokens[$stackPtr]['content'][3] === ' ';
                if ($hasMoreThanOneLeadingSpace) {
                    $fix = $phpcsFile->addFixableWarning(
                        'Double slash comments must start with a single space',
                        $stackPtr,
                        'SingleLeadingSpaceNeeded'
                    );
                    if ($fix) {
                        $fixedComment = preg_replace('/[ ]+/', ' ', $comment);
                        $phpcsFile->fixer->replaceToken($stackPtr, $fixedComment);
                    }
                }
            } else {
                $fix = $phpcsFile->addFixableWarning(
                    'Double slash comments must start with a space',
                    $stackPtr,
                    'LeadingSpaceNeeded'
                );
                if ($fix) {
                    $fixedComment = substr_replace($comment, '// ', 0, 2);
                    $phpcsFile->fixer->replaceToken($stackPtr, $fixedComment);
                }
            }
        }
    }
}
