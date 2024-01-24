<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\HasJsonResponse;
class BlogController extends Controller
{
    use HasJsonResponse;
    
    public function index()
    {
       $blogs = Blog::all();
       return $this->jsonResponse(true, 200, "Blog fetched successfully", ['blogs' => $blogs], false, false);

    }

    public function store(Request $request)
    {
        try{
            $data['title']      = $request['title'];
            $data['content']    = $request['content'];
            $data['author']     = auth()->user()->id;

            $blog = Blog::create($data);
            $user = auth()->user();
            $responseData = [
                'blog' => $blog,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ];
            return $this->jsonResponse(true, 200, "Blog created successfully", [ $responseData], false, false);

        }catch(\Exception $e){
            $log =  $e->getMessage();
            return $this->jsonResponse(false, 500, "Sorry we could not create the request at this time. Try again later", ['error' => $log], false, false);
        }
    }

    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        try{




            $blog = Blog::with('user')->where('id',$id)->first();

            if(!$blog){
                return $this->jsonResponse(true, 200, "Blog not found", false, false, false);
            }
            $blog->title    = $request['title'];
            $blog->content  = $request['content'];
            $blog->update();

            return $this->jsonResponse(true, 200, "Blog updated successfully", ['blog' => $blog], false, false);

        }catch(\Exception $e){
            $log =  $e->getMessage();
            return $this->jsonResponse(false, 500, "Sorry we could not create the request at this time. Try again later", ['error' => $log], false, false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $blog = Blog::find($id);
            if(!$blog){
                return $this->jsonResponse(true, 200, "Blog not found", false, false, false);
            }
            $delete_blog = $blog->delete();
            return $this->jsonResponse(true, 200, "Blog deleted successfully", false, false, false);
        }catch(\Exception $e){
            $log =  $e->getMessage();
            return $this->jsonResponse(false, 500, "Sorry we could not create the request at this time. Try again later", ['error' => $log], false, false);
        }
    }
}
