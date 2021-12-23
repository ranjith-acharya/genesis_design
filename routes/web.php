<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/datatable', function(){
    return view('home1');
});

Auth::routes(['verify' => true]);
Route::post('/register/verify', '\App\Http\Controllers\Auth\RegisterController@verify')->name('register.verify');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['verified', 'auth'])->group(function () {

    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('admin')->group(function () {
            Route::name('admin.')->group(function () {
                Route::namespace('Admin')->group(function () {
                //Route::get('/customer','UserController@customerIndex')->name('customers');
                //Route::get('/engineer','UserController@engineerIndex')->name('engineers');
                Route::get('/home', 'ProjectController@index')->name('home');

                //project controller edit, delete
                Route::resource('/projects', 'ProjectController');
                Route::post('/projects/file', 'ProjectController@attachFile')->name('projects.file');
                Route::get('/projects', 'ProjectController@getProjects')->name('get');
                Route::post('/projects/assign', 'ProjectController@assign')->name('assign');

                //Admin Customer Controller
                Route::resource('/customer', 'CustomerController');

                //Admin Engineer Controller
                Route::resource('/engineer', 'EngineerController');

                //Admin Manager Controller
                Route::resource('/manager', 'ManagerController');

                //Role and Permissions
                Route::resource('/roles', 'RoleController');
                Route::resource('/permissions', 'PermissionController');

                //API
                Route::get('/users/{role}', 'UserController@getList')->name('list');
            });
        });
        });
    });

    Route::group(['middleware' => ['role:engineer']], function () {
        Route::prefix('engineer')->group(function () {
            Route::name('engineer.')->group(function () {
                Route::namespace('Engineer')->group(function () {

                    Route::prefix('project')->group(function () {
                        Route::name('project.')->group(function () {
                            Route::get('/available', 'ProjectController@availableProjects')->name('available');
                            Route::get('/assign/{id}', 'ProjectController@assign')->name('assign');
                            Route::get('/view/{id?}', 'ProjectController@view')->name('view');

//                      Get APIs
                            Route::get('/my-projects', 'ProjectController@getProjects')->name('get');
                        });
                    });

                    Route::prefix('proposal')->group(function () {
                        Route::name('proposal.')->group(function () {
                            Route::get('/new/{design}', 'ProposalController@from')->name('new');

                            Route::post('/insert', 'ProposalController@insert')->name('insert');
                            Route::post('/file', 'ProposalController@attachFile')->name('file.attach');
                        });
                    });

                    Route::prefix('designs')->group(function () {
                        Route::name('design.')->group(function () {
                            Route::get('list/{project_id?}', 'SystemDesignController@index')->name('list');
                            Route::get('start/{id}', 'SystemDesignController@start')->name('start');


                            Route::get('getDesigns', 'SystemDesignController@getDesigns')->name('get');
                            Route::get('view/{id?}', 'SystemDesignController@view')->name('view');
                            Route::get('file', 'SystemDesignController@getFile')->name('file');
                        });
                    });
                });

                Route::prefix('changeRequests')->group(function () {
                    Route::name('change_requests.')->group(function () {
                        Route::post('quote', 'ChangeRequestController@quote')->name('quote');
                    });
                });

                Route::get('/get/file', 'ProjectController@getFile')->name('project.file.get');
            });
        });
    });

    //Route::group(['middleware' => ['role:customer']], function () {

        Route::prefix('project')->group(function () {
            Route::name('project.')->group(function () {

//            View forms
                Route::get('new/{type}', 'ProjectController@form')->name('form');
                Route::get('update/{id?}', 'ProjectController@edit')->name('edit');

//            Save things
                Route::post('/', 'ProjectController@insert')->name('insert');
//            Route::post('update','ProjectController@update')->name('update');
                Route::post('/file', 'ProjectController@attachFile')->name('file.attach');
                Route::post('/archive/{id}', 'ProjectController@archive')->name('archive');

//            Get APIs
                Route::get('/projects', 'ProjectController@getProjects')->name('get');
                Route::get('/get/file', 'ProjectController@getFile')->name('file.get');
            });
        });

        Route::prefix('design')->group(function () {
            Route::name('design.')->group(function () {
                Route::get('list/{project_id?}', 'DesignRequestController@index')->name('list');
                Route::get('getDesigns', 'DesignRequestController@getDesigns')->name('get');
                Route::get('view/{id?}', 'DesignRequestController@view')->name('view');

                Route::get('new/{project_id}/{type}', 'DesignRequestController@form')->name('form');
                Route::post('close/{id}', 'DesignRequestController@closeDesignRequest')->name('close');
                Route::post('/file', 'DesignRequestController@attachFile')->name('file.attach');
                Route::post('/aurora', 'DesignRequestController@saveAurora')->name('aurora');
                Route::post('/structural_load', 'DesignRequestController@saveStructuralLoad')->name('structural_load');
            });
        });

        Route::prefix('payment')->group(function () {
            Route::name('payment.')->group(function () {
                Route::post('hold', 'PaymentController@placeHoldOnFunds')->name('hold');
                Route::post('new', 'PaymentController@newCard')->name('new');
                Route::post('update', 'PaymentController@setDefault')->name('update');

                Route::get('methods', 'PaymentController@getPaymentMethods')->name('methods');
            });
        });

        Route::prefix('profile')->group(function () {
            Route::name('profile.')->group(function () {
                Route::view('/', 'profile.profile')->name('main');
                Route::get('/payment/methods', 'PaymentController@getPaymentMethods')->name('payment.methods');
            });
        });

        Route::prefix('changeRequests')->group(function () {
            Route::name('change_requests.')->group(function () {
                Route::post('/new', 'ChangeRequestController@insert')->name('new');
                Route::post('/file', 'ChangeRequestController@attachFile')->name('file.attach');
                Route::post('hold', 'PaymentController@placeHoldOnFundsForCR')->name('hold');
                Route::post('accept', 'ChangeRequestController@accept')->name('accept');
                Route::post('reject', 'ChangeRequestController@reject')->name('reject');
            });
        });
    //});

    Route::group(['middleware' => ['role:engineer|customer']], function () {
        Route::prefix('messages')->group(function () {
            Route::name('messages.')->group(function () {
                Route::post('/insert', 'MessageController@insert')->name('insert');

                Route::post('/attach', 'MessageController@attachFile')->name('file.attach');

                Route::get('/file', 'MessageController@getFile')->name('file.get');
                Route::get('/unread', 'MessageController@unread')->name('unread');
                Route::post('/markRead/{design?}', 'MessageController@markRead')->name('mark.read');
            });
        });

        Route::prefix('proposal')->group(function () {
            Route::name('proposal.')->group(function () {
                Route::get('/view/{design}', 'Engineer\ProposalController@view')->name('view');
                Route::get('file', 'Engineer\ProposalController@getFile')->name('file');
            });
        });

        Route::prefix('designs')->group(function () {
            Route::name('design.')->group(function () {
                Route::get('file', 'DesignRequestController@getFile')->name('file');
            });
        });

        Route::prefix('changeRequests')->group(function () {
            Route::name('change_requests.')->group(function () {
                Route::get('/file', 'ChangeRequestController@getFile')->name('file');
            });
        });
    });

});
