<?php

namespace Gawain\Classes\Net;

/** Manages JS and CSS ordered loading
 *
 * Class Jierarchy
 */
class Jierarchy
{

    /** Dependency Source parsed from file
     *
     * @var mixed
     */
    private $depSource;

    /** Javascript paths
     *
     * @var array
     */
    private $JsPaths = array();

    /** CSS paths
     *
     * @var array
     */
    private $CssPaths = array();


    /** Constructor
     *
     * @param string $str_DepFilePath
     */
    public function __construct($str_DepFilePath)
    {
        $str_JsonContent = file_get_contents($str_DepFilePath);
        $this->depSource = json_decode($str_JsonContent, true);
    }


    /** Loads the selected file sets into current page by echoing inclusion strings
     *
     * @param array $arr_LibraryNames
     */
    public function load($arr_LibraryNames)
    {

        $this->calculateDependencies($arr_LibraryNames);

        $arr_Output['css'] = array_filter($this->CssPaths);
        $arr_Output['js'] = array_filter($this->JsPaths);

        return $arr_Output;
    }


    /** Calculates the dependencies of the given file sets
     *
     * @param array $arr_LibraryNames
     */
    private function calculateDependencies($arr_LibraryNames)
    {

        foreach ($arr_LibraryNames as $str_LibraryName) {
            $arr_CurrentNode = $this->depSource[$str_LibraryName];

            if (!empty($arr_CurrentNode['dependencies'])) {
                $this->calculateDependencies($arr_CurrentNode['dependencies']);
            }

            if (!empty($arr_CurrentNode['path']['js'])) {
                foreach ($arr_CurrentNode['path']['js'] as $str_JsPath) {
                    $this->JsPaths[] = $str_JsPath;
                }
            }

            if (!empty($arr_CurrentNode['path']['css'])) {
                foreach ($arr_CurrentNode['path']['css'] as $str_CssPath) {
                    $this->CssPaths[] = $str_CssPath;
                }
            }
        }


        // 'Flip flip' method to get a unique array °_°
        $this->JsPaths = array_merge(array_flip(array_flip($this->JsPaths)));
        $this->CssPaths = array_merge(array_flip(array_flip($this->CssPaths)));

    }
}
