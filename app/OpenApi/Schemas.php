<?php

declare(strict_types=1);

namespace App\OpenApi;

use OpenApi\Attributes as OA;

/**
 * Переиспользуемые схемы для OpenAPI документации
 */
final class Schemas {}

#[OA\Schema(schema: 'ProjectStatus', properties: [
    new OA\Property(property: 'value', type: 'string', enum: ['draft', 'active', 'closed'], example: 'active'),
    new OA\Property(property: 'label', type: 'string', example: 'Активный'),
], type: 'object')]
final class ProjectStatusSchema {}

#[OA\Schema(schema: 'Money', properties: [
    new OA\Property(property: 'amount', description: 'Сумма в копейках', type: 'integer', example: 100050),
    new OA\Property(property: 'currency', description: 'Код валюты (ISO 4217)', type: 'string', example: 'RUB'),
], type: 'object')]
final class MoneySchema {}

#[OA\Schema(schema: 'CharityProject', title: 'Благотворительный проект (краткая информация)', properties: [
    new OA\Property(property: 'id', type: 'integer', example: 1),
    new OA\Property(property: 'name', type: 'string', example: 'Помощь детям'),
    new OA\Property(property: 'slug', type: 'string', example: 'help-children'),
    new OA\Property(property: 'short_description', type: 'string', example: 'Программа поддержки нуждающихся детей'),
    new OA\Property(
        property: 'status',
        ref: '#/components/schemas/ProjectStatus',
    ),
    new OA\Property(property: 'launch_date', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
    new OA\Property(
        property: 'donation_amount',
        ref: '#/components/schemas/Money',
    ),
], type: 'object')]
final class CharityProjectSchema {}

#[OA\Schema(schema: 'CharityProjectDetail', title: 'Благотворительный проект (полная информация)', type: 'object', allOf: [
    new OA\Schema(ref: '#/components/schemas/CharityProject'),
    new OA\Schema(
        properties: [
            new OA\Property(
                property: 'additional_description',
                type: 'string',
                example: 'Подробное описание проекта и его целей...'
            ),
        ],
        type: 'object'
    ),
])]
final class CharityProjectDetailSchema {}

#[OA\Schema(schema: 'Donation', title: 'Пожертвование', properties: [
    new OA\Property(property: 'id', type: 'integer', example: 1),
    new OA\Property(property: 'charity_project_id', type: 'integer', example: 1),
    new OA\Property(property: 'donation_date', type: 'string', format: 'date-time', example: '2024-12-20 14:30:00'),
    new OA\Property(
        property: 'amount',
        ref: '#/components/schemas/Money',
    ),
    new OA\Property(property: 'comment', type: 'string', example: 'Помогу всем, чем смогу!', nullable: true),
], type: 'object')]
final class DonationSchema {}

#[OA\Schema(schema: 'ValidationError', title: 'Ошибка валидации', properties: [
    new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
    new OA\Property(
        property: 'errors',
        type: 'object',
        example: ['field_name' => ['Error message 1', 'Error message 2']],
        additionalProperties: new OA\AdditionalProperties(
            type: 'array',
            items: new OA\Items(type: 'string')
        )
    ),
], type: 'object')]
final class ValidationErrorSchema {}
