<?php

namespace App;

use App\Enums\UserPersonTypes;

class PendingUser
{
    private string $name;

    private string $email;

    private string $password;

    private UserPersonTypes $type;

    private string $documentNumber;

    protected function __construct(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $method = 'set'.str_replace('_', '', $attribute);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    /**
     * @return static
     */
    public static function createWithAttributes(array $attributes)
    {
        return new static($attributes);
    }

    public function setDocumentNumber(string $documentNumber): self
    {
        $this->documentNumber = $documentNumber;
        $this->inferTypeOfPersonFromDocument($documentNumber);

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = bcrypt($password);

        return $this;
    }

    protected function inferTypeOfPersonFromDocument(string $number): void
    {
        if (14 === strlen($number)) {
            $this->setType(UserPersonTypes::LEGAL_PERSON());
        }

        $this->setType(UserPersonTypes::PHYSICAL_PERSON());
    }

    protected function setType(UserPersonTypes $type): self
    {
        $this->type = $type;

        return $this;
    }
}
