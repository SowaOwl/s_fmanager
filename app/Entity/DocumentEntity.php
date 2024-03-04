<?php

namespace App\Entity;

use App\Models\User;

class DocumentEntity
{
    public $user_id;
    public $name;
    public $email;
    public $format;
    public $extension;
    public $size;
    public $path;
    public $full_url;

    public function __construct(User $user, array $fileData)
    {
        $this->user_id   = $user->id ?? null;
        $this->name      = $user->name ?? null;
        $this->email     = $user->email ?? null;
        $this->format    = $fileData['resource_type'] ?? null;
        $this->extension = $fileData['format'] ?? null;
        $this->size      = $fileData['bytes'] ?? null;
        $this->path      = $this->generatePath($fileData);
        $this->full_url  = $fileData['url'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'user_id'   => $this->user_id,
            'name'      => $this->name,
            'email'     => $this->email,
            'format'    => $this->format,
            'extension' => $this->extension,
            'size'      => $this->size,
            'path'      => $this->path,
            'full_url'  => $this->full_url,
        ];
    }

    /**
     * @param array $fileData
     * @return string
     */
    private function generatePath(array $fileData): string
    {
        return $fileData['resource_type']
            . '/' . $fileData['type']
            . '/v' . $fileData['version']
            . '/' . $fileData['public_id']
            . '.' . $fileData['format'];
    }
}
