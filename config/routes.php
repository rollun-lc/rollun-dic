<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

/*$app->get('/', App\Action\HomePageAction::class, 'home');*/

if($container->has('home-service')){
    $app->route('/','home-service' ,['GET'],'home-page');
}
if($container->has('api-datastore')){
    $app->route('/api/datastore[/{resourceName}[/{id}]]','api-datastore',['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],'api-datastore');
}
if($container->has('webhookActionRender')){
    $app->route('/webhook[/{resourceName}]','webhookActionRender',['GET', 'POST'],'webhook');
}
if($container->has('loginPageAR')){
    $app->route('/login','loginPageAR' ,['GET','POST'],'login-page');
}
if($container->has('loginServiceAR')){
    $app->route('/login/{resourceName}','loginServiceAR' ,['GET','POST'],'login-service');
}
if($container->has('loginPrepareServiceAR')){
    $app->route('/login_prepare/{resourceName}','loginPrepareServiceAR' ,['GET','POST'],'login-prepare-service');
}
if($container->has('logoutAR')){
    $app->route('/logout','logoutAR' ,['GET','POST'],'logout');
}
if($container->has('user-page')){
    $app->route('/user','user-page' ,['GET','POST'],'user-page');
}