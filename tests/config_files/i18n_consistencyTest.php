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

namespace tests\config_files;

require_once('common/php/constants/global_defines.php');


class i18n_consistencyTest extends \PHPUnit_Framework_TestCase
{

    public function testAllLanguagesHaveSameSections()
    {
        $arr_ReferenceLanguageFileContent = parse_ini_file(I18N_DIR . 'en_EN.ini', true);
        $arr_OtherLanguages = array_diff(scandir(I18N_DIR), array('..', '.', 'en_EN.ini'));

        foreach ($arr_OtherLanguages as $str_OtherLanguageFile) {
            $arr_OtherLanguageFileContent = parse_ini_file(I18N_DIR . $str_OtherLanguageFile, true);

            $this->assertEquals(array_keys($arr_ReferenceLanguageFileContent),
                                array_keys($arr_OtherLanguageFileContent));
        }
    }


    public function testAllLanguageSectionsHaveAllEntries()
    {
        $arr_ReferenceLanguageFileContent = parse_ini_file(I18N_DIR . 'en_EN.ini', true);
        $arr_OtherLanguages = array_diff(scandir(I18N_DIR), array('..', '.', 'en_EN.ini'));

        $arr_ReferenceLanguageSections = array_keys($arr_ReferenceLanguageFileContent);

        foreach ($arr_OtherLanguages as $str_OtherLanguageFile) {
            $arr_OtherLanguageFileContent = parse_ini_file(I18N_DIR . $str_OtherLanguageFile, true);

            foreach ($arr_ReferenceLanguageSections as $str_LanguageSection) {
                $this->assertEquals(array_keys($arr_ReferenceLanguageFileContent[$str_LanguageSection]),
                                    array_keys($arr_OtherLanguageFileContent[$str_LanguageSection]));
            }
        }
    }
}
