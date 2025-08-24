<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Messaging;

use AppUtils\Interfaces\StringableInterface;

class Messages
{
    public const string TYPE_SUCCESS = 'success';
    public const string TYPE_INFO = 'info';
    public const string TYPE_WARNING = 'warning';
    public const string TYPE_ERROR = 'error';

    private const string SESSION_VAR_NAME = 'ui_messages';

    /**
     * @var array<int,Message>
     */
    private array $messages;

    public function __construct()
    {
        foreach($_SESSION[self::SESSION_VAR_NAME] ?? array() as $msg)
        {
            $this->addMessage(
                $msg['message'],
                $msg['type'],
                $msg['code']
            );
        }

        unset($_SESSION[self::SESSION_VAR_NAME]);
    }

    public function writeToSession() : void
    {
        foreach($this->messages as $msg)
        {
            $_SESSION[self::SESSION_VAR_NAME][] = array(
                'message' => $msg->getMessage(),
                'type' => $msg->getType(),
                'code' => $msg->getCode()
            );
        }

        // In case the method is called multiple times,
        // we don't want to add duplicate messages.
        $this->messages = array();
    }

    public function addSuccess(string|StringableInterface|null $message, ?int $code=null) : self
    {
        return $this->addMessage($message, self::TYPE_SUCCESS, $code);
    }

    public function addInfo(string|StringableInterface|null $message, ?int $code=null) : self
    {
        return $this->addMessage($message, self::TYPE_INFO, $code);
    }

    public function addWarning(string|StringableInterface|null $message, int $code) : self
    {
        return $this->addMessage($message, self::TYPE_WARNING, $code);
    }

    public function addError(string|StringableInterface|null $message, int $code) : self
    {
        return $this->addMessage($message, self::TYPE_ERROR, $code);
    }

    public function addMessage(string|StringableInterface|null $message, string $type, ?int $code=null) : self
    {
        $this->messages[] = new Message($type, $message, $code);

        return $this;
    }

    /**
     * @return array<int,Message>
     */
    public function getMessages() : array
    {
        return $this->messages;
    }

    public function hasMessages() : bool
    {
        return !empty($this->messages);
    }

    public function clear() : self
    {
        $this->messages = array();
        return $this;
    }
}
