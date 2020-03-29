<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
        $router->addRoute('filmy', 'Movie:list');
        $router->addRoute('pridat-film', 'Movie:add');
        $router->addRoute('uzivatele', 'User:list');
        $router->addRoute('prihlaseni', 'Sign:in');
        $router->addRoute('registrace', 'Sign:up');
        $router->addRoute('odhlaseni', 'Sign:up');
        $router->addRoute('film/<slug>/ohodnotit', 'Movie:comment');
        $router->addRoute('film/<slug>', 'Movie:detail');
		$router->addRoute('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}
}
