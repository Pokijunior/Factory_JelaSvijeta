<?php

namespace App\Http\Controllers\Api\v1;

use App\Filters\v1\MealsFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\MealResource;
use App\Http\Resources\v1\MealCollection;
use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\mapInto;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rules = [
            'with' => 'string',
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
            'category' => 'nullable|in:NULL,!NULL', 
            'tags' => 'array',
            'diff_time' => 'integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);   
        }
        

        $query = Meal::query();

        if ($request->has('with')) {
            $withParameters = explode(',', $request->input('with'));


            $categoryFilter = $request->input('category');

            if ($categoryFilter === 'NULL') {
                $query->whereNull('category_id');
            } elseif ($categoryFilter === '!NULL') {
                $query->whereNotNull('category_id');
            } elseif ($categoryFilter) {
                $query->where('category_id', $categoryFilter);
            }

            $tagsFilter = $request->input('tags');
            if ($tagsFilter) {
                $tags = explode(',', $tagsFilter);
                $query->whereHas('tags', function ($q) use ($tags) {
                    $q->whereIn('tag_id', $tags);
                }, '=', count($tags));
            }

            $diffTime = $request->input('diff_time');
            if ($diffTime) {
                $query->where(function ($q) use ($diffTime) {
                    $q->where('created_at', '>=', date('Y-m-d H:i:s', $diffTime))
                        ->orWhere('updated_at', '>=', date('Y-m-d H:i:s', $diffTime))
                        ->orWhere('deleted_at', '>=', date('Y-m-d H:i:s', $diffTime));
                });
            }

            if (in_array('category', $withParameters)) {
                $query->with('category');
            }

            if (in_array('tags', $withParameters)) {
                $query->with('tags');
            }

            if (in_array('ingredients', $withParameters)) {
                $query->with('ingredients');
            }
        

            
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);


        $meals = $query->get();
        $meals = $query->paginate($perPage);
        $meals = $query->paginate($perPage, ['*'], 'page', $page);

        $meta = [
            'currentPage' => $meals->currentPage(),
            'totalItems' => $meals->total(),
            'itemsPerPage' => $meals->perPage(),
            'totalPages' => $meals->lastPage(),
        ];
        
            return response()->json([
                'meta' => $meta,
                'data' => $meals]);


    } else {

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);


        $meals = $query->get();
        $meals = $query->paginate($perPage);
        $meals = $query->paginate($perPage, ['*'], 'page', $page);

        $meta = [
            'currentPage' => $meals->currentPage(),
            'totalItems' => $meals->total(),
            'itemsPerPage' => $meals->perPage(),
            'totalPages' => $meals->lastPage(),
        ];

        return response()->json([
                'meta' => $meta,
                'data' => MealResource::collection(Meal::all()->makeHidden('category_id'))
            ]);
    }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMealRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal)
    {
        return new MealResource($meal);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMealRequest $request, Meal $meal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        //
    }
}
