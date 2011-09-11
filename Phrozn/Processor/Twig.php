<?php
/**
 * Copyright 2011 Victor Farazdagi
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at 
 *
 *          http://www.apache.org/licenses/LICENSE-2.0 
 *
 * Unless required by applicable law or agreed to in writing, software 
 * distributed under the License is distributed on an "AS IS" BASIS, 
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
 * See the License for the specific language governing permissions and 
 * limitations under the License. 
 *
 * @category    Phrozn
 * @package     Phrozn\Processor
 * @author      Victor Farazdagi
 * @copyright   2011 Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrozn\Processor;
use Phrozn\Autoloader as Loader;

/**
 * Twig templates processor
 *
 * @category    Phrozn
 * @package     Phrozn\Processor
 * @author      Victor Farazdagi
 */
class Twig
    extends Base
    implements \Phrozn\Processor 
{
    /**
     * Reference to twig engine environment object
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * Reference to twig current template loader
     *
     * @var \Twig_LoaderInterface
     */
    protected $loader;

    /**
     * If configuration options are passed then twig environment
     * is initialized right away
     *
     * @param array $options Processor options
     *
     * @return \Phrozn\Processor\Twig
     */
    public function __construct($options = array())
    {
        $path = Loader::getInstance()->getPath('library');

        // Twig uses perverted file naming (due to absense of NSs at a time it was written)
        // so fire up its own autoloader
        require_once $path . '/Vendor/Twig/Autoloader.php';
        \Twig_Autoloader::register();

        if (count($options)) {
            $this->setConfig($options)
                 ->getEnvironment();
        }
    }

    /**
     * Parse the incoming template
     *
     * @param string $tpl Source template content
     * @param array $vars List of variables passed to template engine
     *
     * @return string Processed template
     */
    public function render($tpl, $vars = array())
    {
        $config = $this->getConfig();
        return $this->getEnvironment()
                    ->loadTemplate($config['phr_template_filename'])
                    ->render($vars);
    }

    /**
     * Get (init if necessary) twig environment
     *
     * @param boolean $reset Force re-initialization
     *
     * @return \Twig_Environment
     */
    protected function getEnvironment($reset = false)
    {
        if ($reset === true || null === $this->twig) {
            $this->twig = new \Twig_Environment(
                $this->getLoader(), $this->getConfig());
            $this->twig->removeExtension('escaper');
        }

        return $this->twig;
    }

    /**
     * Get template loader
     *
     * @return \Twig_LoaderInterface
     */
    protected function getLoader()
    {
        $config = $this->getConfig();
        return new \Twig_Loader_Filesystem($config['phr_template_dir']);
    }
}
