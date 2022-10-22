<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Http\Controllers\ValidationsApi\V1\AdsRequest;
use App\Http\Controllers\ValidationsApi\V1\AdsFilterRequest;


use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class AdApi extends Controller
{
    protected $selectColumns = [
		"id",
		"title",
        "description",
        "type",
        "category_id",
        'advertiser_id',
        'start_date',

	];

            /**
             * Display the specified releationshop.
             * @return array to assign with index & show methods
             */
            public function arrWith(){
               return ['tags'];
            }


            /**
             * Display a listing of the resource. Api
             * @return \Illuminate\Http\Response
             */
            public function index()
            {
                if(request('category_id')){
                    $AdSelect = Ad::select($this->selectColumns)->where('category_id',request('category_id'));

                }else{
                    $AdSelect = Ad::select($this->selectColumns);
                }

            	$Ad = $AdSelect->with($this->arrWith())->orderBy("id","desc")->paginate(15);
               return successResponseJson(["data"=>$Ad]);
            }


            public function AdFilters(AdsFilterRequest $request)
            {


               $AdSelect = Ad::with($this->arrWith())->select($this->selectColumns , function($query) use ($request){
                return $query->where('advertiser_id',$request->advertiser_id);
               })
               ->when($request->category_id , function($query) use($request){
                return $query->where('category_id',$request->category_id);
               })
               ->when($request->tags, function($query) use ($request){

                return $query->whereHas('tags',function($query)use ($request){


                      foreach ($request->tags as $key => $value) {
                         return $query->where('tag_id',$value);
                      }
                });
               })
               ->latest()
               ->get();

               return successResponseJson(["data"=>$AdSelect]);
            }




            /**
             * Store a newly created resource in storage. Api
             * @return \Illuminate\Http\Response
             */
    public function store(AdsRequest $request)
    {
    	$data = $request->except('tags');
        $tags = $request->tags;

         $Ad = Ad::create($data);
         $Ad->tags()->attach($tags);

		  $Ad = Ad::with($this->arrWith())->find($Ad->id,$this->selectColumns);
        return successResponseJson([
            "message"=>trans("api.added"),
            "data"=>$Ad
        ]);
    }


            /**
             * Display the specified resource.
             * @param  int  $id
             * @return \Illuminate\Http\Response
             */
            public function show($id)
            {
                $Ad = Ad::with($this->arrWith())->find($id,$this->selectColumns);
            	if(is_null($Ad) || empty($Ad)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}

                 return successResponseJson([
              "data"=> $Ad
              ]);  ;
            }


            /**
             * update a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function updateFillableColumns() {
				       $fillableCols = [];
				       foreach (array_keys((new AdsRequest)->attributes()) as $fillableUpdate) {
  				        if (!is_null(request($fillableUpdate))) {
						  $fillableCols[$fillableUpdate] = request($fillableUpdate);
						}
				       }
  				     return $fillableCols;
  	     		}

            public function update(AdsRequest $request,$id)
            {
            	$Ad = Ad::find($id);
            	if(is_null($Ad) || empty($Ad)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
  			       }

            	$data = $this->updateFillableColumns();

              Ad::where("id",$id)->update($data);

              $Ad = Ad::with($this->arrWith())->find($id,$this->selectColumns);
              return successResponseJson([
               "message"=>trans("api.updated"),
               "data"=> $Ad
               ]);
            }

            /**
             * destroy a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function destroy($id)
            {
               $Ad = Ad::find($id);
            	if(is_null($Ad) || empty($Ad)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}



               $Ad->delete();
               return successResponseJson([
                "message"=>trans("api.deleted")
               ]);
            }



 			public function multi_delete()
            {
                $data = request("selected_data");
                if(is_array($data)){
                    foreach($data as $id){
                    $Ad = Ad::find($id);
	            	if(is_null($Ad) || empty($Ad)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}

                    	$Ad->delete();
                    }
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }else {
                    $Ad = Ad::find($data);
	            	if(is_null($Ad) || empty($Ad)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}


                    $Ad->delete();
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }
            }

}
