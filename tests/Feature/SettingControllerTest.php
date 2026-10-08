<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class SettingControllerTest
 *
 * Verifies updating system preferences and session persistence.
 */
class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test saving system configuration preferences.
     */
    public function test_can_update_settings(): void
    {
        $response = $this->post(route('settings.update'), [
            'admin_name'          => 'Jane Operations Lead',
            'admin_email'         => 'jane.lead@warehouse.local',
            'currency'            => 'EUR (€)',
            'low_stock_threshold' => 15,
        ]);

        $response->assertRedirect(route('settings.index'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('admin_name', 'Jane Operations Lead');
        $response->assertSessionHas('admin_email', 'jane.lead@warehouse.local');
        $response->assertSessionHas('inventory_currency', 'EUR (€)');
        $response->assertSessionHas('inventory_low_stock_threshold', 15);
    }
}
