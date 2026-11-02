<?php

namespace App\OpenApi;

use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Parameter;
use Dedoc\Scramble\Support\Generator\RequestBodyObject;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Server;
use Dedoc\Scramble\Support\Generator\Types\ArrayType;
use Dedoc\Scramble\Support\Generator\Types\BooleanType;
use Dedoc\Scramble\Support\Generator\Types\ObjectType;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Support\RouteInfo;

class AuthOperationTransformer
{
    public const PATHS = ['login', 'logout', 'sanctum/csrf-cookie'];

    public function __invoke(Operation $operation, RouteInfo $routeInfo): void
    {
        $path = $routeInfo->route->uri();

        if (!in_array($path, self::PATHS, true)) {
            return;
        }

        // 業務APIの /api ベースを維持し、認証ルートだけルートURLを使う。
        $operation->servers([Server::make(url('/'))]);
        $operation->setTags(['Authentication']);
        $operation->security = [];
        $operation->description('Accept: application/json を指定し、セッションCookieを保持してください。');

        if ($path === 'sanctum/csrf-cookie') {
            $operation->summary('CSRF Cookieを取得する');
            $operation->responses = [Response::make(204)->setDescription(
                'CSRF CookieとセッションCookieを発行します。',
            )];

            return;
        }

        $csrf = Parameter::make('X-XSRF-TOKEN', 'header');
        $csrf->required = true;
        $csrf->description = 'XSRF-TOKEN CookieをURLデコードした値。先に /sanctum/csrf-cookie を取得してください。';
        $csrf->schema = Schema::fromType(new StringType());
        $operation->addParameters([$csrf]);

        if ($path === 'logout') {
            $operation->summary('ログアウト');
            $operation->responses = [
                Response::make(204)->setDescription('セッションを無効化します。本文はありません。'),
                Response::make(401)->setDescription('未認証です。'),
                Response::make(419)->setDescription('CSRFトークンが無効、またはセッションが期限切れです。'),
            ];

            return;
        }

        $operation->summary('ログイン');
        $operation->addRequestBodyObject(
            RequestBodyObject::make()
                ->required()
                ->setContent(
                    'application/json',
                    Schema::fromType(
                        new ObjectType()
                            ->addProperty('email', new StringType())
                            ->addProperty('password', new StringType()->format('password'))
                            ->addProperty('remember', new BooleanType())
                            ->setRequired(['email', 'password']),
                    ),
                ),
        );
        $operation->responses = [
            Response::make(200)
                ->setDescription('ログイン成功。ユーザー情報は /api/me で取得してください。')
                ->setContent(
                    'application/json',
                    Schema::fromType(
                        new ObjectType()
                            ->addProperty('two_factor', new BooleanType())
                            ->setRequired(['two_factor']),
                    ),
                ),
            Response::make(422)
                ->setDescription('入力が不正、または認証情報が一致しません。')
                ->setContent(
                    'application/json',
                    Schema::fromType(
                        new ObjectType()
                            ->addProperty('message', new StringType())
                            ->addProperty(
                                'errors',
                                new ObjectType()->additionalProperties(new ArrayType()->setItems(new StringType())),
                            )
                            ->setRequired(['message', 'errors']),
                    ),
                ),
            Response::make(419)->setDescription('CSRFトークンが無効、またはセッションが期限切れです。'),
            Response::make(429)->setDescription('ログイン試行回数の上限を超えました。'),
        ];
    }
}