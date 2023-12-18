<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->query('page');
        $page = $page ? $page : 1;

        // dd($page);
        $perPage = 20;
        $cacheKey = "articles_page_{$page}_per_page_{$perPage}";
        $countKey = 'count';

        if(Cache::tags(['articles_count'])->has($countKey)){
            $count = Cache::tags(['articles_count'])->get($countKey);
        } else {
            $count = Article::count();
        }

        // Check if data is cached
        if (Cache::tags(['articles'])->has($cacheKey)) {
            $articles = Cache::tags(['articles'])->get($cacheKey);
        } else {
            // Data not found in cache, query the database
            $offset = ($page - 1) * $perPage;
            $articles = Article::orderBy('published', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();

            // Cache the query result with the 'articles' tag
            Cache::tags(['articles'])->put($cacheKey, $articles, now()->addMinutes(30)); // Adjust the expiration time as needed
            Cache::tags(['articles_count'])->put($countKey, $count, now()->addMinutes(30));
        }
        $count = (int) $count;

        // $articlesData = ArticleResource::collection($articles);
        // Return the data
        return response()->json([
            'articles' => $articles,
            'article_count' => $count,
        ], Response::HTTP_OK);
    }

    /**
     * Call this api to fetch more articles on user request
     */


    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query('query');
        // echo $query;
        $articles = Article::search($query)->simplePaginate(20);

        return response()->json($articles, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        // Invalidate the 'articles' cache tag
        Cache::tags(['articles'])->flush();
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
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
