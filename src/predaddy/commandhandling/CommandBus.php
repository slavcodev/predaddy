<?php
/*
 * Copyright (c) 2013 Janos Szurovecz
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace predaddy\commandhandling;

use precore\util\Preconditions;
use predaddy\messagehandling\SimpleMessageBus;

/**
 * A typical command bus has the following behaviours:
 *  - all command handler methods are wrapped by a unique transaction
 *  - the type of the message must be exactly the same as the parameter in the handler method
 *
 * Only one command handler can process a particular command, otherwise a runtime exception will be thrown.
 *
 * @author Janos Szurovecz <szjani@szjani.hu>
 */
class CommandBus extends SimpleMessageBus
{
    /**
     * @param CommandBusBuilder $builder
     */
    public function __construct(CommandBusBuilder $builder = null)
    {
        if ($builder === null) {
            $builder = self::builder();
        }
        parent::__construct($builder);
    }

    /**
     * @return CommandBusBuilder
     */
    public static function builder()
    {
        return new CommandBusBuilder();
    }

    protected function callableWrappersFor($message)
    {
        $wrappers = parent::callableWrappersFor($message);
        Preconditions::checkState(
            count($wrappers) <= 1,
            "More than one command handler is registered for message '%s'",
            get_class($message)
        );
        return $wrappers;
    }
}
