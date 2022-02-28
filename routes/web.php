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

                Route::get('/markRead', 'NotificationController@markRead')->name('markRead');
                
                Route::namespace('Admin')->group(function () {
                //Route::get('/customer','UserController@customerIndex')->name('customers');
                //Route::get('/engineer','UserController@engineerIndex')->name('engineers');
                Route::get('/home', 'DashboardController@index')->name('home');
                Route::get('/project/monthly', 'DashboardController@projectMonthly')->name('project.monthly');


                Route::get('/getProjects', 'ProjectController@getProjectData')->name('projects.getProjects');
                Route::get('/export/excel', 'DashboardController@exportExcel')->name('export.excel');
                Route::get('/export/pdf', 'ReportController@exportPDF')->name('export.pdf');

                Route::get('/projects/list', 'ProjectController@indexProject')->name('projects.list');
                Route::post('/project/set/status', 'ProjectController@setStatus')->name('projects.set.status');
                
                //Project Payment Info 
                Route::get('/payments', 'ProjectController@paymentInfo')->name('payment');
                Route::get('/paymentview', 'ProjectController@getPayments')->name('projects.getPayments');
                //update Payment Status
                Route::post('/update/paymentStatus','ProjectController@paymentStatus')->name('payment.status');

                //Route::get('/design/view/{id?}', 'SystemDesignController@view')->name('view');

                //project controller edit, delete
                Route::resource('/projects', 'ProjectController');
                Route::post('/projects/file', 'ProjectController@attachFile')->name('projects.file');
                Route::get('/projects/get/file', 'ProjectController@getFile')->name('projects.get.file');
                Route::get('/projects', 'ProjectController@getProjects')->name('get');
                Route::post('/projects/assign', 'ProjectController@assign')->name('assign');
                Route::get('/projects/{id}/assign', 'ProjectController@getAssignEngineer')->name('assignValue');

                //Admin Customer Controller
                Route::resource('/customer', 'CustomerController');

                //Admin Engineer Controller
                Route::resource('/engineer', 'EngineerController');

                //Admin Manager Controller
                Route::resource('/manager', 'ManagerController');

                //Admin Users Controller
                Route::resource('/users', 'UserController');

                //Admin Design Price Controller
                Route::resource('/price', 'DesignPriceController');
                
                //Role and Permissions
                Route::resource('/roles', 'RoleController');
                Route::resource('/permissions', 'PermissionController');

                //API
                Route::get('/users/{role}', 'UserController@getList')->name('list');
            });
        });
        });
    });

    Route::group(['middleware' => ['role:manager']], function() {
        Route::prefix('manager')->group(function() {
            Route::name('manager.')->group(function() {
                Route::get('/markRead', 'NotificationController@markRead')->name('markRead');
                Route::resource('/home', 'Manager\DashboardController');
                Route::get('/project/monthly', 'Manager\DashboardController@projectMonthly')->name('project.monthly');
                Route::get('/getProjects', 'Admin\ProjectController@getProjectData')->name('projects.getProjects');
                Route::get('/export/excel', 'Manager\DashboardController@exportExcel')->name('export.excel');
                Route::get('/export/pdf', 'Manager\ReportController@exportPDF')->name('export.pdf');

                Route::resource('/customer', 'Admin\CustomerController');
                Route::resource('/engineer', 'Admin\EngineerController');
                
                Route::get('/projects/list', 'Admin\ProjectController@indexProject')->name('projects.list');
                Route::post('/project/set/status', 'Admin\ProjectController@setStatus')->name('projects.set.status');
                
                Route::resource('/projects', 'Admin\ProjectController');
                Route::post('/projects/file', 'Admin\ProjectController@attachFile')->name('projects.file');
                Route::get('/projects/get/file', 'Admin\ProjectController@getFile')->name('projects.get.file');
                Route::get('/projects', 'Admin\ProjectController@getProjects')->name('get');

                Route::get('/projects/getProjects', 'Admin\ProjectController@getProjectData')->name('getProjects');

                Route::post('/projects/assign', 'Admin\ProjectController@assign')->name('assign');
                Route::get('/projects/{id}/assign', 'Admin\ProjectController@getAssignEngineer')->name('assignValue');

                //Route::get('view/{id?}', 'Engineer\SystemDesignController@view')->name('design.view');
            });
        });
    });

    Route::group(['middleware' => ['role:engineer|manager|admin']], function () {
        Route::prefix('engineer')->group(function () {
            Route::name('engineer.')->group(function () {
                Route::namespace('Engineer')->group(function () {

                    Route::prefix('project')->group(function () {
                        Route::name('project.')->group(function () {
                            Route::get('/available', 'ProjectController@availableProjects')->name('available');
                            Route::get('/assign/{id}', 'ProjectController@assign')->name('assign');
                            Route::get('/view/{id?}', 'ProjectController@view')->name('view');
                            Route::post('/set/status', 'ProjectController@setStatus')->name('set.status');

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
        Route::get('/markRead', 'NotificationController@markRead')->name('customer.markRead');

        Route::get('/customer/reports', 'ReportController@reportIndex')->name('customer.export');
        Route::get('/customer/projects/self', 'ReportController@getProjects')->name('customer.projects.list');
        Route::get('/customer/export/excel', 'ReportController@exportExcel')->name('customer.export.excel');
        Route::get('/customer/export/pdf', 'ReportController@exportPDF')->name('customer.export.pdf');

        Route::prefix('project')->group(function () {
            Route::name('project.')->group(function () {

//            View forms
                Route::get('new/{type}', 'ProjectController@form')->name('form');
                Route::get('update/{id?}', 'ProjectController@edit')->name('edit');

                Route::get('/multiple', 'ProjectController@bulkProject')->name('bulk');

//          Get all Designs 
                Route::post('/getform', 'DesignRequestController@getDesignForms')->name('designs');

//            Save things
                Route::post('/', 'ProjectController@insert')->name('insert');

                Route::post('/bulk', 'ProjectController@Bulkinsert')->name('bulkinsert');
//            Route::post('update','ProjectController@update')->name('update');
                Route::post('/file', 'ProjectController@attachFile')->name('file.attach');
                Route::post('/archive/{id}', 'ProjectController@archive')->name('archive');

//            Get APIs
                Route::get('/projects', 'ProjectController@getProjects')->name('get');
               
                Route::get('/projects/getProjects', 'ProjectController@getProjectData')->name('getProjects');

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
                Route::post('/electrical_load', 'DesignRequestController@saveElectricalLoad')->name('electrical_load');
                Route::post('/pe_stamping', 'DesignRequestController@savePEStamping')->name('pe_stamping');
                Route::post('/engineering_permit_package', 'DesignRequestController@saveEngPermitPackage')->name('engineering_permit_package');
                Route::post('/multiple/design', 'DesignRequestController@storeMultiple')->name('multiple_design');
            });
        });

        Route::prefix('payment')->group(function () {
            Route::name('payment.')->group(function () {
                Route::post('hold', 'PaymentController@placeHoldOnFunds')->name('hold');
                Route::post('new', 'PaymentController@newCard')->name('new');
                Route::post('update', 'PaymentController@setDefault')->name('update');

                Route::post('cancel', 'PaymentController@cancelPayment')->name('cancel');

                Route::get('methods', 'PaymentController@getPaymentMethods')->name('methods');
            });
        });

        Route::name('profile.')->group(function () {
            Route::put('/profile/admin/update/{id}', 'ProfileController@adminUpdate')->name('admin.update');
            Route::put('/profile/manager/update/{id}', 'ProfileController@managerUpdate')->name('manager.update');
            Route::put('/profile/engineer/update/{id}', 'ProfileController@engineerUpdate')->name('engineer.update');
            Route::put('/profile/customer/update/{id}', 'ProfileController@customerUpdate')->name('customer.update');
            Route::resource('/profile', 'ProfileController');
            Route::get('/profile/payment/methods', 'PaymentController@getPaymentMethods')->name('payment.methods');
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

    Route::group(['middleware' => ['role:engineer|customer|manager|admin']], function () {
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
