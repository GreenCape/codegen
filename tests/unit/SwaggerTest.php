<?php

use GreenCape\CodeGen\Swagger;

class SwaggerTest extends \PHPUnit\Framework\TestCase
{
    public function testSwaggerGenerator()
    {
        $swagger = new Swagger();

        $swagger->generate('-i /local/tests/tmp/swagger.io/swagger.yml -l html2 -o /local/tests/tmp/swagger.io/api-doc');

        $this->assertFileExists('tests/tmp/swagger.io/api-doc/index.html');
    }
}
