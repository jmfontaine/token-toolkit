<?php
/**
 * Copyright (c) 2011-2012, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name PHP Token Toolkit nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL Jean-Marc Fontaine BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   PhpTokenToolkit
 * @subpackage Tests
 * @author    Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit\Tests\Search\Pattern;

use PhpTokenToolkit\Search\Pattern\IpPattern;
use PhpTokenToolkit\Search\Query as SearchQuery;
use PhpTokenToolkit\TokenStack;

class IpPatternTest extends \PHPUnit_Framework_TestCase
{
    private function assertIpIsValid($ip, $expected, $message = '')
    {
        $searchQuery = new SearchQuery(
            new TokenStack("<?php $ip = '$ip'; ?>"),
            new IpPattern()
        );
        $resultSet = $searchQuery->search();

        $foundInResults = false;
        foreach ($resultSet as $result) {
            if ($ip === $result->getToken()->getContent()) {
                $foundInResults = true;
                break;
            }
        }

        $this->assertSame($expected, $foundInResults, $message);
    }

    /*
     * Methods
     */

    /**
     * @test
     * @see https://en.wikipedia.org/wiki/IPv4#Address_representations
     */
    public function matchesIpv4()
    {
        $data = array(
            // Dot-decimal notation
            '192.0.2.235' => true,

            /*
             * KLUDGE: PHP Filter extension do not recognize the following valid notations
             * so we have to ignore them for now.
             *
             */
            /*
            // Dotted Hexadecimal notation
			'0xC0.0x00.0x02.0xEB' => true,

            // Dotted Octal notation
            '0300.0000.0002.0353' => true,

            // Hexadecimal notation
            '0xC00002EB' => true,

            // Decimal notation
            '3221226219' => true,

            // Octal notation
            '030000001353' => true,
            */

            // Garbage
			''      => false,
            '42'    => false,
        	'dummy' => false,
        );

        foreach ($data as $ip => $isValid) {
            $this->assertIpIsValid($ip, $isValid, "IP: $ip");
        }
    }

    /**
     * @test
     * @see https://en.wikipedia.org/wiki/IPv6#Address_Format
     * @see http://crisp.tweakblogs.net/blog/2031/ipv6-validation-%28and-caveats%29.html
     * @see http://therealcrisp.xs4all.nl/blog/article-content/ipv6/ipv6.phps
     */
    public function matchesIpv6()
    {
        $data = array(
            '2001:0db8:0000:0000:0000:0000:1428:57ab'  => true,
            '2001:0DB8:0000:0000:0000:0000:1428:57AB'  => true,
            '2001:00db8:0000:0000:0000:0000:1428:57ab' => false,
            '2001:0db8:xxxx:0000:0000:0000:1428:57ab'  => false,

        	'2001:db8::1428:57ab'  => true,
            '2001:db8::1428::57ab' => false,
            '2001:dx0::1234'       => false,
            '2001:db0::12345'      => false,

            ':'    => false,
            '::'   => true,
            ':::'  => false,
            '::::' => false,
            '::1'  => true,
            ':::1' => false,

        	'::1.2.3.4'             => true,
            '::256.0.0.1'           => false,
            '::01.02.03.04'         => false,
            'a:b:c::1.2.3.4'        => true,
            'a:b:c:d::1.2.3.4'      => true,
            'a:b:c:d:e::1.2.3.4'    => true,
            'a:b:c:d:e:f:1.2.3.4'   => true,
            'a:b:c:d:e:f:1.256.3.4' => false,
            'a:b:c:d:e:f::1.2.3.4'  => false,

            'a:b:c:d:e:f:0:1:2' => false,
            'a:b:c:d:e:f:0:1'   => true,
            'a::b:c:d:e:f:0:1'  => false,
            'a::c:d:e:f:0:1'    => true,
            'a::d:e:f:0:1'      => true,
            'a::e:f:0:1'        => true,
            'a::f:0:1'          => true,
            'a::0:1'            => true,
            'a::1'              => true,
            'a::'               => true,

            '::0:1:a:b:c:d:e:f' => false,

            /*
             * KLUDGE: This notation incorrectly handled in PHP extension Filter
             * '::0:a:b:c:d:e:f'   => false,
             */
            '::a:b:c:d:e:f'     => true,
            '::b:c:d:e:f'       => true,
            '::c:d:e:f'         => true,
            '::d:e:f'           => true,
            '::e:f'             => true,
            '::f'               => true,

            '0:1:a:b:c:d:e:f::' => false,
            /*
             * KLUDGE: This notation incorrectly handled in PHP extension Filter
             * '0:a:b:c:d:e:f::'   => false,
             */
            'a:b:c:d:e:f::'     => true,
            'b:c:d:e:f::'       => true,
            'c:d:e:f::'         => true,
            'd:e:f::'           => true,
            'e:f::'             => true,
            'f::'               => true,

            'a:b:::e:f' => false,
            '::a:'      => false,
            '::a::'     => false,
            ':a::b'     => false,
            'a::b:'     => false,
            '::a:b::c'  => false,
            'abcde::f'  => false,

            /*
             * KLUDGE: This notation is incorrect IPv6 but valid IPv4.
             * The search pattern is not smart enough right now to make the difference.
             * '10.0.0.1'                     => false,
             */
            ':10.0.0.1'                    => false,
            '0:0:0:255.255.255.255'        => false,
            '1fff::a88:85a3::172.31.128.1' => false,

            'a:b:c:d:e:f:0::1' => false,
            /*
             * KLUDGE: This notation incorrectly handled in PHP extension Filter
             * 'a:b:c:d:e:f:0::'  => false,
             */
            'a:b:c:d:e:f::0'   => true,
            'a:b:c:d:e:f::'    => true,

            // Garbage
			''      => false,
            '42'    => false,
        	'dummy' => false,
        );

        foreach ($data as $ip => $isValid) {
            $this->assertIpIsValid($ip, $isValid, "IP: $ip");
        }
    }

    /*
     * Bugs
     */
}