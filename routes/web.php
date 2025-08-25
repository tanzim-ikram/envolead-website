<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use App\Models\TeamMember;
use App\Http\Controllers\TeamController;
use App\Models\Project;
use App\Models\Partner;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\ProjectController;
use App\Models\News;

// Helper function to get partner logo URL
function getPartnerLogoUrl($logoPath)
{
    if (!$logoPath) {
        return null;
    }

    // Check if it's an old path (public/partners)
    if (File::exists(public_path('partners/' . basename($logoPath)))) {
        return '/partners/' . basename($logoPath);
    }

    // Check if it's a new path (storage/partners)
    if (File::exists(storage_path('app/public/' . $logoPath))) {
        return '/storage/' . $logoPath;
    }

    // Fallback - assume it's in storage
    return '/storage/' . $logoPath;
}

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'projects' => Project::whereNull('parent_id')->orderBy('sort_order')->get(),
        'clubPartners' => Partner::where('type', 'clubs')->get()->map(function ($partner) {
            $partner->logo_url = getPartnerLogoUrl($partner->logo);
            return $partner;
        }),
        'companyPartners' => Partner::where('type', 'company')->get()->map(function ($partner) {
            $partner->logo_url = getPartnerLogoUrl($partner->logo);
            return $partner;
        }),
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

// Public routes for viewing projects
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

// Admin routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [ProjectController::class, 'manage'])->name('index');
            Route::get('/create', [ProjectController::class, 'create'])->name('create');
            Route::post('/', [ProjectController::class, 'store'])->name('store');
            Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
            Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
            Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
            Route::post('/{project}/details', [ProjectController::class, 'updateDetails'])->name('update-details');
        });
    });
});

// ------------------ News Public Routes (no auth) ------------------
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

// ------------------ News Admin Routes (auth) ------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // News management
        Route::resource('news', AdminNewsController::class);
        Route::patch('news/{news}/toggle-status', [AdminNewsController::class, 'toggleStatus'])
            ->name('news.toggle-status');

        // Partners management - Use resource route (FIXED)
        Route::resource('partners', PartnerController::class);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
