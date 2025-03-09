<?php

declare(strict_types=1);

namespace Tempest\View\Attributes;

use Tempest\View\Attribute;
use Tempest\View\Element;
use Tempest\View\Elements\PhpForeachElement;

final readonly class ForeachAttribute implements Attribute
{
    #[\Override]
    public function apply(Element $element): Element
    {
        return new PhpForeachElement($element);
    }
}
