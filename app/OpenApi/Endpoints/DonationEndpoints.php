<?php

declare(strict_types=1);

namespace App\OpenApi\Endpoints;

use OpenApi\Attributes as OA;

final class DonationEndpoints
{
    #[OA\Post(
        path: '/v1/donations',
        operationId: 'createDonation',
        description: 'Создать новое пожертвование для благотворительного проекта',
        summary: 'Создать пожертвование',
        tags: ['Donations'],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Данные пожертвования',
            content: new OA\JsonContent(
                required: ['charity_project_id', 'amount'],
                properties: [
                    new OA\Property(
                        property: 'charity_project_id',
                        type: 'integer',
                        description: 'ID благотворительного проекта',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'amount',
                        type: 'number',
                        format: 'float',
                        description: 'Сумма пожертвования в рублях',
                        example: 1000.50
                    ),
                    new OA\Property(
                        property: 'donation_date',
                        type: 'string',
                        format: 'date',
                        description: 'Дата пожертвования (опционально, по умолчанию - сегодня)',
                        example: '2024-12-20'
                    ),
                    new OA\Property(
                        property: 'comment',
                        type: 'string',
                        description: 'Комментарий к пожертвованию (опционально, макс. 1000 символов)',
                        example: 'Помогу всем, чем смогу!'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Пожертвование успешно создано',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'charity_project_id', type: 'integer', example: 1),
                        new OA\Property(property: 'donation_date', type: 'string', format: 'date-time', example: '2024-12-20 14:30:00'),
                        new OA\Property(
                            property: 'amount',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'amount', type: 'integer', example: 100050),
                                new OA\Property(property: 'currency', type: 'string', example: 'RUB'),
                            ]
                        ),
                        new OA\Property(property: 'comment', type: 'string', example: 'Помогу всем, чем смогу!'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    type: 'object',
                    example: [
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'charity_project_id' => ['ID проекта обязателен'],
                            'amount' => ['Сумма пожертвования обязательна'],
                        ],
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Проект не найден'
            ),
        ]
    )]
    public function store(): void {}
}
