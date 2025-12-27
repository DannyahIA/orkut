<?php

use App\Core\Router;

require_once __DIR__ . '/../src/Core/bootstrap.php';

// Simple error reporting for dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize Router
$router = new Router();

// Define Routes (we might move this to a routes file later)
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@store');
$router->get('/logout', 'AuthController@logout');

// Profile
$router->get('/profile', 'ProfileController@show');
$router->get('/profile/edit', 'ProfileController@edit');
$router->post('/profile/update', 'ProfileController@update');

// Friends
$router->post('/friends/add', 'FriendController@add');
$router->get('/friends/requests', 'FriendController@requests');
$router->get('/friends', 'FriendController@index');
$router->get('/friends/network', 'FriendController@network');
$router->get('/friends/network-data', 'FriendController@networkJson');
$router->post('/friends/accept', 'FriendController@accept');
$router->post('/friends/reject', 'FriendController@reject');

// Communities
$router->get('/communities', 'CommunityController@index');
$router->get('/communities/create', 'CommunityController@create');
$router->post('/communities/store', 'CommunityController@store');
$router->get('/communities/show', 'CommunityController@show');
$router->get('/communities/join', 'CommunityController@join');
$router->get('/communities/leave', 'CommunityController@leave');
$router->get('/communities/edit', 'CommunityController@edit');
$router->post('/communities/update', 'CommunityController@update');
$router->post('/communities/delete', 'CommunityController@delete');

// Scraps
$router->get('/scraps', 'ScrapController@index');
$router->post('/scraps/store', 'ScrapController@store');
$router->post('/scraps/delete', 'ScrapController@delete');

// Videos
$router->get('/videos', 'VideoController@index');
$router->get('/videos/create', 'VideoController@create');
$router->post('/videos/store', 'VideoController@store');
$router->post('/videos/delete', 'VideoController@delete');
$router->get('/testimonials', 'TestimonialController@index');
$router->post('/testimonials/store', 'TestimonialController@store');
$router->post('/testimonials/approve', 'TestimonialController@approve');
$router->post('/testimonials/delete', 'TestimonialController@delete');
$router->get('/testimonials/pending', 'TestimonialController@pending');

// Stats
$router->post('/stats/vote', 'StatsController@vote');

// Topics
$router->get('/topics/create', 'TopicController@create');
$router->post('/topics/store', 'TopicController@store');
$router->get('/communities/topic', 'TopicController@show');
$router->post('/topics/reply', 'TopicController@reply');

// Polls
$router->get('/polls/create', 'PollController@create');
$router->post('/polls/store', 'PollController@store');
$router->post('/polls/vote', 'PollController@vote');

// Fans
$router->post('/fans/toggle', 'FanController@toggle');

// Search
$router->get('/search', 'SearchController@search');

// Photos
$router->get('/photos', 'PhotoController@index');
$router->get('/photos/create_album', 'PhotoController@createAlbum');
$router->post('/photos/store_album', 'PhotoController@storeAlbum');
$router->get('/photos/album', 'PhotoController@showAlbum');
$router->get('/photos/add', 'PhotoController@addPhoto');
$router->post('/photos/store_photo', 'PhotoController@storePhoto');

// Dispatch
$router->dispatch();
