<?php

namespace EXAM0098\Test\Lib;

use EXAM0098\Lib\ApplicationException;
use EXAM0098\Lib\BaseVo;
use EXAM0098\Lib\DbDaoTrait;
use EXAM0098\Testlib\BaseTestCase;

class DbDaoTest extends BaseTestCase
{
    use DbDaoTrait, DbDaoTraitOverride {
        DbDaoTraitOverride::getDbConnectionManager insteadof DbDaoTrait;
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->mockDbConnectionManager);
    }


    public function testGetConnection()
    {
        $mockPDO = \Phake::mock('\PDO');
        \Phake::when($this->getDbConnectionManagerMock())->connect(\Phake::anyParameters())->thenReturn($mockPDO);

        assertThat($this->getConnection(), $mockPDO);
        \Phake::verify($this->getDbConnectionManagerMock())->connect(\Phake::anyParameters());
    }

    public function testExecute()
    {
        $self = $this;

        // normal
        $itShouldGetExecutedRowCount = function () use ($self) {
            unset($this->mockDbConnectionManager);
            $mockPDO = \Phake::mock('\PDO');
            $mockStmt = \Phake::mock('\PDOStatement');
            \Phake::when($mockStmt)->rowCount()->thenReturn(5);
            \Phake::when($mockPDO)->prepare(\Phake::anyParameters())->thenReturn($mockStmt);
            \Phake::when($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters())->thenReturn($mockPDO);

            assertThat($self->execute("sql", array(":id" => 1), array()), is(5));
            \Phake::verify($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters());
            \Phake::verify($mockPDO)->prepare(\Phake::anyParameters());
            \Phake::verify($mockStmt)->bindValue(\Phake::anyParameters());
            \Phake::verify($mockStmt)->execute();
            \Phake::verify($mockStmt)->rowCount();
        };

        // exception
        $itShouldThrowApplicationException = function () use ($self) {
            unset($this->mockDbConnectionManager);

            // stub
            $expected = new \PDOException("test");
            \Phake::when($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters())->thenThrow($expected);

            // verify
            try {
                $self->execute("sql", array(":id" => 1), array());
            } catch (ApplicationException $e) {
                \Phake::verify($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters());
            }
        };

        $itShouldGetExecutedRowCount();
        $itShouldThrowApplicationException();

    }

    public function testQuery()
    {
        $self = $this;

        // normal
        $itShouldGetQueryData = function () use ($self) {
            unset($this->mockDbConnectionManager);
            $mockPDO = \Phake::mock('\PDO');
            $mockStmt = \Phake::mock('\PDOStatement');
            \Phake::when($mockStmt)->execute()->thenReturn(true);
            \Phake::when($mockStmt)->fetch()->thenReturn(array());
            \Phake::when($mockStmt)->fetch()->thenReturn(false);
            \Phake::when($mockPDO)->prepare(\Phake::anyParameters())->thenReturn($mockStmt);
            \Phake::when($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters())->thenReturn($mockPDO);

            assertThat($self->query("query", array(), array()), is(array()));
            \Phake::verify($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters());
            \Phake::verify($mockPDO)->prepare(\Phake::anyParameters());
            \Phake::verify($mockStmt)->execute();
            \Phake::verify($mockStmt)->fetch();
        };

        // exception
        $itShouldThrowApplicationException = function () use ($self) {
            unset($this->mockDbConnectionManager);

            // stub
            $expected = new \PDOException("test");
            \Phake::when($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters())->thenThrow($expected);

            // verify
            try {
                $self->query("query", array(), array());
            } catch (ApplicationException $e) {
                \Phake::verify($self->getDbConnectionManagerMock())->connect(\Phake::anyParameters());
            }
        };

        $itShouldGetQueryData();
        $itShouldThrowApplicationException();
    }

    public function testCreateLimitClause()
    {
        // null
        assertThat($this->createLimitClause(), is(''));
        assertThat($this->createLimitClause(new SimpleBaseVo()), is(''));

        // data
        $vo = new SimpleBaseVo();
        $vo->setLimit(1);
        assertThat($this->createLimitClause($vo), is(' LIMIT 1 '));
        $vo->setOffset(5);
        assertThat($this->createLimitClause($vo), is(' LIMIT 5,1 '));
        $vo->setLimit(null);
        assertThat($this->createLimitClause($vo), is(''));
    }

    public function testCreateOrderByClause()
    {
        // null
        assertThat($this->createOrderByClause(), is(''));
        assertThat($this->createOrderByClause(new SimpleBaseVo()), is(''));

        // data
        $vo = new SimpleBaseVo();
        $vo->setOrder("order");
        assertThat($this->createOrderByClause($vo), is(' ORDER BY order '));
        $vo->setOrder("+order");
        assertThat($this->createOrderByClause($vo), is(' ORDER BY order ASC '));
        $vo->setOrder("-order");
        assertThat($this->createOrderByClause($vo), is(' ORDER BY order DESC '));
    }

}

trait DbDaoTraitOverride
{
    private function getDbConnectionManager()
    {
        return $this->getDbConnectionManagerMock();
    }

    private function getDbConnectionManagerMock()
    {
        if (isset($this->mockDbConnectionManager)) {
            return $this->mockDbConnectionManager;
        }
        $this->mockDbConnectionManager = \Phake::mock('EXAM0098\Lib\DbConnectionManager');
        return $this->mockDbConnectionManager;
    }

}

class SimpleBaseVo extends BaseVo
{
}
