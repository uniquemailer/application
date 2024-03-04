<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LogService
{
    public function log(Request $request, array $keys, $transactionId, $service_id, User $user): void
    {
        DB::table('api_audits')->insert(
            [
                'user_id' => $user->id,
                'request' => $this->parse_data($request, $keys),
                'transaction_id' => $transactionId,
                'service_id' => $service_id,
                'created_at' => Carbon::now()->toDateTimeString(),
                ]
        );
    }

    private function parse_data(Request $request, array $keys): string
    {
        $data = $request->json()->all();
        if (is_array($data['data'])){
            foreach($data['data'] as $key => $value){
                if (in_array($key, $keys)){
                    $data['data'][$key] = str_repeat('*', strlen ($value));    
                }
            }
        }
        return json_encode($data);
    }
}