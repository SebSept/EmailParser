<?php

namespace MS\Email\Parser\Test;

use MS\Email\Parser\Part;

/**
 * @author msmith
 * @author sebastien monterisi <sebastienmonterisi@yahoo.fr>
 */
class PartTest extends TestCase
{
    public function testGetDecodedContent()
    {
        $p = new Part('This is a test', '', 'image/jpg', '');
        $this->assertEquals('This is a test', $p->getDecodedContent());

        $p = new Part(base64_encode('This is a test'), 'base64', 'image/jpg', '');
        $this->assertEquals('This is a test', $p->getDecodedContent());

        // test content has a '=' which will be modified with quoted_printable_encode()/quoted_printable_decode()
        $test_content = 'This is a test = with modified char ;)';
        $p = new Part(quoted_printable_encode($test_content), 'quoted-printable', 'image/jpg', '');
        $this->assertEquals($test_content, $p->getDecodedContent());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetDecodedContentException()
    {
        $p = new Part('This is a test', 'not an encoding', 'image/jpg', '');
        $p->getDecodedContent();
    }

    public function testJsonSerializable()
    {
        $part = new Part(quoted_printable_encode('This is a test 5=4+1'), 'quoted-printable', 'image/jpg', 'inline');
        $this->assertEquals('{"type":"image\/jpg","disposition":"inline","encoding":"quoted-printable","content":"This is a test 5=4+1"}', json_encode($part));
    }
}
