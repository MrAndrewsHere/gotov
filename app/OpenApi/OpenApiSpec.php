<?php

declare(strict_types=1);

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'REST API для управления благотворительными проектами и пожертвованиями. API предоставляет возможность получить список проектов, детальную информацию о конкретном проекте и создание пожертвований.',
    title: 'Charity Project API',
    contact: new OA\Contact(
        name: 'API Support',
        email: 'support@charity.ru'
    ),
    license: new OA\License(
        name: 'MIT',
        identifier: 'MIT'
    )
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Development Server'
)]
#[OA\Server(
    url: 'https://api.charity.ru',
    description: 'Production Server'
)]
#[OA\Tag(
    name: 'Charity Projects',
    description: 'API для работы с благотворительными проектами'
)]
#[OA\Tag(
    name: 'Donations',
    description: 'API для работы с пожертвованиями'
)]
#[OA\ExternalDocumentation(
    description: 'Документация проекта',
    url: 'https://github.com/MrAndrewsHere/gotov'
)]
final class OpenApiSpec {}
