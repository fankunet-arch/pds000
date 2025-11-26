<?php
// lang_cn.php

return [
    // General
    'pds_title' => 'PDS - 研发与备料系统',
    'dashboard' => '仪表盘',
    'save' => '保存',
    'submit' => '提交',
    'actions' => '操作',
    'select_one' => '-- 请选择 --',
    'error_all_fields_required' => '所有字段均为必填项。',
    'db_connection_unavailable' => '数据库连接不可用。',

    // Navigation
    'nav_create_item' => '创建新物品',
    'nav_create_recipe' => '创建新配方',
    'nav_traceability' => '追溯与BOM查询',

    // Item Management
    'item_management' => '物品管理',
    'create_new_item' => '创建新物品',
    'item_code' => '物品编码',
    'item_name' => '物品名称',
    'base_unit' => '基本单位 (例如: g, ml, pcs)',
    'item_type' => '物品类型 (至少选一个)',
    'error_item_type_required' => '必须选择至少一个物品类型。',
    'success_item_created' => "物品 '%s' 创建成功，ID为：%d。",
    'error_item_creation_failed' => '物品创建失败：%s',

    // Recipe Management
    'recipe_management' => '配方管理',
    'create_new_recipe' => '创建新配方',
    'recipe_header' => '配方头部信息',
    'product_target_item' => '产出品 (目标物品)',
    'process_location' => '工序位置',
    'yield_quantity' => '标准产出量',
    'yield_unit' => '产出单位',
    'instructions' => '操作说明',
    'ingredients_bom' => '配料 (BOM)',
    'add_ingredient' => '添加配料',
    'remove' => '移除',
    'select_material' => '-- 选择原料 --',
    'usage_qty' => '使用数量',
    'is_consumable' => '消耗品',
    'error_at_least_one_ingredient' => '至少需要一个有效的配料。',
    'success_recipe_created' => '配方创建成功，ID为：%d。',
    'error_recipe_creation_failed' => '配方创建失败：%s',

    // Traceability
    'traceability_demo' => '追溯查询',
    'bom_explosion' => 'BOM 展开 (正向追溯)',
    'impact_analysis' => '影响分析 (反向追溯)',
    'select_product' => '选择一个产品:',
    'select_material_to_trace' => '选择一个原料:',
    'raw_materials_for' => "1 单位 “%s” 所需的原材料",
    'no_raw_materials_found' => '未找到原材料。这可能本身就是一种原料，或者尚未定义配方。',
    'total_quantity' => '总计数量',
    'unit' => '单位',
    'affected_final_products' => "受 “%s” 影响的最终产品",
    'material_does_not_affect_products' => '此原料不影响任何最终产品。',
    'product_name' => '产品名称',
];
