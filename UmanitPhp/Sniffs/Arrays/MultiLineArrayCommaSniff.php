 <?php

/**
 * This sniff prohibits the use of a comma at the beginning
 * of a multi line array item.
 *
 * An example of a bad array declaration is :
 *
 * <code>
 * $array = array(
 *     "one"
 *     , "two"
 *     , "three"
 * );
 * </code>
 *
 * Whereas an example of a good array declaration is :
 *
 * <code>
 * $array = array(
 *     "one",
 *     "two",
 *     "three"
 * );
 * </code>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Vincent Robic <vrobic@umanit.fr>
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class UmanitPhp_Sniffs_Arrays_MultiLineArrayCommaSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return int[]
     */
    public function register()
    {
        return array(
            T_ARRAY, // array()
            T_OPEN_SHORT_ARRAY, // []
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
        // @todo
    }
}
