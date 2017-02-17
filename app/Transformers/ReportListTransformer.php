<?php

namespace App\Transformers;

use App\Report;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class ReportListTransformer extends TransformerAbstract
{
    public function transform(Report $report)
    {
        return [
           'id' => (int)$report->id,
           'sender' => "{$report->sender->firstname} {$report->sender->lastname}",
           'sender_id' => (int)$report->sender_id,
           'recipient' => "{$report->recipient->firstname} {$report->recipient->lastname}",
           'recipient_id' => (int)$report->recipient_id,
           'message' =>  $report->message,
           'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $report->created_at)->format('M d, Y')
	    ];
    }

}