WITH source AS (
    SELECT
        me.genre_id my_genre_id,
        me.genre_name my_genre_name,
        me.genre_level my_genre_level,
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
        left join m_rakuten_genres p1 ON me.parent_genre_id = p1.genre_id
        left join m_rakuten_genres p2 ON p1.parent_genre_id = p2.genre_id
        left join m_rakuten_genres p3 ON p2.parent_genre_id = p3.genre_id
        left join m_rakuten_genres p4 ON p3.parent_genre_id = p4.genre_id
)
select
    genre_level1_genre_id,
    genre_level1_genre_name,
    count(*)
from
    source
group by
    genre_level1_genre_id,
    genre_level1_genre_name;

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
        select
            parent.genre_id,
            count(children.genre_id) children_cnt
        from
            m_rakuten_genres parent
            left join m_rakuten_genres children ON parent.genre_id = children.parent_genre_id
        group by
            parent.genre_id
    ) children_cnt ON me.genre_id = children_cnt.genre_id
    left join m_rakuten_genres p1 ON me.parent_genre_id = p1.genre_id
    left join m_rakuten_genres p2 ON p1.parent_genre_id = p2.genre_id
    left join m_rakuten_genres p3 ON p2.parent_genre_id = p3.genre_id
    left join m_rakuten_genres p4 ON p3.parent_genre_id = p4.genre_id;

-- 子ジャンルの数を取得
select
    parent.genre_id,
    count(children.genre_id) children_cnt
from
    m_rakuten_genres parent
    left join m_rakuten_genres children ON parent.genre_id = children.parent_genre_id
group by
    parent.genre_id;

-- ジャンルID 100939 化粧品系 のジャンル情報を取得
select
    v.genre_level1_genre_id,
    v.genre_level1_genre_name,
    v.genre_level2_genre_id,
    v.genre_level2_genre_name,
    v.genre_level3_genre_id,
    v.genre_level3_genre_name,
    count(*)
from
    v_rakuten_genres v
where
    v.genre_level1_genre_id = 100939
    and v.children_cnt = 0
group BY
    v.genre_level1_genre_id,
    v.genre_level1_genre_name,
    v.genre_level2_genre_id,
    v.genre_level2_genre_name,
    v.genre_level3_genre_id,
    v.genre_level3_genre_name
order by
    v.genre_level2_genre_id,
    v.genre_level3_genre_id;

-- level1 美容・コスメ・香水
select
    count(*)
from
    v_rakuten_genres
where
    genre_level1_genre_id = 100939
    and children_cnt = 0;

-- level2 ベースメイク・メイクアップ
select
    *
from
    v_rakuten_genres
where
    genre_level2_genre_id = 204233;

-- level3 口紅・リップスティック, リップライナー, リップグロス すべて小ジャンルは0
select
    *
from
    v_rakuten_genres
where
    genre_level3_genre_id in(216600, 503106, 216620);

select
    *
from
    t_valiations v
WHERE
    v.is_active = 1
order by
    -- ユークリッド距離
    sqrt(
        pow(v.r - 215, 2) + pow(v.g - 81, 2) + pow(v.g - 77, 2)
    ) asc;

SELECT
    LEAST(1, 5, 2, 3, 4);

select
    p.product_id,
    p.product_name,
    p.points,
    count(r.product_id) 楽天商品数
from
    t_products p
    left join t_rakuten_products r on
        r.item_name like
        REPLACE(
            concat('%', REPLACE(p.product_name, ' ', '%'), '%'),
            '　',
            '%'
        ) OR r.catchcopy like REPLACE(
            concat('%', REPLACE(p.product_name, ' ', '%'), '%'),
            '　',
            '%'
        ) or r.item_caption like REPLACE(
            concat('%', REPLACE(p.product_name, ' ', '%'), '%'),
            '　',
            '%'
        )
GROUP BY
    p.product_id,
    p.product_name,
    p.points
LIMIT
  1000
;

select * from t_rakuten_products where item_name like '%ディオール%アディクト%リップ%マキシマイザー%';