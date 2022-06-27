<?php

namespace App\Http\Controllers\Api\v0;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v0\LayoutResource;
use App\Http\Resources\Api\v0\LayoutWidgetResource;
use App\Http\Resources\Api\v0\PostResource;
use App\Http\Resources\Api\v0\TagResource;
use App\Http\Resources\Api\v0\PostCategoryResource;
use App\Http\Resources\Api\v0\PostTagResource;
use App\Models\Category;
use App\Models\Layout;
use App\Models\LayoutWidget;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{

    public function __construct() {
    //    $this->middleware('auth:api', ['except' => ['test']]);
    }

    public function index()
    {
        $layout =  Layout::where(['status' => 1])->firstOrFail();
        $data['activeLayout'] = new LayoutResource($layout);

        $LayoutWidget =  LayoutWidget::where(['layout_id' => $layout->id, 'status' => 1])->OrderBy('id' , 'asc')->get();
        $data['activeWidgets'] = LayoutWidgetResource::collection($LayoutWidget);
        return $data;
    }

    public function header()
    {
        $layout =  Layout::where(['status' => 1])->firstOrFail();

        $LayoutWidget =  LayoutWidget::
            join('widgets', 'widgets.id', '=', 'layout_widgets.widget_id')
            ->where('widgets.widget_type', '=', 'header')
            ->where(['layout_id' => $layout->id, 'layout_widgets.status' => 1])
            ->get();
        return $LayoutWidget;
    }

    public function posts() {
        $post = Post::orderBy('id' , 'desc')->paginate(15);
        return PostResource::collection($post);
    }

    public function getAllPost(Request $request){
        $per_page = 10;
        if ($request->per_page) {
            $per_page = $request->per_page;
        } 

        $year = $month = $day = $exclude = $category_slugs = $category_ids = $tag_ids = $tag_slugs = $districts = $title = $hasMeta = $sort = false;
        if(empty($request->all())) {
            $key = preg_replace("/[^A-Za-z0-9\-]/", "", json_encode($request));
        } else {
            $key = preg_replace("/[^A-Za-z0-9\-]/", "", json_encode($request->all()));
        }

        if ($request->sort === 'asc') {
            $sort = 'asc';
        } else {
            $sort = 'desc';
        }

        if ($request->title) {
            $title = $request->title;
        }

        if (!empty($request->category_ids)) {
            $category_ids = $request->category_ids;
        } elseif (!empty($request->category_slugs)) {
            $category_slugs = $request->category_slugs;
        }

        if (!empty($request->tag_ids)) {
            $tag_ids = $request->tag_ids;
        } elseif (!empty($request->tag_slugs)) {
            $tag_slugs = $request->tag_slugs;
        }

        if (!empty($request->districts)) {
            $districts = $request->districts;
        }

        if($request->date) {
            $dateArray = date_parse_from_format('Y-m-d', $request->date);

            if (!$dateArray['warning_count']) {
                $year = $dateArray['year'];
                $month = $dateArray['month'];
                $day = $dateArray['day'];
            }
        }
        if (!empty($request->exclude)) {
            if (is_array($request->exclude)) {
                $exclude = array_filter($request->exclude, 'ctype_digit');
            } else {
                $exclude = preg_replace("/[^0-9,]/", "", $request->exclude);
                $exclude = explode(',', $exclude);
                $exclude = array_filter($exclude);
            }
        }

        // filter by meta keys
        if ($request->meta_key && $request->meta_value) {
            $hasMeta = true;
        }

        return Cache::remember($key, 300, function () use($title, $category_slugs, $category_ids, $tag_ids, $tag_slugs, $districts, $year, $month, $day, $exclude, $hasMeta, $per_page, $sort, $request) {
            return Post::published()
                ->with('pictures')
                ->with('seo')
                ->with('categories')
                ->with('tags')
                ->with('postMetas')
                ->with('socials')
                ->with('districts')
                ->when($title, function ($query, $title) {
                    return $query->where('title', 'like', '%' . $title. '%');
                })
                ->when($category_ids, function ($query, $category_ids) {
                    return $query->categories($category_ids);
                })
                ->when($category_slugs, function ($query, $category_slugs) {
                    return $query->taxonomies('categories', $category_slugs);
                })
                ->when($tag_ids, function ($query, $tag_ids) {
                    return $query->tags($tag_ids);
                })
                ->when($tag_slugs, function ($query, $tag_slugs) {
                    return $query->taxonomies('tags', $tag_slugs);
                })
                ->when($districts, function ($query, $districts) {
                    return $query->taxonomies('districts', $districts);
                })
                ->when($year, function ($query, $year) {
                    return $query->whereYear('created_at', '=', $year);
                })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('created_at', '=', $month);
                })
                ->when($day, function ($query, $day) {
                    return $query->whereDay('created_at', '=', $day);
                })
                ->when($exclude, function ($query, $exclude) {
                    return $query->whereNotIn('id', $exclude);
                })
                ->when($hasMeta, function ($query) use($request) {
                    return $query->hasMeta($request->meta_key, $request->meta_value);
                })
                ->orderBy('posts.created_at', $sort)
                ->paginate($per_page);
        });
    }

    public function getPost(Request $request){
        $slug = $id = null;
        if($request->slug) {
            $slug = strtolower(urlencode($request->slug));
            $key = 'post-slug-'.md5($request->slug);
        } elseif ($request->id) {
            $id = $request->id;
            $key = 'post-id-'.$request->id;
        } else {
            abort(404);
        }

        return Cache::remember($key, 300, function () use($slug, $id) {
            return Post::published()
                ->when($slug, function ($query, $slug) {
                    return $query->where('slug', '=', $slug);
                })
                ->when($id, function ($query, $id) {
                    return $query->where('id', $id);
                })
                ->with('pictures')
                ->with('seo')
                ->with('categories')
                ->with('tags')
                ->with('postMetas')
                ->with('socials')
                ->with('districts')
                ->firstOrFail();
        });
    }

    public function postsCat(Request $request) {
        /*return Category::where('id', 1)
            ->with('posts')
            ->get();*/
        $category = null;
        if($request->category) {
            $category = $request->category;
        }
        return Post::published()
            ->with('pictures')
            ->with('seo')
            ->with('tags')
            ->with('postMetas')
            ->with('socials')
            ->with('districts')
            ->with('categories')
            ->when($category, function ($query, $category) {
                return $query->taxonomies('categories', $category);
            })
            ->newest()
            ->paginate(10);
    }

    public function formatted()
    {
        $layout =  Layout::where(['status' => 1])->firstOrFail();
        $LayoutWidget =  LayoutWidget::where(['layout_id' => $layout->id, 'status' => 1])->OrderBy('id' , 'asc')->get();
        $layoutStructure = json_decode($layout->structure, true);
        self::pushWidget($layoutStructure, self::indexWidgets($LayoutWidget));
        $layout->structure = $layoutStructure;
        return $layout;
    }

    private static function indexWidgets($LayoutWidget) {
        $widgets = [];
        foreach ($LayoutWidget as $key => $widget) {
             $widget['widget_settings'] = json_decode($widget['widget_settings']);
            $widgets[$widget->widget_space_id] = $widget;
        }
        return $widgets;
    }

    private static function pushWidget(&$array, $widgets) {
        foreach($array as $key=>&$value)
        {
            if(is_array($value))
            {
                self::pushWidget($value, $widgets);
            }

            if($key == "section_id" && isset($widgets[$value]))
            {
                if(is_array($array)) {
                    $array['widget'] = $widgets[$value];
                }
            }
        }
    }
    public function catPosts($id){
        // $cat_post = Category::where('slug',$slug)
        // ->with(['posts' => function($query){
        //     $query->orderBy('created_at','DESC')->with('pictures')->paginate(2);
        // }])->get();

        $category = Category::where('id',$id)->first();

       $post = $category->posts()->orderBy('created_at','DESC')->paginate(10);

       $data =  PostResource::collection($post);

        // $data =  new PostCategoryResource($category);

        return $data->additional(['category' => new PostCategoryResource($category)]);
    }
    // New Achive start
    public function tagPosts($id){
    //    $tag = Tag::with(['posts' => function($query){
    //     $query->orderBy('created_at','DESC')->with('pictures');
    //    }])->orderBy('created_at','DESC')->get();
    //    return $tag;
       $tag = Tag::where('id', $id)->first();
       $post = $tag->posts()->orderBy('created_at','DESC')->paginate(2);
       $data = PostResource::collection($post);
       return $data->additional(['tag' => new PostTagResource($tag)]);
    }

    public function postTitles(Request $request) {
        if ($request->per_page) {
            $per_page = $request->per_page;
        } else {
            $per_page = 10;
        }

        return Post::published()
            ->select('id', 'title')
            ->orderBy('posts.created_at', 'DESC')
            ->paginate($per_page);

    }
    public function getTag(){
        $tag = Tag::orderBy('id' , 'desc')->get();
        $data['allTag'] = TagResource::Collection($tag);
        $tagActive =  Tag::filterByStatus()->latest()->get();
        $data['activeTag'] = TagResource::Collection($tagActive);

        return $data;
    }
}
