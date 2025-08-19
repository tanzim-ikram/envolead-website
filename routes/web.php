<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\TeamMember;
use App\Http\Controllers\TeamController;
use App\Models\Project;
use App\Models\Partner;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Models\News;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'projects' => Project::whereNull('parent_id')->orderBy('sort_order')->get(),
        'clubPartners' => Partner::where('type', 'clubs')->get(),
        'companyPartners' => Partner::where('type', 'company')->get(),
        'team' => TeamMember::orderBy('id')->get(),
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/team/{slug}', [TeamController::class, 'show']);

// Admin team routes
Route::prefix('admin/team')->name('admin.team.')->group(function () {
    Route::get('/', [AdminTeamController::class, 'index'])->name('index');
    Route::get('/manage', [AdminTeamController::class, 'manage'])->name('manage');
    Route::get('/create', [AdminTeamController::class, 'create'])->name('create');
    Route::post('/', [AdminTeamController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminTeamController::class, 'edit'])->name('edit');
    Route::match(['post', 'put'], '/{id}', [AdminTeamController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminTeamController::class, 'destroy'])->name('destroy');
});

// ------------------ Public (no auth) ------------------
Route::get('/news', function () {
    $news = News::published()->latest()->paginate(12);

    // Transform the paginated data to ensure proper structure
    $news->getCollection()->transform(function ($item) {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'excerpt' => $item->excerpt,
            'content' => $item->content,
            'featured_image' => $item->featured_image,
            'image_caption' => $item->image_caption,
            'quote_text' => $item->quote_text,
            'quote_author' => $item->quote_author,
            'key_highlights' => $item->key_highlights,
            'published_at' => $item->published_at?->toISOString(),
            'status' => $item->status,
            'meta_description' => $item->meta_description,
        ];
    });

    return Inertia::render('News/Index', ['news' => $news]);
})->name('news.index');

Route::get('/news/{news:slug}', function (News $news) {
    abort_unless($news->status === 'published', 404);

    // Transform the news data to ensure proper structure
    $newsData = [
        'id' => $news->id,
        'title' => $news->title,
        'slug' => $news->slug,
        'excerpt' => $news->excerpt,
        'content' => $news->content,
        'featured_image' => $news->featured_image,
        'image_caption' => $news->image_caption,
        'quote_text' => $news->quote_text,
        'quote_author' => $news->quote_author,
        'key_highlights' => $news->key_highlights ?: [],
        'published_at' => $news->published_at?->toISOString(),
        'status' => $news->status,
        'meta_description' => $news->meta_description,
    ];

    return Inertia::render('News/Show', ['news' => $newsData]);
})->name('news.show');

// ------------------ Admin (auth) ------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('news', AdminNewsController::class);
        Route::patch('news/{news}/toggle-status', [AdminNewsController::class, 'toggleStatus'])
            ->name('news.toggle-status');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
