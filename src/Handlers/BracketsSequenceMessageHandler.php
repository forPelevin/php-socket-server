<?php

namespace Gukasov\Handlers;

use Gukasov\BracketsChecker\BracketsChecker;

class BracketsSequenceMessageHandler implements MessageHandlerInterface
{
    /**
     * @var BracketsChecker
     */
    protected $bracketsChecker;

    /**
     * BracketsSequenceMessageHandler constructor.
     */
    public function __construct()
    {
        $this->bracketsChecker = new BracketsChecker();
    }

    /**
     * @inheritdoc
     */
    public function handle(string $message): string
    {
        try {
            if ($this->bracketsChecker->isCorrectSequence($message)) {
                return "The string contains correct bracket sequence\n";
            }

            return "The brackets sequence is broken\n";
        } catch (\Exception $e) {
            return "The string contains invalid characters\n";
        }
    }
}