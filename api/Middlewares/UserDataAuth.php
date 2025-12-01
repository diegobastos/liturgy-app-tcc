<?php

namespace Middlewares;

use stdClass;

final class UserDataAuth {
    
    protected $sub;
    protected string $name;
    protected string $username;
    protected string $email;

    public function __construct(stdClass $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getId(): ?int { return $this->sub; }
    public function getName(): string { return $this->name; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }    
}