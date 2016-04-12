<?php
/**
 * Gawain
 * Copyright (C) 2016  Stefano Romanò (rumix87 (at) gmail (dot) com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once(PHP_CLASSES_DIR . 'misc/Options.php');

/**
 * Class i18n
 *
 * Localizes all strings in Gawain
 */
class i18n
{

    /** Language files folder
     *
     * @var string
     */
    private $languageFilesFolder = I18N_DIR;

    /** Options handler
     *
     * @var Options
     */
    private $options;

    /** Translations array
     *
     * @var array
     */
    private $translations = array(
        'main' => null,
        'fallback' => null
    );

    /** Main language
     *
     * @var
     */
    private $language;

    /** Fallback language, used if the string is not found in main language
     *
     * @var mixed
     */
    private $fallbackLanguage;


    /**
     * i18n constructor.
     *
     * @param $str_LanguageID
     */
    public function __construct($str_LanguageID)
    {
        // Main language passed to constructor
        $this->language = $str_LanguageID;
        $this->translations['main'] = $this->loadLanguage($this->language, false);

        // Fallback language loaded from options
        $this->options = new Options();
        $this->fallbackLanguage = $this->options->get('fallback_locale');
        $this->translations['fallback'] = $this->loadLanguage($this->fallbackLanguage, true);
    }


    /** Loads selected language file.
     *  If the language is marked as critical and the language file is not found, an exception is raised.
     *
     * @param      $str_LanguageID
     * @param bool $bool_IsCritical
     *
     * @return array
     * @throws Exception
     */
    private function loadLanguage($str_LanguageID, $bool_IsCritical = false)
    {
        $str_MainLanguageFilePath = $this->languageFilesFolder . $str_LanguageID . '.ini';

        // If the language is not critical, check if main language file exists... if not exists, skip this file
        if (!file_exists($str_MainLanguageFilePath) && $bool_IsCritical) {
            throw new Exception('Fallback language file does not exist');
        }

        return parse_ini_file($str_MainLanguageFilePath, true);
    }


    /** Magic call to get the selected string
     *  The string has to be specified as {section}__{stringID}
     *
     * @param $str_StringID
     *
     * @return mixed
     */
    public function __call($str_StringID)
    {
        $arr_Parameters = explode('__', $str_StringID, 2);

        if (isset($this->translations['main'][$arr_Parameters[0]]) &&
            isset($this->translations['main'][$arr_Parameters[0]][$arr_Parameters[1]])) {
            return $this->translations['main'][$arr_Parameters[0]][$arr_Parameters[1]];
        } elseif (isset($this->translations['fallback'][$arr_Parameters[0]]) &&
            isset($this->translations['fallback'][$arr_Parameters[0]][$arr_Parameters[1]])) {
            return $this->translations['fallback'][$arr_Parameters[0]][$arr_Parameters[1]];
        }

        return $str_StringID;
    }


    public function getAvailableLanguages()
    {
        $arr_AvailableLanguages = array();
        $arr_LanguageFiles = array_diff(scandir(I18N_DIR), array('..', '.'));

        foreach ($arr_LanguageFiles as $str_Filename) {
            $arr_AvailableLanguages[] = str_replace('.ini', '', $str_Filename);
        }

        return $arr_AvailableLanguages;
    }

}
