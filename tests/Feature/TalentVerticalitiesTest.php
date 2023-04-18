<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use App\Models\Talent;
use App\Models\Verticality;
use App\Models\TalentVerticality;

use function PHPUnit\Framework\assertTrue;

class TalentVerticalitiesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $talent=Talent::create([
                    'name'=>'testName',
                ]);

        $verticality=Verticality::create([
            'description' => 'influencer',
        ]);

        $talent_verticality=TalentVerticality::create([
            'talent_id' => $talent->id,
            'verticality_id' => $verticality->id,
        ]);

        $verticalities=$talent->verticalities();

        foreach($verticalities as $v){
            var_dump($v);
        }
        assertTrue($verticalities !=NULL);
    }
}
