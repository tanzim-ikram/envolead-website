<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::orderByDesc('created_at')->paginate(12);

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
                'created_at' => $item->created_at->toISOString(),
                'updated_at' => $item->updated_at->toISOString(),
                'status' => $item->status,
                'meta_description' => $item->meta_description,
            ];
        });

        return Inertia::render('Admin/News/Index', ['news' => $news]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/News/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_caption' => 'nullable|string',
            'quote_text' => 'nullable|string',
            'quote_author' => 'nullable|string',
            'key_highlights' => 'nullable|array',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'meta_description' => 'nullable|string|max:160',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('news-images', 'public');
        }

        // Ensure published_at is set when creating as published
        if (($validated['status'] ?? 'draft') === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $news = News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        // Admin show should point to the public-facing page for published news
        if ($news->status === 'published') {
            return redirect()->route('news.show', $news->slug);
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
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

        return Inertia::render('Admin/News/Edit', [
            'news' => $newsData
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_caption' => 'nullable|string',
            'quote_text' => 'nullable|string',
            'quote_author' => 'nullable|string',
            'key_highlights' => 'nullable|array',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'meta_description' => 'nullable|string|max:160',
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('news-images', 'public');
        }

        // Ensure published_at exists if status becomes published without a date
        if (($validated['status'] ?? $news->status) === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = $news->published_at ?: now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Delete featured image if exists
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully!');
    }

    /**
     * Toggle news status
     */
    public function toggleStatus(News $news)
    {
        $newStatus = $news->status === 'published' ? 'draft' : 'published';
        $payload = ['status' => $newStatus];
        if ($newStatus === 'published' && empty($news->published_at)) {
            $payload['published_at'] = now();
        }
        $news->update($payload);

        return back()->with('success', 'News status updated successfully!');
    }
}
