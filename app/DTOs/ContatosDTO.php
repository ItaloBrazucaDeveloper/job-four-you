<?php
namespace App\DTOs;

class ContatosDTO {
    public function __construct(
        public readonly ?string $contato_email = null,
        public readonly ?string $contato_celular = null,
        public readonly ?string $contato_facebook = null,
        public readonly ?string $contato_instagram = null,
        public readonly ?string $contato_whatsapp = null,
        public readonly ?string $contato_outro = null
    ) {}
} 