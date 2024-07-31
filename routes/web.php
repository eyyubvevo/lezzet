<?php

use App\Http\Controllers\Back\AttributeController;
use App\Http\Controllers\ContactController as CCT;
use App\Http\Controllers\Back\CategoryController;
use App\Http\Controllers\Back\AboutController;
use App\Http\Controllers\Back\CompanyController;
use App\Http\Controllers\Back\ContactController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\GeneralQuestionController;
use App\Http\Controllers\Back\NewsController;
use App\Http\Controllers\Back\OrderController;
use App\Http\Controllers\Back\PageMetaTagController;
use App\Http\Controllers\Back\PartnerController;
use App\Http\Controllers\Back\ProductController;
use App\Http\Controllers\Back\ProfileController;
use App\Http\Controllers\Back\SettingController;
use App\Http\Controllers\Back\SliderController;
use App\Http\Controllers\Back\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\ShoppingCartList;
use App\Jobs\OrderConfirm;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\App;
use App\Models\Product;
use App\Models\User;



 Route::get("/sitemap.xml" , [\App\Http\Controllers\SiteMapController::class, 'index']);
 

 
Route::get('/test', function () {
    // return Auth::loginUsingId(1);

return Product::where('title->en', 'like', '%Arzum%')->get();
    return User::all();
});



// Route::fallback('\App\Http\Controllers\HomeController@error');


Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    flash()->addFlash('success', 'SUCCESS', 'Optimize clear command executed successfully!', ['timeout' => 3000, 'position' => 'top-center']);
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    flash()->addFlash('success', 'SUCCESS', 'Storage link created successfully!', ['timeout' => 3000, 'position' => 'top-center']);
});

Route::get('language/{locale}/{route}/{slug1?}/{slug2?}', function ($locale, $route, $slug1 = null, $slug2 = null) {
    app()->setLocale($locale);
    session()->put('locale', $locale);


    if ($slug1 && !$slug2) {
        return redirect()->to(localized_route($route, $slug1));
    } elseif ($slug2) {
        return redirect()->to(localized_route($route, [$slug1, $slug2]));
    }

    return redirect()->to(localized_route($route));
});

// Route::get('language/{route}/{locale}', function ($route,$locale) {
//     app()->setLocale($locale);
//     session()->put('locale', $locale);
//     return redirect()->to(localized_route($route)) ;
// });

// locales()
Route::middleware('set-locale')->group(function () {
    foreach (locales() as $locale) {
        Route::get("/" , [\App\Http\Controllers\HomeController::class, 'index'])->name("{$locale}.home");
    }
    foreach (locales() as $locale) {
        Route::get("{$locale}" , [\App\Http\Controllers\HomeController::class, 'index'])->name("{$locale}.home");
    }
    // foreach (locales() as $locale) {
    //     Route::get("{$locale}/" . __('paths.about', [], $locale), [\App\Http\Controllers\AboutController::class, 'index'])->name("{$locale}.about");
    // }

    foreach (locales() as $locale) {
        Route::get("{$locale}/" . __('paths.partners', [], $locale), [\App\Http\Controllers\PartnerController::class, 'index'])->name("{$locale}.partners");
    }


    foreach (locales() as $locale) {
        Route::get("{$locale}/" . __('paths.blogs', [], $locale), [\App\Http\Controllers\BlogController::class, 'index'])->name("{$locale}.news");
    }


    foreach (locales() as $locale) {
        Route::get("{$locale}/" . __('paths.faq', [], $locale), [\App\Http\Controllers\GeneralQuestionController::class, 'index'])->name("{$locale}.general_questions");
    }

    foreach (locales() as $locale) {
        Route::get("{$locale}/" . __('paths.contact', [], $locale), [\App\Http\Controllers\ContactController::class, 'index'])->name("{$locale}.contact");
    }
    foreach (locales() as $locale) {
        // Route::get("{$locale}/" . __('paths.shopping_cart', [], $locale), [\App\Http\Controllers\ContactController::class])->name("{$locale}.shopping_cart");
        Route::get("{$locale}/" . __('paths.shopping_cart', [], $locale), '\App\Http\Livewire\ShoppingCartList')->name("{$locale}.shoppingCart");

    }

    
    foreach (locales() as $locale) {
        Route::get("{$locale}/" . __('paths.blog_detail', [], $locale) . '/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name("{$locale}.news.show");
    }


    foreach (locales() as $locale) {
        Route::get("{$locale}/" . __('paths.products', [], $locale) . '/{slug}', [\App\Http\Controllers\ProductController::class, 'index'])->name("{$locale}.home.products");
    }



    foreach (locales() as $locale) {

        Route::get("{$locale}/" . __('paths.products', [], $locale) . '/{categorySlug}/{slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name("{$locale}.home.product.show");
    }
    
    foreach (locales() as $locale) {

        Route::get("{$locale}/" . __('paths.order_success', [], $locale) , [\App\Http\Controllers\OrderController::class, 'order_success'])->name("{$locale}.order.order_success");
    }
});

Route::group(
    [
        //        'prefix' => LaravelLocalization::setLocale(),
        //        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        // Route::multilingual('/', '\App\Http\Controllers\HomeController@index')->name('home');
        // Route::multilingual('home', '\App\Http\Controllers\HomeController@index')->name('home');
        // Route::multilingual('about', '\App\Http\Controllers\AboutController@index')->name('about');
        // Route::multilingual('partners', '\App\Http\Controllers\PartnerController@index')->name('partners');
        // Route::multilingual('blogs', '\App\Http\Controllers\BlogController@index')->name('news');
        // Route::multilingual('faq', '\App\Http\Controllers\GeneralQuestionController@index')->name('general_questions');
        // Route::multilingual('blog_detail/{slug}', '\App\Http\Controllers\BlogController@show')->name('news.show');
        // Route::multilingual('contact', '\App\Http\Controllers\ContactController@index')->name('contact');
        // Route::multilingual('shopping_cart', '\App\Http\Livewire\ShoppingCartList')->name('shoppingCart');
        // Route::multilingual('products/{slug}', '\App\Http\Controllers\ProductController@index')->name('home.products');
        // Route::multilingual('product/{categorySlug}/{slug}', '\App\Http\Controllers\ProductController@show')->name('home.product.show');

        Route::post('contact/store', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

        Route::post('/order', [\App\Http\Controllers\OrderController::class, 'orderForm'])->name('order.form');
        Route::post('/order/success', [\App\Http\Controllers\OrderController::class, 'store'])->name('order.form.store');
        // Route::get('/order-success', [\App\Http\Controllers\OrderController::class, 'order_success'])->name('order.order_success');

        Auth::routes();
        Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });
        Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
            Route::get('/', [DashboardController::class, 'index'])->name('admin.home');
            Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
            Route::get('/profile/password', [ProfileController::class, 'passwordIndex'])->name('profile.password.index');
            Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
            Route::get('/filemanager', function () {
                return view('admin.file-manager.index');
            })->name('admin.filemanager');
            Route::put('/profile/password/update', [ProfileController::class, 'passwordUpdate'])->name('profile.password.update');
            Route::prefix('sliders')->group(function () {
                Route::controller(SliderController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.sliders');
                    Route::name('admin.sliders.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('contacts')->group(function () {
                Route::controller(ContactController::class)->group(function () {
                    Route::get('/not/viewed', 'notViewedIndex')->name('admin.contacts.notViewed');
                    Route::get('/viewed', 'viewedIndex')->name('admin.contacts.viewed');
                    Route::name('admin.contacts.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/notList', 'notList')->name('notList');
                        Route::get('/show/{id}', 'show')->name('show');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('general_questions')->group(function () {
                Route::controller(GeneralQuestionController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.general_questions');
                    Route::name('admin.general_questions.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::post('/upload', 'upload')->name('upload');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('partners')->group(function () {
                Route::controller(PartnerController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.partners');
                    Route::name('admin.partners.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('about')->group(function () {
                Route::controller(AboutController::class)->group(function () {
                    Route::name('admin.about.')->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/upload', 'upload')->name('upload');
                        Route::put('/update/{id}', 'update')->name('update');
                    });
                });
            });

            Route::prefix('settings')->group(function () {
                Route::controller(SettingController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.settings');
                    Route::name('admin.settings.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::post('/upload', 'upload')->name('upload');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::post('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                    });
                });
            });

            Route::prefix('news')->group(function () {
                Route::controller(NewsController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.news');
                    Route::name('admin.news.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::post('/upload', 'upload')->name('upload');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('categories')->group(function () {
                Route::controller(CategoryController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.categories');
                    Route::get('/tree', 'indexTree')->name('admin.categories.tree');
                    Route::name('admin.categories.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('attributes')->group(function () {
                Route::controller(AttributeController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.attributes');
                    Route::name('admin.attributes.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('tags')->group(function () {
                Route::controller(TagController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.tags');
                    Route::name('admin.tags.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('products')->group(function () {
                Route::controller(ProductController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.products');
                    Route::name('admin.products.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::post('/upload', 'upload')->name('upload');
                        Route::get('/get-attributes', 'getAttributes')->name('getAttributes');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::get('/image/delete', 'imageDelete')->name('imageDelete');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('companies')->group(function () {
                Route::controller(CompanyController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.companies');
                    Route::name('admin.companies.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });

            Route::prefix('orders')->group(function () {
                Route::controller(OrderController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.orders');
                    Route::name('admin.orders.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/show/{id}', 'show')->name('show');
                        Route::post('/confirmation', 'confirmation')->name('confirmation');
                    });
                });
            });

            Route::prefix('page_meta_tags')->group(function () {
                Route::controller(PageMetaTagController::class)->group(function () {
                    Route::get('/', 'index')->name('admin.page_meta_tags');
                    Route::name('admin.page_meta_tags.')->group(function () {
                        Route::get('/list', 'list')->name('list');
                        Route::get('/edit/{id}', 'edit')->name('edit');
                        Route::put('/update/{id}', 'update')->name('update');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/delete/{id}', 'destroy')->name('delete');
                    });
                });
            });
        });
    }
);
