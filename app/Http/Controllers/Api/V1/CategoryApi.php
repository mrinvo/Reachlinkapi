<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Category;
use Validator;
use App\Http\Controllers\ValidationsApi\V1\CategoryRequest;


class CategoryApi extends Controller{
	protected $selectColumns = [
		"id",
		"name",
	];

            /**
             * Display the specified releationshop.
             * @return array to assign with index & show methods
             */
            public function arrWith(){
               return [];
            }


            /**
             * Display a listing of the resource. Api
             * @return \Illuminate\Http\Response
             */
            public function index()
            {
            	$Category = Category::select($this->selectColumns)->with($this->arrWith())->orderBy("id","desc")->paginate(15);
               return successResponseJson(["data"=>$Category]);
            }


            /**
             * Store a newly created resource in storage. Api
             * @return \Illuminate\Http\Response
             */
    public function store(CategoryRequest $request)
    {
    	$data = $request->except("_token");

        $Category = Category::create($data);

		  $Category = Category::with($this->arrWith())->find($Category->id,$this->selectColumns);
        return successResponseJson([
            "message"=>trans("api.added"),
            "data"=>$Category
        ]);
    }


            /**
             * Display the specified resource.
             * @param  int  $id
             * @return \Illuminate\Http\Response
             */
            public function show($id)
            {
                $Category = Category::with($this->arrWith())->find($id,$this->selectColumns);
            	if(is_null($Category) || empty($Category)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}

                 return successResponseJson([
              "data"=> $Category
              ]);  ;
            }


            /**
             * update a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function updateFillableColumns() {
				       $fillableCols = [];
				       foreach (array_keys((new CategoryRequest)->attributes()) as $fillableUpdate) {
  				        if (!is_null(request($fillableUpdate))) {
						  $fillableCols[$fillableUpdate] = request($fillableUpdate);
						}
				       }
  				     return $fillableCols;
  	     		}

            public function update(CategoryRequest $request,$id)
            {
            	$Category = Category::find($id);
            	if(is_null($Category) || empty($Category)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
  			       }

            	$data = $this->updateFillableColumns();

              Category::where("id",$id)->update($data);

              $Category = Category::with($this->arrWith())->find($id,$this->selectColumns);
              return successResponseJson([
               "message"=>trans("api.updated"),
               "data"=> $Category
               ]);
            }

            /**
             * destroy a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function destroy($id)
            {
               $category = Category::find($id);
            	if(is_null($category) || empty($category)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}




               $category->delete();
               return successResponseJson([
                "message"=>trans("api.deleted")
               ]);
            }



 			public function multi_delete()
            {
                $data = request("selected_data");
                if(is_array($data)){
                    foreach($data as $id){
                    $category = Category::find($id);
	            	if(is_null($category) || empty($category)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}

                    	$category->delete();
                    }
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }else {
                    $category = Category::find($data);
	            	if(is_null($category) || empty($category)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}


                    $category->delete();
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }
            }


}
