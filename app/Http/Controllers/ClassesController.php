<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Sla_reason;
use App\Http\Resources\ClassesResource;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller {

    public function index() {

        return ClassesResource::collection(Classes::all());
    }

    public function show( Classes $class ) {
        $class->load( 'sla_reason' );
        return new ClassesResource( $class );
    }

    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'required',
            'program_id' => 'required',
            'site_id' => 'required',
            'created_by' => 'required',
            'is_active' => 'required',
            'weeks_start'=>'required',
            'backfill'=>'required',
            'growth'=>'required',
            'reason' => 'nullable'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error' => $validator->errors() ], 400 );
        }

        $class = Classes::create( $request->all() );

        $slaReason = new Sla_reason( [ 'reason' => $request->input( 'reason' ) ] );
        $slaReason->class_id = $class->id;
        $slaReason->save();

        return new ClassesResource( $class );
    }

    public function update( Request $request, Classes $class ) {
        $validatedData = $request->validate( [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'required',
            'program_id' => 'required',
            'site_id' => 'required',
            'created_by' => 'required',
            'is_active' => 'required',
            'reason'    =>'required'
        ] );

        if ( $validatedData[ 'name' ] ) {
            $class->name = $validatedData[ 'name' ];
        }

        if ( $validatedData[ 'description' ] ) {
            $class->description = $validatedData[ 'description' ];
        }

        $class->save();

        if ( $validatedData[ 'reason' ] ) {
            $class->site_id = $validatedData[ 'site_id' ];
            $class->program_id = $validatedData[ 'program_id' ];
            $class->type_of_hiring = $validatedData[ 'type_of_hiring' ];
            $class->external_target = $validatedData[ 'external_target' ];
            $class->internal_target = $validatedData[ 'internal_target' ];
            $class->total_target = $validatedData[ 'total_target' ];
            $class->original_start_date = $validatedData[ 'original_start_date' ];
            $class->wfm_requested_date = $validatedData[ 'wfm_requested_date' ];
            $class->notice_days = $validatedData[ 'notice_days' ];
            $class->notice_weeks = $validatedData[ 'notice_weeks' ];
            $class->with_erf = $validatedData[ 'with_erf' ];
            $class->category = $validatedData[ 'category' ];
            $class->within_sla = $validatedData[ 'within_sla' ];
            $class->approved_status = $validatedData[ 'approved_status' ];
            $class->created_by = $validatedData[ 'updated_by' ];
            $class->is_active = $validatedData[ 'is_active' ];
        }

        return new ClassesResource( $class );
    }

    public function destroy( Classes $class ) {
        $class->delete();

        return response( null, 204 );
    }
}

?>
