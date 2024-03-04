<?php

namespace App\Repositories\Eloquent;

use App\Entity\DocumentEntity;
use App\Models\Document;
use App\Models\User;
use Exception;

class DocumentRepository
{
    /**
     * @param User $user
     * @param array $data
     * @return array
     */
    public function setDocument(User $user, array $data): array
    {
        try {
            $document = (new DocumentEntity($user, $data))->toArray();
            $document = Document::query()->updateOrCreate(['path' => $document['path']], $document);
            return [
                'success'  => true,
                'document' => $document,
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'error'   => $exception->getMessage(),
            ];
        }
    }
}
