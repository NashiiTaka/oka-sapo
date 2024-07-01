<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW v_rakuten_genres AS
            SELECT
            me.genre_id my_genre_id,
            me.genre_name my_genre_name,
            me.genre_level my_genre_level,
            children_cnt.children_cnt,
            case
                when me.genre_level = 1 then me.genre_id
                when p1.genre_level = 1 then p1.genre_id
                when p2.genre_level = 1 then p2.genre_id
                when p3.genre_level = 1 then p3.genre_id
                when p4.genre_level = 1 then p4.genre_id
            end genre_level1_genre_id,
            case
                when me.genre_level = 1 then me.genre_name
                when p1.genre_level = 1 then p1.genre_name
                when p2.genre_level = 1 then p2.genre_name
                when p3.genre_level = 1 then p3.genre_name
                when p4.genre_level = 1 then p4.genre_name
            end genre_level1_genre_name,
            case
                when me.genre_level = 2 then me.genre_id
                when p1.genre_level = 2 then p1.genre_id
                when p2.genre_level = 2 then p2.genre_id
                when p3.genre_level = 2 then p3.genre_id
                when p4.genre_level = 2 then p4.genre_id
            end genre_level2_genre_id,
            case
                when me.genre_level = 2 then me.genre_name
                when p1.genre_level = 2 then p1.genre_name
                when p2.genre_level = 2 then p2.genre_name
                when p3.genre_level = 2 then p3.genre_name
                when p4.genre_level = 2 then p4.genre_name
            end genre_level2_genre_name,
            case
                when me.genre_level = 3 then me.genre_id
                when p1.genre_level = 3 then p1.genre_id
                when p2.genre_level = 3 then p2.genre_id
                when p3.genre_level = 3 then p3.genre_id
                when p4.genre_level = 3 then p4.genre_id
            end genre_level3_genre_id,
            case
                when me.genre_level = 3 then me.genre_name
                when p1.genre_level = 3 then p1.genre_name
                when p2.genre_level = 3 then p2.genre_name
                when p3.genre_level = 3 then p3.genre_name
                when p4.genre_level = 3 then p4.genre_name
            end genre_level3_genre_name,
            case
                when me.genre_level = 4 then me.genre_id
                when p1.genre_level = 4 then p1.genre_id
                when p2.genre_level = 4 then p2.genre_id
                when p3.genre_level = 4 then p3.genre_id
                when p4.genre_level = 4 then p4.genre_id
            end genre_level4_genre_id,
            case
                when me.genre_level = 4 then me.genre_name
                when p1.genre_level = 4 then p1.genre_name
                when p2.genre_level = 4 then p2.genre_name
                when p3.genre_level = 4 then p3.genre_name
                when p4.genre_level = 4 then p4.genre_name
            end genre_level4_genre_name,
            case
                when me.genre_level = 5 then me.genre_id
            end genre_level5_genre_id,
            case
                when me.genre_level = 5 then me.genre_name
            end genre_level5_genre_name
        FROM
            m_rakuten_genres me
            inner join (
                select parent.genre_id, count(children.genre_id) children_cnt
                from
                    m_rakuten_genres parent
                    left join m_rakuten_genres children ON parent.genre_id = children.parent_genre_id
                group by
                    parent.genre_id
            ) children_cnt ON me.genre_id = children_cnt.genre_id
            left join m_rakuten_genres p1 ON me.parent_genre_id = p1.genre_id
            left join m_rakuten_genres p2 ON p1.parent_genre_id = p2.genre_id
            left join m_rakuten_genres p3 ON p2.parent_genre_id = p3.genre_id
            left join m_rakuten_genres p4 ON p3.parent_genre_id = p4.genre_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_rakuten_genres");
    }
};
