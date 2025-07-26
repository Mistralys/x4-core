<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Ajax;

use AppUtils\Baskets\GenericStringPrimaryBasket;
use AppUtils\ConvertHelper\JSONConverter;
use Mistralys\X4\UI\UserInterface;

/**
 * @method AjaxMethodInterface getAll()
 * @method AjaxMethodInterface getByID(string $id)
 */
class AjaxMethods extends GenericStringPrimaryBasket
{
    public const REQUEST_PARAM_AJAX = 'ajax';
    private UserInterface $ui;

    public function __construct(UserInterface $ui)
    {
        $this->ui = $ui;

        parent::__construct();
    }

    public function getUI(): UserInterface
    {
        return $this->ui;
    }

    public function getAllowedItemClasses(): array
    {
        return array(
            AjaxMethodInterface::class
        );
    }

    public function sendError(string $message, int $code, array $payload=array()) : never
    {
        http_response_code(400);

        $this->sendJSON(false, $message, $code, $payload);
    }

    public function sendSuccess(string $message='', array $payload=array()) : never
    {
        $this->sendJSON(true, $message, 0, $payload);
    }

    protected function sendJSON(bool $success, string $message, int $code=0, array $payload=array()) : never
    {
        header('Content-Type: application/json');

        echo JSONConverter::var2json(array(
            'success' => $success,
            'message' => $message,
            'code' => $code,
            'payload' => $payload
        ));

        exit;
    }
}
