<?php

namespace RM_PagSeguro\Tests;

use RM_PagSeguro\Helpers\Functions;
use PHPUnit\Framework\TestCase;

/**
 * Class FunctionsTest
 *
 * @author    Ricardo Martins
 * @copyright 2023 Magenteiro
 * @package   RM_PagSeguro\Tests
 * @covers \RM_PagSeguro\Helpers\Functions
 */
class FunctionsTest extends TestCase
{

    public function testFormat_date()
    {
        $this->assertEquals('15/07/2023 às 15:12:56 (Horário de Brasília)', Functions::format_date('2023-07-15T15:12:56.000-03:00'), 'Format date failed');
        $this->assertEquals('15/07/2023 às 03:12:56 (Horário de Brasília)', Functions::format_date('2023-07-15T03:12:56.000-03:00'), 'Format date failed when < 12:00');
        $this->assertEquals('', Functions::format_date(''), 'Format date failed when empry str');
        $this->assertEquals('', Functions::format_date(false), 'Format date failed when false');
        $this->assertEquals('', Functions::format_date(true), 'Format date failed when true');
    }
}
