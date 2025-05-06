<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Auth;

class ProducerContentController extends Controller
{
    public function create()
    {
        return view('producer.content.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:movie,tvshow',
            'genre' => 'required|string',
            'price' => 'required|numeric|min:0',
            'video' => 'required|file|mimes:mp4,mov,avi|max:10240',
            'audio.*' => 'nullable|file|mimes:mp3,wav,aac|max:100',
            'subtitles.*' => 'nullable|file|mimes:srt,vtt|max:2',
            'documents.*' => 'required|file|mimes:pdf,doc,docx|max:10',
        ]);

        $content = new Content();
        $content->title = $validated['title'];
        $content->description = $validated['description'];
        $content->type = $validated['type'];
        $content->genre = $validated['genre'];
        $content->price = $validated['price'];
        $content->producer_id = Auth::id();
        $content->status = 'pending';
        $content->save();

        // Handle file uploads
        if ($request->hasFile('video')) {
            $content->addMedia($request->file('video'))
                ->toMediaCollection('video');
        }

        if ($request->hasFile('audio')) {
            foreach ($request->file('audio') as $audio) {
                $content->addMedia($audio)
                    ->toMediaCollection('audio');
            }
        }

        if ($request->hasFile('subtitles')) {
            foreach ($request->file('subtitles') as $subtitle) {
                $content->addMedia($subtitle)
                    ->toMediaCollection('subtitles');
            }
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $content->addMedia($document)
                    ->toMediaCollection('documents');
            }
        }

        return redirect()->route('producer.dashboard')
            ->with('success', 'Content uploaded successfully and is pending review.');
    }

    public function edit(Content $content)
    {
        $this->authorize('update', $content);
        return view('producer.content.edit', compact('content'));
    }

    public function update(Request $request, Content $content)
    {
        $this->authorize('update', $content);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'genre' => 'required|string',
            'price' => 'required|numeric|min:0',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
            'audio.*' => 'nullable|file|mimes:mp3,wav,aac|max:100',
            'subtitles.*' => 'nullable|file|mimes:srt,vtt|max:2',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx|max:10',
        ]);

        $content->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'genre' => $validated['genre'],
            'price' => $validated['price'],
            'status' => 'pending', // Reset status for review
        ]);

        // Handle file uploads
        if ($request->hasFile('video')) {
            $content->clearMediaCollection('video');
            $content->addMedia($request->file('video'))
                ->toMediaCollection('video');
        }

        if ($request->hasFile('audio')) {
            $content->clearMediaCollection('audio');
            foreach ($request->file('audio') as $audio) {
                $content->addMedia($audio)
                    ->toMediaCollection('audio');
            }
        }

        if ($request->hasFile('subtitles')) {
            $content->clearMediaCollection('subtitles');
            foreach ($request->file('subtitles') as $subtitle) {
                $content->addMedia($subtitle)
                    ->toMediaCollection('subtitles');
            }
        }

        if ($request->hasFile('documents')) {
            $content->clearMediaCollection('documents');
            foreach ($request->file('documents') as $document) {
                $content->addMedia($document)
                    ->toMediaCollection('documents');
            }
        }

        return redirect()->route('producer.dashboard')
            ->with('success', 'Content updated successfully and is pending review.');
    }

    public function analytics(Content $content)
    {
        $this->authorize('view', $content);
        
        $analytics = [
            'views' => $content->views_count,
            'downloads' => $content->downloads_count,
            'revenue' => $content->revenue,
            'recent_views' => $content->recent_views,
            'recent_downloads' => $content->recent_downloads,
        ];

        return view('producer.content.analytics', compact('content', 'analytics'));
    }
} 