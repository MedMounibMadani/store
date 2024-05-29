<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\MessageController;
use App\Models\Article;
use App\Models\Category;
use App\Models\Command;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {
    $categories = Category::all();
    $offers = Offer::all();
    $category = isset($request->categorie) ? Category::where('slug', $request->categorie)->first() : null;
    $articles = Article::where(function ($query) use ($category) {
        if ( $category ) {
            $query->where("category_id", $category->id);
        }
    })->where('count', '>', 0)->orderByDesc('vues')->paginate(20);
    return view('welcome', compact('categories', 'articles', 'offers'));
})->name('welcome');

Route::post('/', [ArticleController::class, 'search'])->name('articles.search');
Route::get('/articles/{id}', [ArticleController::class, 'details'])->name('articles.details');

Route::get('/offres/{id}/devis', [MessageController::class, 'devisRequest'])->name('devis.get');
Route::post('/offres/{id}/devis', [MessageController::class, 'devisStore'])->name('devis.store');

Route::get('/login', function () {
    return !auth()->user() ? 
        view('login') :
        ( ! in_array("admin", auth()->user()->getRoleNames()->toArray() ) ? redirect()->route('welcome') : redirect()->route('admin.home') );
})->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('admin.login');

Route::get('/register', function () {
    return view('register');
} )->name('register');
Route::post('/register', [AdminController::class, 'register'])->name('post.register');
Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

Route::get('/mot-de-passe-oublie', [AdminController::class, 'forgot'])->name('password.forgot');
Route::post('/verifier-email', [AdminController::class, 'verify'])->name('password.verify');
Route::post('/reset-password', [AdminController::class, 'updatePwd'])->name('post.reset');
Route::get('/reset-password', [AdminController::class, 'reset'])->name('password.reset');


Route::prefix('paiement')->group(function () {
    Route::post('/etape-une', [CommandController::class, 'stepOne'])->name('step.one');
    Route::get('/etape-une', [CommandController::class, 'getStepOne'])->name('get.step.one');
    Route::post('/etape-deux', [CommandController::class, 'stepTwo'])->name('step.two');
    Route::get('/etape-deux', [CommandController::class, 'getStepTwo'])->name('get.step.two');
    Route::post('/etape-trois', [CommandController::class, 'stepThree'])->name('step.three');
    Route::get('/etape-trois', [CommandController::class, 'getStepThree'])->name('get.step.three');
});



Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', function () {
            $cmds = Command::count();
            $arts = Article::count();
            $cats = Category::count();
            return view('admin.home', compact('cmds', 'arts', 'cats'));
        })->name('admin.home');

      
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories');
            Route::get('/ajouter', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/sauvegarder', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/editer/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/modifier/{id}', [CategoryController::class, 'update'])->name('categories.update');
            Route::get('/supprimer/{id}', [CategoryController::class, 'delete'])->name('categories.delete');
        });

        Route::prefix('articles')->group(function () {
            Route::get('/', [ArticleController::class, 'index'])->name('articles');
            Route::get('/ajouter', [ArticleController::class, 'create'])->name('articles.create');
            Route::post('/sauvegarder', [ArticleController::class, 'store'])->name('articles.store');
            Route::get('/editer/{id}', [ArticleController::class, 'edit'])->name('articles.edit');
            Route::put('/modifier/{id}', [ArticleController::class, 'update'])->name('articles.update');
            Route::get('/supprimer/{id}', [ArticleController::class, 'delete'])->name('articles.delete');
            Route::get('/media/supprimer/{id}', [ArticleController::class, 'deleteMedia'])->name('articles.media.delete');
        });

        Route::prefix('offres')->group(function () {
            Route::get('/', [OfferController::class, 'index'])->name('offers');
            Route::get('/ajouter', [OfferController::class, 'create'])->name('offers.create');
            Route::post('/sauvegarder', [OfferController::class, 'store'])->name('offers.store');
            Route::get('/editer/{id}', [OfferController::class, 'edit'])->name('offers.edit');
            Route::put('/modifier/{id}', [OfferController::class, 'update'])->name('offers.update');
            Route::get('/supprimer/{id}', [OfferController::class, 'delete'])->name('offers.delete');
            Route::get('/visibility/{id}', [OfferController::class, 'visibility'])->name('offers.visibility');
            Route::get('/media/supprimer/{id}', [OfferController::class, 'deleteMedia'])->name('offers.media.delete');
        });

        Route::prefix('commandes')->group(function () {
            Route::get('/', [CommandController::class, 'index'])->name('commands');
            Route::get('/historique', [CommandController::class, 'history'])->name('commands.history');
            Route::get('/{id}', [CommandController::class, 'details'])->name('commands.detail');
            Route::get('/archiver/{id}', [CommandController::class, 'setToDone'])->name('commands.done');
        });

        Route::get('/messages', [MessageController::class, 'index'])->name('messages');
        Route::get('/messages/{id}/detail', [MessageController::class, 'detail'])->name('messages.detail');

    });
});