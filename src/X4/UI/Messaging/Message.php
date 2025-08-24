<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Messaging;

use AppUtils\Interfaces\StringableInterface;
use AppUtils\OutputBuffering;
use function AppLocalize\pt;

class Message
{
    private string $type;
    private string $message;
    private ?int $code;

    public function __construct(string $type, string|StringableInterface|null $message, ?int $code)
    {
        $this->type = $type;
        $this->message = (string)$message;
        $this->code = $code;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getCode() : ?int
    {
        return $this->code;
    }

    public function render() : string
    {
        OutputBuffering::start();

        ?>
        <div class="alert alert-<?php echo $this->getType() ?>" role="alert">
            <?php echo $this->getMessage() ?>

            <?php if($this->code !== null) { ?>
            <br>
            <small class="text-secondary"><?php pt('Code: %1$s', $this->getCode()); ?></small>
            <?php } ?>
        </div>
        <?php

        return OutputBuffering::get();
    }
}
