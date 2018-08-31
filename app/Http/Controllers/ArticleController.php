<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Article;
 
class ArticleController extends Controller
{
    public function index()
    {
        $Article = Article::all();
        if(count($Article) == 0){
           $feedback = [
               'status'     => "error",
               'message'    => "data not found",
               'data'       => null
            ]; 

        }else{
            $feedback = [
               'status'     => "success",
               'message'    => "data found",
               'data'       => $Article
            ]; 
        }

		return $feedback;
    }
 



    public function show($id)
    {
        $Article = Article::find($id);
        if(count($Article) == 0){
           $feedback = [
               'status'     => "error",
               'message'    => "data not found",
               'data'       => null
            ]; 

        }else{
            $feedback = [
               'status'     => "success",
               'message'    => "data found",
               'data'       => $Article
            ]; 
        }

		return $feedback;
    }




    public function store(Request $request)
    {
        $Article = Article::create($request->all());
        if(count($Article) == 0){
		       $feedback = [
		           'status'     => "error",
		           'message'    => "insert error"
		        ]; 
		       
		    }else{
		        $feedback = [
		           'status'     => "success",
		           'message'    => "inserted successfully"
		        ]; 
		    }
		    
		return $feedback;
    }




    public function update(Request $request, Article $article)
    {
        $article = $article->find($request->id);

		if(count($article) == 0){
		       $feedback = [
		           'status'     => "error",
		           'message'    => "updated error"
		        ]; 
		       
		    }else{
		        $feedback = [
		           'status'     => "success",
		           'message'    => "updated successfully"
		        ]; 
		    }
		    
		return $feedback;
    }



    public function delete(Article $article)
    {
        $article->find($request->id);

        return 204;
    }


}