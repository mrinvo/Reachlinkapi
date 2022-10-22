<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tag;
use Validator;
use App\Http\Controllers\ValidationsApi\V1\TagRequest;
// Auto Controller Maker By Baboon Script

class TagApi extends Controller{
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
            	$Tag = Tag::select($this->selectColumns)->with($this->arrWith())->orderBy("id","desc")->paginate(15);
               return successResponseJson(["data"=>$Tag]);
            }


            /**
             * Store a newly created resource in storage. Api
             * @return \Illuminate\Http\Response
             */
    public function store(TagRequest $request)
    {
    	$data = $request->except("_token");

        $Tag = Tag::create($data);

		  $Tag = Tag::with($this->arrWith())->find($Tag->id,$this->selectColumns);
        return successResponseJson([
            "message"=>trans("api.added"),
            "data"=>$Tag
        ]);
    }


            /**
             * Display the specified resource.
             * @param  int  $id
             * @return \Illuminate\Http\Response
             */
            public function show($id)
            {
                $Tag = Tag::with($this->arrWith())->find($id,$this->selectColumns);
            	if(is_null($Tag) || empty($Tag)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}

                 return successResponseJson([
              "data"=> $Tag
              ]);  ;
            }


            /**
             * update a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function updateFillableColumns() {
				       $fillableCols = [];
				       foreach (array_keys((new TagRequest)->attributes()) as $fillableUpdate) {
  				        if (!is_null(request($fillableUpdate))) {
						  $fillableCols[$fillableUpdate] = request($fillableUpdate);
						}
				       }
  				     return $fillableCols;
  	     		}

            public function update(TagRequest $request,$id)
            {
            	$Tag = Tag::find($id);
            	if(is_null($Tag) || empty($Tag)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
  			       }

            	$data = $this->updateFillableColumns();

              Tag::where("id",$id)->update($data);

              $Tag = Tag::with($this->arrWith())->find($id,$this->selectColumns);
              return successResponseJson([
               "message"=>trans("api.updated"),
               "data"=> $Tag
               ]);
            }

            /**
             * destroy a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function destroy($id)
            {
               $tag = Tag::find($id);
            	if(is_null($tag) || empty($tag)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}



               $tag->delete();
               return successResponseJson([
                "message"=>trans("api.deleted")
               ]);
            }



 			public function multi_delete()
            {
                $data = request("selected_data");
                if(is_array($data)){
                    foreach($data as $id){
                    $tag = Tag::find($id);
	            	if(is_null($tag) || empty($tag)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}

                    	$tag->delete();
                    }
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }else {
                    $tag = Tag::find($data);
	            	if(is_null($tag) || empty($tag)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}


                    $tag->delete();
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }
            }


}
