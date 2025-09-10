<?php

declare(strict_types=1);

namespace ManyData\Folio\Application;

use ManyData\Folio\Domain\Folio;
use ManyData\Folio\Domain\FolioRepository;
use Illuminate\Support\Str;

final class CreateFolioUseCase
{
    public function __construct(
        private readonly FolioRepository $repository
    ) {}

    public function execute(
        string $name,
        float $amount,
        string $folioNumber,
        string $fechaTransaccion,
        ?string $extraField1,
        ?string $extraField2
    ): void {

        $folioEntity = Folio::create(
            id: (string) Str::uuid(),
            name: $name,
            amount: $amount,
            folio: $folioNumber,
            fechaTransaccion: $fechaTransaccion,
            extraField1: $extraField1,
            extraField2: $extraField2
        );

        $this->repository->save($folioEntity);
    }
}
