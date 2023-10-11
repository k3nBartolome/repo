<!-- public function perxFilter(Request $request)
{
    $query = DB::connection('mysql')
        ->table('sites');

    if ($request->has('filter_name')) {
        $query->where('name', 'like', '%' . $request->input('filter_name') . '%');
    }

    if ($request->has('filter_region')) {
        $query->where('region', 'like', '%' . $request->input('filter_region')  . '%');
    }

    if ($request->has('filter_country')) {
        $query->where('country', 'like', '%' . $request->input('filter_country')  . '%');
    }

    $data = $query->get();

    return response()->json([
        'perx' => $data,
    ]);

 public function perxFilter(Request $request)
{
    $query = DB::connection('mysql')
        ->table('sites');

    if ($request->has('filter_name')) {
        $query->where('name', 'like', '%' . $request->input('filter_name') . '%');
    }

    if ($request->has('filter_region')) {
        $query->where('region', 'like', '%' . $request->input('filter_region')  . '%');
    }

    if ($request->has('filter_country')) {
        $query->where('country', 'like', '%' . $request->input('filter_country')  . '%');
    }

    $data = $query->get();

    return response()->json([
        'perx' => $data,
    ]);
}
 -->
 <template>
    <div>
        <h1>asd</h1>
    </div>
 </template>