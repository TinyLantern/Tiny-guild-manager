<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use App\Models\ActivityParticipant;
use App\Models\Character;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProcessDKP extends Command
{
    protected $signature = 'activities:process-dkp';
    protected $description = 'Process activities that have ended and distribute DKP points to participants';

    public function handle()
    {
        Log::info('Processing DKP for completed activities...');

        $activities = Activity::where('end_time', '<=', Carbon::now())
            ->where('processed', false) // Only process unprocessed activities
            ->get();

        foreach ($activities as $activity) {
            DB::beginTransaction();
            try {
                $participants = ActivityParticipant::where('activity_id', $activity->id)
                    ->where('activity_participation_status_id', 1)
                    ->get();

                foreach ($participants as $participant) {
                    $character = Character::find($participant->character_id);
                    if ($character) {
                        $character->dkp_points += $activity->dkp;
                        $character->save();
                    }
                }

                $activity->processed = true;
                $activity->save();

                Log::info("DKP awarded for Activity ID: {$activity->id}");
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error processing DKP for Activity ID: {$activity->id} - " . $e->getMessage());
            }
        }

        Log::info('DKP processing completed.');
        return Command::SUCCESS;
    }
}