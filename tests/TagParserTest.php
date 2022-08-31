<?php

namespace Tests;

use App\TagParser;
use PHPUnit\Framework\TestCase;

class TagParserTest extends TestCase
{
    protected TagParser $parser;
    protected function setUp(): void
    {
        $this->parser = new TagParser();
    }
    public function test_it_parses_single_tag()
    {

        $result = $this->parser->parse("React");
        $expected = ["React"];
        $this->assertSame($expected, $result);
    }
    public function test_it_parses_list_pipe_seperated()
    {

        $result = $this->parser->parse("React|Node|Vue");
        $expected = ["React", "Node", "Vue"];
        $this->assertSame($expected, $result);
    }
}
