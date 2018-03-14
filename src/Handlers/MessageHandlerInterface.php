<?php

namespace Gukasov\Handlers;

interface MessageHandlerInterface
{
    /**
     * Handle the given message.
     *
     * @return string
     */
    public function handle(string $message): string;
}