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
 * @package     Phrozn\Outputter
 * @author      Victor Farazdagi
 * @copyright   2011 Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace PhroznTest\Outputter;
use Phrozn\Outputter\DefaultOutputter as Outputter;

/**
 * @category    Phrozn
 * @package     Phrozn\Outputter
 * @author      Victor Farazdagi
 */
class DefaultOutputterTest 
    extends \PHPUnit_Framework_TestCase
{
    public function testStdOut()
    {
        $outputter = new Outputter();
        ob_start();
        $outputter->stdout('sending output', '');
        $out = trim(ob_get_clean());

        $this->assertSame('sending output', $out);
    }

    public function testStdErr()
    {
        $outputter = new Outputter();
        ob_start();
        $outputter->stderr('sending output', '');
        $out = trim(ob_get_clean());

        $this->assertSame('sending output', $out);
    }

    public function testStdOutWithResource()
    {
        $fp = fopen('/tmp/stdout', 'w+');
        define('STDOUT', $fp);
        fclose($fp);

        $outputter = new Outputter();
        $outputter->stdout('sending output', '');

        $this->assertSame('sending output', trim(file_get_contents('/tmp/stdout')));
    }

    public function testStdErrWithResource()
    {
        $fp = fopen('/tmp/stderr', 'w+');
        define('STDERR', $fp);
        fclose($fp);

        $outputter = new Outputter();
        $outputter->stderr('sending output', '');

        $this->assertSame('sending output', trim(file_get_contents('/tmp/stderr')));
    }
}
