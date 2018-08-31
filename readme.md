
******* Create model for content

php artisan make:model Article -m


_________________________________


CREATE TABLE articles(
 id int not null PRIMARY KEY AUTO_INCREMENT,
 title varchar(100) not null,
 body varchar(1000) not null,
 created_at timestamp,
 updated_at timestamp
)


******* Create users table with api token

CREATE TABLE users(
 id int not null PRIMARY KEY AUTO_INCREMENT,
 name varchar(100) not null,
 email varchar(1000) not null,
 api_token varchar(100) not null,
 created_at timestamp,
 updated_at timestamp
)



_________________________________


php artisan make:controller ArticleController


_________________________________


******* Createcontent controller

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
        $article = $article->update($request->all());

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
        $article->delete();

        return 204;
    }


}






_________________________________





The routes/api.php file:

Route::get('articles', 'ArticleController@index');
Route::get('articles/{id}', 'ArticleController@show');
Route::post('articles', 'ArticleController@store');
Route::put('articles/{id}', 'ArticleController@update');
Route::delete('articles/{id}', 'ArticleController@delete');




_________________________________




API Created !! Check by :

http://localhost/api_all_in_one/public/api/articles
http://localhost/api_all_in_one/public/api/articles/1




________________________________



Mobile & web user management :


1. after login or register a new token set in users table
2. after logout set token null
3. everytime on request api check token exists in our database.
4. everytime send a post or get api request with token



UserManagement controller :



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Session;

class UserManagement extends Controller
{
    public function register(Request $request)
	{
	    $user = new user;
	    $user->name = "random name";
	    $user->email = "random email";
	    $user->api_token = str_random(60);
        $user->save();   
	    
	    if(count($user) == 0){
	       $feedback = [
	           'status'     => "error",
	           'message'    => "register error"
	        ]; 
	       
	    }else{
	        $feedback = [
	           'status'     => "success",
	           'message'    => "register successfully",
	           'token'		=>  $user->api_token
	        ]; 
	    }
	    
	   return $feedback;
	}



	public function login(Request $request)
	{
		$login = user::where([
                    ['name', '=', $request->name],
                 ])->first();

        if($login == null){


	       $feedback = [
	           'status'     => "error",
	           'message'    => "auth error"
	        ]; 
	       
	    }else{

	    	$user = user::find($login->id);
	    	$user->api_token = str_random(60);
        	$user->save();
        	session(['token' => $user->api_token]);

	        $feedback = [
	           'status'     => "success",
	           'message'    => "login successfully",
	           'token'		=>  $user->api_token
	        ]; 

	        
	    }

	    return $feedback;
	}


	public function logout(Request $request)
	{
		$user = user::find($request->id);
	    $user->api_token = "";
        $user->save();

	    $feedback = [
           'status'     => "success",
           'message'    => "logout successfully"
        ]; 

        return $feedback;
	}

}

_______________________________________________


API security :



****  create middleware & check token exits in our database :



namespace App\Http\Middleware;

use Closure;
use Session;
use App\User;

class TokenApi
{
    public function handle($request, Closure $next)
    {
        $DatabaseToken = User::where('api_token',$request->token)->first();

        if($DatabaseToken != null) {
            return $next($request);  // if exist proceed to next step
        } else {
            return redirect('/');
        }
    }
}




**** set middleware in karnel.php :

protected $routeMiddleware = [
        'TokenApi' => \App\Http\Middleware\TokenApi::class
    ];


**** route check :


Route::group(['middleware'=>'TokenApi'], function () {
	Route::post('articles_security_check', 'ArticleController@index');
});
