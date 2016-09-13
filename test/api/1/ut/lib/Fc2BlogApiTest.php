<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\ApplicationException;
use EXAM0098\Lib\Fc2BlogApi;
use EXAM0098\Testlib\BaseTestCase;
use EXAM0098\Testlib\TestHelper;

class Fc2BlogApiTest extends BaseTestCase
{
    private $testee = null;

    protected function setUp()
    {
        parent::setUp();
        $this->testee = new Fc2BlogApi();
    }

    public function testGetBlogEntities()
    {
        $self = $this;

        // empty
        $itShouldBeEmpty = function () use ($self) {
            // mock
            $mockFc2BlogRdfParser = \Phake::mock('EXAM0098\Lib\Fc2BlogRdfParser');
            // set mock to testee
            TestHelper::setProperty($self->testee, "parser", $mockFc2BlogRdfParser);

            // stub
            \Phake::when($mockFc2BlogRdfParser)->parseFromUrl()->thenReturn(array());

            // verify
            assertThat($self->testee->getBlogEntities(), is(array()));
            \Phake::verify($mockFc2BlogRdfParser)->parseFromUrl();
        };

        // exception
        $itShouldThrowApplicationException = function () use ($self) {
            // mock
            $mockFc2BlogRdfParser = \Phake::mock('EXAM0098\Lib\Fc2BlogRdfParser');
            // set mock to testee
            TestHelper::setProperty($self->testee, "parser", $mockFc2BlogRdfParser);

            // stub
            $expected = new ApplicationException("test");
            \Phake::when($mockFc2BlogRdfParser)->parseFromUrl()->thenThrow($expected);

            // verify
            try {
                assertThat($self->testee->getBlogEntities(), is(array()));
            } catch (ApplicationException $e) {
                \Phake::verify($mockFc2BlogRdfParser)->parseFromUrl();
                assertThat($e, is($expected));
            }
        };

        // normal
        $itShouldGetNotEmptyData = function () use ($self) {
            // mock
            $mockFc2BlogRdfParser = \Phake::mock('EXAM0098\Lib\Fc2BlogRdfParser');
            // stub
            \Phake::when($mockFc2BlogRdfParser)->parseFromUrl()->thenReturn(array(
                array("test" => "test"),
                array("link" => "http://xxx.xxx"),
                array("title" => "title"),
                array("description" => "desc"),
                array("dc:date" => "2000-01-01 00:00:00"),
                array("link" => "http://exam0098.blog.fc2.com/blog-entry-1.html", "title" => "title", "description" => "desc", "dc:date" => "2000-01-01 00:00:00", "test" => "test"),
                array("link" => "http://xxx.xxx", "title" => "title", "description" => "desc", "dc:date" => "2000-01-01 00:00:00", "test" => "test")
            ));
            // set mock to testee
            TestHelper::setProperty($self->testee, "parser", $mockFc2BlogRdfParser);

            // verify
            $entities = $self->testee->getBlogEntities();
            assertThat(count($entities), is(1));
            foreach ($entities as $entity) {
                assertThat($entity->getLink(), is("http://exam0098.blog.fc2.com/blog-entry-1.html"));
                assertThat($entity->getTitle(), is("title"));
                assertThat($entity->getUserName(), is("exam0098"));
                assertThat($entity->getServerNo(), is(0));
                assertThat($entity->getEntryNo(), is(1));
                assertThat($entity->getDescription(), is("desc"));
                assertThat($entity->getPostedAt(), is("2000-01-01 00:00:00"));
            }
            \Phake::verify($mockFc2BlogRdfParser)->parseFromUrl();
        };

        $itShouldBeEmpty();
        $itShouldThrowApplicationException();
        $itShouldGetNotEmptyData();
    }

}
