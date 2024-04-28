<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $articles = Article::where('is_audio_article', false)->latest()->paginate();

        if ($request->category) {
            $articles = Article::where(['category' => $request->category, 'is_audio_article' => false])->latest()->paginate();
        }
        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $article = new Article(
            [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'category' => $request->input('category'),
                'is_audio_article' => $request->input('is_audio_article') ?? false,
                'user_id' => Auth::id(), // Associate the article with the currently authenticated user
            ]
        );

        // Handle cover image upload if provided
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $folder = 'uploads/cover_images';
            $url = $this->uploadSingle($file, $folder);
            $article->cover_image = $url;
            $article->save();
        }

        // Handle audio file upload if provided
        if ($request->hasFile('audio_file')) {
            $audioFile = $request->file('audio_file');
            $audioFileName = time() . '_' . $audioFile->getClientOriginalName();
            $audioFile->storeAs('public/audio_files', $audioFileName);
            $article->audio_file = $audioFileName;
        }

        // Save the article to the database
        $article->save();

        return response()->json([
            'data' => [
                'article' => $article, 'message' => 'Article created successfully'
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
