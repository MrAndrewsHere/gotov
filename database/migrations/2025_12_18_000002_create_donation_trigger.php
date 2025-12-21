<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            CREATE OR REPLACE FUNCTION update_charity_project_donation_amount()
            RETURNS TRIGGER AS $$
            BEGIN
                IF TG_OP = \'INSERT\' THEN
                    UPDATE charity_projects
                    SET donation_amount = donation_amount + NEW.amount
                    WHERE id = NEW.charity_project_id;
                    RETURN NEW;
                ELSIF TG_OP = \'UPDATE\' THEN
                    UPDATE charity_projects
                    SET donation_amount = donation_amount - OLD.amount + NEW.amount
                    WHERE id = NEW.charity_project_id;
                    RETURN NEW;
                ELSIF TG_OP = \'DELETE\' THEN
                    UPDATE charity_projects
                    SET donation_amount = donation_amount - OLD.amount
                    WHERE id = OLD.charity_project_id;
                    RETURN OLD;
                END IF;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // INSERT
        DB::statement('DROP TRIGGER IF EXISTS trigger_donations_insert ON donations');
        DB::statement('
            CREATE TRIGGER trigger_donations_insert
            AFTER INSERT ON donations
            FOR EACH ROW
            EXECUTE FUNCTION update_charity_project_donation_amount()
        ');

        // UPDATE
        DB::statement('DROP TRIGGER IF EXISTS trigger_donations_update ON donations');
        DB::statement('
            CREATE TRIGGER trigger_donations_update
            AFTER UPDATE ON donations
            FOR EACH ROW
            EXECUTE FUNCTION update_charity_project_donation_amount()
        ');

        // DELETE
        DB::statement('DROP TRIGGER IF EXISTS trigger_donations_delete ON donations');
        DB::statement('
            CREATE TRIGGER trigger_donations_delete
            AFTER DELETE ON donations
            FOR EACH ROW
            EXECUTE FUNCTION update_charity_project_donation_amount()
        ');
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trigger_donations_delete ON donations;');
        DB::statement('DROP TRIGGER IF EXISTS trigger_donations_update ON donations;');
        DB::statement('DROP TRIGGER IF EXISTS trigger_donations_insert ON donations;');
        DB::statement('DROP FUNCTION IF EXISTS update_charity_project_donation_amount();');
    }
};
