<?php

namespace spec\Moon\Utilities\JavaScript;

use PhpSpec\ObjectBehavior;
use Laracasts\Utilities\JavaScript\ViewBinder;

class PHPToJavaScriptTransformerSpec extends ObjectBehavior
{
    // function let(ViewBinder $viewBinder)
    // {
    //     $this->beConstructedWith($viewBinder);
    // }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Moon\Utilities\JavaScript\PHPToJavaScriptTransformer');
    }

    public function it_nests_all_vars_under_namespace()
    {
        // defaulting to window
        $this->transform([])
            ->shouldMatch('/window.window = window.window || {};/');
    }

    public function it_transforms_php_strings()
    {
        $this->transform(['foo' => 'bar'])
             ->shouldMatch("/window.foo = 'bar';/");
    }

    public function it_transforms_php_arrays()
    {
        $this->transform(['letters' => ['a', 'b']])
             ->shouldMatch('/window.letters = \["a","b"\];/');
    }

    public function it_transforms_php_booleans()
    {
        $this->transform(['isFoo' => false])
            ->shouldMatch('/window.isFoo = false;/');
    }

    public function it_transforms_numerics()
    {
        $this->transform(['age' => 10, 'sum' => 10.12, 'dec' => 0])
            ->shouldMatch('/window.age = 10;window.sum = 10.12;window.dec = 0;/');
    }

    public function it_transforms_null_values()
    {
        $this->transform(['age' => null, 'sum' => null])
            ->shouldMatch('/window.age = null;window.sum = null;/');
    }

    public function it_throws_an_exception_if_an_object_cant_be_transformed(\StdClass $obj)
    {
        $this->shouldThrow('Exception')
            ->duringTransform(['foo' => $obj]);
    }
}
