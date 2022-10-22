<?php
namespace App\Http\Controllers\ValidationsApi\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdsRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array (onCreate,onUpdate,rules) methods
	 */
	protected function onCreate() {
		return [

             'title'=>'required|string|max:50',
			 'description'=>'required|string|max:150',
			 'type' => 'required|in:free,paid',
			 'category_id' => 'required|exists:categories,id',
			 'advertiser_id' => 'required|exists:advertisers,id',
			 'start_date' => 'required|date|after_or_equal:today',
             'tags'=>'exists:tags,id'

		];
	}


	protected function onUpdate() {
		return [
            'title'=>'required|string|max:50',
            'description'=>'required|string|max:150',
            'type' => 'required|in:free,paid',
            'category_id' => 'required|exists:categories,id',
            'advertiser_id' => 'required|exists:advertisers,id',
            'start_date' => 'required|date|after_or_equal:today',
            'tags'=>'exists:tags,id'
		];
	}

	public function rules() {
		return request()->isMethod('put') || request()->isMethod('patch') ?
		$this->onUpdate() : $this->onCreate();
	}


	/**
	 * Get the validation attributes that apply to the request.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
             'name'=>trans('api.name'),
		];
	}

	/**
	 * response redirect if fails or failed request
	 *
	 * @return redirect
	 */
	public function response(array $errors) {
		return $this->ajax() || $this->wantsJson() ?
		response([
			'status' => false,
			'StatusCode' => 422,
			'StatusType' => 'Unprocessable',
			'errors' => $errors,
		], 422) :
		back()->withErrors($errors)->withInput(); // Redirect back
	}



}
