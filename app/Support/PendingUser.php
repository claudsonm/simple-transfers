<?php

namespace App\Support;

use App\Models\User;
use App\Enums\UserPersonTypes;
use App\Actions\CreateUserAction;

class PendingUser
{
    private string $name;

    private string $email;

    private string $password;

    private UserPersonTypes $type;

    private string $documentNumber;

    public function __construct(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $method = 'set'.str_replace('_', '', $attribute);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
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

    public function setDocumentNumber(string $documentNumber): self
    {
        $this->documentNumber = $documentNumber;
        $this->inferTypeOfPersonFromDocument($documentNumber);

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

    public function getAttributes(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'person_type' => $this->type->getValue(),
            'document_number' => $this->documentNumber,
        ];
    }

    public function save(): User
    {
        return resolve(CreateUserAction::class)->execute($this);
    }
}
