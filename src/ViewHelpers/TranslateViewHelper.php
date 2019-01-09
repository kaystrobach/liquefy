<?php

<?php

namespace KayStrobach\Liquefy\ViewHelpers;

/**
 * Class TranslateViewHelper
 * @package KayStrobach\Fluege\ViewHelpers
 */
class TranslateViewHelper extends \Neos\FluidAdaptor\ViewHelpers\TranslateViewHelper
{
    /**
     * Renders the translated label.

     * Replaces all placeholders with corresponding values if they exist in the
     * translated label.
     *
     * @param string $id Id to use for finding translation (trans-unit id in XLIFF)
     * @param string $value If $key is not specified or could not be resolved, this value is used. If this argument is not set, child nodes will be used to render the default
     * @param array $arguments Numerically indexed array of values to be inserted into placeholders
     * @param string $source Name of file with translations
     * @param string $package Target package key. If not set, the current package key will be used
     * @param mixed $quantity A number to find plural form for (float or int), NULL to not use plural forms
     * @param string $locale An identifier of locale to use (NULL for use the default locale)
     * @return string Translated label or source label / ID key
     * @throws ViewHelperException
     */
    public function render($id = null, $value = null, array $arguments = array(), $source = 'Main', $package = null, $quantity = null, $locale = null)
    {
        if ($value !== null) {
            return $value;
        }
        return $id;
    }
}
