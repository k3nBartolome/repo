<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SiteController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 1 )
        ->where( 'country', 'Philippines' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    public function index2() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 0 )
        ->where( 'country', 'Philippines' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    public function index3() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 1 )
        ->where( 'country', 'India' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    public function index4() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 0 )
        ->where( 'country', 'India' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    public function index5() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 1 )
        ->where( 'country', 'Jamaica' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    public function index6() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 0 )
        ->where( 'country', 'Jamaica' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    public function index7() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 1 )
        ->where( 'country', 'Guatemala' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    public function index8() {
        $sites = Site::with( [ 'createdBy', 'updatedBy' ] )
        ->where( 'is_active', 0 )
        ->where( 'country', 'Guatemala' )
        ->get();

        return response()->json( [ 'data' => $sites ] );
    }

    /**
    * Store a newly created resource in storage.
    *
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'name' => 'required|unique:sites',
            'description' => 'required',
            'site_director' => 'required',
            'region' => 'required',
            'is_active' => Rule::in( [ '0', '1' ] ),
            'created_by' => 'nullable',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $site = new Site();
        $site->fill( $request->all() );
        $site->country = 'Philippines';
        $site->save();
        $site->site_id = $site->id;
        $site->save();

        return response()->json( [ 'data' => $site ] );
    }

    public function store2( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'name' => 'required|unique:sites',
            'description' => 'required',
            'site_director' => 'required',
            'region' => 'required',
            'is_active' => Rule::in( [ '0', '1' ] ),
            'created_by' => 'nullable',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $site = new Site();
        $site->fill( $request->all() );
        $site->country = 'India';
        $site->save();
        $site->site_id = $site->id;
        $site->save();

        return response()->json( [ 'data' => $site ] );
    }

    public function store3( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'name' => 'required|unique:sites',
            'description' => 'required',
            'site_director' => 'required',
            'region' => 'required',
            'is_active' => Rule::in( [ '0', '1' ] ),
            'created_by' => 'nullable',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $site = new Site();
        $site->fill( $request->all() );
        $site->country = 'Jamaica';
        $site->save();
        $site->site_id = $site->id;
        $site->save();

        return response()->json( [ 'data' => $site ] );
    }

    public function store4( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'name' => 'required|unique:sites',
            'description' => 'required',
            'site_director' => 'required',
            'region' => 'required',
            'is_active' => Rule::in( [ '0', '1' ] ),
            'created_by' => 'nullable',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $site = new Site();
        $site->fill( $request->all() );
        $site->country = 'Guatemala';
        $site->save();
        $site->site_id = $site->id;
        $site->save();
        return response()->json( [ 'data' => $site ] );
    }

    /**
    * Display the specified resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function show( $id ) {
        $site = Site::FindOrFail( $id );

        return response()->json( [ 'data' => $site ] );
    }

    /**
    * Update the specified resource in storage.
    *
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, $id ) {
        $validator = Validator::make( $request->all(), [
            'name' => 'sometimes|unique:sites,name,' . $id,
            'description' => 'sometimes',
            'site_director' => 'sometimes',
            'region' => 'sometimes',
            'updated_by' => 'nullable',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $site = Site::find( $id );
        if ( !$site ) {
            return response()->json( [ 'error' => 'Site not found' ], 404 );
        }

        $site->fill( $request->all() );
        $site->save();

        return response()->json( [ 'data' => $site ] );
    }

    /**
    * Remove the specified resource from storage.
    *
    * @return \Illuminate\Http\Response
    */

    public function destroy( $id ) {
        $site = Site::find( $id );

        if ( !$site ) {
            return response()->json( [ 'error' => 'Site not found' ], 404 );
        }

        $site->delete();

        return response()->json( null, 204 );
    }

    public function deactivate( Request $request, $id ) {
        $validator = Validator::make( $request->all(), [
            'is_Active' => 'sometimes',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $site = Site::find( $id );
        if ( !$site ) {
            return response()->json( [ 'error' => 'Site not found' ], 404 );
        }

        $site->fill( $request->all() );
        $site->save();

        return response()->json( [ 'data' => $site ] );
    }

    public function activate( Request $request, $id ) {
        $validator = Validator::make( $request->all(), [
            'is_Active' => 'sometimes',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $site = Site::find( $id );
        if ( !$site ) {
            return response()->json( [ 'error' => 'Site not found' ], 404 );
        }

        $site->fill( $request->all() );
        $site->save();

        return response()->json( [ 'data' => $site ] );
    }
}
