<?php


if (!function_exists('redirectWithSuccess')) {
	function redirectWithSuccess($url, $msg = null) {
		!empty($msg) ? session()->flash('success', $msg) : '';
		if (request()->ajax() or request()->wantsJson()) {
			return successResponseJson([
				'message' => $msg,
			]);
		} else {
			return redirect($url);
		}
	}
}

if (!function_exists('redirectWithError')) {
	function redirectWithError($url, $msg = null) {
		if (request()->ajax() || !request()->ajax()) {
			!empty($msg) && !is_null($msg) ? session()->flash('error', $msg) : '';
		}

		if (request()->ajax() or request()->wantsJson()) {
			return errorResponseJson([
				'message' => $msg,
			]);
		} else {
			return redirect($url);
		}
	}
}

if (!function_exists('backWithSuccess')) {
	function backWithSuccess($msg = null, $url = null) {
		if (request()->ajax() || !request()->ajax()) {
			!empty($msg) && !is_null($msg) ? session()->flash('error', $msg) : '';
		}

		if (request()->ajax() or request()->wantsJson()) {
			return successResponseJson([
				'message' => $msg,
			]);
		} else {
			if (!empty($url)) {
				return redirect($url);
			} else {
				return back();
			}
		}
	}
}

if (!function_exists('backWithError')) {
	function backWithError($msg = null, $url = null) {
		if (request()->ajax() || !request()->ajax()) {
			!empty($msg) && !is_null($msg) ? session()->flash('error', $msg) : '';
		}

		if (request()->ajax() or request()->wantsJson()) {
			return errorResponseJson([
				'message' => $msg,
			]);
		} else {
			if (!empty($url)) {
				return redirect($url);
			} else {
				return back();
			}
		}
	}
}

if (!function_exists('errorResponse')) {
	function errorResponseJson(array $data, $status = 422) {
		$data['status'] = false;
		$data['StatusCode'] = $status;
		$data['StatusType'] = 'Unprocessable Entity';
		$data['explainError'] = 'The request was well-formed but was unable to be followed due to semantic errors.';
		if (!isset($data['message'])) {
			$data['message'] = trans("admin.undefinedRecord");
		}
		return response()->json($data, $status);

	}
}

if (!function_exists('successResponseJson')) {
	function successResponseJson(array $data) {
		$data['status'] = true;
		$data['StatusCode'] = 200;
		$data['StatusType'] = 'OK';
		return response()->json($data, 200);

	}
}

