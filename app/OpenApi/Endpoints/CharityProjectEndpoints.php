<?php

declare(strict_types=1);

namespace App\OpenApi\Endpoints;

use OpenApi\Attributes as OA;

final class CharityProjectEndpoints
{
    #[OA\Get(
        path: '/v1/projects',
        operationId: 'getCharityProjects',
        description: 'Получить список всех благотворительных проектов с возможностью фильтрации и сортировки',
        summary: 'Список проектов',
        tags: ['Charity Projects'],
        parameters: [
            new OA\QueryParameter(
                name: 'status',
                description: 'Статус проекта (active, closed)',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['active', 'closed'])
            ),
            new OA\QueryParameter(
                name: 'launch_date_from',
                description: 'Дата начала фильтрации (Y-m-d)',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\QueryParameter(
                name: 'launch_date_to',
                description: 'Дата окончания фильтрации (Y-m-d)',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\QueryParameter(
                name: 'sort_by',
                description: 'Параметр сортировки',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['sort_order', 'launch_date', 'donation_amount'])
            ),
            new OA\QueryParameter(
                name: 'order',
                description: 'Направление сортировки',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])
            ),
            new OA\QueryParameter(
                name: 'per_page',
                description: 'Количество элементов на странице (макс. 10)',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 3)
            ),
            new OA\QueryParameter(
                name: 'page',
                description: 'Номер страницы',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешно получен список проектов',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Помощь детям'),
                            new OA\Property(property: 'slug', type: 'string', example: 'help-children'),
                            new OA\Property(property: 'short_description', type: 'string', example: 'Программа поддержки нуждающихся детей'),
                            new OA\Property(
                                property: 'status',
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'value', type: 'string', example: 'active'),
                                    new OA\Property(property: 'label', type: 'string', example: 'Активный'),
                                ]
                            ),
                            new OA\Property(property: 'launch_date', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
                            new OA\Property(
                                property: 'donation_amount',
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'amount', type: 'integer', example: 50000),
                                    new OA\Property(property: 'currency', type: 'string', example: 'RUB'),
                                ]
                            ),
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации параметров'
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Get(
        path: '/v1/projects/{slug}',
        operationId: 'getCharityProjectBySlug',
        description: 'Получить полную информацию о конкретном благотворительном проекте',
        summary: 'Получить проект по slug',
        tags: ['Charity Projects'],
        parameters: [
            new OA\PathParameter(
                name: 'slug',
                description: 'Уникальный идентификатор проекта',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'help-children')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешно получена информация о проекте',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Помощь детям'),
                        new OA\Property(property: 'slug', type: 'string', example: 'help-children'),
                        new OA\Property(property: 'short_description', type: 'string', example: 'Программа поддержки нуждающихся детей'),
                        new OA\Property(
                            property: 'status',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'value', type: 'string', example: 'active'),
                                new OA\Property(property: 'label', type: 'string', example: 'Активный'),
                            ]
                        ),
                        new OA\Property(property: 'launch_date', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
                        new OA\Property(property: 'additional_description', type: 'string', example: 'Подробное описание проекта...'),
                        new OA\Property(
                            property: 'donation_amount',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'amount', type: 'integer', example: 50000),
                                new OA\Property(property: 'currency', type: 'string', example: 'RUB'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Проект не найден'
            ),
        ]
    )]
    public function show(): void {}
}
