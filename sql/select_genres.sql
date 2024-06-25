WITH
    source AS (
        SELECT
            me.id my_id,
            me.name my_name,
            me.depth my_depth,
            case
                when me.depth = 1 then me.id
                when p1.depth = 1 then p1.id
                when p2.depth = 1 then p2.id
                when p3.depth = 1 then p3.id
                when p4.depth = 1 then p4.id
            end depth1_id,
            case
                when me.depth = 1 then me.name
                when p1.depth = 1 then p1.name
                when p2.depth = 1 then p2.name
                when p3.depth = 1 then p3.name
                when p4.depth = 1 then p4.name
            end depth1_name,
            case
                when me.depth = 2 then me.id
                when p1.depth = 2 then p1.id
                when p2.depth = 2 then p2.id
                when p3.depth = 2 then p3.id
                when p4.depth = 2 then p4.id
            end depth2_id,
            case
                when me.depth = 2 then me.name
                when p1.depth = 2 then p1.name
                when p2.depth = 2 then p2.name
                when p3.depth = 2 then p3.name
                when p4.depth = 2 then p4.name
            end depth2_name,
            case
                when me.depth = 3 then me.id
                when p1.depth = 3 then p1.id
                when p2.depth = 3 then p2.id
                when p3.depth = 3 then p3.id
                when p4.depth = 3 then p4.id
            end depth3_id,
            case
                when me.depth = 3 then me.name
                when p1.depth = 3 then p1.name
                when p2.depth = 3 then p2.name
                when p3.depth = 3 then p3.name
                when p4.depth = 3 then p4.name
            end depth3_name,
            case
                when me.depth = 4 then me.id
                when p1.depth = 4 then p1.id
                when p2.depth = 4 then p2.id
                when p3.depth = 4 then p3.id
                when p4.depth = 4 then p4.id
            end depth4_id,
            case
                when me.depth = 4 then me.name
                when p1.depth = 4 then p1.name
                when p2.depth = 4 then p2.name
                when p3.depth = 4 then p3.name
                when p4.depth = 4 then p4.name
            end depth4_name,
            case
                when me.depth = 5 then me.id
            end depth5_id,
            case
                when me.depth = 5 then me.name
            end depth5_name
        FROM
            m_rakuten_genres me
            left join m_rakuten_genres p1 ON me.parent_id = p1.id
            left join m_rakuten_genres p2 ON p1.parent_id = p2.id
            left join m_rakuten_genres p3 ON p2.parent_id = p3.id
            left join m_rakuten_genres p4 ON p3.parent_id = p4.id
    )
select depth1_id, depth1_name, count(*)
from source
group by
    depth1_id,
    depth1_name;