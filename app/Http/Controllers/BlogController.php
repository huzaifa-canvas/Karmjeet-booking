<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();
        return view('modules.admin.blog.list', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit = false;
        return view('modules.admin.blog.forms', compact('isEdit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['sometimes', 'string'],
            'image' => 'mimes:jpeg,png,jpg'
        ]);

        try {

            $slug                   = slugGenerator($request->title, Blog::class, 'slug');
            $request['slug']        = $slug;
            $request['image_url']   = 'assets/images/no-preview.png';


            if (!empty($request->file('image'))) {

                $path = $request->file('image')->store('public/blog');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = $attachment_url;
            }

            Blog::create($request->except('_token', 'image'));
            return redirect()->route('blog-list')->with(['status' => 'success', 'message' => "Blog add successfully"]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.blog.forms', compact('id', 'blog', 'isEdit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\blog  blog
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $isEdit = true;
        return view('modules.admin.blog.forms', compact('id', 'blog', 'isEdit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['sometimes', 'string'],
            'image' => 'mimes:jpeg,png,jpg'

        ]);

        $blog = Blog::findOrFail($id);

        try {

            if(!$blog->image_url) $request['image_url']   = 'assets/images/no-preview.png';

            if (!empty($request->file('image'))) {
                $old_url = str_replace("storage", "public", "/" . $blog->image_url);
                Storage::delete($old_url);

                $path = $request->file('image')->store('public/blog');
                $attachment_url = str_replace("public", "storage", "/" . $path);
                $request['image_url']  = $attachment_url;
            }

            Blog::where('id', $id)->update($request->except('_token', 'image'));
            return redirect()->route('blog-list')->with(['status' => 'success', 'message' => "Blog update successfully"]);

        } catch (\Exception $e) {
            return redirect()->route('blog-list')->with(['status' => 'failed', 'message' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        $old_url = str_replace("storage", "public", "/" . $blog->image_url);
        Storage::delete($old_url);

        Blog::where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => "Blog Delete successfully"]);
    }
}
