<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\datastar\models;

use Craft;
use craft\base\Model;
use craft\helpers\Json;
use putyourlightson\datastar\Datastar;

class ConfigModel extends Model
{
    public ?int $siteId = null;
    public string $template = '';
    public array $variables = [];
    public bool $includeCsrfToken = false;
    public ?string $csrfToken = null;

    protected function defineRules(): array
    {
        return [
            [['siteId', 'template'], 'required'],
            [['siteId'], 'integer'],
            [['template'], 'string'],
            [['variables'], 'validateVariables'],
            [['includeCsrfToken'], 'boolean'],
        ];
    }

    /**
     * Validates that none of the variables are objects, recursively.
     *
     * @used-by defineRules()
     */
    public function validateVariables(mixed $attribute): bool
    {
        $signalsVariableName = Datastar::getInstance()->settings->signalsVariableName;

        foreach ($this->variables as $key => $value) {
            if ($key === $signalsVariableName) {
                $this->addError($attribute, 'Variable `' . $signalsVariableName . '` is reserved. Use a different name or modify the name of the signals variable using the `signalsVariableName` config setting.');

                return false;
            }

            if (is_object($value) || (is_array($value) && !$this->validateVariables($value))) {
                $this->addError($attribute, 'Variable `' . $key . '` is an object, which is a forbidden variable type in the context of a Datastar request.');

                return false;
            }
        }

        return true;
    }

    /**
     * Returns a hashed, JSON-encoded array of attributes.
     */
    public function getHashed(): string
    {
        if ($this->includeCsrfToken) {
            $this->csrfToken = Craft::$app->getRequest()->csrfToken;
        }

        $attributes = array_filter([
            'siteId' => $this->siteId,
            'template' => $this->template,
            'variables' => $this->variables,
            'csrfToken' => $this->csrfToken,
        ]);
        $encoded = Json::encode($attributes);

        return Craft::$app->getSecurity()->hashData($encoded);
    }
}
