<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Advertiser;
use Validator;
use App\Http\Controllers\ValidationsApi\V1\AdvertiserRequest;


class AdvertiserApi extends Controller{
	protected $selectColumns = [
		"id",
		"name",
		"email",
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
            	$Advertiser = Advertiser::select($this->selectColumns)->with($this->arrWith())->orderBy("id","desc")->paginate(15);
               return successResponseJson(["data"=>$Advertiser]);
            }


            /**
             * Store a newly created resource in storage. Api
             * @return \Illuminate\Http\Response
             */
    public function store(AdvertiserRequest $request)
    {
    	$data = $request->except("_token");

        $Advertiser = Advertiser::create($data);

		  $Advertiser = Advertiser::with($this->arrWith())->find($Advertiser->id,$this->selectColumns);
        return successResponseJson([
            "message"=>trans("api.added"),
            "data"=>$Advertiser
        ]);
    }


            /**
             * Display the specified resource.
             * @param  int  $id
             * @return \Illuminate\Http\Response
             */
            public function show($id)
            {
                $Advertiser = Advertiser::with($this->arrWith())->find($id,$this->selectColumns);
            	if(is_null($Advertiser) || empty($Advertiser)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}

                 return successResponseJson([
              "data"=> $Advertiser
              ]);  ;
            }


            /**

             * update a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function updateFillableColumns() {
				       $fillableCols = [];
				       foreach (array_keys((new AdvertiserRequest)->attributes()) as $fillableUpdate) {
  				        if (!is_null(request($fillableUpdate))) {
						  $fillableCols[$fillableUpdate] = request($fillableUpdate);
						}
				       }
  				     return $fillableCols;
  	     		}

            public function update(AdvertiserRequest $request,$id)
            {

            	$Advertiser = Advertiser::find($id);
            	if(is_null($Advertiser) || empty($Advertiser)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
  			       }

            	$data = $this->updateFillableColumns();

              Advertiser::where("id",$id)->update($data);

              $Advertiser = Advertiser::with($this->arrWith())->find($id,$this->selectColumns);
              return successResponseJson([
               "message"=>trans("api.updated"),
               "data"=> $Advertiser
               ]);
            }

            /**
             * destroy a newly created resource in storage.
             * @return \Illuminate\Http\Response
             */
            public function destroy($id)
            {
               $advertiser = Advertiser::find($id);
            	if(is_null($advertiser) || empty($advertiser)){
            	 return errorResponseJson([
            	  "message"=>trans("api.undefinedRecord")
            	 ]);
            	}




               $advertiser->delete();
               return successResponseJson([
                "message"=>trans("api.deleted")
               ]);
            }



 			public function multi_delete()
            {
                $data = request("selected_data");
                if(is_array($data)){
                    foreach($data as $id){
                    $advertiser = Advertiser::find($id);
	            	if(is_null($advertiser) || empty($advertiser)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}


                    	$advertiser->delete();
                    }
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }else {
                    $advertiser = Advertiser::find($data);
	            	if(is_null($advertiser) || empty($advertiser)){
	            	 return errorResponseJson([
	            	  "message"=>trans("api.undefinedRecord")
	            	 ]);
	            	}



                    $advertiser->delete();
                    return successResponseJson([
                     "message"=>trans("api.deleted")
                    ]);
                }
            }


}
