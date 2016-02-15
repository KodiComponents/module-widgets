<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['web']], function () {
    Route::get('snippets', ['as' => 'snippet.list', 'uses' => 'SnippetController@getIndex']);
    Route::get('snippet/create', ['as' => 'snippet.create', 'uses' => 'SnippetController@getCreate']);
    Route::post('snippet/create', ['as' => 'snippet.create.post', 'uses' => 'SnippetController@postCreate']);
    Route::get('snippet/{id}', ['as' => 'snippet.edit', 'uses' => 'SnippetController@getEdit']);
    Route::post('snippet/{id}', ['as' => 'snippet.edit.post', 'uses' => 'SnippetController@postEdit']);
    Route::post('snippet/{id}/delete', ['as' => 'snippet.delete', 'uses' => 'SnippetController@postDelete']);

    Route::get('widget', ['as' => 'widget.list', 'uses' => 'WidgetController@getIndex']);
    Route::get('widget/{id}/location', ['as' => 'widget.location', 'uses' => 'WidgetController@getLocation']);
    Route::post('widget/{id}/location', ['as' => 'widget.location.post', 'uses' => 'WidgetController@postLocation']);
    Route::get('widget/{id}/edit', ['as' => 'widget.edit', 'uses' => 'WidgetController@getEdit']);
    Route::post('widget/{id}/edit', ['as' => 'widget.edit.post', 'uses' => 'WidgetController@postEdit']);
    Route::get('widget/create/{type?}', ['as' => 'widget.create', 'uses' => 'WidgetController@getCreate']);
    Route::post('widget/create', ['as' => 'widget.create.post', 'uses' => 'WidgetController@postCreate']);
    Route::get('widget/{id/}template', ['as' => 'widget.template', 'uses' => 'WidgetController@getCreate']);
    Route::post('widget/{id}/delete', ['as' => 'widget.delete', 'uses' => 'WidgetController@postDelete']);
    Route::get('widget/popup', ['as' => 'widget.popup_list', 'uses' => 'WidgetController@getPopupList']);
    Route::get('widget/{type}', ['as' => 'widget.list.by_type', 'uses' => 'WidgetController@getIndex']);
});

Route::group(['as' => 'api.', 'middleware' => ['web', 'api']], function () {
    RouteAPI::put('widget', ['as' => 'widget.place', 'uses' => 'API\WidgetController@putPlace']);
    RouteAPI::post('widget.set.template', [
        'as'   => 'widget.set.template',
        'uses' => 'API\WidgetController@setTemplate',
    ]);
    RouteAPI::post('page.widgets.reorder', [
        'as'   => 'page.widgets.reorder',
        'uses' => 'API\WidgetController@postReorder',
    ]);
    RouteAPI::post('snippet', ['as' => 'snippet.create', 'uses' => 'API\SnippetController@postCreate']);
    RouteAPI::put('snippet', ['as' => 'snippet.edit', 'uses' => 'API\SnippetController@postEdit']);
    RouteAPI::get('snippet.list', ['as' => 'snippet.list', 'uses' => 'API\SnippetController@getList']);
    RouteAPI::get('snippet.xeditable', [
        'as'   => 'snippet.xeditable',
        'uses' => 'API\SnippetController@getListForXEditable',
    ]);
});

Route::get('handler/{handler}', ['as' => 'widget.handler', 'uses' => 'HandlerController@getHandle', 'middleware' => ['web']]);
