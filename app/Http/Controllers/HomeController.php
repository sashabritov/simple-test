<?php

namespace App\Http\Controllers;

use App\Helpers\MyOptions;
use App\Model\Category;
use App\Model\Comment;
use Illuminate\Http\Request;
use App\Model\Post;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use LaravelLocalization;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Set and return current locale
     *
     * @param  string $locale	        Locale to set the App to (optional)
     *
     * @return string 			Returns locale (if route has any) or null (if route does not have a locale)
     */
    public function index()
    {
        $posts = Post::paginate(5);
        $categories = Category::all();
        return view('content.home', ['posts' => $posts, 'categories' => $categories]);
    }

    public function about()
    {
        $data = [];
        $data['about_image'] = asset(MyOptions::getOption('about_image'));
        $data['about_text'] = MyOptions::getOption('about_text');
        $data['about_title'] = MyOptions::getOption('about_title');
        //dd($data);
        return view('content.about', ['data' => $data]);
    }

    public function feedback()
    {
        return view('content.feedback');
    }

    public function changeLang($lang)
    {
        LaravelLocalization::setLocale($lang);
        return Redirect::to(LaravelLocalization::getLocalizedURL($lang, \URL::previous()));
    }

    public function getPost($post)
    {
        $p_data = Post::find($post);
        $comments = Comment::where('id_post', $post)->get();
        $categories = Category::all();
        return view('content.onepost', ['post' => $p_data, 'comments' => $comments, 'categories' => $categories]);
    }

    public function getCategory($category_id)
    {
        $category = Category::find($category_id);
        $posts = $category->posts()->paginate(5);
        $categories = Category::all();
        return view('content.category', ['category' => $category, 'posts' => $posts, 'categories' => $categories]);
    }
}
