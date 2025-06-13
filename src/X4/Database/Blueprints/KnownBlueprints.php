<?php
/**
 * @package X4 Database
 * @subpackage Blueprints 
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

/**
 * This class contains constants and getter methods for all
 * known blueprints.
 * 
 * This utility is meant to be used in tandem with the main
 * collection class {@see BlueprintDefs}. When you want to
 * access items manually in your code, the getter methods are a
 * great help to find what you need.
 * 
 * ## Usage
 * 
 * Use the method {@see self::getInstance()} to get the
 * instance of this class, then call the getter methods to
 * retrieve the items you need.
 * 
 * ----
 * 
 * > NOTE: This is an auto-generated class.
 * 
 * @package X4 Database
 * @subpackage Blueprints 
 */
class KnownBlueprints
{
    public const BLUEPRINT_1M6S_BASIC_DOCK_AREA = 'module_arg_dock_m_01_lowtech';
    public const BLUEPRINT_1M6S_LUXURY_DOCK_AREA = 'module_arg_dock_m_01_hightech';
    public const BLUEPRINT_1M6S_STANDARD_DOCK_AREA = 'module_arg_dock_m_01';
    public const BLUEPRINT_3M6S_BASIC_DOCK_AREA = 'module_arg_dock_m_02_lowtech';
    public const BLUEPRINT_3M6S_LUXURY_DOCK_AREA = 'module_arg_dock_m_02_hightech';
    public const BLUEPRINT_3M6S_STANDARD_DOCK_AREA = 'module_arg_dock_m_02';
    public const BLUEPRINT_8M_LUXURY_DOCK_AREA = 'module_arg_dock_tradestation_02';
    public const BLUEPRINT_ADVANCED_COMPOSITE_PRODUCTION = 'module_gen_prod_advancedcomposites_01';
    public const BLUEPRINT_ADVANCED_ELECTRONICS_PRODUCTION = 'module_gen_prod_advancedelectronics_01';
    public const BLUEPRINT_ADVANCED_SATELLITE = 'satellite_mk2';
    public const BLUEPRINT_ALBATROSS_SENTINEL = 'ship_tel_xl_builder_01_b';
    public const BLUEPRINT_ALBATROSS_VANGUARD = 'ship_tel_xl_builder_01_a';
    public const BLUEPRINT_ALLIGATOR_GAS = 'ship_spl_m_miner_liquid_01_a';
    public const BLUEPRINT_ALLIGATOR_MINERAL = 'ship_spl_m_miner_solid_01_a';
    public const BLUEPRINT_ANTIMATTER_CELL_PRODUCTION = 'module_gen_prod_antimattercells_01';
    public const BLUEPRINT_ANTIMATTER_CONVERTER_PRODUCTION = 'module_gen_prod_antimatterconverters_01';
    public const BLUEPRINT_ARES = 'ship_par_s_heavyfighter_01_a';
    public const BLUEPRINT_ARGON_1_DOCK_PIER = 'module_arg_pier_l_02';
    public const BLUEPRINT_ARGON_1_DOCK_SHORT_PIER = 'module_arg_pier_l_04';
    public const BLUEPRINT_ARGON_3_DOCK_E_PIER = 'module_arg_pier_l_03';
    public const BLUEPRINT_ARGON_3_DOCK_T_PIER = 'module_arg_pier_l_01';
    public const BLUEPRINT_ARGON_ADMINISTRATIVE_CENTRE = 'module_arg_def_claim_01';
    public const BLUEPRINT_ARGON_ARC_CONNECTION_STRUCTURE = 'module_conn_arg_antigonearc_01';
    public const BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_01 = 'module_arg_conn_base_01';
    public const BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_02 = 'module_arg_conn_base_02';
    public const BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_03 = 'module_arg_conn_base_03';
    public const BLUEPRINT_ARGON_BRIDGE_DEFENCE_PLATFORM = 'module_arg_def_tube_01';
    public const BLUEPRINT_ARGON_CROSS_CONNECTION_STRUCTURE = 'module_arg_conn_cross_01';
    public const BLUEPRINT_ARGON_DISC_DEFENCE_PLATFORM = 'module_arg_def_disc_01';
    public const BLUEPRINT_ARGON_L_CONTAINER_STORAGE = 'module_arg_stor_container_l_01';
    public const BLUEPRINT_ARGON_L_DORMITORY = 'module_pir_hab_l_01';
    public const BLUEPRINT_ARGON_L_HABITAT = 'module_arg_hab_l_01';
    public const BLUEPRINT_ARGON_L_HOUSING_SPIRE = 'module_hab_arg_antigonepillar_01';
    public const BLUEPRINT_ARGON_L_LIQUID_STORAGE = 'module_arg_stor_liquid_l_01';
    public const BLUEPRINT_ARGON_L_SOLID_STORAGE = 'module_arg_stor_solid_l_01';
    public const BLUEPRINT_ARGON_MEDICAL_SUPPLY_PRODUCTION = 'module_arg_prod_medicalsupplies_01';
    public const BLUEPRINT_ARGON_M_CONTAINER_STORAGE = 'module_arg_stor_container_m_01';
    public const BLUEPRINT_ARGON_M_DORMITORY = 'module_pir_hab_m_01';
    public const BLUEPRINT_ARGON_M_HABITAT = 'module_arg_hab_m_01';
    public const BLUEPRINT_ARGON_M_LIQUID_STORAGE = 'module_arg_stor_liquid_m_01';
    public const BLUEPRINT_ARGON_M_SOLID_STORAGE = 'module_arg_stor_solid_m_01';
    public const BLUEPRINT_ARGON_SPAN_CONNECTION_STRUCTURE = 'module_conn_arg_antigonestraight_01';
    public const BLUEPRINT_ARGON_SPAN_CONNECTION_STRUCTURE_02 = 'module_conn_arg_antigonescaffolding_01';
    public const BLUEPRINT_ARGON_S_CONTAINER_STORAGE = 'module_arg_stor_container_s_01';
    public const BLUEPRINT_ARGON_S_DORMITORY = 'module_pir_hab_s_01';
    public const BLUEPRINT_ARGON_S_HABITAT = 'module_arg_hab_s_01';
    public const BLUEPRINT_ARGON_S_LIQUID_STORAGE = 'module_arg_stor_liquid_s_01';
    public const BLUEPRINT_ARGON_S_SOLID_STORAGE = 'module_arg_stor_solid_s_01';
    public const BLUEPRINT_ARGON_VERTICAL_CONNECTION_STRUCTURE_01 = 'module_arg_conn_vertical_01';
    public const BLUEPRINT_ARGON_VERTICAL_CONNECTION_STRUCTURE_02 = 'module_arg_conn_vertical_02';
    public const BLUEPRINT_ARGON_XL_HOUSING_SPIRE = 'module_hab_arg_antigonespire_01';
    public const BLUEPRINT_ARG_BEHEMOTH_MAIN_BATTERY = 'weapon_arg_l_destroyer_01_mk1';
    public const BLUEPRINT_ARG_L_ALL_ROUND_ENGINE = 'engine_arg_l_allround_01_mk1';
    public const BLUEPRINT_ARG_L_BEAM_TURRET = 'turret_arg_l_beam_01_mk1';
    public const BLUEPRINT_ARG_L_DUMBFIRE_TURRET = 'turret_arg_l_dumbfire_01_mk1';
    public const BLUEPRINT_ARG_L_MINING_TURRET = 'turret_arg_l_mining_01_mk1';
    public const BLUEPRINT_ARG_L_PLASMA_TURRET = 'turret_arg_l_plasma_01_mk1';
    public const BLUEPRINT_ARG_L_PULSE_TURRET = 'turret_arg_l_laser_01_mk1';
    public const BLUEPRINT_ARG_L_SHIELD_GENERATOR_01_MK1 = 'shield_arg_l_standard_01_mk1';
    public const BLUEPRINT_ARG_L_SHIELD_GENERATOR_01_MK2 = 'shield_arg_l_standard_01_mk2';
    public const BLUEPRINT_ARG_L_TRACKING_TURRET = 'turret_arg_l_guided_01_mk1';
    public const BLUEPRINT_ARG_L_TRAVEL_ENGINE = 'engine_arg_l_travel_01_mk1';
    public const BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK1 = 'engine_arg_m_allround_01_mk1';
    public const BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK2 = 'engine_arg_m_allround_01_mk2';
    public const BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK3 = 'engine_arg_m_allround_01_mk3';
    public const BLUEPRINT_ARG_M_BEAM_TURRET_01_MK1 = 'turret_arg_m_beam_01_mk1';
    public const BLUEPRINT_ARG_M_BEAM_TURRET_02_MK1 = 'turret_arg_m_beam_02_mk1';
    public const BLUEPRINT_ARG_M_BOLT_TURRET_01_MK1 = 'turret_arg_m_gatling_01_mk1';
    public const BLUEPRINT_ARG_M_BOLT_TURRET_02_MK1 = 'turret_arg_m_gatling_02_mk1';
    public const BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK1 = 'engine_arg_m_combat_01_mk1';
    public const BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK2 = 'engine_arg_m_combat_01_mk2';
    public const BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK3 = 'engine_arg_m_combat_01_mk3';
    public const BLUEPRINT_ARG_M_DUMBFIRE_TURRET = 'turret_arg_m_dumbfire_02_mk1';
    public const BLUEPRINT_ARG_M_FLAK_TURRET_01_MK1 = 'turret_arg_m_flak_01_mk1';
    public const BLUEPRINT_ARG_M_FLAK_TURRET_02_MK1 = 'turret_arg_m_flak_02_mk1';
    public const BLUEPRINT_ARG_M_ION_BLASTER_01_MK1 = 'weapon_arg_m_ion_01_mk1';
    public const BLUEPRINT_ARG_M_ION_BLASTER_01_MK2 = 'weapon_arg_m_ion_01_mk2';
    public const BLUEPRINT_ARG_M_MINING_TURRET_01_MK1 = 'turret_arg_m_mining_01_mk1';
    public const BLUEPRINT_ARG_M_MINING_TURRET_02_MK1 = 'turret_arg_m_mining_02_mk1';
    public const BLUEPRINT_ARG_M_PLASMA_TURRET_01_MK1 = 'turret_arg_m_plasma_01_mk1';
    public const BLUEPRINT_ARG_M_PLASMA_TURRET_02_MK1 = 'turret_arg_m_plasma_02_mk1';
    public const BLUEPRINT_ARG_M_PULSE_TURRET_01_MK1 = 'turret_arg_m_laser_01_mk1';
    public const BLUEPRINT_ARG_M_PULSE_TURRET_02_MK1 = 'turret_arg_m_laser_02_mk1';
    public const BLUEPRINT_ARG_M_SHARD_TURRET_01_MK1 = 'turret_arg_m_shotgun_01_mk1';
    public const BLUEPRINT_ARG_M_SHARD_TURRET_02_MK1 = 'turret_arg_m_shotgun_02_mk1';
    public const BLUEPRINT_ARG_M_SHIELD_GENERATOR_01_MK1 = 'shield_arg_m_standard_01_mk1';
    public const BLUEPRINT_ARG_M_SHIELD_GENERATOR_01_MK2 = 'shield_arg_m_standard_01_mk2';
    public const BLUEPRINT_ARG_M_SHIELD_GENERATOR_02_MK1 = 'shield_arg_m_standard_02_mk1';
    public const BLUEPRINT_ARG_M_SHIELD_GENERATOR_02_MK2 = 'shield_arg_m_standard_02_mk2';
    public const BLUEPRINT_ARG_M_TRACKING_TURRET = 'turret_arg_m_guided_02_mk1';
    public const BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK1 = 'engine_arg_m_travel_01_mk1';
    public const BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK2 = 'engine_arg_m_travel_01_mk2';
    public const BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK3 = 'engine_arg_m_travel_01_mk3';
    public const BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK1 = 'engine_arg_s_allround_01_mk1';
    public const BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK2 = 'engine_arg_s_allround_01_mk2';
    public const BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK3 = 'engine_arg_s_allround_01_mk3';
    public const BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK1 = 'engine_arg_s_combat_01_mk1';
    public const BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK2 = 'engine_arg_s_combat_01_mk2';
    public const BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK3 = 'engine_arg_s_combat_01_mk3';
    public const BLUEPRINT_ARG_S_GAMMA_HEPT = 'weapon_ter_s_plasma_01_mk1';
    public const BLUEPRINT_ARG_S_ION_BLASTER_01_MK1 = 'weapon_arg_s_ion_01_mk1';
    public const BLUEPRINT_ARG_S_ION_BLASTER_01_MK2 = 'weapon_arg_s_ion_01_mk2';
    public const BLUEPRINT_ARG_S_RACING_ENGINE = 'engine_arg_s_racer_01_mk1';
    public const BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK1 = 'shield_arg_s_standard_01_mk1';
    public const BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK1_RACING = 'shield_arg_s_racer_01_mk1';
    public const BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK2 = 'shield_arg_s_standard_01_mk2';
    public const BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK3 = 'shield_arg_s_standard_01_mk3';
    public const BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK1 = 'engine_arg_s_travel_01_mk1';
    public const BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK2 = 'engine_arg_s_travel_01_mk2';
    public const BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK3 = 'engine_arg_s_travel_01_mk3';
    public const BLUEPRINT_ARG_XL_ALL_ROUND_ENGINE = 'engine_arg_xl_allround_01_mk1';
    public const BLUEPRINT_ARG_XL_SHIELD_GENERATOR = 'shield_arg_xl_standard_01_mk1';
    public const BLUEPRINT_ARG_XL_TRAVEL_ENGINE = 'engine_arg_xl_travel_01_mk1';
    public const BLUEPRINT_ASGARD = 'ship_atf_xl_battleship_01_a';
    public const BLUEPRINT_ASP = 'ship_spl_s_fighter_02_a';
    public const BLUEPRINT_ASP_RAIDER = 'ship_spl_s_fighter_02_b';
    public const BLUEPRINT_ATF_XL_MAIN_BATTERY = 'weapon_atf_xl_battleship_01_mk1';
    public const BLUEPRINT_ATLAS_E = 'ship_par_xl_resupplier_02_a';
    public const BLUEPRINT_ATLAS_SENTINEL = 'ship_par_xl_resupplier_01_b';
    public const BLUEPRINT_ATLAS_VANGUARD = 'ship_par_xl_resupplier_01_a';
    public const BLUEPRINT_B = 'ship_xen_m_corvette_01_a';
    public const BLUEPRINT_BALAUR = 'ship_spl_s_heavyfighter_02_a';
    public const BLUEPRINT_BALDRIC = 'ship_ter_m_trans_container_01_a';
    public const BLUEPRINT_BARBAROSSA = 'ship_pir_l_scavenger_01_a';
    public const BLUEPRINT_BARRACUDA = 'ship_bor_s_heavyfighter_01_a';
    public const BLUEPRINT_BEHEMOTH_E = 'ship_arg_l_destroyer_02_a';
    public const BLUEPRINT_BEHEMOTH_SENTINEL = 'ship_arg_l_destroyer_01_b';
    public const BLUEPRINT_BEHEMOTH_VANGUARD = 'ship_arg_l_destroyer_01_a';
    public const BLUEPRINT_BOA = 'ship_spl_m_trans_container_01_a';
    public const BLUEPRINT_BOFU_PRODUCTION = 'module_bor_prod_bofu_01';
    public const BLUEPRINT_BOGAS_PRODUCTION = 'module_bor_prod_bogas_01';
    public const BLUEPRINT_BOLO_GAS = 'ship_ter_m_miner_liquid_01_a';
    public const BLUEPRINT_BOLO_MINERAL = 'ship_ter_m_miner_solid_01_a';
    public const BLUEPRINT_BORON_1_DOCK_PIER = 'module_bor_pier_l_02';
    public const BLUEPRINT_BORON_3_DOCK_E_PIER = 'module_bor_pier_l_03';
    public const BLUEPRINT_BORON_4M14S_LUXURY_DOCK_AREA = 'module_bor_dock_m_01_standard';
    public const BLUEPRINT_BORON_4_DOCK_T_PIER = 'module_bor_pier_l_01';
    public const BLUEPRINT_BORON_ADMINISTRATIVE_CENTRE = 'module_bor_def_claim_01';
    public const BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_01 = 'module_bor_conn_base_01';
    public const BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_02 = 'module_bor_conn_base_02';
    public const BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_03 = 'module_bor_conn_base_03';
    public const BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_04 = 'module_bor_conn_base_04';
    public const BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_05 = 'module_bor_conn_base_05';
    public const BLUEPRINT_BORON_BRIDGE_DEFENCE_PLATFORM = 'module_bor_def_tube_01';
    public const BLUEPRINT_BORON_CROSS_CONNECTION_STRUCTURE_01 = 'module_bor_conn_cross_01';
    public const BLUEPRINT_BORON_CROSS_CONNECTION_STRUCTURE_02 = 'module_bor_conn_cross_02';
    public const BLUEPRINT_BORON_DISC_DEFENCE_PLATFORM = 'module_bor_def_disc_01';
    public const BLUEPRINT_BORON_L_CONTAINER_STORAGE = 'module_bor_stor_container_l_01';
    public const BLUEPRINT_BORON_L_LIQUID_STORAGE = 'module_bor_stor_liquid_l_01';
    public const BLUEPRINT_BORON_L_OASIS = 'module_bor_hab_l_01';
    public const BLUEPRINT_BORON_L_SHIP_FABRICATION_BAY = 'module_bor_build_l_01';
    public const BLUEPRINT_BORON_L_SHIP_MAINTENANCE_BAY = 'module_bor_equip_l_01';
    public const BLUEPRINT_BORON_L_SOLID_STORAGE = 'module_bor_stor_solid_l_01';
    public const BLUEPRINT_BORON_MEDICAL_SUPPLY_PRODUCTION = 'module_bor_prod_medicalsupplies_01';
    public const BLUEPRINT_BORON_M_CONTAINER_STORAGE = 'module_bor_stor_container_m_01';
    public const BLUEPRINT_BORON_M_LIQUID_STORAGE = 'module_bor_stor_liquid_m_01';
    public const BLUEPRINT_BORON_M_OASIS = 'module_bor_hab_m_01';
    public const BLUEPRINT_BORON_M_SOLID_STORAGE = 'module_bor_stor_solid_m_01';
    public const BLUEPRINT_BORON_S_CONTAINER_STORAGE = 'module_bor_stor_container_s_01';
    public const BLUEPRINT_BORON_S_LIQUID_STORAGE = 'module_bor_stor_liquid_s_01';
    public const BLUEPRINT_BORON_S_M_SHIP_FABRICATION_BAY = 'module_bor_build_dockarea_m_01';
    public const BLUEPRINT_BORON_S_M_SHIP_MAINTENANCE_BAY = 'module_bor_equip_dockarea_m_01';
    public const BLUEPRINT_BORON_S_OASIS = 'module_bor_hab_s_01';
    public const BLUEPRINT_BORON_S_SOLID_STORAGE = 'module_bor_stor_solid_s_01';
    public const BLUEPRINT_BORON_TRADING_STATION_4_DOCK_PIER = 'module_bor_pier_l_04';
    public const BLUEPRINT_BORON_TRADING_STATION_HEXA_DOCK_PIER = 'module_bor_pier_tradestation_01';
    public const BLUEPRINT_BORON_VERTICAL_CONNECTION_STRUCTURE_01 = 'module_bor_conn_vertical_01';
    public const BLUEPRINT_BORON_VERTICAL_CONNECTION_STRUCTURE_02 = 'module_bor_conn_vertical_02';
    public const BLUEPRINT_BORON_XL_SHIP_FABRICATION_BAY = 'module_bor_build_xl_01';
    public const BLUEPRINT_BORON_XL_SHIP_MAINTENANCE_BAY = 'module_bor_equip_xl_01';
    public const BLUEPRINT_BOR_L_ALL_ROUND_ENGINE = 'engine_bor_l_travel_01_mk1';
    public const BLUEPRINT_BOR_L_ION_FLAK_TURRET = 'turret_bor_l_flak_01_mk1';
    public const BLUEPRINT_BOR_L_ION_NET_LAUNCHER = 'turret_bor_l_disruptor_01_mk1';
    public const BLUEPRINT_BOR_L_MINING_TURRET = 'turret_bor_l_mining_01_mk1';
    public const BLUEPRINT_BOR_L_PHASE_TURRET = 'turret_bor_l_laser_01_mk1';
    public const BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK1 = 'shield_bor_l_standard_01_mk1';
    public const BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK2 = 'shield_bor_l_standard_01_mk2';
    public const BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK3 = 'shield_bor_l_standard_01_mk3';
    public const BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK1 = 'engine_bor_m_allround_01_mk1';
    public const BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK2 = 'engine_bor_m_allround_01_mk2';
    public const BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK3 = 'engine_bor_m_allround_01_mk3';
    public const BLUEPRINT_BOR_M_ARC_TURRET_01_MK1 = 'turret_bor_m_arc_01_mk1';
    public const BLUEPRINT_BOR_M_ARC_TURRET_02_MK1 = 'turret_bor_m_arc_02_mk1';
    public const BLUEPRINT_BOR_M_DUMBFIRE_LAUNCHER = 'weapon_bor_m_dumbfire_01_mk1';
    public const BLUEPRINT_BOR_M_DUMBFIRE_TURRET = 'turret_bor_m_dumbfire_01_mk1';
    public const BLUEPRINT_BOR_M_ION_ATOMISER = 'weapon_bor_m_flak_01_mk1';
    public const BLUEPRINT_BOR_M_ION_PULSE_RAILGUN = 'weapon_bor_m_railgun_01_mk1';
    public const BLUEPRINT_BOR_M_ION_PULSE_TURRET_01_MK1 = 'turret_bor_m_railgun_01_mk1';
    public const BLUEPRINT_BOR_M_ION_PULSE_TURRET_02_MK1 = 'turret_bor_m_railgun_02_mk1';
    public const BLUEPRINT_BOR_M_MINING_DRILL = 'weapon_bor_m_mining_01_mk1';
    public const BLUEPRINT_BOR_M_MINING_TURRET_01_MK1 = 'turret_bor_m_mining_01_mk1';
    public const BLUEPRINT_BOR_M_MINING_TURRET_02_MK1 = 'turret_bor_m_mining_02_mk1';
    public const BLUEPRINT_BOR_M_PHASE_CANNON = 'weapon_bor_m_laser_01_mk1';
    public const BLUEPRINT_BOR_M_PHASE_TURRET_01_MK1 = 'turret_bor_m_laser_01_mk1';
    public const BLUEPRINT_BOR_M_PHASE_TURRET_02_MK1 = 'turret_bor_m_laser_02_mk1';
    public const BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK1 = 'shield_bor_m_standard_01_mk1';
    public const BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK2 = 'shield_bor_m_standard_01_mk2';
    public const BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK3 = 'shield_bor_m_standard_01_mk3';
    public const BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK1 = 'shield_bor_m_standard_02_mk1';
    public const BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK2 = 'shield_bor_m_standard_02_mk2';
    public const BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK3 = 'shield_bor_m_standard_02_mk3';
    public const BLUEPRINT_BOR_M_TORPEDO_LAUNCHER = 'weapon_bor_m_torpedo_01_mk1';
    public const BLUEPRINT_BOR_M_TRACKING_LAUNCHER = 'weapon_bor_m_guided_01_mk1';
    public const BLUEPRINT_BOR_M_TRACKING_TURRET = 'turret_bor_m_guided_01_mk1';
    public const BLUEPRINT_BOR_RAY_ION_PROJECTOR = 'weapon_bor_l_beam_01_mk1';
    public const BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK1 = 'engine_bor_s_allround_01_mk1';
    public const BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK2 = 'engine_bor_s_allround_01_mk2';
    public const BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK3 = 'engine_bor_s_allround_01_mk3';
    public const BLUEPRINT_BOR_S_ARC_GUN = 'weapon_bor_s_arc_01_mk1';
    public const BLUEPRINT_BOR_S_DUMBFIRE_LAUNCHER = 'weapon_bor_s_dumbfire_01_mk1';
    public const BLUEPRINT_BOR_S_ION_GATLING = 'weapon_bor_s_gatling_01_mk1';
    public const BLUEPRINT_BOR_S_MINING_DRILL = 'weapon_bor_s_mining_01_mk1';
    public const BLUEPRINT_BOR_S_PHASE_GUN = 'weapon_bor_s_laser_01_mk1';
    public const BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK1 = 'shield_bor_s_standard_01_mk1';
    public const BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK2 = 'shield_bor_s_standard_01_mk2';
    public const BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK3 = 'shield_bor_s_standard_01_mk3';
    public const BLUEPRINT_BOR_S_TORPEDO_LAUNCHER = 'weapon_bor_s_torpedo_01_mk1';
    public const BLUEPRINT_BOR_S_TRACKING_LAUNCHER = 'weapon_bor_s_guided_01_mk1';
    public const BLUEPRINT_BOR_XL_ALL_ROUND_ENGINE = 'engine_bor_xl_travel_01_mk1';
    public const BLUEPRINT_BOR_XL_SHIELD_GENERATOR_01_MK1 = 'shield_bor_xl_standard_01_mk1';
    public const BLUEPRINT_BOR_XL_SHIELD_GENERATOR_01_MK2 = 'shield_bor_xl_standard_01_mk2';
    public const BLUEPRINT_BUFFALO = 'ship_spl_l_trans_container_01_a';
    public const BLUEPRINT_BUILDING_DRONE = 'ship_gen_xs_buildingdrone_01_a';
    public const BLUEPRINT_BUZZARD_SENTINEL = 'ship_tel_s_fighter_02_b';
    public const BLUEPRINT_BUZZARD_VANGUARD = 'ship_tel_s_fighter_02_a';
    public const BLUEPRINT_CALLISTO_SENTINEL = 'ship_arg_s_trans_container_02_b';
    public const BLUEPRINT_CALLISTO_VANGUARD = 'ship_arg_s_trans_container_02_a';
    public const BLUEPRINT_CARGO_DRONE = 'ship_gen_xs_cargodrone_empty_01_a';
    public const BLUEPRINT_CASINO = 'module_gen_welfare_casino_01';
    public const BLUEPRINT_CERBERUS_SENTINEL = 'ship_arg_m_frigate_01_b';
    public const BLUEPRINT_CERBERUS_VANGUARD = 'ship_arg_m_frigate_01_a';
    public const BLUEPRINT_CHELT_PRODUCTION = 'module_spl_prod_cheltmeat_01';
    public const BLUEPRINT_CHIMERA = 'ship_spl_s_heavyfighter_01_a';
    public const BLUEPRINT_CHTHONIOS_E_GAS = 'ship_par_l_miner_liquid_02_a';
    public const BLUEPRINT_CHTHONIOS_E_MINERAL = 'ship_par_l_miner_solid_02_a';
    public const BLUEPRINT_CHTHONIOS_GAS_SENTINEL = 'ship_par_l_miner_liquid_01_b';
    public const BLUEPRINT_CHTHONIOS_GAS_VANGUARD = 'ship_par_l_miner_liquid_01_a';
    public const BLUEPRINT_CHTHONIOS_MINERAL_SENTINEL = 'ship_par_l_miner_solid_01_b';
    public const BLUEPRINT_CHTHONIOS_MINERAL_VANGUARD = 'ship_par_l_miner_solid_01_a';
    public const BLUEPRINT_CLAYTRONICS_PRODUCTION = 'module_gen_prod_claytronics_01';
    public const BLUEPRINT_CLUSTER_MINE = 'weapon_clustermine_01';
    public const BLUEPRINT_COBRA = 'ship_spl_m_frigate_01_a';
    public const BLUEPRINT_COLOSSUS_E = 'ship_arg_xl_carrier_02_a';
    public const BLUEPRINT_COLOSSUS_SENTINEL = 'ship_arg_xl_carrier_01_b';
    public const BLUEPRINT_COLOSSUS_VANGUARD = 'ship_arg_xl_carrier_01_a';
    public const BLUEPRINT_COMPUTRONIC_SUBSTRATE_PRODUCTION = 'module_ter_prod_computronicsubstrate_01';
    public const BLUEPRINT_CONDENSATE_CONTAINMENT_FACILITY = 'module_pir_stor_condensate_s_01';
    public const BLUEPRINT_CONDOR_SENTINEL = 'ship_tel_xl_carrier_01_b';
    public const BLUEPRINT_CONDOR_VANGUARD = 'ship_tel_xl_carrier_01_a';
    public const BLUEPRINT_CONSERVATORY_OBSERVATION_DECK = 'module_gen_conn_observationdeck_01';
    public const BLUEPRINT_CORMORANT_VANGUARD = 'ship_tel_m_trans_container_03_a';
    public const BLUEPRINT_COURIER_MINERAL = 'ship_arg_s_miner_solid_01_a';
    public const BLUEPRINT_COURIER_SENTINEL = 'ship_arg_s_trans_container_01_b';
    public const BLUEPRINT_COURIER_VANGUARD = 'ship_arg_s_trans_container_01_a';
    public const BLUEPRINT_CRANE_E_GAS = 'ship_tel_l_miner_liquid_02_a';
    public const BLUEPRINT_CRANE_E_MINERAL = 'ship_tel_l_miner_solid_02_a';
    public const BLUEPRINT_CRANE_GAS_SENTINEL = 'ship_tel_l_miner_liquid_01_b';
    public const BLUEPRINT_CRANE_GAS_VANGUARD = 'ship_tel_l_miner_liquid_01_a';
    public const BLUEPRINT_CRANE_MINERAL_SENTINEL = 'ship_tel_l_miner_solid_01_b';
    public const BLUEPRINT_CRANE_MINERAL_VANGUARD = 'ship_tel_l_miner_solid_01_a';
    public const BLUEPRINT_CUTLASS = 'ship_ter_s_fighter_04_a';
    public const BLUEPRINT_DART = 'ship_gen_s_racer_01_a';
    public const BLUEPRINT_DEFENCE_DRONE = 'ship_gen_s_fightingdrone_01_a';
    public const BLUEPRINT_DEMETER_SENTINEL = 'ship_par_m_trans_container_01_b';
    public const BLUEPRINT_DEMETER_VANGUARD = 'ship_par_m_trans_container_01_a';
    public const BLUEPRINT_DISCOVERER_SENTINEL = 'ship_arg_s_scout_01_b';
    public const BLUEPRINT_DISCOVERER_VANGUARD = 'ship_arg_s_scout_01_a';
    public const BLUEPRINT_DOLPHIN = 'ship_bor_m_trans_container_01_a';
    public const BLUEPRINT_DONIA = 'ship_pir_l_miner_solid_01_a';
    public const BLUEPRINT_DRAGON = 'ship_spl_m_corvette_01_a';
    public const BLUEPRINT_DRAGON_RAIDER = 'ship_spl_m_corvette_01_b';
    public const BLUEPRINT_DRILL_MINERAL_SENTINEL = 'ship_arg_m_miner_solid_01_b';
    public const BLUEPRINT_DRILL_MINERAL_VANGUARD = 'ship_arg_m_miner_solid_01_a';
    public const BLUEPRINT_DRONE_COMPONENT_PRODUCTION = 'module_gen_prod_dronecomponents_01';
    public const BLUEPRINT_ECLIPSE_SENTINEL = 'ship_arg_s_heavyfighter_01_b';
    public const BLUEPRINT_ECLIPSE_VANGUARD_01_A = 'ship_arg_s_heavyfighter_01_a';
    public const BLUEPRINT_ECLIPSE_VANGUARD_02_A = 'ship_arg_s_heavyfighter_02_a';
    public const BLUEPRINT_ELEPHANT = 'ship_spl_xl_builder_01_a';
    public const BLUEPRINT_ELITE_SENTINEL = 'ship_arg_s_fighter_02_b';
    public const BLUEPRINT_ELITE_SPORT = 'ship_arg_s_racer_01_a';
    public const BLUEPRINT_ELITE_VANGUARD = 'ship_arg_s_fighter_02_a';
    public const BLUEPRINT_EMP_MISSILE = 'missile_emp_mk1';
    public const BLUEPRINT_ENERGY_CELL_PRODUCTION = 'module_gen_prod_energycells_01';
    public const BLUEPRINT_ENGINE_PART_PRODUCTION = 'module_gen_prod_engineparts_01';
    public const BLUEPRINT_ERLKING = 'ship_pir_xl_battleship_01_a';
    public const BLUEPRINT_ERLKING_L_TURRET = 'turret_pir_l_battleship_01_laser_01_mk1';
    public const BLUEPRINT_ERLKING_MAIN_BATTERY = 'weapon_pir_xl_battleship_01_mk1';
    public const BLUEPRINT_ERLKING_M_TURRET = 'turret_pir_m_battleship_01_gatling_02_mk1';
    public const BLUEPRINT_ERLKING_XL_ENGINE = 'engine_pir_xl_battleship_01_allround_01_mk1';
    public const BLUEPRINT_ERLKING_XL_SHIELD_GENERATOR = 'shield_pir_xl_battleship_01_standard_01_mk1';
    public const BLUEPRINT_F = 'ship_xen_s_heavyfighter_01_a';
    public const BLUEPRINT_FALCON_SENTINEL = 'ship_tel_s_fighter_01_b';
    public const BLUEPRINT_FALCON_VANGUARD = 'ship_tel_s_fighter_01_a';
    public const BLUEPRINT_FALX = 'ship_ter_m_frigate_01_a';
    public const BLUEPRINT_FIELD_COIL_PRODUCTION = 'module_gen_prod_fieldcoils_01';
    public const BLUEPRINT_FLARES = 'countermeasure_flares_01';
    public const BLUEPRINT_FOOD_RATION_PRODUCTION = 'module_arg_prod_foodrations_01';
    public const BLUEPRINT_FRIEND_FOE_MINE = 'weapon_gen_mine_03';
    public const BLUEPRINT_FROG = 'ship_ter_s_trans_container_01_a';
    public const BLUEPRINT_GAMBLING_DEN = 'module_gen_welfare_gamblinghall_01';
    public const BLUEPRINT_GANNASCUS = 'ship_pir_xl_builder_01';
    public const BLUEPRINT_GAS_COLLECTOR_DRONE = 'ship_gen_s_miningdrone_liquid_01_a';
    public const BLUEPRINT_GLADIUS = 'ship_ter_s_heavyfighter_01_a';
    public const BLUEPRINT_GORGON_SENTINEL = 'ship_par_m_frigate_01_b';
    public const BLUEPRINT_GORGON_VANGUARD = 'ship_par_m_frigate_01_a';
    public const BLUEPRINT_GRAPHENE_PRODUCTION = 'module_gen_prod_graphene_01';
    public const BLUEPRINT_GROUPER_MINERAL = 'ship_bor_s_miner_solid_01_a';
    public const BLUEPRINT_GUILLEMOT_SENTINEL = 'ship_tel_s_scout_02_b';
    public const BLUEPRINT_GUILLEMOT_VANGUARD = 'ship_tel_s_scout_02_a';
    public const BLUEPRINT_GUPPY = 'ship_bor_l_carrier_01_a';
    public const BLUEPRINT_H = 'ship_xen_l_terraformer_01_a';
    public const BLUEPRINT_HEAVY_BARRAGE_MISSILE = 'missile_flagship_heavy_mk1';
    public const BLUEPRINT_HEAVY_CLUSTER_MISSILE = 'missile_cluster_heavy_mk1';
    public const BLUEPRINT_HEAVY_DUMBFIRE_MISSILE_MK1 = 'missile_dumbfire_heavy_mk1';
    public const BLUEPRINT_HEAVY_DUMBFIRE_MISSILE_MK2 = 'missile_dumbfire_heavy_mk2';
    public const BLUEPRINT_HEAVY_GUIDED_MISSILE = 'missile_guided_heavy_mk1';
    public const BLUEPRINT_HEAVY_HEATSEEKER_MISSILE = 'missile_heatseeker_heavy_mk1';
    public const BLUEPRINT_HEAVY_SCATTER_MISSILE = 'missile_scatter_heavy_mk1';
    public const BLUEPRINT_HEAVY_SMART_MISSILE = 'missile_smart_heavy_mk1';
    public const BLUEPRINT_HEAVY_STARBURST_MISSILE = 'missile_starburst_heavy_mk1';
    public const BLUEPRINT_HEAVY_SWARM_MISSILE = 'missile_swarm_heavy_mk1';
    public const BLUEPRINT_HEAVY_TORPEDO_MISSILE = 'missile_torpedo_heavy_mk1';
    public const BLUEPRINT_HELIOS_E = 'ship_par_l_trans_container_03_a';
    public const BLUEPRINT_HELIOS_SENTINEL = 'ship_par_l_trans_container_01_b';
    public const BLUEPRINT_HELIOS_VANGUARD = 'ship_par_l_trans_container_01_a';
    public const BLUEPRINT_HERACLES_SENTINEL = 'ship_par_xl_builder_01_b';
    public const BLUEPRINT_HERACLES_VANGUARD = 'ship_par_xl_builder_01_a';
    public const BLUEPRINT_HERMES_SENTINEL = 'ship_par_m_trans_container_02_b';
    public const BLUEPRINT_HERMES_VANGUARD = 'ship_par_m_trans_container_02_a';
    public const BLUEPRINT_HERON_E = 'ship_tel_l_trans_container_03_a';
    public const BLUEPRINT_HERON_SENTINEL = 'ship_tel_l_trans_container_02_b';
    public const BLUEPRINT_HERON_VANGUARD = 'ship_tel_l_trans_container_02_a';
    public const BLUEPRINT_HOKKAIDO_GAS = 'ship_ter_l_miner_liquid_01_a';
    public const BLUEPRINT_HOKKAIDO_MINERAL = 'ship_ter_l_miner_solid_01_a';
    public const BLUEPRINT_HONSHU = 'ship_ter_xl_resupplier_01_a';
    public const BLUEPRINT_HULL_PART_PRODUCTION = 'module_gen_prod_hullparts_01';
    public const BLUEPRINT_HYDRA = 'ship_bor_m_corvette_01_a';
    public const BLUEPRINT_HYPERION = 'ship_par_l_expeditionary_01_a';
    public const BLUEPRINT_IDES_SENTINEL = 'ship_arg_m_trans_container_02_b';
    public const BLUEPRINT_IDES_VANGUARD = 'ship_arg_m_trans_container_02_a';
    public const BLUEPRINT_INCARCATURA_SENTINEL = 'ship_arg_l_trans_container_03_b';
    public const BLUEPRINT_INCARCATURA_VANGUARD = 'ship_arg_l_trans_container_03_a';
    public const BLUEPRINT_IRUKANDJI = 'ship_bor_s_scout_02_a';
    public const BLUEPRINT_JAGUAR = 'ship_spl_s_scout_01_a';
    public const BLUEPRINT_JIAN = 'ship_ter_m_gunboat_01_a';
    public const BLUEPRINT_KALIS = 'ship_ter_s_fighter_02_a';
    public const BLUEPRINT_KATANA = 'ship_ter_m_corvette_01_a';
    public const BLUEPRINT_KESTREL_SENTINEL = 'ship_tel_s_scout_01_b';
    public const BLUEPRINT_KESTREL_SPORT = 'ship_tel_s_racer_01_a';
    public const BLUEPRINT_KESTREL_VANGUARD = 'ship_tel_s_scout_01_a';
    public const BLUEPRINT_KOPIS_MINERAL = 'ship_ter_s_miner_solid_01_a';
    public const BLUEPRINT_KUKRI = 'ship_ter_s_fighter_01_a';
    public const BLUEPRINT_KURAOKAMI = 'ship_yak_m_corvette_01_a';
    public const BLUEPRINT_KYD = 'ship_pir_s_fighter_01_a';
    public const BLUEPRINT_KYUSHU = 'ship_ter_xl_builder_01_a';
    public const BLUEPRINT_LASER_TOWER_01_A_S = 'ship_gen_s_lasertower_01_a';
    public const BLUEPRINT_LASER_TOWER_01_A_XS = 'ship_gen_xs_lasertower_01_a';
    public const BLUEPRINT_LIGHT_BARRAGE_MISSILE = 'missile_flagship_light_mk1';
    public const BLUEPRINT_LIGHT_CLUSTER_MISSILE = 'missile_cluster_light_mk1';
    public const BLUEPRINT_LIGHT_DISRUPTOR_MISSILE = 'missile_disruptor_light_mk1';
    public const BLUEPRINT_LIGHT_DUMBFIRE_MISSILE_MK1 = 'missile_dumbfire_light_mk1';
    public const BLUEPRINT_LIGHT_DUMBFIRE_MISSILE_MK2 = 'missile_dumbfire_light_mk2';
    public const BLUEPRINT_LIGHT_GUIDED_MISSILE = 'missile_guided_light_mk1';
    public const BLUEPRINT_LIGHT_HEATSEEKER_MISSILE = 'missile_heatseeker_light_mk1';
    public const BLUEPRINT_LIGHT_INTERCEPTOR_MISSILE = 'missile_interceptor_light_mk1';
    public const BLUEPRINT_LIGHT_SMART_MISSILE = 'missile_smart_light_mk1';
    public const BLUEPRINT_LIGHT_SWARM_MISSILE = 'missile_swarm_light_mk1';
    public const BLUEPRINT_LIGHT_TORPEDO_MISSILE = 'missile_torpedo_light_mk1';
    public const BLUEPRINT_LUX = 'ship_pir_s_fighter_02_a';
    public const BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK1 = 'thruster_gen_l_allround_01_mk1';
    public const BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK2 = 'thruster_gen_l_allround_01_mk2';
    public const BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK3 = 'thruster_gen_l_allround_01_mk3';
    public const BLUEPRINT_L_SHIP_FABRICATION_BAY = 'module_gen_build_l_01';
    public const BLUEPRINT_L_SHIP_MAINTENANCE_BAY = 'module_gen_equip_l_01';
    public const BLUEPRINT_MAGNETAR_GAS_SENTINEL = 'ship_arg_l_miner_liquid_01_b';
    public const BLUEPRINT_MAGNETAR_GAS_VANGUARD = 'ship_arg_l_miner_liquid_01_a';
    public const BLUEPRINT_MAGNETAR_MINERAL_SENTINEL = 'ship_arg_l_miner_solid_01_b';
    public const BLUEPRINT_MAGNETAR_MINERAL_VANGUARD = 'ship_arg_l_miner_solid_01_a';
    public const BLUEPRINT_MAGPIE_MINERAL = 'ship_tel_s_miner_solid_01_a';
    public const BLUEPRINT_MAGPIE_SENTINEL = 'ship_tel_s_trans_container_01_b';
    public const BLUEPRINT_MAGPIE_VANGUARD = 'ship_tel_s_trans_container_01_a';
    public const BLUEPRINT_MAJA_DUST_PRODUCTION = 'module_par_prod_majadust_01';
    public const BLUEPRINT_MAJA_SNAIL_PRODUCTION = 'module_par_prod_majasnails_01';
    public const BLUEPRINT_MAKO = 'ship_bor_s_fighter_01_a';
    public const BLUEPRINT_MAMBA = 'ship_spl_s_fighter_01_a';
    public const BLUEPRINT_MAMMOTH_SENTINEL = 'ship_arg_xl_builder_01_b';
    public const BLUEPRINT_MAMMOTH_VANGUARD = 'ship_arg_xl_builder_01_a';
    public const BLUEPRINT_MANORINA_GAS_SENTINEL = 'ship_tel_m_miner_liquid_01_b';
    public const BLUEPRINT_MANORINA_GAS_VANGUARD = 'ship_tel_m_miner_liquid_01_a';
    public const BLUEPRINT_MANORINA_MINERAL_SENTINEL = 'ship_tel_m_miner_solid_01_b';
    public const BLUEPRINT_MANORINA_MINERAL_VANGUARD = 'ship_tel_m_miner_solid_01_a';
    public const BLUEPRINT_MANTICORE = 'ship_gen_m_tugboat_01_a';
    public const BLUEPRINT_MEAT_PRODUCTION = 'module_arg_prod_meat_01';
    public const BLUEPRINT_MERCURY_SENTINEL = 'ship_arg_m_trans_container_01_b';
    public const BLUEPRINT_MERCURY_VANGUARD = 'ship_arg_m_trans_container_01_a';
    public const BLUEPRINT_METALLIC_MICROLATTICE_PRODUCTION = 'module_ter_prod_metallicmicrolattice_01';
    public const BLUEPRINT_MICROCHIP_PRODUCTION = 'module_gen_prod_microchips_01';
    public const BLUEPRINT_MINE = 'weapon_gen_mine_01';
    public const BLUEPRINT_MINING_DRONE = 'ship_gen_s_miningdrone_solid_01_a';
    public const BLUEPRINT_MINOTAUR_RAIDER = 'ship_arg_m_bomber_02_a';
    public const BLUEPRINT_MINOTAUR_SENTINEL = 'ship_arg_m_bomber_01_b';
    public const BLUEPRINT_MINOTAUR_VANGUARD = 'ship_arg_m_bomber_01_a';
    public const BLUEPRINT_MISSILE_COMPONENT_PRODUCTION = 'module_gen_prod_missilecomponents_01';
    public const BLUEPRINT_MOKOSI_SENTINEL = 'ship_arg_l_trans_container_02_b';
    public const BLUEPRINT_MOKOSI_VANGUARD = 'ship_arg_l_trans_container_02_a';
    public const BLUEPRINT_MONITOR = 'ship_spl_xl_resupplier_01_a';
    public const BLUEPRINT_MOREYA = 'ship_yak_s_fighter_01_a';
    public const BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK1 = 'thruster_gen_m_allround_01_mk1';
    public const BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK2 = 'thruster_gen_m_allround_01_mk2';
    public const BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK3 = 'thruster_gen_m_allround_01_mk3';
    public const BLUEPRINT_M_BEAM_EMITTER_01_MK1 = 'weapon_gen_m_beam_01_mk1';
    public const BLUEPRINT_M_BEAM_EMITTER_01_MK2 = 'weapon_gen_m_beam_01_mk2';
    public const BLUEPRINT_M_BOLT_REPEATER_01_MK1 = 'weapon_gen_m_gatling_01_mk1';
    public const BLUEPRINT_M_BOLT_REPEATER_01_MK2 = 'weapon_gen_m_gatling_01_mk2';
    public const BLUEPRINT_M_COMBAT_THRUSTERS_01_MK1 = 'thruster_gen_m_combat_01_mk1';
    public const BLUEPRINT_M_COMBAT_THRUSTERS_01_MK2 = 'thruster_gen_m_combat_01_mk2';
    public const BLUEPRINT_M_COMBAT_THRUSTERS_01_MK3 = 'thruster_gen_m_combat_01_mk3';
    public const BLUEPRINT_M_DUMBFIRE_LAUNCHER_01_MK1 = 'weapon_gen_m_dumbfire_01_mk1';
    public const BLUEPRINT_M_DUMBFIRE_LAUNCHER_01_MK2 = 'weapon_gen_m_dumbfire_01_mk2';
    public const BLUEPRINT_M_MINING_DRILL_01_MK1 = 'weapon_gen_m_mining_01_mk1';
    public const BLUEPRINT_M_MINING_DRILL_01_MK2 = 'weapon_gen_m_mining_01_mk2';
    public const BLUEPRINT_M_PLASMA_CANNON_01_MK1 = 'weapon_gen_m_plasma_01_mk1';
    public const BLUEPRINT_M_PLASMA_CANNON_01_MK2 = 'weapon_gen_m_plasma_01_mk2';
    public const BLUEPRINT_M_PULSE_LASER_01_MK1 = 'weapon_gen_m_laser_01_mk1';
    public const BLUEPRINT_M_PULSE_LASER_01_MK2 = 'weapon_gen_m_laser_01_mk2';
    public const BLUEPRINT_M_SHARD_BATTERY_01_MK1 = 'weapon_gen_m_shotgun_01_mk1';
    public const BLUEPRINT_M_SHARD_BATTERY_01_MK2 = 'weapon_gen_m_shotgun_01_mk2';
    public const BLUEPRINT_M_TORPEDO_LAUNCHER_01_MK1 = 'weapon_gen_m_torpedo_01_mk1';
    public const BLUEPRINT_M_TORPEDO_LAUNCHER_01_MK2 = 'weapon_gen_m_torpedo_01_mk2';
    public const BLUEPRINT_M_TRACKING_LAUNCHER_01_MK1 = 'weapon_gen_m_guided_01_mk1';
    public const BLUEPRINT_M_TRACKING_LAUNCHER_01_MK2 = 'weapon_gen_m_guided_01_mk2';
    public const BLUEPRINT_NAV_BEACON = 'waypointmarker_01';
    public const BLUEPRINT_NEMESIS_SENTINEL = 'ship_par_m_corvette_01_b';
    public const BLUEPRINT_NEMESIS_VANGUARD = 'ship_par_m_corvette_01_a';
    public const BLUEPRINT_NIMCHA = 'ship_ter_s_scout_02_a';
    public const BLUEPRINT_NODAN_SENTINEL = 'ship_gen_s_fighter_01_b';
    public const BLUEPRINT_NODAN_VANGUARD = 'ship_gen_s_fighter_01_a';
    public const BLUEPRINT_NOMAD_SENTINEL = 'ship_arg_xl_resupplier_01_b';
    public const BLUEPRINT_NOMAD_VANGUARD = 'ship_arg_xl_resupplier_01_a';
    public const BLUEPRINT_NOSTROP_OIL_PRODUCTION = 'module_tel_prod_nostropoil_01';
    public const BLUEPRINT_NOVA_SENTINEL = 'ship_arg_s_fighter_01_b';
    public const BLUEPRINT_NOVA_VANGUARD = 'ship_arg_s_fighter_01_a';
    public const BLUEPRINT_ODACHI = 'ship_ter_m_corvette_02_a';
    public const BLUEPRINT_ODYSSEUS_E = 'ship_par_l_destroyer_02_a';
    public const BLUEPRINT_ODYSSEUS_SENTINEL = 'ship_par_l_destroyer_01_b';
    public const BLUEPRINT_ODYSSEUS_VANGUARD = 'ship_par_l_destroyer_01_a';
    public const BLUEPRINT_OKINAWA = 'ship_ter_l_trans_container_01_a';
    public const BLUEPRINT_ORCA = 'ship_bor_xl_resupplier_01_a';
    public const BLUEPRINT_OSAKA = 'ship_ter_l_destroyer_01_a';
    public const BLUEPRINT_OSPREY_SENTINEL = 'ship_tel_m_frigate_01_b';
    public const BLUEPRINT_OSPREY_VANGUARD = 'ship_tel_m_frigate_01_a';
    public const BLUEPRINT_PARANID_1_DOCK_PIER = 'module_par_pier_l_02';
    public const BLUEPRINT_PARANID_3_DOCK_E_PIER = 'module_par_pier_l_03';
    public const BLUEPRINT_PARANID_3_DOCK_T_PIER = 'module_par_pier_l_01';
    public const BLUEPRINT_PARANID_ADMINISTRATIVE_CENTRE = 'module_par_def_claim_01';
    public const BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_01 = 'module_par_conn_base_01';
    public const BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_02 = 'module_par_conn_base_02';
    public const BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_03 = 'module_par_conn_base_03';
    public const BLUEPRINT_PARANID_BRIDGE_DEFENCE_PLATFORM = 'module_par_def_tube_01';
    public const BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_01 = 'module_par_conn_cross_01';
    public const BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_02 = 'module_par_conn_cross_02';
    public const BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_03 = 'module_par_conn_cross_03';
    public const BLUEPRINT_PARANID_DISC_DEFENCE_PLATFORM = 'module_par_def_disc_01';
    public const BLUEPRINT_PARANID_FACTION_CAPITAL = 'module_par_def_claim_story_01';
    public const BLUEPRINT_PARANID_L_CONTAINER_STORAGE = 'module_par_stor_container_l_01';
    public const BLUEPRINT_PARANID_L_DOME = 'module_par_hab_l_01';
    public const BLUEPRINT_PARANID_L_LIQUID_STORAGE = 'module_par_stor_liquid_l_01';
    public const BLUEPRINT_PARANID_L_SOLID_STORAGE = 'module_par_stor_solid_l_01';
    public const BLUEPRINT_PARANID_MEDICAL_SUPPLY_PRODUCTION = 'module_par_prod_medicalsupplies_01';
    public const BLUEPRINT_PARANID_M_CONTAINER_STORAGE = 'module_par_stor_container_m_01';
    public const BLUEPRINT_PARANID_M_DOME = 'module_par_hab_m_01';
    public const BLUEPRINT_PARANID_M_LIQUID_STORAGE = 'module_par_stor_liquid_m_01';
    public const BLUEPRINT_PARANID_M_SOLID_STORAGE = 'module_par_stor_solid_m_01';
    public const BLUEPRINT_PARANID_S_CONTAINER_STORAGE = 'module_par_stor_container_s_01';
    public const BLUEPRINT_PARANID_S_DOME = 'module_par_hab_s_01';
    public const BLUEPRINT_PARANID_S_LIQUID_STORAGE = 'module_par_stor_liquid_s_01';
    public const BLUEPRINT_PARANID_S_SOLID_STORAGE = 'module_par_stor_solid_s_01';
    public const BLUEPRINT_PARANID_VERTICAL_CONNECTION_STRUCTURE_01 = 'module_par_conn_vertical_01';
    public const BLUEPRINT_PARANID_VERTICAL_CONNECTION_STRUCTURE_02 = 'module_par_conn_vertical_02';
    public const BLUEPRINT_PAR_HYPERION_MAIN_BATTERY = 'weapon_par_l_expeditionary_01_mk1';
    public const BLUEPRINT_PAR_L_ALL_ROUND_ENGINE = 'engine_par_l_allround_01_mk1';
    public const BLUEPRINT_PAR_L_BEAM_TURRET = 'turret_par_l_beam_01_mk1';
    public const BLUEPRINT_PAR_L_DUMBFIRE_TURRET = 'turret_par_l_dumbfire_01_mk1';
    public const BLUEPRINT_PAR_L_MINING_TURRET = 'turret_par_l_mining_01_mk1';
    public const BLUEPRINT_PAR_L_PLASMA_TURRET = 'turret_par_l_plasma_01_mk1';
    public const BLUEPRINT_PAR_L_PULSE_TURRET = 'turret_par_l_laser_01_mk1';
    public const BLUEPRINT_PAR_L_SHIELD_GENERATOR_01_MK1 = 'shield_par_l_standard_01_mk1';
    public const BLUEPRINT_PAR_L_SHIELD_GENERATOR_01_MK2 = 'shield_par_l_standard_01_mk2';
    public const BLUEPRINT_PAR_L_TRACKING_TURRET = 'turret_par_l_guided_01_mk1';
    public const BLUEPRINT_PAR_L_TRAVEL_ENGINE = 'engine_par_l_travel_01_mk1';
    public const BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK1 = 'engine_par_m_allround_01_mk1';
    public const BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK2 = 'engine_par_m_allround_01_mk2';
    public const BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK3 = 'engine_par_m_allround_01_mk3';
    public const BLUEPRINT_PAR_M_BEAM_TURRET_01_MK1 = 'turret_par_m_beam_01_mk1';
    public const BLUEPRINT_PAR_M_BEAM_TURRET_02_MK1 = 'turret_par_m_beam_02_mk1';
    public const BLUEPRINT_PAR_M_BOLT_TURRET_01_MK1 = 'turret_par_m_gatling_01_mk1';
    public const BLUEPRINT_PAR_M_BOLT_TURRET_02_MK1 = 'turret_par_m_gatling_02_mk1';
    public const BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK1 = 'engine_par_m_combat_01_mk1';
    public const BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK2 = 'engine_par_m_combat_01_mk2';
    public const BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK3 = 'engine_par_m_combat_01_mk3';
    public const BLUEPRINT_PAR_M_DUMBFIRE_TURRET = 'turret_par_m_dumbfire_02_mk1';
    public const BLUEPRINT_PAR_M_MASS_DRIVER_01_MK1 = 'weapon_par_m_railgun_01_mk1';
    public const BLUEPRINT_PAR_M_MASS_DRIVER_01_MK2 = 'weapon_par_m_railgun_01_mk2';
    public const BLUEPRINT_PAR_M_MINING_TURRET_01_MK1 = 'turret_par_m_mining_01_mk1';
    public const BLUEPRINT_PAR_M_MINING_TURRET_02_MK1 = 'turret_par_m_mining_02_mk1';
    public const BLUEPRINT_PAR_M_PLASMA_TURRET_01_MK1 = 'turret_par_m_plasma_01_mk1';
    public const BLUEPRINT_PAR_M_PLASMA_TURRET_02_MK1 = 'turret_par_m_plasma_02_mk1';
    public const BLUEPRINT_PAR_M_PULSE_TURRET_01_MK1 = 'turret_par_m_laser_01_mk1';
    public const BLUEPRINT_PAR_M_PULSE_TURRET_02_MK1 = 'turret_par_m_laser_02_mk1';
    public const BLUEPRINT_PAR_M_SHARD_TURRET_01_MK1 = 'turret_par_m_shotgun_01_mk1';
    public const BLUEPRINT_PAR_M_SHARD_TURRET_02_MK1 = 'turret_par_m_shotgun_02_mk1';
    public const BLUEPRINT_PAR_M_SHIELD_GENERATOR_01_MK1 = 'shield_par_m_standard_01_mk1';
    public const BLUEPRINT_PAR_M_SHIELD_GENERATOR_01_MK2 = 'shield_par_m_standard_01_mk2';
    public const BLUEPRINT_PAR_M_SHIELD_GENERATOR_02_MK1 = 'shield_par_m_standard_02_mk1';
    public const BLUEPRINT_PAR_M_SHIELD_GENERATOR_02_MK2 = 'shield_par_m_standard_02_mk2';
    public const BLUEPRINT_PAR_M_TRACKING_TURRET = 'turret_par_m_guided_02_mk1';
    public const BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK1 = 'engine_par_m_travel_01_mk1';
    public const BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK2 = 'engine_par_m_travel_01_mk2';
    public const BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK3 = 'engine_par_m_travel_01_mk3';
    public const BLUEPRINT_PAR_ODYSSEUS_MAIN_BATTERY = 'weapon_par_l_destroyer_01_mk1';
    public const BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK1 = 'engine_par_s_allround_01_mk1';
    public const BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK2 = 'engine_par_s_allround_01_mk2';
    public const BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK3 = 'engine_par_s_allround_01_mk3';
    public const BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK1 = 'engine_par_s_combat_01_mk1';
    public const BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK2 = 'engine_par_s_combat_01_mk2';
    public const BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK3 = 'engine_par_s_combat_01_mk3';
    public const BLUEPRINT_PAR_S_MASS_DRIVER_01_MK1 = 'weapon_par_s_railgun_01_mk1';
    public const BLUEPRINT_PAR_S_MASS_DRIVER_01_MK2 = 'weapon_par_s_railgun_01_mk2';
    public const BLUEPRINT_PAR_S_RACING_ENGINE = 'engine_par_s_racer_01_mk1';
    public const BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK1 = 'shield_par_s_standard_01_mk1';
    public const BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK1_RACING = 'shield_par_s_racer_01_mk1';
    public const BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK2 = 'shield_par_s_standard_01_mk2';
    public const BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK3 = 'shield_par_s_standard_01_mk3';
    public const BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK1 = 'engine_par_s_travel_01_mk1';
    public const BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK2 = 'engine_par_s_travel_01_mk2';
    public const BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK3 = 'engine_par_s_travel_01_mk3';
    public const BLUEPRINT_PAR_XL_ALL_ROUND_ENGINE = 'engine_par_xl_allround_01_mk1';
    public const BLUEPRINT_PAR_XL_SHIELD_GENERATOR = 'shield_par_xl_standard_01_mk1';
    public const BLUEPRINT_PAR_XL_TRAVEL_ENGINE = 'engine_par_xl_travel_01_mk1';
    public const BLUEPRINT_PAVILION_OBSERVATION_DECK = 'module_gen_conn_observationdeck_02';
    public const BLUEPRINT_PE = 'ship_xen_m_corvette_02_a';
    public const BLUEPRINT_PEGASUS_SENTINEL = 'ship_par_s_scout_01_b';
    public const BLUEPRINT_PEGASUS_VANGUARD = 'ship_par_s_scout_01_a';
    public const BLUEPRINT_PELICAN_SENTINEL = 'ship_tel_l_trans_container_01_b';
    public const BLUEPRINT_PELICAN_VANGUARD = 'ship_tel_l_trans_container_01_a';
    public const BLUEPRINT_PENTHOUSE_OBSERVATION_DECK = 'module_gen_conn_observationdeck_03';
    public const BLUEPRINT_PEREGRINE_SENTINEL = 'ship_tel_m_bomber_01_b';
    public const BLUEPRINT_PEREGRINE_VANGUARD = 'ship_tel_m_bomber_01_a';
    public const BLUEPRINT_PERSEUS_SENTINEL = 'ship_par_s_fighter_01_b';
    public const BLUEPRINT_PERSEUS_VANGUARD = 'ship_par_s_fighter_01_a';
    public const BLUEPRINT_PHEROMONE_ART_GALLERY = 'welfare_bor_artacademy_01';
    public const BLUEPRINT_PHOENIX_E = 'ship_tel_l_destroyer_02_a';
    public const BLUEPRINT_PHOENIX_SENTINEL = 'ship_tel_l_destroyer_01_b';
    public const BLUEPRINT_PHOENIX_VANGUARD = 'ship_tel_l_destroyer_01_a';
    public const BLUEPRINT_PIRANHA = 'ship_bor_s_scout_01_a';
    public const BLUEPRINT_PLANKTON_PRODUCTION = 'module_bor_prod_plankton_01';
    public const BLUEPRINT_PLASMA_CONDUCTOR_PRODUCTION = 'module_gen_prod_plasmaconductors_01';
    public const BLUEPRINT_PLUTUS_GAS_SENTINEL = 'ship_par_m_miner_liquid_01_b';
    public const BLUEPRINT_PLUTUS_GAS_VANGUARD = 'ship_par_m_miner_liquid_01_a';
    public const BLUEPRINT_PLUTUS_MINERAL_SENTINEL = 'ship_par_m_miner_solid_01_b';
    public const BLUEPRINT_PLUTUS_MINERAL_VANGUARD = 'ship_par_m_miner_solid_01_a';
    public const BLUEPRINT_PORPOISE_GAS = 'ship_bor_m_miner_liquid_01_a';
    public const BLUEPRINT_PORPOISE_MINERAL = 'ship_bor_m_miner_solid_01_a';
    public const BLUEPRINT_PROMETHEUS = 'ship_par_m_trans_container_03_a';
    public const BLUEPRINT_PROTECTYON_SHIELD_GENERATOR = 'module_pir_stor_condensate_l_01';
    public const BLUEPRINT_PROTEIN_PASTE_PRODUCTION = 'module_ter_prod_proteinpaste_01';
    public const BLUEPRINT_PULSAR_VANGUARD = 'ship_arg_s_fighter_03_a';
    public const BLUEPRINT_QUANTUM_TUBE_PRODUCTION = 'module_gen_prod_quantumtubes_01';
    public const BLUEPRINT_QUASAR_VANGUARD = 'ship_arg_s_fighter_04_a';
    public const BLUEPRINT_RALEIGH_CONDENSATE = 'ship_pir_s_trans_condensate_01_a';
    public const BLUEPRINT_RALEIGH_CONTAINER = 'ship_pir_s_trans_container_01_a';
    public const BLUEPRINT_RAPIER = 'ship_ter_s_scout_01_a';
    public const BLUEPRINT_RAPTOR = 'ship_spl_xl_carrier_01_a';
    public const BLUEPRINT_RATTLESNAKE = 'ship_spl_l_destroyer_01_a';
    public const BLUEPRINT_RAY = 'ship_bor_l_destroyer_01_a';
    public const BLUEPRINT_REFINED_METAL_PRODUCTION = 'module_gen_prod_refinedmetals_01';
    public const BLUEPRINT_REPAIR_DRONE = 'ship_gen_xs_repairdrone_01_a';
    public const BLUEPRINT_RESOURCE_PROBE = 'resourceprobe_01';
    public const BLUEPRINT_RORQUAL_GAS = 'ship_bor_l_miner_liquid_01_a';
    public const BLUEPRINT_RORQUAL_MINERAL = 'ship_bor_l_miner_solid_01_a';
    public const BLUEPRINT_SAPPORO = 'ship_ter_l_flagship_01_a';
    public const BLUEPRINT_SATELLITE = 'satellite_mk1';
    public const BLUEPRINT_SCANNING_ARRAY_PRODUCTION = 'module_gen_prod_scanningarrays_01';
    public const BLUEPRINT_SCRAP_PROCESSOR = 'module_gen_proc_scrapworks';
    public const BLUEPRINT_SCRAP_RECYCLER = 'module_gen_prod_scrap_recycler';
    public const BLUEPRINT_SCRAP_TRACTOR = 'turret_gen_m_scrapbeam_01_mk1';
    public const BLUEPRINT_SCRUFFIN_PRODUCTION = 'module_spl_prod_scruffinfruits_01';
    public const BLUEPRINT_SE = 'ship_xen_m_miner_solid_01_a';
    public const BLUEPRINT_SELENE_SENTINEL = 'ship_par_l_trans_container_02_b';
    public const BLUEPRINT_SELENE_VANGUARD = 'ship_par_l_trans_container_02_a';
    public const BLUEPRINT_SHARK = 'ship_bor_xl_carrier_01_a';
    public const BLUEPRINT_SHIELD_COMPONENT_PRODUCTION = 'module_gen_prod_shieldcomponents_01';
    public const BLUEPRINT_SHIH = 'ship_pir_s_heavyfighter_01_a';
    public const BLUEPRINT_SHUYAKU_SENTINEL = 'ship_arg_l_trans_container_04_b';
    public const BLUEPRINT_SHUYAKU_VANGUARD = 'ship_arg_l_trans_container_04_a';
    public const BLUEPRINT_SILICON_CARBIDE_PRODUCTION = 'module_ter_prod_siliconcarbide_01';
    public const BLUEPRINT_SILICON_WAFER_PRODUCTION = 'module_gen_prod_siliconwafers_01';
    public const BLUEPRINT_SMART_CHIP_PRODUCTION = 'module_gen_prod_smartchips_01';
    public const BLUEPRINT_SOJA_BEAN_PRODUCTION = 'module_par_prod_sojabeans_01';
    public const BLUEPRINT_SOJA_HUSK_PRODUCTION = 'module_par_prod_sojahusk_01';
    public const BLUEPRINT_SONRA_SENTINEL = 'ship_arg_l_trans_container_05_b';
    public const BLUEPRINT_SONRA_VANGUARD = 'ship_arg_l_trans_container_05_a';
    public const BLUEPRINT_SPACEFUEL_PRODUCTION = 'module_arg_prod_spacefuel_01';
    public const BLUEPRINT_SPACEWEED_PRODUCTION = 'module_tel_prod_spaceweed_01';
    public const BLUEPRINT_SPICE_PRODUCTION = 'module_gen_prod_spices_01';
    public const BLUEPRINT_SPLIT_1_DOCK_PIER = 'module_spl_pier_l_02';
    public const BLUEPRINT_SPLIT_3_DOCK_E_PIER = 'module_spl_pier_l_03';
    public const BLUEPRINT_SPLIT_4_DOCK_T_PIER = 'module_spl_pier_l_01';
    public const BLUEPRINT_SPLIT_ADMINISTRATIVE_CENTRE = 'module_spl_def_claim_01';
    public const BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_01 = 'module_spl_conn_base_01';
    public const BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_02 = 'module_spl_conn_base_02';
    public const BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_03 = 'module_spl_conn_base_03';
    public const BLUEPRINT_SPLIT_BRIDGE_DEFENCE_PLATFORM = 'module_spl_def_tube_01';
    public const BLUEPRINT_SPLIT_CROSS_CONNECTION_STRUCTURE = 'module_spl_conn_cross_01';
    public const BLUEPRINT_SPLIT_DISC_DEFENCE_PLATFORM = 'module_spl_def_disc_01';
    public const BLUEPRINT_SPLIT_L_CONTAINER_STORAGE = 'module_spl_stor_container_l_01';
    public const BLUEPRINT_SPLIT_L_LIQUID_STORAGE = 'module_spl_stor_liquid_l_01';
    public const BLUEPRINT_SPLIT_L_PARLOUR = 'module_spl_hab_l_01';
    public const BLUEPRINT_SPLIT_L_SOLID_STORAGE = 'module_spl_stor_solid_l_01';
    public const BLUEPRINT_SPLIT_MEDICAL_SUPPLY_PRODUCTION = 'module_spl_prod_medicalsupplies_01';
    public const BLUEPRINT_SPLIT_M_CONTAINER_STORAGE = 'module_spl_stor_container_m_01';
    public const BLUEPRINT_SPLIT_M_LIQUID_STORAGE = 'module_spl_stor_liquid_m_01';
    public const BLUEPRINT_SPLIT_M_PARLOUR = 'module_spl_hab_m_01';
    public const BLUEPRINT_SPLIT_M_SOLID_STORAGE = 'module_spl_stor_solid_m_01';
    public const BLUEPRINT_SPLIT_S_CONTAINER_STORAGE = 'module_spl_stor_container_s_01';
    public const BLUEPRINT_SPLIT_S_LIQUID_STORAGE = 'module_spl_stor_liquid_s_01';
    public const BLUEPRINT_SPLIT_S_PARLOUR = 'module_spl_hab_s_01';
    public const BLUEPRINT_SPLIT_S_SOLID_STORAGE = 'module_spl_stor_solid_s_01';
    public const BLUEPRINT_SPLIT_VERTICAL_CONNECTION_STRUCTURE_01 = 'module_spl_conn_vertical_01';
    public const BLUEPRINT_SPLIT_VERTICAL_CONNECTION_STRUCTURE_02 = 'module_spl_conn_vertical_02';
    public const BLUEPRINT_SPL_L_ALL_ROUND_ENGINE = 'engine_spl_l_allround_01_mk1';
    public const BLUEPRINT_SPL_L_BEAM_TURRET = 'turret_spl_l_beam_01_mk1';
    public const BLUEPRINT_SPL_L_DUMBFIRE_TURRET = 'turret_spl_l_dumbfire_01_mk1';
    public const BLUEPRINT_SPL_L_MINING_TURRET = 'turret_spl_l_mining_01_mk1';
    public const BLUEPRINT_SPL_L_PLASMA_TURRET = 'turret_spl_l_plasma_01_mk1';
    public const BLUEPRINT_SPL_L_PULSE_TURRET = 'turret_spl_l_laser_01_mk1';
    public const BLUEPRINT_SPL_L_SHIELD_GENERATOR_01_MK1 = 'shield_spl_l_standard_01_mk1';
    public const BLUEPRINT_SPL_L_SHIELD_GENERATOR_01_MK2 = 'shield_spl_l_standard_01_mk2';
    public const BLUEPRINT_SPL_L_TRACKING_TURRET = 'turret_spl_l_guided_01_mk1';
    public const BLUEPRINT_SPL_L_TRAVEL_ENGINE = 'engine_spl_l_travel_01_mk1';
    public const BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK1 = 'engine_spl_m_allround_01_mk1';
    public const BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK2 = 'engine_spl_m_allround_01_mk2';
    public const BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK3 = 'engine_spl_m_allround_01_mk3';
    public const BLUEPRINT_SPL_M_BEAM_TURRET_01_MK1 = 'turret_spl_m_beam_01_mk1';
    public const BLUEPRINT_SPL_M_BEAM_TURRET_02_MK1 = 'turret_spl_m_beam_02_mk1';
    public const BLUEPRINT_SPL_M_BOLT_TURRET_01_MK1 = 'turret_spl_m_gatling_01_mk1';
    public const BLUEPRINT_SPL_M_BOLT_TURRET_02_MK1 = 'turret_spl_m_gatling_02_mk1';
    public const BLUEPRINT_SPL_M_BOSON_LANCE_01_MK1 = 'weapon_spl_m_railgun_01_mk1';
    public const BLUEPRINT_SPL_M_BOSON_LANCE_01_MK2 = 'weapon_spl_m_railgun_01_mk2';
    public const BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK1 = 'engine_spl_m_combat_01_mk1';
    public const BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK2 = 'engine_spl_m_combat_01_mk2';
    public const BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK3 = 'engine_spl_m_combat_01_mk3';
    public const BLUEPRINT_SPL_M_COMBAT_ENGINE_MK4 = 'engine_spl_m_combat_01_mk4';
    public const BLUEPRINT_SPL_M_DUMBFIRE_TURRET = 'turret_spl_m_dumbfire_02_mk1';
    public const BLUEPRINT_SPL_M_FLAK_TURRET_01_MK1 = 'turret_spl_m_flak_01_mk1';
    public const BLUEPRINT_SPL_M_FLAK_TURRET_02_MK1 = 'turret_spl_m_flak_02_mk1';
    public const BLUEPRINT_SPL_M_MINING_TURRET_01_MK1 = 'turret_spl_m_mining_01_mk1';
    public const BLUEPRINT_SPL_M_MINING_TURRET_02_MK1 = 'turret_spl_m_mining_02_mk1';
    public const BLUEPRINT_SPL_M_NEUTRON_GATLING_01_MK1 = 'weapon_spl_m_gatling_01_mk1';
    public const BLUEPRINT_SPL_M_NEUTRON_GATLING_01_MK2 = 'weapon_spl_m_gatling_01_mk2';
    public const BLUEPRINT_SPL_M_PLASMA_TURRET_01_MK1 = 'turret_spl_m_plasma_01_mk1';
    public const BLUEPRINT_SPL_M_PLASMA_TURRET_02_MK1 = 'turret_spl_m_plasma_02_mk1';
    public const BLUEPRINT_SPL_M_PULSE_TURRET_01_MK1 = 'turret_spl_m_laser_01_mk1';
    public const BLUEPRINT_SPL_M_PULSE_TURRET_02_MK1 = 'turret_spl_m_laser_02_mk1';
    public const BLUEPRINT_SPL_M_SHARD_TURRET_01_MK1 = 'turret_spl_m_shotgun_01_mk1';
    public const BLUEPRINT_SPL_M_SHARD_TURRET_02_MK1 = 'turret_spl_m_shotgun_02_mk1';
    public const BLUEPRINT_SPL_M_SHIELD_GENERATOR_01_MK1 = 'shield_spl_m_standard_01_mk1';
    public const BLUEPRINT_SPL_M_SHIELD_GENERATOR_01_MK2 = 'shield_spl_m_standard_01_mk2';
    public const BLUEPRINT_SPL_M_SHIELD_GENERATOR_02_MK1 = 'shield_spl_m_standard_02_mk1';
    public const BLUEPRINT_SPL_M_SHIELD_GENERATOR_02_MK2 = 'shield_spl_m_standard_02_mk2';
    public const BLUEPRINT_SPL_M_TAU_ACCELERATOR_01_MK1 = 'weapon_spl_m_shotgun_01_mk1';
    public const BLUEPRINT_SPL_M_TAU_ACCELERATOR_01_MK2 = 'weapon_spl_m_shotgun_01_mk2';
    public const BLUEPRINT_SPL_M_THERMAL_DISINTEGRATOR_01_MK1 = 'weapon_spl_m_sticky_01_mk1';
    public const BLUEPRINT_SPL_M_THERMAL_DISINTEGRATOR_01_MK2 = 'weapon_spl_m_sticky_01_mk2';
    public const BLUEPRINT_SPL_M_TRACKING_TURRET = 'turret_spl_m_guided_02_mk1';
    public const BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK1 = 'engine_spl_m_travel_01_mk1';
    public const BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK2 = 'engine_spl_m_travel_01_mk2';
    public const BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK3 = 'engine_spl_m_travel_01_mk3';
    public const BLUEPRINT_SPL_RATTLESNAKE_MAIN_BATTERY = 'weapon_spl_l_destroyer_01_mk1';
    public const BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK1 = 'engine_spl_s_allround_01_mk1';
    public const BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK2 = 'engine_spl_s_allround_01_mk2';
    public const BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK3 = 'engine_spl_s_allround_01_mk3';
    public const BLUEPRINT_SPL_S_BOSON_LANCE_01_MK1 = 'weapon_spl_s_railgun_01_mk1';
    public const BLUEPRINT_SPL_S_BOSON_LANCE_01_MK2 = 'weapon_spl_s_railgun_01_mk2';
    public const BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK1 = 'engine_spl_s_combat_01_mk1';
    public const BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK2 = 'engine_spl_s_combat_01_mk2';
    public const BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK3 = 'engine_spl_s_combat_01_mk3';
    public const BLUEPRINT_SPL_S_COMBAT_ENGINE_MK4 = 'engine_spl_s_combat_01_mk4';
    public const BLUEPRINT_SPL_S_NEUTRON_GATLING_01_MK1 = 'weapon_spl_s_gatling_01_mk1';
    public const BLUEPRINT_SPL_S_NEUTRON_GATLING_01_MK2 = 'weapon_spl_s_gatling_01_mk2';
    public const BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK1 = 'shield_spl_s_standard_01_mk1';
    public const BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK2 = 'shield_spl_s_standard_01_mk2';
    public const BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK3 = 'shield_spl_s_standard_01_mk3';
    public const BLUEPRINT_SPL_S_TAU_ACCELERATOR_01_MK1 = 'weapon_spl_s_shotgun_01_mk1';
    public const BLUEPRINT_SPL_S_TAU_ACCELERATOR_01_MK2 = 'weapon_spl_s_shotgun_01_mk2';
    public const BLUEPRINT_SPL_S_THERMAL_DISINTEGRATOR_01_MK1 = 'weapon_spl_s_sticky_01_mk1';
    public const BLUEPRINT_SPL_S_THERMAL_DISINTEGRATOR_01_MK2 = 'weapon_spl_s_sticky_01_mk2';
    public const BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK1 = 'engine_spl_s_travel_01_mk1';
    public const BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK2 = 'engine_spl_s_travel_01_mk2';
    public const BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK3 = 'engine_spl_s_travel_01_mk3';
    public const BLUEPRINT_SPL_XL_ALL_ROUND_ENGINE = 'engine_spl_xl_allround_01_mk1';
    public const BLUEPRINT_SPL_XL_SHIELD_GENERATOR = 'shield_spl_xl_standard_01_mk1';
    public const BLUEPRINT_SPL_XL_TRAVEL_ENGINE = 'engine_spl_xl_travel_01_mk1';
    public const BLUEPRINT_STIMULANT_PRODUCTION = 'module_ter_prod_stimulants_01';
    public const BLUEPRINT_STORK_SENTINEL = 'ship_tel_xl_resupplier_01_b';
    public const BLUEPRINT_STORK_VANGUARD = 'ship_tel_xl_resupplier_01_a';
    public const BLUEPRINT_STURGEON = 'ship_bor_l_trans_container_01_a';
    public const BLUEPRINT_SUNDER_GAS_SENTINEL = 'ship_arg_m_miner_liquid_01_b';
    public const BLUEPRINT_SUNDER_GAS_VANGUARD = 'ship_arg_m_miner_liquid_01_a';
    public const BLUEPRINT_SUNRISE_FLOWER_PRODUCTION = 'module_tel_prod_sunriseflowers_01';
    public const BLUEPRINT_SUPERFLUID_COOLANT_PRODUCTION = 'module_gen_prod_superfluidcoolant_01';
    public const BLUEPRINT_SWAMP_PLANT_PRODUCTION = 'module_tel_prod_swampplant_01';
    public const BLUEPRINT_SYN = 'ship_atf_l_destroyer_01_a';
    public const BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK1 = 'thruster_gen_s_allround_01_mk1';
    public const BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK2 = 'thruster_gen_s_allround_01_mk2';
    public const BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK3 = 'thruster_gen_s_allround_01_mk3';
    public const BLUEPRINT_S_BEAM_EMITTER_01_MK1 = 'weapon_gen_s_beam_01_mk1';
    public const BLUEPRINT_S_BEAM_EMITTER_01_MK2 = 'weapon_gen_s_beam_01_mk2';
    public const BLUEPRINT_S_BLAST_MORTAR_01_MK1 = 'weapon_gen_s_cannon_01_mk1';
    public const BLUEPRINT_S_BLAST_MORTAR_01_MK2 = 'weapon_gen_s_cannon_01_mk2';
    public const BLUEPRINT_S_BOLT_REPEATER_01_MK1 = 'weapon_gen_s_gatling_01_mk1';
    public const BLUEPRINT_S_BOLT_REPEATER_01_MK2 = 'weapon_gen_s_gatling_01_mk2';
    public const BLUEPRINT_S_BURST_RAY_01_MK1 = 'weapon_gen_s_burst_01_mk1';
    public const BLUEPRINT_S_BURST_RAY_01_MK2 = 'weapon_gen_s_burst_01_mk2';
    public const BLUEPRINT_S_COMBAT_THRUSTERS_01_MK1 = 'thruster_gen_s_combat_01_mk1';
    public const BLUEPRINT_S_COMBAT_THRUSTERS_01_MK2 = 'thruster_gen_s_combat_01_mk2';
    public const BLUEPRINT_S_COMBAT_THRUSTERS_01_MK3 = 'thruster_gen_s_combat_01_mk3';
    public const BLUEPRINT_S_DUMBFIRE_LAUNCHER_01_MK1 = 'weapon_gen_s_dumbfire_01_mk1';
    public const BLUEPRINT_S_DUMBFIRE_LAUNCHER_01_MK2 = 'weapon_gen_s_dumbfire_01_mk2';
    public const BLUEPRINT_S_MINING_DRILL_01_MK1 = 'weapon_gen_s_mining_01_mk1';
    public const BLUEPRINT_S_MINING_DRILL_01_MK2 = 'weapon_gen_s_mining_01_mk2';
    public const BLUEPRINT_S_M_SHIP_FABRICATION_BAY = 'module_gen_build_dockarea_m_01';
    public const BLUEPRINT_S_M_SHIP_MAINTENANCE_BAY = 'module_gen_equip_dockarea_m_01';
    public const BLUEPRINT_S_M_VENTURE_SENDOFF_DOCK = 'module_gen_dock_m_venturer_01';
    public const BLUEPRINT_S_PLASMA_CANNON_01_MK1 = 'weapon_gen_s_plasma_01_mk1';
    public const BLUEPRINT_S_PLASMA_CANNON_01_MK2 = 'weapon_gen_s_plasma_01_mk2';
    public const BLUEPRINT_S_PULSE_LASER_01_MK1 = 'weapon_gen_s_laser_01_mk1';
    public const BLUEPRINT_S_PULSE_LASER_01_MK2 = 'weapon_gen_s_laser_01_mk2';
    public const BLUEPRINT_S_RACING_ENGINE_01_MK1 = 'engine_gen_s_racer_01_mk1';
    public const BLUEPRINT_S_RACING_ENGINE_01_MK2 = 'engine_gen_s_racer_01_mk2';
    public const BLUEPRINT_S_RACING_SHIELD_GENERATOR = 'shield_gen_s_racer_01_mk1';
    public const BLUEPRINT_S_SHARD_BATTERY_01_MK1 = 'weapon_gen_s_shotgun_01_mk1';
    public const BLUEPRINT_S_SHARD_BATTERY_01_MK2 = 'weapon_gen_s_shotgun_01_mk2';
    public const BLUEPRINT_S_TORPEDO_LAUNCHER_01_MK1 = 'weapon_gen_s_torpedo_01_mk1';
    public const BLUEPRINT_S_TORPEDO_LAUNCHER_01_MK2 = 'weapon_gen_s_torpedo_01_mk2';
    public const BLUEPRINT_S_TRACKING_LAUNCHER_01_MK1 = 'weapon_gen_s_guided_01_mk1';
    public const BLUEPRINT_S_TRACKING_LAUNCHER_01_MK2 = 'weapon_gen_s_guided_01_mk2';
    public const BLUEPRINT_TAKOBA = 'ship_ter_s_fighter_03_a';
    public const BLUEPRINT_TELADIANIUM_PRODUCTION = 'module_tel_prod_teladianium_01';
    public const BLUEPRINT_TELADI_1_DOCK_PIER = 'module_tel_pier_l_02';
    public const BLUEPRINT_TELADI_3_DOCK_E_PIER = 'module_tel_pier_l_03';
    public const BLUEPRINT_TELADI_3_DOCK_T_PIER = 'module_tel_pier_l_01';
    public const BLUEPRINT_TELADI_ADMINISTRATIVE_CENTRE = 'module_tel_def_claim_01';
    public const BLUEPRINT_TELADI_ADVANCED_COMPOSITE_PRODUCTION = 'module_tel_prod_advancedcomposites_01';
    public const BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_01 = 'module_tel_conn_base_01';
    public const BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_02 = 'module_tel_conn_base_02';
    public const BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_03 = 'module_tel_conn_base_03';
    public const BLUEPRINT_TELADI_BRIDGE_DEFENCE_PLATFORM = 'module_tel_def_tube_01';
    public const BLUEPRINT_TELADI_CROSS_CONNECTION_STRUCTURE = 'module_tel_conn_cross_01';
    public const BLUEPRINT_TELADI_DISC_DEFENCE_PLATFORM = 'module_tel_def_disc_01';
    public const BLUEPRINT_TELADI_ENGINE_PART_PRODUCTION = 'module_tel_prod_engineparts_01';
    public const BLUEPRINT_TELADI_HULL_PART_PRODUCTION = 'module_tel_prod_hullparts_01';
    public const BLUEPRINT_TELADI_L_BIOME = 'module_tel_hab_l_01';
    public const BLUEPRINT_TELADI_L_CONTAINER_STORAGE = 'module_tel_stor_container_l_01';
    public const BLUEPRINT_TELADI_L_LIQUID_STORAGE = 'module_tel_stor_liquid_l_01';
    public const BLUEPRINT_TELADI_L_SOLID_STORAGE = 'module_tel_stor_solid_l_01';
    public const BLUEPRINT_TELADI_MEDICAL_SUPPLY_PRODUCTION = 'module_tel_prod_medicalsupplies_01';
    public const BLUEPRINT_TELADI_M_BIOME = 'module_tel_hab_m_01';
    public const BLUEPRINT_TELADI_M_CONTAINER_STORAGE = 'module_tel_stor_container_m_01';
    public const BLUEPRINT_TELADI_M_LIQUID_STORAGE = 'module_tel_stor_liquid_m_01';
    public const BLUEPRINT_TELADI_M_SOLID_STORAGE = 'module_tel_stor_solid_m_01';
    public const BLUEPRINT_TELADI_SCANNING_ARRAY_PRODUCTION = 'module_tel_prod_scanningarrays_01';
    public const BLUEPRINT_TELADI_S_BIOME = 'module_tel_hab_s_01';
    public const BLUEPRINT_TELADI_S_CONTAINER_STORAGE = 'module_tel_stor_container_s_01';
    public const BLUEPRINT_TELADI_S_LIQUID_STORAGE = 'module_tel_stor_liquid_s_01';
    public const BLUEPRINT_TELADI_S_SOLID_STORAGE = 'module_tel_stor_solid_s_01';
    public const BLUEPRINT_TELADI_VERTICAL_CONNECTION_STRUCTURE_01 = 'module_tel_conn_vertical_01';
    public const BLUEPRINT_TELADI_VERTICAL_CONNECTION_STRUCTURE_02 = 'module_tel_conn_vertical_02';
    public const BLUEPRINT_TEL_L_ALL_ROUND_ENGINE = 'engine_tel_l_allround_01_mk1';
    public const BLUEPRINT_TEL_L_BEAM_TURRET = 'turret_tel_l_beam_01_mk1';
    public const BLUEPRINT_TEL_L_DUMBFIRE_TURRET = 'turret_tel_l_dumbfire_01_mk1';
    public const BLUEPRINT_TEL_L_MINING_TURRET = 'turret_tel_l_mining_01_mk1';
    public const BLUEPRINT_TEL_L_PLASMA_TURRET = 'turret_tel_l_plasma_01_mk1';
    public const BLUEPRINT_TEL_L_PULSE_TURRET = 'turret_tel_l_laser_01_mk1';
    public const BLUEPRINT_TEL_L_SHIELD_GENERATOR_01_MK1 = 'shield_tel_l_standard_01_mk1';
    public const BLUEPRINT_TEL_L_SHIELD_GENERATOR_01_MK2 = 'shield_tel_l_standard_01_mk2';
    public const BLUEPRINT_TEL_L_TRACKING_TURRET = 'turret_tel_l_guided_01_mk1';
    public const BLUEPRINT_TEL_L_TRAVEL_ENGINE = 'engine_tel_l_travel_01_mk1';
    public const BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK1 = 'engine_tel_m_allround_01_mk1';
    public const BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK2 = 'engine_tel_m_allround_01_mk2';
    public const BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK3 = 'engine_tel_m_allround_01_mk3';
    public const BLUEPRINT_TEL_M_BEAM_TURRET_01_MK1 = 'turret_tel_m_beam_01_mk1';
    public const BLUEPRINT_TEL_M_BEAM_TURRET_02_MK1 = 'turret_tel_m_beam_02_mk1';
    public const BLUEPRINT_TEL_M_BOLT_TURRET_01_MK1 = 'turret_tel_m_gatling_01_mk1';
    public const BLUEPRINT_TEL_M_BOLT_TURRET_02_MK1 = 'turret_tel_m_gatling_02_mk1';
    public const BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK1 = 'engine_tel_m_combat_01_mk1';
    public const BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK2 = 'engine_tel_m_combat_01_mk2';
    public const BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK3 = 'engine_tel_m_combat_01_mk3';
    public const BLUEPRINT_TEL_M_DUMBFIRE_TURRET = 'turret_tel_m_dumbfire_02_mk1';
    public const BLUEPRINT_TEL_M_MINING_TURRET_01_MK1 = 'turret_tel_m_mining_01_mk1';
    public const BLUEPRINT_TEL_M_MINING_TURRET_02_MK1 = 'turret_tel_m_mining_02_mk1';
    public const BLUEPRINT_TEL_M_MUON_CHARGER_01_MK1 = 'weapon_tel_m_charge_01_mk1';
    public const BLUEPRINT_TEL_M_MUON_CHARGER_01_MK2 = 'weapon_tel_m_charge_01_mk2';
    public const BLUEPRINT_TEL_M_PLASMA_TURRET_01_MK1 = 'turret_tel_m_plasma_01_mk1';
    public const BLUEPRINT_TEL_M_PLASMA_TURRET_02_MK1 = 'turret_tel_m_plasma_02_mk1';
    public const BLUEPRINT_TEL_M_PULSE_TURRET_01_MK1 = 'turret_tel_m_laser_01_mk1';
    public const BLUEPRINT_TEL_M_PULSE_TURRET_02_MK1 = 'turret_tel_m_laser_02_mk1';
    public const BLUEPRINT_TEL_M_SHARD_TURRET_01_MK1 = 'turret_tel_m_shotgun_01_mk1';
    public const BLUEPRINT_TEL_M_SHARD_TURRET_02_MK1 = 'turret_tel_m_shotgun_02_mk1';
    public const BLUEPRINT_TEL_M_SHIELD_GENERATOR_01_MK1 = 'shield_tel_m_standard_01_mk1';
    public const BLUEPRINT_TEL_M_SHIELD_GENERATOR_01_MK2 = 'shield_tel_m_standard_01_mk2';
    public const BLUEPRINT_TEL_M_SHIELD_GENERATOR_02_MK1 = 'shield_tel_m_standard_02_mk1';
    public const BLUEPRINT_TEL_M_SHIELD_GENERATOR_02_MK2 = 'shield_tel_m_standard_02_mk2';
    public const BLUEPRINT_TEL_M_TRACKING_TURRET = 'turret_tel_m_guided_02_mk1';
    public const BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK1 = 'engine_tel_m_travel_01_mk1';
    public const BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK2 = 'engine_tel_m_travel_01_mk2';
    public const BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK3 = 'engine_tel_m_travel_01_mk3';
    public const BLUEPRINT_TEL_PHOENIX_MAIN_BATTERY = 'weapon_tel_l_destroyer_01_mk1';
    public const BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK1 = 'engine_tel_s_allround_01_mk1';
    public const BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK2 = 'engine_tel_s_allround_01_mk2';
    public const BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK3 = 'engine_tel_s_allround_01_mk3';
    public const BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK1 = 'engine_tel_s_combat_01_mk1';
    public const BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK2 = 'engine_tel_s_combat_01_mk2';
    public const BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK3 = 'engine_tel_s_combat_01_mk3';
    public const BLUEPRINT_TEL_S_MUON_CHARGER_01_MK1 = 'weapon_tel_s_charge_01_mk1';
    public const BLUEPRINT_TEL_S_MUON_CHARGER_01_MK2 = 'weapon_tel_s_charge_01_mk2';
    public const BLUEPRINT_TEL_S_RACING_ENGINE = 'engine_tel_s_racer_01_mk1';
    public const BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK1 = 'shield_tel_s_standard_01_mk1';
    public const BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK1_RACING = 'shield_tel_s_racer_01_mk1';
    public const BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK2 = 'shield_tel_s_standard_01_mk2';
    public const BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK3 = 'shield_tel_s_standard_01_mk3';
    public const BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK1 = 'engine_tel_s_travel_01_mk1';
    public const BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK2 = 'engine_tel_s_travel_01_mk2';
    public const BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK3 = 'engine_tel_s_travel_01_mk3';
    public const BLUEPRINT_TEL_XL_ALL_ROUND_ENGINE = 'engine_tel_xl_allround_01_mk1';
    public const BLUEPRINT_TEL_XL_SHIELD_GENERATOR = 'shield_tel_xl_standard_01_mk1';
    public const BLUEPRINT_TEL_XL_TRAVEL_ENGINE = 'engine_tel_xl_travel_01_mk1';
    public const BLUEPRINT_TERN_SENTINEL = 'ship_tel_m_trans_container_02_b';
    public const BLUEPRINT_TERN_VANGUARD = 'ship_tel_m_trans_container_02_a';
    public const BLUEPRINT_TERRAN_1_DOCK_PIER = 'module_ter_pier_02';
    public const BLUEPRINT_TERRAN_3_DOCK_E_PIER = 'module_ter_pier_03';
    public const BLUEPRINT_TERRAN_3_DOCK_T_PIER = 'module_ter_pier_01';
    public const BLUEPRINT_TERRAN_4M10S_LUXURY_DOCK_AREA = 'module_ter_dock_m_01_hightech';
    public const BLUEPRINT_TERRAN_4_DOCK_T_PIER = 'module_ter_pier_04';
    public const BLUEPRINT_TERRAN_ADMINISTRATIVE_CENTRE = 'module_ter_def_claim_01';
    public const BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_01 = 'module_ter_conn_base_01';
    public const BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_02 = 'module_ter_conn_base_02';
    public const BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_03 = 'module_ter_conn_base_03';
    public const BLUEPRINT_TERRAN_BRIDGE_DEFENCE_PLATFORM = 'module_ter_def_tube_01';
    public const BLUEPRINT_TERRAN_CROSS_CONNECTION_STRUCTURE = 'module_ter_conn_cross_01';
    public const BLUEPRINT_TERRAN_DISC_DEFENCE_PLATFORM = 'module_ter_def_disc_01';
    public const BLUEPRINT_TERRAN_ENERGY_CELL_PRODUCTION = 'module_ter_prod_energycells_01';
    public const BLUEPRINT_TERRAN_L_CONTAINER_STORAGE = 'module_ter_stor_container_l_01';
    public const BLUEPRINT_TERRAN_L_LIQUID_STORAGE = 'module_ter_stor_liquid_l_01';
    public const BLUEPRINT_TERRAN_L_LIVING_QUARTERS = 'module_ter_hab_l_01';
    public const BLUEPRINT_TERRAN_L_SHIP_FABRICATION_BAY = 'module_ter_build_l_01';
    public const BLUEPRINT_TERRAN_L_SHIP_MAINTENANCE_BAY = 'module_ter_equip_l_01';
    public const BLUEPRINT_TERRAN_L_SOLID_STORAGE = 'module_ter_stor_solid_l_01';
    public const BLUEPRINT_TERRAN_MAIN_BATTERY = 'weapon_ter_l_destroyer_01_mk1';
    public const BLUEPRINT_TERRAN_MEDICAL_SUPPLY_PRODUCTION = 'module_ter_prod_medicalsupplies_01';
    public const BLUEPRINT_TERRAN_MRE_PRODUCTION = 'module_ter_prod_terranmre_01';
    public const BLUEPRINT_TERRAN_M_CONTAINER_STORAGE = 'module_ter_stor_container_m_01';
    public const BLUEPRINT_TERRAN_M_LIQUID_STORAGE = 'module_ter_stor_liquid_m_01';
    public const BLUEPRINT_TERRAN_M_LIVING_QUARTERS = 'module_ter_hab_m_01';
    public const BLUEPRINT_TERRAN_M_SOLID_STORAGE = 'module_ter_stor_solid_m_01';
    public const BLUEPRINT_TERRAN_SCRAP_RECYCLER = 'module_ter_prod_scrap_recycler';
    public const BLUEPRINT_TERRAN_S_CONTAINER_STORAGE = 'module_ter_stor_container_s_01';
    public const BLUEPRINT_TERRAN_S_LIQUID_STORAGE = 'module_ter_stor_liquid_s_01';
    public const BLUEPRINT_TERRAN_S_LIVING_QUARTERS = 'module_ter_hab_s_01';
    public const BLUEPRINT_TERRAN_S_M_SHIP_FABRICATION_BAY = 'module_ter_build_dockarea_m_01';
    public const BLUEPRINT_TERRAN_S_M_SHIP_MAINTENANCE_BAY = 'module_ter_equip_dockarea_m_01';
    public const BLUEPRINT_TERRAN_S_SOLID_STORAGE = 'module_ter_stor_solid_s_01';
    public const BLUEPRINT_TERRAN_TRADING_STATION_HEXA_DOCK_PIER = 'module_ter_pier_tradestation_01';
    public const BLUEPRINT_TERRAN_VERTICAL_CONNECTION_STRUCTURE_01 = 'module_ter_conn_vertical_01';
    public const BLUEPRINT_TERRAN_VERTICAL_CONNECTION_STRUCTURE_02 = 'module_ter_conn_vertical_02';
    public const BLUEPRINT_TERRAN_XL_SHIP_FABRICATION_BAY = 'module_ter_build_xl_01';
    public const BLUEPRINT_TERRAN_XL_SHIP_MAINTENANCE_BAY = 'module_ter_equip_xl_01';
    public const BLUEPRINT_TERRAPIN = 'ship_bor_s_trans_container_01_a';
    public const BLUEPRINT_TER_L_ALL_ROUND_ENGINE = 'engine_ter_l_allround_01_mk1';
    public const BLUEPRINT_TER_L_BEAM_TURRET = 'turret_ter_l_beam_01_mk1';
    public const BLUEPRINT_TER_L_BOLT_TURRET = 'turret_ter_l_gatling_01_mk1';
    public const BLUEPRINT_TER_L_DUMBFIRE_TURRET = 'turret_ter_l_dumbfire_01_mk1';
    public const BLUEPRINT_TER_L_FRONTIER_ENGINE = 'engine_ter_l_allround_02_mk1';
    public const BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK1 = 'shield_ter_l_standard_02_mk1';
    public const BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK2 = 'shield_ter_l_standard_02_mk2';
    public const BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK3 = 'shield_ter_l_standard_02_mk3';
    public const BLUEPRINT_TER_L_MINING_TURRET = 'turret_ter_l_mining_01_mk1';
    public const BLUEPRINT_TER_L_PULSE_TURRET = 'turret_ter_l_laser_01_mk1';
    public const BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK1 = 'shield_ter_l_standard_01_mk1';
    public const BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK2 = 'shield_ter_l_standard_01_mk2';
    public const BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK3 = 'shield_ter_l_standard_01_mk3';
    public const BLUEPRINT_TER_L_TRACKING_TURRET = 'turret_ter_l_guided_01_mk1';
    public const BLUEPRINT_TER_L_TRAVEL_ENGINE = 'engine_ter_l_travel_01_mk1';
    public const BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK1 = 'engine_ter_m_allround_01_mk1';
    public const BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK2 = 'engine_ter_m_allround_01_mk2';
    public const BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK3 = 'engine_ter_m_allround_01_mk3';
    public const BLUEPRINT_TER_M_BEAM_TURRET_01_MK1 = 'turret_ter_m_beam_01_mk1';
    public const BLUEPRINT_TER_M_BEAM_TURRET_02_MK1 = 'turret_ter_m_beam_02_mk1';
    public const BLUEPRINT_TER_M_BOLT_TURRET_01_MK1 = 'turret_ter_m_gatling_01_mk1';
    public const BLUEPRINT_TER_M_BOLT_TURRET_02_MK1 = 'turret_ter_m_gatling_02_mk1';
    public const BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK1 = 'engine_ter_m_combat_01_mk1';
    public const BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK2 = 'engine_ter_m_combat_01_mk2';
    public const BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK3 = 'engine_ter_m_combat_01_mk3';
    public const BLUEPRINT_TER_M_DUMBFIRE_TURRET = 'turret_ter_m_dumbfire_02_mk1';
    public const BLUEPRINT_TER_M_ELECTROMAGNETIC_CANNON = 'weapon_ter_m_laser_02_mk1';
    public const BLUEPRINT_TER_M_ELECTROMAGNETIC_TURRET_03_MK1 = 'turret_ter_m_laser_03_mk1';
    public const BLUEPRINT_TER_M_ELECTROMAGNETIC_TURRET_04_MK1 = 'turret_ter_m_laser_04_mk1';
    public const BLUEPRINT_TER_M_FRONTIER_ENGINE = 'engine_ter_m_virtual_01_mk1';
    public const BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK1 = 'shield_ter_m_virtual_01_mk1';
    public const BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK2 = 'shield_ter_m_virtual_01_mk2';
    public const BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK3 = 'shield_ter_m_virtual_01_mk3';
    public const BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK1 = 'shield_ter_m_standard_04_mk1';
    public const BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK2 = 'shield_ter_m_standard_04_mk2';
    public const BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK3 = 'shield_ter_m_standard_04_mk3';
    public const BLUEPRINT_TER_M_MESON_STREAM_01_MK1 = 'weapon_ter_m_beam_01_mk1';
    public const BLUEPRINT_TER_M_MESON_STREAM_01_MK2 = 'weapon_ter_m_beam_01_mk2';
    public const BLUEPRINT_TER_M_MINING_TURRET_01_MK1 = 'turret_ter_m_mining_01_mk1';
    public const BLUEPRINT_TER_M_MINING_TURRET_02_MK1 = 'turret_ter_m_mining_02_mk1';
    public const BLUEPRINT_TER_M_PROTON_BARRAGE_01_MK1 = 'weapon_ter_m_gatling_01_mk1';
    public const BLUEPRINT_TER_M_PROTON_BARRAGE_01_MK2 = 'weapon_ter_m_gatling_01_mk2';
    public const BLUEPRINT_TER_M_PULSE_LASER_01_MK1 = 'weapon_ter_m_laser_01_mk1';
    public const BLUEPRINT_TER_M_PULSE_LASER_01_MK2 = 'weapon_ter_m_laser_01_mk2';
    public const BLUEPRINT_TER_M_PULSE_TURRET_01_MK1 = 'turret_ter_m_laser_01_mk1';
    public const BLUEPRINT_TER_M_PULSE_TURRET_02_MK1 = 'turret_ter_m_laser_02_mk1';
    public const BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK1 = 'shield_ter_m_standard_01_mk1';
    public const BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK2 = 'shield_ter_m_standard_01_mk2';
    public const BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK3 = 'shield_ter_m_standard_01_mk3';
    public const BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK1 = 'shield_ter_m_standard_02_mk1';
    public const BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK2 = 'shield_ter_m_standard_02_mk2';
    public const BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK3 = 'shield_ter_m_standard_02_mk3';
    public const BLUEPRINT_TER_M_TRACKING_TURRET = 'turret_ter_m_guided_02_mk1';
    public const BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK1 = 'engine_ter_m_travel_01_mk1';
    public const BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK2 = 'engine_ter_m_travel_01_mk2';
    public const BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK3 = 'engine_ter_m_travel_01_mk3';
    public const BLUEPRINT_TER_SAPPORO_LAUNCHER_ARRAY = 'weapon_ter_l_flagship_01_mk1';
    public const BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK1 = 'engine_ter_s_allround_01_mk1';
    public const BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK2 = 'engine_ter_s_allround_01_mk2';
    public const BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK3 = 'engine_ter_s_allround_01_mk3';
    public const BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK1 = 'engine_ter_s_combat_01_mk1';
    public const BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK2 = 'engine_ter_s_combat_01_mk2';
    public const BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK3 = 'engine_ter_s_combat_01_mk3';
    public const BLUEPRINT_TER_S_ELECTROMAGNETIC_GUN = 'weapon_ter_s_laser_02_mk1';
    public const BLUEPRINT_TER_S_FRONTIER_ENGINE = 'engine_ter_s_virtual_01_mk1';
    public const BLUEPRINT_TER_S_FRONTIER_SHIELD_GENERATOR = 'shield_ter_s_virtual_01_mk1';
    public const BLUEPRINT_TER_S_FRONTIER_SHIELD_GENERATOR_MK5 = 'shield_ter_s_xperimental_01_mk5';
    public const BLUEPRINT_TER_S_MESON_STREAM_01_MK1 = 'weapon_ter_s_beam_01_mk1';
    public const BLUEPRINT_TER_S_MESON_STREAM_01_MK2 = 'weapon_ter_s_beam_01_mk2';
    public const BLUEPRINT_TER_S_PROTON_BARRAGE_01_MK1 = 'weapon_ter_s_gatling_01_mk1';
    public const BLUEPRINT_TER_S_PROTON_BARRAGE_01_MK2 = 'weapon_ter_s_gatling_01_mk2';
    public const BLUEPRINT_TER_S_PULSE_LASER_01_MK1 = 'weapon_ter_s_laser_01_mk1';
    public const BLUEPRINT_TER_S_PULSE_LASER_01_MK2 = 'weapon_ter_s_laser_01_mk2';
    public const BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK1 = 'shield_ter_s_standard_01_mk1';
    public const BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK2 = 'shield_ter_s_standard_01_mk2';
    public const BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK3 = 'shield_ter_s_standard_01_mk3';
    public const BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK1 = 'engine_ter_s_travel_01_mk1';
    public const BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK2 = 'engine_ter_s_travel_01_mk2';
    public const BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK3 = 'engine_ter_s_travel_01_mk3';
    public const BLUEPRINT_TER_XL_ALL_ROUND_ENGINE = 'engine_ter_xl_allround_01_mk1';
    public const BLUEPRINT_TER_XL_SHIELD_GENERATOR_01_MK1 = 'shield_ter_xl_standard_01_mk1';
    public const BLUEPRINT_TER_XL_SHIELD_GENERATOR_01_MK2 = 'shield_ter_xl_standard_01_mk2';
    public const BLUEPRINT_TER_XL_TRAVEL_ENGINE = 'engine_ter_xl_travel_01_mk1';
    public const BLUEPRINT_TETHYS_MINERAL = 'ship_par_s_miner_solid_01_a';
    public const BLUEPRINT_TETHYS_SENTINEL = 'ship_par_s_trans_container_01_b';
    public const BLUEPRINT_TETHYS_VANGUARD = 'ship_par_s_trans_container_01_a';
    public const BLUEPRINT_TEUTA = 'ship_pir_l_scrapper_01';
    public const BLUEPRINT_THESEUS_SENTINEL = 'ship_par_s_fighter_02_b';
    public const BLUEPRINT_THESEUS_SPORT = 'ship_par_s_racer_01_a';
    public const BLUEPRINT_THESEUS_VANGUARD = 'ship_par_s_fighter_02_a';
    public const BLUEPRINT_THRESHER = 'ship_bor_m_gunboat_01_a';
    public const BLUEPRINT_TOKYO = 'ship_ter_xl_carrier_01_a';
    public const BLUEPRINT_TRACKER_MINE = 'weapon_gen_mine_02';
    public const BLUEPRINT_TUATARA = 'ship_spl_s_trans_container_01_a';
    public const BLUEPRINT_TUATARA_MINERAL = 'ship_spl_s_miner_solid_01_a';
    public const BLUEPRINT_TURRET_COMPONENT_PRODUCTION = 'module_gen_prod_turretcomponents_01';
    public const BLUEPRINT_VELES_SENTINEL = 'ship_arg_l_trans_container_01_b';
    public const BLUEPRINT_VELES_VANGUARD = 'ship_arg_l_trans_container_01_a';
    public const BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_01 = 'module_gen_conn_venturerbase_01';
    public const BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_02 = 'module_gen_conn_venturerbase_02';
    public const BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_03 = 'module_gen_conn_venturerbase_03';
    public const BLUEPRINT_VENTURE_CROSS_CONNECTION_STRUCTURE = 'module_gen_conn_venturercross_01';
    public const BLUEPRINT_VENTURE_PLATFORM = 'module_gen_ventureplatform_cross_01';
    public const BLUEPRINT_VENTURE_VERTICAL_CONNECTION_STRUCTURE_01 = 'module_gen_conn_venturervertical_01';
    public const BLUEPRINT_VENTURE_VERTICAL_CONNECTION_STRUCTURE_02 = 'module_gen_conn_venturervertical_02';
    public const BLUEPRINT_VULTURE_SENTINEL = 'ship_tel_m_trans_container_01_b';
    public const BLUEPRINT_VULTURE_VANGUARD = 'ship_tel_m_trans_container_01_a';
    public const BLUEPRINT_WALRUS = 'ship_bor_xl_builder_01_a';
    public const BLUEPRINT_WATER_PRODUCTION = 'module_gen_prod_water_01';
    public const BLUEPRINT_WEAPON_COMPONENT_PRODUCTION = 'module_gen_prod_weaponcomponents_01';
    public const BLUEPRINT_WHEAT_PRODUCTION = 'module_arg_prod_wheat_01';
    public const BLUEPRINT_WIDE_AREA_SENSOR_ARRAY = 'module_arg_radar_dish_01';
    public const BLUEPRINT_WYVERN_GAS = 'ship_spl_l_miner_liquid_01_a';
    public const BLUEPRINT_WYVERN_MINERAL = 'ship_spl_l_miner_solid_01_a';
    public const BLUEPRINT_XEN_L_ALL_ROUND_ENGINE = 'engine_xen_l_allround_02_mk1';
    public const BLUEPRINT_XEN_L_SEISMIC_CHARGE_TURRET = 'turret_xen_l_plasma_01_mk1';
    public const BLUEPRINT_XEN_L_SHIELD_GENERATOR_02_MK1 = 'shield_xen_l_standard_02_mk1';
    public const BLUEPRINT_XEN_L_SHIELD_GENERATOR_02_MK2 = 'shield_xen_l_standard_02_mk2';
    public const BLUEPRINT_XEN_M_COMBAT_ENGINE = 'engine_xen_m_virtual_01_mk1';
    public const BLUEPRINT_XEN_M_IMPULSE_PROJECTOR = 'weapon_xen_m_laser_02_mk1';
    public const BLUEPRINT_XEN_M_MINING_DRILL = 'weapon_xen_m_mining_02_mk1';
    public const BLUEPRINT_XEN_M_NEEDLER_TURRET_01_MK1 = 'turret_xen_m_gatling_01_mk1';
    public const BLUEPRINT_XEN_M_NEEDLER_TURRET_02_MK1 = 'turret_xen_m_gatling_02_mk1';
    public const BLUEPRINT_XEN_M_PLASMA_CUTTER_BEAM = 'weapon_xen_m_beam_01_mk1';
    public const BLUEPRINT_XEN_M_SHIELD_GENERATOR_01_MK1 = 'shield_xen_m_virtual_01_mk1';
    public const BLUEPRINT_XEN_M_SHIELD_GENERATOR_04_MK1 = 'shield_xen_m_standard_04_mk1';
    public const BLUEPRINT_XEN_S_COMBAT_ENGINE = 'engine_xen_s_virtual_01_mk1';
    public const BLUEPRINT_XEN_S_NEEDLER_GUN = 'weapon_xen_s_gatling_01_mk1';
    public const BLUEPRINT_XEN_S_SHIELD_GENERATOR = 'shield_xen_s_virtual_01_mk1';
    public const BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK1 = 'thruster_gen_xl_allround_01_mk1';
    public const BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK2 = 'thruster_gen_xl_allround_01_mk2';
    public const BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK3 = 'thruster_gen_xl_allround_01_mk3';
    public const BLUEPRINT_XL_SHIP_FABRICATION_BAY = 'module_gen_build_xl_01';
    public const BLUEPRINT_XL_SHIP_MAINTENANCE_BAY = 'module_gen_equip_xl_01';
    public const BLUEPRINT_XPERIMENTAL_SHUTTLE = 'ship_ter_s_xperimental_01_a';
    public const BLUEPRINT_ZEUS_E = 'ship_par_xl_carrier_02_a';
    public const BLUEPRINT_ZEUS_SENTINEL = 'ship_par_xl_carrier_01_b';
    public const BLUEPRINT_ZEUS_VANGUARD = 'ship_par_xl_carrier_01_a';
    
    public const BLUEPRINTS = array(
        self::BLUEPRINT_1M6S_BASIC_DOCK_AREA,
        self::BLUEPRINT_1M6S_LUXURY_DOCK_AREA,
        self::BLUEPRINT_1M6S_STANDARD_DOCK_AREA,
        self::BLUEPRINT_3M6S_BASIC_DOCK_AREA,
        self::BLUEPRINT_3M6S_LUXURY_DOCK_AREA,
        self::BLUEPRINT_3M6S_STANDARD_DOCK_AREA,
        self::BLUEPRINT_8M_LUXURY_DOCK_AREA,
        self::BLUEPRINT_ADVANCED_COMPOSITE_PRODUCTION,
        self::BLUEPRINT_ADVANCED_ELECTRONICS_PRODUCTION,
        self::BLUEPRINT_ADVANCED_SATELLITE,
        self::BLUEPRINT_ALBATROSS_SENTINEL,
        self::BLUEPRINT_ALBATROSS_VANGUARD,
        self::BLUEPRINT_ALLIGATOR_GAS,
        self::BLUEPRINT_ALLIGATOR_MINERAL,
        self::BLUEPRINT_ANTIMATTER_CELL_PRODUCTION,
        self::BLUEPRINT_ANTIMATTER_CONVERTER_PRODUCTION,
        self::BLUEPRINT_ARES,
        self::BLUEPRINT_ARGON_1_DOCK_PIER,
        self::BLUEPRINT_ARGON_1_DOCK_SHORT_PIER,
        self::BLUEPRINT_ARGON_3_DOCK_E_PIER,
        self::BLUEPRINT_ARGON_3_DOCK_T_PIER,
        self::BLUEPRINT_ARGON_ADMINISTRATIVE_CENTRE,
        self::BLUEPRINT_ARGON_ARC_CONNECTION_STRUCTURE,
        self::BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_ARGON_BRIDGE_DEFENCE_PLATFORM,
        self::BLUEPRINT_ARGON_CROSS_CONNECTION_STRUCTURE,
        self::BLUEPRINT_ARGON_DISC_DEFENCE_PLATFORM,
        self::BLUEPRINT_ARGON_L_CONTAINER_STORAGE,
        self::BLUEPRINT_ARGON_L_DORMITORY,
        self::BLUEPRINT_ARGON_L_HABITAT,
        self::BLUEPRINT_ARGON_L_HOUSING_SPIRE,
        self::BLUEPRINT_ARGON_L_LIQUID_STORAGE,
        self::BLUEPRINT_ARGON_L_SOLID_STORAGE,
        self::BLUEPRINT_ARGON_MEDICAL_SUPPLY_PRODUCTION,
        self::BLUEPRINT_ARGON_M_CONTAINER_STORAGE,
        self::BLUEPRINT_ARGON_M_DORMITORY,
        self::BLUEPRINT_ARGON_M_HABITAT,
        self::BLUEPRINT_ARGON_M_LIQUID_STORAGE,
        self::BLUEPRINT_ARGON_M_SOLID_STORAGE,
        self::BLUEPRINT_ARGON_SPAN_CONNECTION_STRUCTURE,
        self::BLUEPRINT_ARGON_SPAN_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_ARGON_S_CONTAINER_STORAGE,
        self::BLUEPRINT_ARGON_S_DORMITORY,
        self::BLUEPRINT_ARGON_S_HABITAT,
        self::BLUEPRINT_ARGON_S_LIQUID_STORAGE,
        self::BLUEPRINT_ARGON_S_SOLID_STORAGE,
        self::BLUEPRINT_ARGON_VERTICAL_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_ARGON_VERTICAL_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_ARGON_XL_HOUSING_SPIRE,
        self::BLUEPRINT_ARG_BEHEMOTH_MAIN_BATTERY,
        self::BLUEPRINT_ARG_L_ALL_ROUND_ENGINE,
        self::BLUEPRINT_ARG_L_BEAM_TURRET,
        self::BLUEPRINT_ARG_L_DUMBFIRE_TURRET,
        self::BLUEPRINT_ARG_L_MINING_TURRET,
        self::BLUEPRINT_ARG_L_PLASMA_TURRET,
        self::BLUEPRINT_ARG_L_PULSE_TURRET,
        self::BLUEPRINT_ARG_L_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_ARG_L_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_ARG_L_TRACKING_TURRET,
        self::BLUEPRINT_ARG_L_TRAVEL_ENGINE,
        self::BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_ARG_M_BEAM_TURRET_01_MK1,
        self::BLUEPRINT_ARG_M_BEAM_TURRET_02_MK1,
        self::BLUEPRINT_ARG_M_BOLT_TURRET_01_MK1,
        self::BLUEPRINT_ARG_M_BOLT_TURRET_02_MK1,
        self::BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_ARG_M_DUMBFIRE_TURRET,
        self::BLUEPRINT_ARG_M_FLAK_TURRET_01_MK1,
        self::BLUEPRINT_ARG_M_FLAK_TURRET_02_MK1,
        self::BLUEPRINT_ARG_M_ION_BLASTER_01_MK1,
        self::BLUEPRINT_ARG_M_ION_BLASTER_01_MK2,
        self::BLUEPRINT_ARG_M_MINING_TURRET_01_MK1,
        self::BLUEPRINT_ARG_M_MINING_TURRET_02_MK1,
        self::BLUEPRINT_ARG_M_PLASMA_TURRET_01_MK1,
        self::BLUEPRINT_ARG_M_PLASMA_TURRET_02_MK1,
        self::BLUEPRINT_ARG_M_PULSE_TURRET_01_MK1,
        self::BLUEPRINT_ARG_M_PULSE_TURRET_02_MK1,
        self::BLUEPRINT_ARG_M_SHARD_TURRET_01_MK1,
        self::BLUEPRINT_ARG_M_SHARD_TURRET_02_MK1,
        self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_ARG_M_TRACKING_TURRET,
        self::BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_ARG_S_GAMMA_HEPT,
        self::BLUEPRINT_ARG_S_ION_BLASTER_01_MK1,
        self::BLUEPRINT_ARG_S_ION_BLASTER_01_MK2,
        self::BLUEPRINT_ARG_S_RACING_ENGINE,
        self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK1_RACING,
        self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_ARG_XL_ALL_ROUND_ENGINE,
        self::BLUEPRINT_ARG_XL_SHIELD_GENERATOR,
        self::BLUEPRINT_ARG_XL_TRAVEL_ENGINE,
        self::BLUEPRINT_ASGARD,
        self::BLUEPRINT_ASP,
        self::BLUEPRINT_ASP_RAIDER,
        self::BLUEPRINT_ATF_XL_MAIN_BATTERY,
        self::BLUEPRINT_ATLAS_E,
        self::BLUEPRINT_ATLAS_SENTINEL,
        self::BLUEPRINT_ATLAS_VANGUARD,
        self::BLUEPRINT_B,
        self::BLUEPRINT_BALAUR,
        self::BLUEPRINT_BALDRIC,
        self::BLUEPRINT_BARBAROSSA,
        self::BLUEPRINT_BARRACUDA,
        self::BLUEPRINT_BEHEMOTH_E,
        self::BLUEPRINT_BEHEMOTH_SENTINEL,
        self::BLUEPRINT_BEHEMOTH_VANGUARD,
        self::BLUEPRINT_BOA,
        self::BLUEPRINT_BOFU_PRODUCTION,
        self::BLUEPRINT_BOGAS_PRODUCTION,
        self::BLUEPRINT_BOLO_GAS,
        self::BLUEPRINT_BOLO_MINERAL,
        self::BLUEPRINT_BORON_1_DOCK_PIER,
        self::BLUEPRINT_BORON_3_DOCK_E_PIER,
        self::BLUEPRINT_BORON_4M14S_LUXURY_DOCK_AREA,
        self::BLUEPRINT_BORON_4_DOCK_T_PIER,
        self::BLUEPRINT_BORON_ADMINISTRATIVE_CENTRE,
        self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_04,
        self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_05,
        self::BLUEPRINT_BORON_BRIDGE_DEFENCE_PLATFORM,
        self::BLUEPRINT_BORON_CROSS_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_BORON_CROSS_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_BORON_DISC_DEFENCE_PLATFORM,
        self::BLUEPRINT_BORON_L_CONTAINER_STORAGE,
        self::BLUEPRINT_BORON_L_LIQUID_STORAGE,
        self::BLUEPRINT_BORON_L_OASIS,
        self::BLUEPRINT_BORON_L_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_BORON_L_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_BORON_L_SOLID_STORAGE,
        self::BLUEPRINT_BORON_MEDICAL_SUPPLY_PRODUCTION,
        self::BLUEPRINT_BORON_M_CONTAINER_STORAGE,
        self::BLUEPRINT_BORON_M_LIQUID_STORAGE,
        self::BLUEPRINT_BORON_M_OASIS,
        self::BLUEPRINT_BORON_M_SOLID_STORAGE,
        self::BLUEPRINT_BORON_S_CONTAINER_STORAGE,
        self::BLUEPRINT_BORON_S_LIQUID_STORAGE,
        self::BLUEPRINT_BORON_S_M_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_BORON_S_M_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_BORON_S_OASIS,
        self::BLUEPRINT_BORON_S_SOLID_STORAGE,
        self::BLUEPRINT_BORON_TRADING_STATION_4_DOCK_PIER,
        self::BLUEPRINT_BORON_TRADING_STATION_HEXA_DOCK_PIER,
        self::BLUEPRINT_BORON_VERTICAL_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_BORON_VERTICAL_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_BORON_XL_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_BORON_XL_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_BOR_L_ALL_ROUND_ENGINE,
        self::BLUEPRINT_BOR_L_ION_FLAK_TURRET,
        self::BLUEPRINT_BOR_L_ION_NET_LAUNCHER,
        self::BLUEPRINT_BOR_L_MINING_TURRET,
        self::BLUEPRINT_BOR_L_PHASE_TURRET,
        self::BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_BOR_M_ARC_TURRET_01_MK1,
        self::BLUEPRINT_BOR_M_ARC_TURRET_02_MK1,
        self::BLUEPRINT_BOR_M_DUMBFIRE_LAUNCHER,
        self::BLUEPRINT_BOR_M_DUMBFIRE_TURRET,
        self::BLUEPRINT_BOR_M_ION_ATOMISER,
        self::BLUEPRINT_BOR_M_ION_PULSE_RAILGUN,
        self::BLUEPRINT_BOR_M_ION_PULSE_TURRET_01_MK1,
        self::BLUEPRINT_BOR_M_ION_PULSE_TURRET_02_MK1,
        self::BLUEPRINT_BOR_M_MINING_DRILL,
        self::BLUEPRINT_BOR_M_MINING_TURRET_01_MK1,
        self::BLUEPRINT_BOR_M_MINING_TURRET_02_MK1,
        self::BLUEPRINT_BOR_M_PHASE_CANNON,
        self::BLUEPRINT_BOR_M_PHASE_TURRET_01_MK1,
        self::BLUEPRINT_BOR_M_PHASE_TURRET_02_MK1,
        self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK3,
        self::BLUEPRINT_BOR_M_TORPEDO_LAUNCHER,
        self::BLUEPRINT_BOR_M_TRACKING_LAUNCHER,
        self::BLUEPRINT_BOR_M_TRACKING_TURRET,
        self::BLUEPRINT_BOR_RAY_ION_PROJECTOR,
        self::BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_BOR_S_ARC_GUN,
        self::BLUEPRINT_BOR_S_DUMBFIRE_LAUNCHER,
        self::BLUEPRINT_BOR_S_ION_GATLING,
        self::BLUEPRINT_BOR_S_MINING_DRILL,
        self::BLUEPRINT_BOR_S_PHASE_GUN,
        self::BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_BOR_S_TORPEDO_LAUNCHER,
        self::BLUEPRINT_BOR_S_TRACKING_LAUNCHER,
        self::BLUEPRINT_BOR_XL_ALL_ROUND_ENGINE,
        self::BLUEPRINT_BOR_XL_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_BOR_XL_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_BUFFALO,
        self::BLUEPRINT_BUILDING_DRONE,
        self::BLUEPRINT_BUZZARD_SENTINEL,
        self::BLUEPRINT_BUZZARD_VANGUARD,
        self::BLUEPRINT_CALLISTO_SENTINEL,
        self::BLUEPRINT_CALLISTO_VANGUARD,
        self::BLUEPRINT_CARGO_DRONE,
        self::BLUEPRINT_CASINO,
        self::BLUEPRINT_CERBERUS_SENTINEL,
        self::BLUEPRINT_CERBERUS_VANGUARD,
        self::BLUEPRINT_CHELT_PRODUCTION,
        self::BLUEPRINT_CHIMERA,
        self::BLUEPRINT_CHTHONIOS_E_GAS,
        self::BLUEPRINT_CHTHONIOS_E_MINERAL,
        self::BLUEPRINT_CHTHONIOS_GAS_SENTINEL,
        self::BLUEPRINT_CHTHONIOS_GAS_VANGUARD,
        self::BLUEPRINT_CHTHONIOS_MINERAL_SENTINEL,
        self::BLUEPRINT_CHTHONIOS_MINERAL_VANGUARD,
        self::BLUEPRINT_CLAYTRONICS_PRODUCTION,
        self::BLUEPRINT_CLUSTER_MINE,
        self::BLUEPRINT_COBRA,
        self::BLUEPRINT_COLOSSUS_E,
        self::BLUEPRINT_COLOSSUS_SENTINEL,
        self::BLUEPRINT_COLOSSUS_VANGUARD,
        self::BLUEPRINT_COMPUTRONIC_SUBSTRATE_PRODUCTION,
        self::BLUEPRINT_CONDENSATE_CONTAINMENT_FACILITY,
        self::BLUEPRINT_CONDOR_SENTINEL,
        self::BLUEPRINT_CONDOR_VANGUARD,
        self::BLUEPRINT_CONSERVATORY_OBSERVATION_DECK,
        self::BLUEPRINT_CORMORANT_VANGUARD,
        self::BLUEPRINT_COURIER_MINERAL,
        self::BLUEPRINT_COURIER_SENTINEL,
        self::BLUEPRINT_COURIER_VANGUARD,
        self::BLUEPRINT_CRANE_E_GAS,
        self::BLUEPRINT_CRANE_E_MINERAL,
        self::BLUEPRINT_CRANE_GAS_SENTINEL,
        self::BLUEPRINT_CRANE_GAS_VANGUARD,
        self::BLUEPRINT_CRANE_MINERAL_SENTINEL,
        self::BLUEPRINT_CRANE_MINERAL_VANGUARD,
        self::BLUEPRINT_CUTLASS,
        self::BLUEPRINT_DART,
        self::BLUEPRINT_DEFENCE_DRONE,
        self::BLUEPRINT_DEMETER_SENTINEL,
        self::BLUEPRINT_DEMETER_VANGUARD,
        self::BLUEPRINT_DISCOVERER_SENTINEL,
        self::BLUEPRINT_DISCOVERER_VANGUARD,
        self::BLUEPRINT_DOLPHIN,
        self::BLUEPRINT_DONIA,
        self::BLUEPRINT_DRAGON,
        self::BLUEPRINT_DRAGON_RAIDER,
        self::BLUEPRINT_DRILL_MINERAL_SENTINEL,
        self::BLUEPRINT_DRILL_MINERAL_VANGUARD,
        self::BLUEPRINT_DRONE_COMPONENT_PRODUCTION,
        self::BLUEPRINT_ECLIPSE_SENTINEL,
        self::BLUEPRINT_ECLIPSE_VANGUARD_01_A,
        self::BLUEPRINT_ECLIPSE_VANGUARD_02_A,
        self::BLUEPRINT_ELEPHANT,
        self::BLUEPRINT_ELITE_SENTINEL,
        self::BLUEPRINT_ELITE_SPORT,
        self::BLUEPRINT_ELITE_VANGUARD,
        self::BLUEPRINT_EMP_MISSILE,
        self::BLUEPRINT_ENERGY_CELL_PRODUCTION,
        self::BLUEPRINT_ENGINE_PART_PRODUCTION,
        self::BLUEPRINT_ERLKING,
        self::BLUEPRINT_ERLKING_L_TURRET,
        self::BLUEPRINT_ERLKING_MAIN_BATTERY,
        self::BLUEPRINT_ERLKING_M_TURRET,
        self::BLUEPRINT_ERLKING_XL_ENGINE,
        self::BLUEPRINT_ERLKING_XL_SHIELD_GENERATOR,
        self::BLUEPRINT_F,
        self::BLUEPRINT_FALCON_SENTINEL,
        self::BLUEPRINT_FALCON_VANGUARD,
        self::BLUEPRINT_FALX,
        self::BLUEPRINT_FIELD_COIL_PRODUCTION,
        self::BLUEPRINT_FLARES,
        self::BLUEPRINT_FOOD_RATION_PRODUCTION,
        self::BLUEPRINT_FRIEND_FOE_MINE,
        self::BLUEPRINT_FROG,
        self::BLUEPRINT_GAMBLING_DEN,
        self::BLUEPRINT_GANNASCUS,
        self::BLUEPRINT_GAS_COLLECTOR_DRONE,
        self::BLUEPRINT_GLADIUS,
        self::BLUEPRINT_GORGON_SENTINEL,
        self::BLUEPRINT_GORGON_VANGUARD,
        self::BLUEPRINT_GRAPHENE_PRODUCTION,
        self::BLUEPRINT_GROUPER_MINERAL,
        self::BLUEPRINT_GUILLEMOT_SENTINEL,
        self::BLUEPRINT_GUILLEMOT_VANGUARD,
        self::BLUEPRINT_GUPPY,
        self::BLUEPRINT_H,
        self::BLUEPRINT_HEAVY_BARRAGE_MISSILE,
        self::BLUEPRINT_HEAVY_CLUSTER_MISSILE,
        self::BLUEPRINT_HEAVY_DUMBFIRE_MISSILE_MK1,
        self::BLUEPRINT_HEAVY_DUMBFIRE_MISSILE_MK2,
        self::BLUEPRINT_HEAVY_GUIDED_MISSILE,
        self::BLUEPRINT_HEAVY_HEATSEEKER_MISSILE,
        self::BLUEPRINT_HEAVY_SCATTER_MISSILE,
        self::BLUEPRINT_HEAVY_SMART_MISSILE,
        self::BLUEPRINT_HEAVY_STARBURST_MISSILE,
        self::BLUEPRINT_HEAVY_SWARM_MISSILE,
        self::BLUEPRINT_HEAVY_TORPEDO_MISSILE,
        self::BLUEPRINT_HELIOS_E,
        self::BLUEPRINT_HELIOS_SENTINEL,
        self::BLUEPRINT_HELIOS_VANGUARD,
        self::BLUEPRINT_HERACLES_SENTINEL,
        self::BLUEPRINT_HERACLES_VANGUARD,
        self::BLUEPRINT_HERMES_SENTINEL,
        self::BLUEPRINT_HERMES_VANGUARD,
        self::BLUEPRINT_HERON_E,
        self::BLUEPRINT_HERON_SENTINEL,
        self::BLUEPRINT_HERON_VANGUARD,
        self::BLUEPRINT_HOKKAIDO_GAS,
        self::BLUEPRINT_HOKKAIDO_MINERAL,
        self::BLUEPRINT_HONSHU,
        self::BLUEPRINT_HULL_PART_PRODUCTION,
        self::BLUEPRINT_HYDRA,
        self::BLUEPRINT_HYPERION,
        self::BLUEPRINT_IDES_SENTINEL,
        self::BLUEPRINT_IDES_VANGUARD,
        self::BLUEPRINT_INCARCATURA_SENTINEL,
        self::BLUEPRINT_INCARCATURA_VANGUARD,
        self::BLUEPRINT_IRUKANDJI,
        self::BLUEPRINT_JAGUAR,
        self::BLUEPRINT_JIAN,
        self::BLUEPRINT_KALIS,
        self::BLUEPRINT_KATANA,
        self::BLUEPRINT_KESTREL_SENTINEL,
        self::BLUEPRINT_KESTREL_SPORT,
        self::BLUEPRINT_KESTREL_VANGUARD,
        self::BLUEPRINT_KOPIS_MINERAL,
        self::BLUEPRINT_KUKRI,
        self::BLUEPRINT_KURAOKAMI,
        self::BLUEPRINT_KYD,
        self::BLUEPRINT_KYUSHU,
        self::BLUEPRINT_LASER_TOWER_01_A_S,
        self::BLUEPRINT_LASER_TOWER_01_A_XS,
        self::BLUEPRINT_LIGHT_BARRAGE_MISSILE,
        self::BLUEPRINT_LIGHT_CLUSTER_MISSILE,
        self::BLUEPRINT_LIGHT_DISRUPTOR_MISSILE,
        self::BLUEPRINT_LIGHT_DUMBFIRE_MISSILE_MK1,
        self::BLUEPRINT_LIGHT_DUMBFIRE_MISSILE_MK2,
        self::BLUEPRINT_LIGHT_GUIDED_MISSILE,
        self::BLUEPRINT_LIGHT_HEATSEEKER_MISSILE,
        self::BLUEPRINT_LIGHT_INTERCEPTOR_MISSILE,
        self::BLUEPRINT_LIGHT_SMART_MISSILE,
        self::BLUEPRINT_LIGHT_SWARM_MISSILE,
        self::BLUEPRINT_LIGHT_TORPEDO_MISSILE,
        self::BLUEPRINT_LUX,
        self::BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK1,
        self::BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK2,
        self::BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK3,
        self::BLUEPRINT_L_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_L_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_MAGNETAR_GAS_SENTINEL,
        self::BLUEPRINT_MAGNETAR_GAS_VANGUARD,
        self::BLUEPRINT_MAGNETAR_MINERAL_SENTINEL,
        self::BLUEPRINT_MAGNETAR_MINERAL_VANGUARD,
        self::BLUEPRINT_MAGPIE_MINERAL,
        self::BLUEPRINT_MAGPIE_SENTINEL,
        self::BLUEPRINT_MAGPIE_VANGUARD,
        self::BLUEPRINT_MAJA_DUST_PRODUCTION,
        self::BLUEPRINT_MAJA_SNAIL_PRODUCTION,
        self::BLUEPRINT_MAKO,
        self::BLUEPRINT_MAMBA,
        self::BLUEPRINT_MAMMOTH_SENTINEL,
        self::BLUEPRINT_MAMMOTH_VANGUARD,
        self::BLUEPRINT_MANORINA_GAS_SENTINEL,
        self::BLUEPRINT_MANORINA_GAS_VANGUARD,
        self::BLUEPRINT_MANORINA_MINERAL_SENTINEL,
        self::BLUEPRINT_MANORINA_MINERAL_VANGUARD,
        self::BLUEPRINT_MANTICORE,
        self::BLUEPRINT_MEAT_PRODUCTION,
        self::BLUEPRINT_MERCURY_SENTINEL,
        self::BLUEPRINT_MERCURY_VANGUARD,
        self::BLUEPRINT_METALLIC_MICROLATTICE_PRODUCTION,
        self::BLUEPRINT_MICROCHIP_PRODUCTION,
        self::BLUEPRINT_MINE,
        self::BLUEPRINT_MINING_DRONE,
        self::BLUEPRINT_MINOTAUR_RAIDER,
        self::BLUEPRINT_MINOTAUR_SENTINEL,
        self::BLUEPRINT_MINOTAUR_VANGUARD,
        self::BLUEPRINT_MISSILE_COMPONENT_PRODUCTION,
        self::BLUEPRINT_MOKOSI_SENTINEL,
        self::BLUEPRINT_MOKOSI_VANGUARD,
        self::BLUEPRINT_MONITOR,
        self::BLUEPRINT_MOREYA,
        self::BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK1,
        self::BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK2,
        self::BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK3,
        self::BLUEPRINT_M_BEAM_EMITTER_01_MK1,
        self::BLUEPRINT_M_BEAM_EMITTER_01_MK2,
        self::BLUEPRINT_M_BOLT_REPEATER_01_MK1,
        self::BLUEPRINT_M_BOLT_REPEATER_01_MK2,
        self::BLUEPRINT_M_COMBAT_THRUSTERS_01_MK1,
        self::BLUEPRINT_M_COMBAT_THRUSTERS_01_MK2,
        self::BLUEPRINT_M_COMBAT_THRUSTERS_01_MK3,
        self::BLUEPRINT_M_DUMBFIRE_LAUNCHER_01_MK1,
        self::BLUEPRINT_M_DUMBFIRE_LAUNCHER_01_MK2,
        self::BLUEPRINT_M_MINING_DRILL_01_MK1,
        self::BLUEPRINT_M_MINING_DRILL_01_MK2,
        self::BLUEPRINT_M_PLASMA_CANNON_01_MK1,
        self::BLUEPRINT_M_PLASMA_CANNON_01_MK2,
        self::BLUEPRINT_M_PULSE_LASER_01_MK1,
        self::BLUEPRINT_M_PULSE_LASER_01_MK2,
        self::BLUEPRINT_M_SHARD_BATTERY_01_MK1,
        self::BLUEPRINT_M_SHARD_BATTERY_01_MK2,
        self::BLUEPRINT_M_TORPEDO_LAUNCHER_01_MK1,
        self::BLUEPRINT_M_TORPEDO_LAUNCHER_01_MK2,
        self::BLUEPRINT_M_TRACKING_LAUNCHER_01_MK1,
        self::BLUEPRINT_M_TRACKING_LAUNCHER_01_MK2,
        self::BLUEPRINT_NAV_BEACON,
        self::BLUEPRINT_NEMESIS_SENTINEL,
        self::BLUEPRINT_NEMESIS_VANGUARD,
        self::BLUEPRINT_NIMCHA,
        self::BLUEPRINT_NODAN_SENTINEL,
        self::BLUEPRINT_NODAN_VANGUARD,
        self::BLUEPRINT_NOMAD_SENTINEL,
        self::BLUEPRINT_NOMAD_VANGUARD,
        self::BLUEPRINT_NOSTROP_OIL_PRODUCTION,
        self::BLUEPRINT_NOVA_SENTINEL,
        self::BLUEPRINT_NOVA_VANGUARD,
        self::BLUEPRINT_ODACHI,
        self::BLUEPRINT_ODYSSEUS_E,
        self::BLUEPRINT_ODYSSEUS_SENTINEL,
        self::BLUEPRINT_ODYSSEUS_VANGUARD,
        self::BLUEPRINT_OKINAWA,
        self::BLUEPRINT_ORCA,
        self::BLUEPRINT_OSAKA,
        self::BLUEPRINT_OSPREY_SENTINEL,
        self::BLUEPRINT_OSPREY_VANGUARD,
        self::BLUEPRINT_PARANID_1_DOCK_PIER,
        self::BLUEPRINT_PARANID_3_DOCK_E_PIER,
        self::BLUEPRINT_PARANID_3_DOCK_T_PIER,
        self::BLUEPRINT_PARANID_ADMINISTRATIVE_CENTRE,
        self::BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_PARANID_BRIDGE_DEFENCE_PLATFORM,
        self::BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_PARANID_DISC_DEFENCE_PLATFORM,
        self::BLUEPRINT_PARANID_FACTION_CAPITAL,
        self::BLUEPRINT_PARANID_L_CONTAINER_STORAGE,
        self::BLUEPRINT_PARANID_L_DOME,
        self::BLUEPRINT_PARANID_L_LIQUID_STORAGE,
        self::BLUEPRINT_PARANID_L_SOLID_STORAGE,
        self::BLUEPRINT_PARANID_MEDICAL_SUPPLY_PRODUCTION,
        self::BLUEPRINT_PARANID_M_CONTAINER_STORAGE,
        self::BLUEPRINT_PARANID_M_DOME,
        self::BLUEPRINT_PARANID_M_LIQUID_STORAGE,
        self::BLUEPRINT_PARANID_M_SOLID_STORAGE,
        self::BLUEPRINT_PARANID_S_CONTAINER_STORAGE,
        self::BLUEPRINT_PARANID_S_DOME,
        self::BLUEPRINT_PARANID_S_LIQUID_STORAGE,
        self::BLUEPRINT_PARANID_S_SOLID_STORAGE,
        self::BLUEPRINT_PARANID_VERTICAL_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_PARANID_VERTICAL_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_PAR_HYPERION_MAIN_BATTERY,
        self::BLUEPRINT_PAR_L_ALL_ROUND_ENGINE,
        self::BLUEPRINT_PAR_L_BEAM_TURRET,
        self::BLUEPRINT_PAR_L_DUMBFIRE_TURRET,
        self::BLUEPRINT_PAR_L_MINING_TURRET,
        self::BLUEPRINT_PAR_L_PLASMA_TURRET,
        self::BLUEPRINT_PAR_L_PULSE_TURRET,
        self::BLUEPRINT_PAR_L_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_PAR_L_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_PAR_L_TRACKING_TURRET,
        self::BLUEPRINT_PAR_L_TRAVEL_ENGINE,
        self::BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_PAR_M_BEAM_TURRET_01_MK1,
        self::BLUEPRINT_PAR_M_BEAM_TURRET_02_MK1,
        self::BLUEPRINT_PAR_M_BOLT_TURRET_01_MK1,
        self::BLUEPRINT_PAR_M_BOLT_TURRET_02_MK1,
        self::BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_PAR_M_DUMBFIRE_TURRET,
        self::BLUEPRINT_PAR_M_MASS_DRIVER_01_MK1,
        self::BLUEPRINT_PAR_M_MASS_DRIVER_01_MK2,
        self::BLUEPRINT_PAR_M_MINING_TURRET_01_MK1,
        self::BLUEPRINT_PAR_M_MINING_TURRET_02_MK1,
        self::BLUEPRINT_PAR_M_PLASMA_TURRET_01_MK1,
        self::BLUEPRINT_PAR_M_PLASMA_TURRET_02_MK1,
        self::BLUEPRINT_PAR_M_PULSE_TURRET_01_MK1,
        self::BLUEPRINT_PAR_M_PULSE_TURRET_02_MK1,
        self::BLUEPRINT_PAR_M_SHARD_TURRET_01_MK1,
        self::BLUEPRINT_PAR_M_SHARD_TURRET_02_MK1,
        self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_PAR_M_TRACKING_TURRET,
        self::BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_PAR_ODYSSEUS_MAIN_BATTERY,
        self::BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_PAR_S_MASS_DRIVER_01_MK1,
        self::BLUEPRINT_PAR_S_MASS_DRIVER_01_MK2,
        self::BLUEPRINT_PAR_S_RACING_ENGINE,
        self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK1_RACING,
        self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_PAR_XL_ALL_ROUND_ENGINE,
        self::BLUEPRINT_PAR_XL_SHIELD_GENERATOR,
        self::BLUEPRINT_PAR_XL_TRAVEL_ENGINE,
        self::BLUEPRINT_PAVILION_OBSERVATION_DECK,
        self::BLUEPRINT_PE,
        self::BLUEPRINT_PEGASUS_SENTINEL,
        self::BLUEPRINT_PEGASUS_VANGUARD,
        self::BLUEPRINT_PELICAN_SENTINEL,
        self::BLUEPRINT_PELICAN_VANGUARD,
        self::BLUEPRINT_PENTHOUSE_OBSERVATION_DECK,
        self::BLUEPRINT_PEREGRINE_SENTINEL,
        self::BLUEPRINT_PEREGRINE_VANGUARD,
        self::BLUEPRINT_PERSEUS_SENTINEL,
        self::BLUEPRINT_PERSEUS_VANGUARD,
        self::BLUEPRINT_PHEROMONE_ART_GALLERY,
        self::BLUEPRINT_PHOENIX_E,
        self::BLUEPRINT_PHOENIX_SENTINEL,
        self::BLUEPRINT_PHOENIX_VANGUARD,
        self::BLUEPRINT_PIRANHA,
        self::BLUEPRINT_PLANKTON_PRODUCTION,
        self::BLUEPRINT_PLASMA_CONDUCTOR_PRODUCTION,
        self::BLUEPRINT_PLUTUS_GAS_SENTINEL,
        self::BLUEPRINT_PLUTUS_GAS_VANGUARD,
        self::BLUEPRINT_PLUTUS_MINERAL_SENTINEL,
        self::BLUEPRINT_PLUTUS_MINERAL_VANGUARD,
        self::BLUEPRINT_PORPOISE_GAS,
        self::BLUEPRINT_PORPOISE_MINERAL,
        self::BLUEPRINT_PROMETHEUS,
        self::BLUEPRINT_PROTECTYON_SHIELD_GENERATOR,
        self::BLUEPRINT_PROTEIN_PASTE_PRODUCTION,
        self::BLUEPRINT_PULSAR_VANGUARD,
        self::BLUEPRINT_QUANTUM_TUBE_PRODUCTION,
        self::BLUEPRINT_QUASAR_VANGUARD,
        self::BLUEPRINT_RALEIGH_CONDENSATE,
        self::BLUEPRINT_RALEIGH_CONTAINER,
        self::BLUEPRINT_RAPIER,
        self::BLUEPRINT_RAPTOR,
        self::BLUEPRINT_RATTLESNAKE,
        self::BLUEPRINT_RAY,
        self::BLUEPRINT_REFINED_METAL_PRODUCTION,
        self::BLUEPRINT_REPAIR_DRONE,
        self::BLUEPRINT_RESOURCE_PROBE,
        self::BLUEPRINT_RORQUAL_GAS,
        self::BLUEPRINT_RORQUAL_MINERAL,
        self::BLUEPRINT_SAPPORO,
        self::BLUEPRINT_SATELLITE,
        self::BLUEPRINT_SCANNING_ARRAY_PRODUCTION,
        self::BLUEPRINT_SCRAP_PROCESSOR,
        self::BLUEPRINT_SCRAP_RECYCLER,
        self::BLUEPRINT_SCRAP_TRACTOR,
        self::BLUEPRINT_SCRUFFIN_PRODUCTION,
        self::BLUEPRINT_SE,
        self::BLUEPRINT_SELENE_SENTINEL,
        self::BLUEPRINT_SELENE_VANGUARD,
        self::BLUEPRINT_SHARK,
        self::BLUEPRINT_SHIELD_COMPONENT_PRODUCTION,
        self::BLUEPRINT_SHIH,
        self::BLUEPRINT_SHUYAKU_SENTINEL,
        self::BLUEPRINT_SHUYAKU_VANGUARD,
        self::BLUEPRINT_SILICON_CARBIDE_PRODUCTION,
        self::BLUEPRINT_SILICON_WAFER_PRODUCTION,
        self::BLUEPRINT_SMART_CHIP_PRODUCTION,
        self::BLUEPRINT_SOJA_BEAN_PRODUCTION,
        self::BLUEPRINT_SOJA_HUSK_PRODUCTION,
        self::BLUEPRINT_SONRA_SENTINEL,
        self::BLUEPRINT_SONRA_VANGUARD,
        self::BLUEPRINT_SPACEFUEL_PRODUCTION,
        self::BLUEPRINT_SPACEWEED_PRODUCTION,
        self::BLUEPRINT_SPICE_PRODUCTION,
        self::BLUEPRINT_SPLIT_1_DOCK_PIER,
        self::BLUEPRINT_SPLIT_3_DOCK_E_PIER,
        self::BLUEPRINT_SPLIT_4_DOCK_T_PIER,
        self::BLUEPRINT_SPLIT_ADMINISTRATIVE_CENTRE,
        self::BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_SPLIT_BRIDGE_DEFENCE_PLATFORM,
        self::BLUEPRINT_SPLIT_CROSS_CONNECTION_STRUCTURE,
        self::BLUEPRINT_SPLIT_DISC_DEFENCE_PLATFORM,
        self::BLUEPRINT_SPLIT_L_CONTAINER_STORAGE,
        self::BLUEPRINT_SPLIT_L_LIQUID_STORAGE,
        self::BLUEPRINT_SPLIT_L_PARLOUR,
        self::BLUEPRINT_SPLIT_L_SOLID_STORAGE,
        self::BLUEPRINT_SPLIT_MEDICAL_SUPPLY_PRODUCTION,
        self::BLUEPRINT_SPLIT_M_CONTAINER_STORAGE,
        self::BLUEPRINT_SPLIT_M_LIQUID_STORAGE,
        self::BLUEPRINT_SPLIT_M_PARLOUR,
        self::BLUEPRINT_SPLIT_M_SOLID_STORAGE,
        self::BLUEPRINT_SPLIT_S_CONTAINER_STORAGE,
        self::BLUEPRINT_SPLIT_S_LIQUID_STORAGE,
        self::BLUEPRINT_SPLIT_S_PARLOUR,
        self::BLUEPRINT_SPLIT_S_SOLID_STORAGE,
        self::BLUEPRINT_SPLIT_VERTICAL_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_SPLIT_VERTICAL_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_SPL_L_ALL_ROUND_ENGINE,
        self::BLUEPRINT_SPL_L_BEAM_TURRET,
        self::BLUEPRINT_SPL_L_DUMBFIRE_TURRET,
        self::BLUEPRINT_SPL_L_MINING_TURRET,
        self::BLUEPRINT_SPL_L_PLASMA_TURRET,
        self::BLUEPRINT_SPL_L_PULSE_TURRET,
        self::BLUEPRINT_SPL_L_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_SPL_L_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_SPL_L_TRACKING_TURRET,
        self::BLUEPRINT_SPL_L_TRAVEL_ENGINE,
        self::BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_SPL_M_BEAM_TURRET_01_MK1,
        self::BLUEPRINT_SPL_M_BEAM_TURRET_02_MK1,
        self::BLUEPRINT_SPL_M_BOLT_TURRET_01_MK1,
        self::BLUEPRINT_SPL_M_BOLT_TURRET_02_MK1,
        self::BLUEPRINT_SPL_M_BOSON_LANCE_01_MK1,
        self::BLUEPRINT_SPL_M_BOSON_LANCE_01_MK2,
        self::BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_SPL_M_COMBAT_ENGINE_MK4,
        self::BLUEPRINT_SPL_M_DUMBFIRE_TURRET,
        self::BLUEPRINT_SPL_M_FLAK_TURRET_01_MK1,
        self::BLUEPRINT_SPL_M_FLAK_TURRET_02_MK1,
        self::BLUEPRINT_SPL_M_MINING_TURRET_01_MK1,
        self::BLUEPRINT_SPL_M_MINING_TURRET_02_MK1,
        self::BLUEPRINT_SPL_M_NEUTRON_GATLING_01_MK1,
        self::BLUEPRINT_SPL_M_NEUTRON_GATLING_01_MK2,
        self::BLUEPRINT_SPL_M_PLASMA_TURRET_01_MK1,
        self::BLUEPRINT_SPL_M_PLASMA_TURRET_02_MK1,
        self::BLUEPRINT_SPL_M_PULSE_TURRET_01_MK1,
        self::BLUEPRINT_SPL_M_PULSE_TURRET_02_MK1,
        self::BLUEPRINT_SPL_M_SHARD_TURRET_01_MK1,
        self::BLUEPRINT_SPL_M_SHARD_TURRET_02_MK1,
        self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_SPL_M_TAU_ACCELERATOR_01_MK1,
        self::BLUEPRINT_SPL_M_TAU_ACCELERATOR_01_MK2,
        self::BLUEPRINT_SPL_M_THERMAL_DISINTEGRATOR_01_MK1,
        self::BLUEPRINT_SPL_M_THERMAL_DISINTEGRATOR_01_MK2,
        self::BLUEPRINT_SPL_M_TRACKING_TURRET,
        self::BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_SPL_RATTLESNAKE_MAIN_BATTERY,
        self::BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_SPL_S_BOSON_LANCE_01_MK1,
        self::BLUEPRINT_SPL_S_BOSON_LANCE_01_MK2,
        self::BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_SPL_S_COMBAT_ENGINE_MK4,
        self::BLUEPRINT_SPL_S_NEUTRON_GATLING_01_MK1,
        self::BLUEPRINT_SPL_S_NEUTRON_GATLING_01_MK2,
        self::BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_SPL_S_TAU_ACCELERATOR_01_MK1,
        self::BLUEPRINT_SPL_S_TAU_ACCELERATOR_01_MK2,
        self::BLUEPRINT_SPL_S_THERMAL_DISINTEGRATOR_01_MK1,
        self::BLUEPRINT_SPL_S_THERMAL_DISINTEGRATOR_01_MK2,
        self::BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_SPL_XL_ALL_ROUND_ENGINE,
        self::BLUEPRINT_SPL_XL_SHIELD_GENERATOR,
        self::BLUEPRINT_SPL_XL_TRAVEL_ENGINE,
        self::BLUEPRINT_STIMULANT_PRODUCTION,
        self::BLUEPRINT_STORK_SENTINEL,
        self::BLUEPRINT_STORK_VANGUARD,
        self::BLUEPRINT_STURGEON,
        self::BLUEPRINT_SUNDER_GAS_SENTINEL,
        self::BLUEPRINT_SUNDER_GAS_VANGUARD,
        self::BLUEPRINT_SUNRISE_FLOWER_PRODUCTION,
        self::BLUEPRINT_SUPERFLUID_COOLANT_PRODUCTION,
        self::BLUEPRINT_SWAMP_PLANT_PRODUCTION,
        self::BLUEPRINT_SYN,
        self::BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK1,
        self::BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK2,
        self::BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK3,
        self::BLUEPRINT_S_BEAM_EMITTER_01_MK1,
        self::BLUEPRINT_S_BEAM_EMITTER_01_MK2,
        self::BLUEPRINT_S_BLAST_MORTAR_01_MK1,
        self::BLUEPRINT_S_BLAST_MORTAR_01_MK2,
        self::BLUEPRINT_S_BOLT_REPEATER_01_MK1,
        self::BLUEPRINT_S_BOLT_REPEATER_01_MK2,
        self::BLUEPRINT_S_BURST_RAY_01_MK1,
        self::BLUEPRINT_S_BURST_RAY_01_MK2,
        self::BLUEPRINT_S_COMBAT_THRUSTERS_01_MK1,
        self::BLUEPRINT_S_COMBAT_THRUSTERS_01_MK2,
        self::BLUEPRINT_S_COMBAT_THRUSTERS_01_MK3,
        self::BLUEPRINT_S_DUMBFIRE_LAUNCHER_01_MK1,
        self::BLUEPRINT_S_DUMBFIRE_LAUNCHER_01_MK2,
        self::BLUEPRINT_S_MINING_DRILL_01_MK1,
        self::BLUEPRINT_S_MINING_DRILL_01_MK2,
        self::BLUEPRINT_S_M_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_S_M_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_S_M_VENTURE_SENDOFF_DOCK,
        self::BLUEPRINT_S_PLASMA_CANNON_01_MK1,
        self::BLUEPRINT_S_PLASMA_CANNON_01_MK2,
        self::BLUEPRINT_S_PULSE_LASER_01_MK1,
        self::BLUEPRINT_S_PULSE_LASER_01_MK2,
        self::BLUEPRINT_S_RACING_ENGINE_01_MK1,
        self::BLUEPRINT_S_RACING_ENGINE_01_MK2,
        self::BLUEPRINT_S_RACING_SHIELD_GENERATOR,
        self::BLUEPRINT_S_SHARD_BATTERY_01_MK1,
        self::BLUEPRINT_S_SHARD_BATTERY_01_MK2,
        self::BLUEPRINT_S_TORPEDO_LAUNCHER_01_MK1,
        self::BLUEPRINT_S_TORPEDO_LAUNCHER_01_MK2,
        self::BLUEPRINT_S_TRACKING_LAUNCHER_01_MK1,
        self::BLUEPRINT_S_TRACKING_LAUNCHER_01_MK2,
        self::BLUEPRINT_TAKOBA,
        self::BLUEPRINT_TELADIANIUM_PRODUCTION,
        self::BLUEPRINT_TELADI_1_DOCK_PIER,
        self::BLUEPRINT_TELADI_3_DOCK_E_PIER,
        self::BLUEPRINT_TELADI_3_DOCK_T_PIER,
        self::BLUEPRINT_TELADI_ADMINISTRATIVE_CENTRE,
        self::BLUEPRINT_TELADI_ADVANCED_COMPOSITE_PRODUCTION,
        self::BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_TELADI_BRIDGE_DEFENCE_PLATFORM,
        self::BLUEPRINT_TELADI_CROSS_CONNECTION_STRUCTURE,
        self::BLUEPRINT_TELADI_DISC_DEFENCE_PLATFORM,
        self::BLUEPRINT_TELADI_ENGINE_PART_PRODUCTION,
        self::BLUEPRINT_TELADI_HULL_PART_PRODUCTION,
        self::BLUEPRINT_TELADI_L_BIOME,
        self::BLUEPRINT_TELADI_L_CONTAINER_STORAGE,
        self::BLUEPRINT_TELADI_L_LIQUID_STORAGE,
        self::BLUEPRINT_TELADI_L_SOLID_STORAGE,
        self::BLUEPRINT_TELADI_MEDICAL_SUPPLY_PRODUCTION,
        self::BLUEPRINT_TELADI_M_BIOME,
        self::BLUEPRINT_TELADI_M_CONTAINER_STORAGE,
        self::BLUEPRINT_TELADI_M_LIQUID_STORAGE,
        self::BLUEPRINT_TELADI_M_SOLID_STORAGE,
        self::BLUEPRINT_TELADI_SCANNING_ARRAY_PRODUCTION,
        self::BLUEPRINT_TELADI_S_BIOME,
        self::BLUEPRINT_TELADI_S_CONTAINER_STORAGE,
        self::BLUEPRINT_TELADI_S_LIQUID_STORAGE,
        self::BLUEPRINT_TELADI_S_SOLID_STORAGE,
        self::BLUEPRINT_TELADI_VERTICAL_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_TELADI_VERTICAL_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_TEL_L_ALL_ROUND_ENGINE,
        self::BLUEPRINT_TEL_L_BEAM_TURRET,
        self::BLUEPRINT_TEL_L_DUMBFIRE_TURRET,
        self::BLUEPRINT_TEL_L_MINING_TURRET,
        self::BLUEPRINT_TEL_L_PLASMA_TURRET,
        self::BLUEPRINT_TEL_L_PULSE_TURRET,
        self::BLUEPRINT_TEL_L_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TEL_L_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TEL_L_TRACKING_TURRET,
        self::BLUEPRINT_TEL_L_TRAVEL_ENGINE,
        self::BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_TEL_M_BEAM_TURRET_01_MK1,
        self::BLUEPRINT_TEL_M_BEAM_TURRET_02_MK1,
        self::BLUEPRINT_TEL_M_BOLT_TURRET_01_MK1,
        self::BLUEPRINT_TEL_M_BOLT_TURRET_02_MK1,
        self::BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_TEL_M_DUMBFIRE_TURRET,
        self::BLUEPRINT_TEL_M_MINING_TURRET_01_MK1,
        self::BLUEPRINT_TEL_M_MINING_TURRET_02_MK1,
        self::BLUEPRINT_TEL_M_MUON_CHARGER_01_MK1,
        self::BLUEPRINT_TEL_M_MUON_CHARGER_01_MK2,
        self::BLUEPRINT_TEL_M_PLASMA_TURRET_01_MK1,
        self::BLUEPRINT_TEL_M_PLASMA_TURRET_02_MK1,
        self::BLUEPRINT_TEL_M_PULSE_TURRET_01_MK1,
        self::BLUEPRINT_TEL_M_PULSE_TURRET_02_MK1,
        self::BLUEPRINT_TEL_M_SHARD_TURRET_01_MK1,
        self::BLUEPRINT_TEL_M_SHARD_TURRET_02_MK1,
        self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_TEL_M_TRACKING_TURRET,
        self::BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_TEL_PHOENIX_MAIN_BATTERY,
        self::BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_TEL_S_MUON_CHARGER_01_MK1,
        self::BLUEPRINT_TEL_S_MUON_CHARGER_01_MK2,
        self::BLUEPRINT_TEL_S_RACING_ENGINE,
        self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK1_RACING,
        self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_TEL_XL_ALL_ROUND_ENGINE,
        self::BLUEPRINT_TEL_XL_SHIELD_GENERATOR,
        self::BLUEPRINT_TEL_XL_TRAVEL_ENGINE,
        self::BLUEPRINT_TERN_SENTINEL,
        self::BLUEPRINT_TERN_VANGUARD,
        self::BLUEPRINT_TERRAN_1_DOCK_PIER,
        self::BLUEPRINT_TERRAN_3_DOCK_E_PIER,
        self::BLUEPRINT_TERRAN_3_DOCK_T_PIER,
        self::BLUEPRINT_TERRAN_4M10S_LUXURY_DOCK_AREA,
        self::BLUEPRINT_TERRAN_4_DOCK_T_PIER,
        self::BLUEPRINT_TERRAN_ADMINISTRATIVE_CENTRE,
        self::BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_TERRAN_BRIDGE_DEFENCE_PLATFORM,
        self::BLUEPRINT_TERRAN_CROSS_CONNECTION_STRUCTURE,
        self::BLUEPRINT_TERRAN_DISC_DEFENCE_PLATFORM,
        self::BLUEPRINT_TERRAN_ENERGY_CELL_PRODUCTION,
        self::BLUEPRINT_TERRAN_L_CONTAINER_STORAGE,
        self::BLUEPRINT_TERRAN_L_LIQUID_STORAGE,
        self::BLUEPRINT_TERRAN_L_LIVING_QUARTERS,
        self::BLUEPRINT_TERRAN_L_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_TERRAN_L_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_TERRAN_L_SOLID_STORAGE,
        self::BLUEPRINT_TERRAN_MAIN_BATTERY,
        self::BLUEPRINT_TERRAN_MEDICAL_SUPPLY_PRODUCTION,
        self::BLUEPRINT_TERRAN_MRE_PRODUCTION,
        self::BLUEPRINT_TERRAN_M_CONTAINER_STORAGE,
        self::BLUEPRINT_TERRAN_M_LIQUID_STORAGE,
        self::BLUEPRINT_TERRAN_M_LIVING_QUARTERS,
        self::BLUEPRINT_TERRAN_M_SOLID_STORAGE,
        self::BLUEPRINT_TERRAN_SCRAP_RECYCLER,
        self::BLUEPRINT_TERRAN_S_CONTAINER_STORAGE,
        self::BLUEPRINT_TERRAN_S_LIQUID_STORAGE,
        self::BLUEPRINT_TERRAN_S_LIVING_QUARTERS,
        self::BLUEPRINT_TERRAN_S_M_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_TERRAN_S_M_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_TERRAN_S_SOLID_STORAGE,
        self::BLUEPRINT_TERRAN_TRADING_STATION_HEXA_DOCK_PIER,
        self::BLUEPRINT_TERRAN_VERTICAL_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_TERRAN_VERTICAL_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_TERRAN_XL_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_TERRAN_XL_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_TERRAPIN,
        self::BLUEPRINT_TER_L_ALL_ROUND_ENGINE,
        self::BLUEPRINT_TER_L_BEAM_TURRET,
        self::BLUEPRINT_TER_L_BOLT_TURRET,
        self::BLUEPRINT_TER_L_DUMBFIRE_TURRET,
        self::BLUEPRINT_TER_L_FRONTIER_ENGINE,
        self::BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK3,
        self::BLUEPRINT_TER_L_MINING_TURRET,
        self::BLUEPRINT_TER_L_PULSE_TURRET,
        self::BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_TER_L_TRACKING_TURRET,
        self::BLUEPRINT_TER_L_TRAVEL_ENGINE,
        self::BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_TER_M_BEAM_TURRET_01_MK1,
        self::BLUEPRINT_TER_M_BEAM_TURRET_02_MK1,
        self::BLUEPRINT_TER_M_BOLT_TURRET_01_MK1,
        self::BLUEPRINT_TER_M_BOLT_TURRET_02_MK1,
        self::BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_TER_M_DUMBFIRE_TURRET,
        self::BLUEPRINT_TER_M_ELECTROMAGNETIC_CANNON,
        self::BLUEPRINT_TER_M_ELECTROMAGNETIC_TURRET_03_MK1,
        self::BLUEPRINT_TER_M_ELECTROMAGNETIC_TURRET_04_MK1,
        self::BLUEPRINT_TER_M_FRONTIER_ENGINE,
        self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK1,
        self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK2,
        self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK3,
        self::BLUEPRINT_TER_M_MESON_STREAM_01_MK1,
        self::BLUEPRINT_TER_M_MESON_STREAM_01_MK2,
        self::BLUEPRINT_TER_M_MINING_TURRET_01_MK1,
        self::BLUEPRINT_TER_M_MINING_TURRET_02_MK1,
        self::BLUEPRINT_TER_M_PROTON_BARRAGE_01_MK1,
        self::BLUEPRINT_TER_M_PROTON_BARRAGE_01_MK2,
        self::BLUEPRINT_TER_M_PULSE_LASER_01_MK1,
        self::BLUEPRINT_TER_M_PULSE_LASER_01_MK2,
        self::BLUEPRINT_TER_M_PULSE_TURRET_01_MK1,
        self::BLUEPRINT_TER_M_PULSE_TURRET_02_MK1,
        self::BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK3,
        self::BLUEPRINT_TER_M_TRACKING_TURRET,
        self::BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_TER_SAPPORO_LAUNCHER_ARRAY,
        self::BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK1,
        self::BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK2,
        self::BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK3,
        self::BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK1,
        self::BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK2,
        self::BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK3,
        self::BLUEPRINT_TER_S_ELECTROMAGNETIC_GUN,
        self::BLUEPRINT_TER_S_FRONTIER_ENGINE,
        self::BLUEPRINT_TER_S_FRONTIER_SHIELD_GENERATOR,
        self::BLUEPRINT_TER_S_FRONTIER_SHIELD_GENERATOR_MK5,
        self::BLUEPRINT_TER_S_MESON_STREAM_01_MK1,
        self::BLUEPRINT_TER_S_MESON_STREAM_01_MK2,
        self::BLUEPRINT_TER_S_PROTON_BARRAGE_01_MK1,
        self::BLUEPRINT_TER_S_PROTON_BARRAGE_01_MK2,
        self::BLUEPRINT_TER_S_PULSE_LASER_01_MK1,
        self::BLUEPRINT_TER_S_PULSE_LASER_01_MK2,
        self::BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK3,
        self::BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK1,
        self::BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK2,
        self::BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK3,
        self::BLUEPRINT_TER_XL_ALL_ROUND_ENGINE,
        self::BLUEPRINT_TER_XL_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_TER_XL_SHIELD_GENERATOR_01_MK2,
        self::BLUEPRINT_TER_XL_TRAVEL_ENGINE,
        self::BLUEPRINT_TETHYS_MINERAL,
        self::BLUEPRINT_TETHYS_SENTINEL,
        self::BLUEPRINT_TETHYS_VANGUARD,
        self::BLUEPRINT_TEUTA,
        self::BLUEPRINT_THESEUS_SENTINEL,
        self::BLUEPRINT_THESEUS_SPORT,
        self::BLUEPRINT_THESEUS_VANGUARD,
        self::BLUEPRINT_THRESHER,
        self::BLUEPRINT_TOKYO,
        self::BLUEPRINT_TRACKER_MINE,
        self::BLUEPRINT_TUATARA,
        self::BLUEPRINT_TUATARA_MINERAL,
        self::BLUEPRINT_TURRET_COMPONENT_PRODUCTION,
        self::BLUEPRINT_VELES_SENTINEL,
        self::BLUEPRINT_VELES_VANGUARD,
        self::BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_03,
        self::BLUEPRINT_VENTURE_CROSS_CONNECTION_STRUCTURE,
        self::BLUEPRINT_VENTURE_PLATFORM,
        self::BLUEPRINT_VENTURE_VERTICAL_CONNECTION_STRUCTURE_01,
        self::BLUEPRINT_VENTURE_VERTICAL_CONNECTION_STRUCTURE_02,
        self::BLUEPRINT_VULTURE_SENTINEL,
        self::BLUEPRINT_VULTURE_VANGUARD,
        self::BLUEPRINT_WALRUS,
        self::BLUEPRINT_WATER_PRODUCTION,
        self::BLUEPRINT_WEAPON_COMPONENT_PRODUCTION,
        self::BLUEPRINT_WHEAT_PRODUCTION,
        self::BLUEPRINT_WIDE_AREA_SENSOR_ARRAY,
        self::BLUEPRINT_WYVERN_GAS,
        self::BLUEPRINT_WYVERN_MINERAL,
        self::BLUEPRINT_XEN_L_ALL_ROUND_ENGINE,
        self::BLUEPRINT_XEN_L_SEISMIC_CHARGE_TURRET,
        self::BLUEPRINT_XEN_L_SHIELD_GENERATOR_02_MK1,
        self::BLUEPRINT_XEN_L_SHIELD_GENERATOR_02_MK2,
        self::BLUEPRINT_XEN_M_COMBAT_ENGINE,
        self::BLUEPRINT_XEN_M_IMPULSE_PROJECTOR,
        self::BLUEPRINT_XEN_M_MINING_DRILL,
        self::BLUEPRINT_XEN_M_NEEDLER_TURRET_01_MK1,
        self::BLUEPRINT_XEN_M_NEEDLER_TURRET_02_MK1,
        self::BLUEPRINT_XEN_M_PLASMA_CUTTER_BEAM,
        self::BLUEPRINT_XEN_M_SHIELD_GENERATOR_01_MK1,
        self::BLUEPRINT_XEN_M_SHIELD_GENERATOR_04_MK1,
        self::BLUEPRINT_XEN_S_COMBAT_ENGINE,
        self::BLUEPRINT_XEN_S_NEEDLER_GUN,
        self::BLUEPRINT_XEN_S_SHIELD_GENERATOR,
        self::BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK1,
        self::BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK2,
        self::BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK3,
        self::BLUEPRINT_XL_SHIP_FABRICATION_BAY,
        self::BLUEPRINT_XL_SHIP_MAINTENANCE_BAY,
        self::BLUEPRINT_XPERIMENTAL_SHUTTLE,
        self::BLUEPRINT_ZEUS_E,
        self::BLUEPRINT_ZEUS_SENTINEL,
        self::BLUEPRINT_ZEUS_VANGUARD
    );

    private BlueprintDefs $defs;

    private function __construct()
    {
        $this->defs = BlueprintDefs::getInstance();
    }

    private static ?KnownBlueprints $instance = null;

    public static function getInstance() : KnownBlueprints
    {
        if (!isset(self::$instance)) {
            self::$instance = new KnownBlueprints();
        }

        return self::$instance;
    }

    public function get1m6sBasicDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_1M6S_BASIC_DOCK_AREA);
    }

    public function get1m6sLuxuryDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_1M6S_LUXURY_DOCK_AREA);
    }

    public function get1m6sStandardDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_1M6S_STANDARD_DOCK_AREA);
    }

    public function get3m6sBasicDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_3M6S_BASIC_DOCK_AREA);
    }

    public function get3m6sLuxuryDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_3M6S_LUXURY_DOCK_AREA);
    }

    public function get3m6sStandardDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_3M6S_STANDARD_DOCK_AREA);
    }

    public function get8mLuxuryDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_8M_LUXURY_DOCK_AREA);
    }

    public function getAdvancedCompositeProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ADVANCED_COMPOSITE_PRODUCTION);
    }

    public function getAdvancedElectronicsProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ADVANCED_ELECTRONICS_PRODUCTION);
    }

    public function getAdvancedSatellite() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ADVANCED_SATELLITE);
    }

    public function getAlbatrossSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ALBATROSS_SENTINEL);
    }

    public function getAlbatrossVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ALBATROSS_VANGUARD);
    }

    public function getAlligatorGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ALLIGATOR_GAS);
    }

    public function getAlligatorMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ALLIGATOR_MINERAL);
    }

    public function getAntimatterCellProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ANTIMATTER_CELL_PRODUCTION);
    }

    public function getAntimatterConverterProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ANTIMATTER_CONVERTER_PRODUCTION);
    }

    public function getAres() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARES);
    }

    public function getArgBehemothMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_BEHEMOTH_MAIN_BATTERY);
    }

    public function getArgLAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_ALL_ROUND_ENGINE);
    }

    public function getArgLBeamTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_BEAM_TURRET);
    }

    public function getArgLDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_DUMBFIRE_TURRET);
    }

    public function getArgLMiningTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_MINING_TURRET);
    }

    public function getArgLPlasmaTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_PLASMA_TURRET);
    }

    public function getArgLPulseTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_PULSE_TURRET);
    }

    public function getArgLShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_SHIELD_GENERATOR_01_MK1);
    }

    public function getArgLShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_SHIELD_GENERATOR_01_MK2);
    }

    public function getArgLTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_TRACKING_TURRET);
    }

    public function getArgLTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_L_TRAVEL_ENGINE);
    }

    public function getArgMAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getArgMAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getArgMAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getArgMBeamTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_BEAM_TURRET_01_MK1);
    }

    public function getArgMBeamTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_BEAM_TURRET_02_MK1);
    }

    public function getArgMBoltTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_BOLT_TURRET_01_MK1);
    }

    public function getArgMBoltTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_BOLT_TURRET_02_MK1);
    }

    public function getArgMCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK1);
    }

    public function getArgMCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK2);
    }

    public function getArgMCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_COMBAT_ENGINE_01_MK3);
    }

    public function getArgMDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_DUMBFIRE_TURRET);
    }

    public function getArgMFlakTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_FLAK_TURRET_01_MK1);
    }

    public function getArgMFlakTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_FLAK_TURRET_02_MK1);
    }

    public function getArgMIonBlaster_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_ION_BLASTER_01_MK1);
    }

    public function getArgMIonBlaster_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_ION_BLASTER_01_MK2);
    }

    public function getArgMMiningTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_MINING_TURRET_01_MK1);
    }

    public function getArgMMiningTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_MINING_TURRET_02_MK1);
    }

    public function getArgMPlasmaTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_PLASMA_TURRET_01_MK1);
    }

    public function getArgMPlasmaTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_PLASMA_TURRET_02_MK1);
    }

    public function getArgMPulseTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_PULSE_TURRET_01_MK1);
    }

    public function getArgMPulseTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_PULSE_TURRET_02_MK1);
    }

    public function getArgMShardTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_SHARD_TURRET_01_MK1);
    }

    public function getArgMShardTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_SHARD_TURRET_02_MK1);
    }

    public function getArgMShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_01_MK1);
    }

    public function getArgMShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_01_MK2);
    }

    public function getArgMShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_02_MK1);
    }

    public function getArgMShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_SHIELD_GENERATOR_02_MK2);
    }

    public function getArgMTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_TRACKING_TURRET);
    }

    public function getArgMTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK1);
    }

    public function getArgMTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK2);
    }

    public function getArgMTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_M_TRAVEL_ENGINE_01_MK3);
    }

    public function getArgSAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getArgSAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getArgSAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getArgSCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK1);
    }

    public function getArgSCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK2);
    }

    public function getArgSCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_COMBAT_ENGINE_01_MK3);
    }

    public function getArgSGammaHept() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_GAMMA_HEPT);
    }

    public function getArgSIonBlaster_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_ION_BLASTER_01_MK1);
    }

    public function getArgSIonBlaster_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_ION_BLASTER_01_MK2);
    }

    public function getArgSRacingEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_RACING_ENGINE);
    }

    public function getArgSShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK1);
    }

    public function getArgSShieldGenerator_01Mk1Racing() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK1_RACING);
    }

    public function getArgSShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK2);
    }

    public function getArgSShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_SHIELD_GENERATOR_01_MK3);
    }

    public function getArgSTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK1);
    }

    public function getArgSTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK2);
    }

    public function getArgSTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_S_TRAVEL_ENGINE_01_MK3);
    }

    public function getArgXlAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_XL_ALL_ROUND_ENGINE);
    }

    public function getArgXlShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_XL_SHIELD_GENERATOR);
    }

    public function getArgXlTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARG_XL_TRAVEL_ENGINE);
    }

    public function getArgon1DockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_1_DOCK_PIER);
    }

    public function getArgon1DockShortPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_1_DOCK_SHORT_PIER);
    }

    public function getArgon3DockEPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_3_DOCK_E_PIER);
    }

    public function getArgon3DockTPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_3_DOCK_T_PIER);
    }

    public function getArgonAdministrativeCentre() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_ADMINISTRATIVE_CENTRE);
    }

    public function getArgonArcConnectionStructure() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_ARC_CONNECTION_STRUCTURE);
    }

    public function getArgonBaseConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_01);
    }

    public function getArgonBaseConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_02);
    }

    public function getArgonBaseConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_BASE_CONNECTION_STRUCTURE_03);
    }

    public function getArgonBridgeDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_BRIDGE_DEFENCE_PLATFORM);
    }

    public function getArgonCrossConnectionStructure() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_CROSS_CONNECTION_STRUCTURE);
    }

    public function getArgonDiscDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_DISC_DEFENCE_PLATFORM);
    }

    public function getArgonLContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_L_CONTAINER_STORAGE);
    }

    public function getArgonLDormitory() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_L_DORMITORY);
    }

    public function getArgonLHabitat() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_L_HABITAT);
    }

    public function getArgonLHousingSpire() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_L_HOUSING_SPIRE);
    }

    public function getArgonLLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_L_LIQUID_STORAGE);
    }

    public function getArgonLSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_L_SOLID_STORAGE);
    }

    public function getArgonMContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_M_CONTAINER_STORAGE);
    }

    public function getArgonMDormitory() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_M_DORMITORY);
    }

    public function getArgonMHabitat() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_M_HABITAT);
    }

    public function getArgonMLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_M_LIQUID_STORAGE);
    }

    public function getArgonMSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_M_SOLID_STORAGE);
    }

    public function getArgonMedicalSupplyProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_MEDICAL_SUPPLY_PRODUCTION);
    }

    public function getArgonSContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_S_CONTAINER_STORAGE);
    }

    public function getArgonSDormitory() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_S_DORMITORY);
    }

    public function getArgonSHabitat() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_S_HABITAT);
    }

    public function getArgonSLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_S_LIQUID_STORAGE);
    }

    public function getArgonSSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_S_SOLID_STORAGE);
    }

    public function getArgonSpanConnectionStructure() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_SPAN_CONNECTION_STRUCTURE);
    }

    public function getArgonSpanConnectionStructure02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_SPAN_CONNECTION_STRUCTURE_02);
    }

    public function getArgonVerticalConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_VERTICAL_CONNECTION_STRUCTURE_01);
    }

    public function getArgonVerticalConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_VERTICAL_CONNECTION_STRUCTURE_02);
    }

    public function getArgonXlHousingSpire() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ARGON_XL_HOUSING_SPIRE);
    }

    public function getAsgard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ASGARD);
    }

    public function getAsp() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ASP);
    }

    public function getAspRaider() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ASP_RAIDER);
    }

    public function getAtfXlMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ATF_XL_MAIN_BATTERY);
    }

    public function getAtlasE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ATLAS_E);
    }

    public function getAtlasSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ATLAS_SENTINEL);
    }

    public function getAtlasVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ATLAS_VANGUARD);
    }

    public function getB() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_B);
    }

    public function getBalaur() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BALAUR);
    }

    public function getBaldric() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BALDRIC);
    }

    public function getBarbarossa() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BARBAROSSA);
    }

    public function getBarracuda() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BARRACUDA);
    }

    public function getBehemothE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BEHEMOTH_E);
    }

    public function getBehemothSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BEHEMOTH_SENTINEL);
    }

    public function getBehemothVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BEHEMOTH_VANGUARD);
    }

    public function getBoa() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOA);
    }

    public function getBofuProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOFU_PRODUCTION);
    }

    public function getBogasProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOGAS_PRODUCTION);
    }

    public function getBoloGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOLO_GAS);
    }

    public function getBoloMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOLO_MINERAL);
    }

    public function getBorLAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_ALL_ROUND_ENGINE);
    }

    public function getBorLIonFlakTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_ION_FLAK_TURRET);
    }

    public function getBorLIonNetLauncher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_ION_NET_LAUNCHER);
    }

    public function getBorLMiningTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_MINING_TURRET);
    }

    public function getBorLPhaseTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_PHASE_TURRET);
    }

    public function getBorLShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK1);
    }

    public function getBorLShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK2);
    }

    public function getBorLShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_L_SHIELD_GENERATOR_01_MK3);
    }

    public function getBorMAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getBorMAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getBorMAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getBorMArcTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ARC_TURRET_01_MK1);
    }

    public function getBorMArcTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ARC_TURRET_02_MK1);
    }

    public function getBorMDumbfireLauncher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_DUMBFIRE_LAUNCHER);
    }

    public function getBorMDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_DUMBFIRE_TURRET);
    }

    public function getBorMIonAtomiser() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ION_ATOMISER);
    }

    public function getBorMIonPulseRailgun() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ION_PULSE_RAILGUN);
    }

    public function getBorMIonPulseTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ION_PULSE_TURRET_01_MK1);
    }

    public function getBorMIonPulseTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_ION_PULSE_TURRET_02_MK1);
    }

    public function getBorMMiningDrill() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_MINING_DRILL);
    }

    public function getBorMMiningTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_MINING_TURRET_01_MK1);
    }

    public function getBorMMiningTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_MINING_TURRET_02_MK1);
    }

    public function getBorMPhaseCannon() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_PHASE_CANNON);
    }

    public function getBorMPhaseTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_PHASE_TURRET_01_MK1);
    }

    public function getBorMPhaseTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_PHASE_TURRET_02_MK1);
    }

    public function getBorMShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK1);
    }

    public function getBorMShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK2);
    }

    public function getBorMShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_01_MK3);
    }

    public function getBorMShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK1);
    }

    public function getBorMShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK2);
    }

    public function getBorMShieldGenerator_02Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_SHIELD_GENERATOR_02_MK3);
    }

    public function getBorMTorpedoLauncher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_TORPEDO_LAUNCHER);
    }

    public function getBorMTrackingLauncher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_TRACKING_LAUNCHER);
    }

    public function getBorMTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_M_TRACKING_TURRET);
    }

    public function getBorRayIonProjector() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_RAY_ION_PROJECTOR);
    }

    public function getBorSAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getBorSAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getBorSAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getBorSArcGun() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_ARC_GUN);
    }

    public function getBorSDumbfireLauncher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_DUMBFIRE_LAUNCHER);
    }

    public function getBorSIonGatling() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_ION_GATLING);
    }

    public function getBorSMiningDrill() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_MINING_DRILL);
    }

    public function getBorSPhaseGun() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_PHASE_GUN);
    }

    public function getBorSShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK1);
    }

    public function getBorSShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK2);
    }

    public function getBorSShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_SHIELD_GENERATOR_01_MK3);
    }

    public function getBorSTorpedoLauncher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_TORPEDO_LAUNCHER);
    }

    public function getBorSTrackingLauncher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_S_TRACKING_LAUNCHER);
    }

    public function getBorXlAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_XL_ALL_ROUND_ENGINE);
    }

    public function getBorXlShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_XL_SHIELD_GENERATOR_01_MK1);
    }

    public function getBorXlShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BOR_XL_SHIELD_GENERATOR_01_MK2);
    }

    public function getBoron1DockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_1_DOCK_PIER);
    }

    public function getBoron3DockEPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_3_DOCK_E_PIER);
    }

    public function getBoron4DockTPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_4_DOCK_T_PIER);
    }

    public function getBoron4m14sLuxuryDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_4M14S_LUXURY_DOCK_AREA);
    }

    public function getBoronAdministrativeCentre() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_ADMINISTRATIVE_CENTRE);
    }

    public function getBoronBaseConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_01);
    }

    public function getBoronBaseConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_02);
    }

    public function getBoronBaseConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_03);
    }

    public function getBoronBaseConnectionStructure_04() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_04);
    }

    public function getBoronBaseConnectionStructure_05() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_BASE_CONNECTION_STRUCTURE_05);
    }

    public function getBoronBridgeDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_BRIDGE_DEFENCE_PLATFORM);
    }

    public function getBoronCrossConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_CROSS_CONNECTION_STRUCTURE_01);
    }

    public function getBoronCrossConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_CROSS_CONNECTION_STRUCTURE_02);
    }

    public function getBoronDiscDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_DISC_DEFENCE_PLATFORM);
    }

    public function getBoronLContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_L_CONTAINER_STORAGE);
    }

    public function getBoronLLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_L_LIQUID_STORAGE);
    }

    public function getBoronLOasis() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_L_OASIS);
    }

    public function getBoronLShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_L_SHIP_FABRICATION_BAY);
    }

    public function getBoronLShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_L_SHIP_MAINTENANCE_BAY);
    }

    public function getBoronLSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_L_SOLID_STORAGE);
    }

    public function getBoronMContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_M_CONTAINER_STORAGE);
    }

    public function getBoronMLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_M_LIQUID_STORAGE);
    }

    public function getBoronMOasis() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_M_OASIS);
    }

    public function getBoronMSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_M_SOLID_STORAGE);
    }

    public function getBoronMedicalSupplyProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_MEDICAL_SUPPLY_PRODUCTION);
    }

    public function getBoronSContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_S_CONTAINER_STORAGE);
    }

    public function getBoronSLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_S_LIQUID_STORAGE);
    }

    public function getBoronSMShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_S_M_SHIP_FABRICATION_BAY);
    }

    public function getBoronSMShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_S_M_SHIP_MAINTENANCE_BAY);
    }

    public function getBoronSOasis() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_S_OASIS);
    }

    public function getBoronSSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_S_SOLID_STORAGE);
    }

    public function getBoronTradingStation4DockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_TRADING_STATION_4_DOCK_PIER);
    }

    public function getBoronTradingStationHexaDockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_TRADING_STATION_HEXA_DOCK_PIER);
    }

    public function getBoronVerticalConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_VERTICAL_CONNECTION_STRUCTURE_01);
    }

    public function getBoronVerticalConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_VERTICAL_CONNECTION_STRUCTURE_02);
    }

    public function getBoronXlShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_XL_SHIP_FABRICATION_BAY);
    }

    public function getBoronXlShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BORON_XL_SHIP_MAINTENANCE_BAY);
    }

    public function getBuffalo() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BUFFALO);
    }

    public function getBuildingDrone() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BUILDING_DRONE);
    }

    public function getBuzzardSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BUZZARD_SENTINEL);
    }

    public function getBuzzardVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_BUZZARD_VANGUARD);
    }

    public function getCallistoSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CALLISTO_SENTINEL);
    }

    public function getCallistoVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CALLISTO_VANGUARD);
    }

    public function getCargoDrone() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CARGO_DRONE);
    }

    public function getCasino() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CASINO);
    }

    public function getCerberusSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CERBERUS_SENTINEL);
    }

    public function getCerberusVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CERBERUS_VANGUARD);
    }

    public function getCheltProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHELT_PRODUCTION);
    }

    public function getChimera() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHIMERA);
    }

    public function getChthoniosEGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHTHONIOS_E_GAS);
    }

    public function getChthoniosEMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHTHONIOS_E_MINERAL);
    }

    public function getChthoniosGasSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHTHONIOS_GAS_SENTINEL);
    }

    public function getChthoniosGasVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHTHONIOS_GAS_VANGUARD);
    }

    public function getChthoniosMineralSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHTHONIOS_MINERAL_SENTINEL);
    }

    public function getChthoniosMineralVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CHTHONIOS_MINERAL_VANGUARD);
    }

    public function getClaytronicsProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CLAYTRONICS_PRODUCTION);
    }

    public function getClusterMine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CLUSTER_MINE);
    }

    public function getCobra() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COBRA);
    }

    public function getColossusE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COLOSSUS_E);
    }

    public function getColossusSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COLOSSUS_SENTINEL);
    }

    public function getColossusVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COLOSSUS_VANGUARD);
    }

    public function getComputronicSubstrateProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COMPUTRONIC_SUBSTRATE_PRODUCTION);
    }

    public function getCondensateContainmentFacility() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CONDENSATE_CONTAINMENT_FACILITY);
    }

    public function getCondorSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CONDOR_SENTINEL);
    }

    public function getCondorVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CONDOR_VANGUARD);
    }

    public function getConservatoryObservationDeck() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CONSERVATORY_OBSERVATION_DECK);
    }

    public function getCormorantVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CORMORANT_VANGUARD);
    }

    public function getCourierMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COURIER_MINERAL);
    }

    public function getCourierSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COURIER_SENTINEL);
    }

    public function getCourierVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_COURIER_VANGUARD);
    }

    public function getCraneEGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CRANE_E_GAS);
    }

    public function getCraneEMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CRANE_E_MINERAL);
    }

    public function getCraneGasSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CRANE_GAS_SENTINEL);
    }

    public function getCraneGasVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CRANE_GAS_VANGUARD);
    }

    public function getCraneMineralSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CRANE_MINERAL_SENTINEL);
    }

    public function getCraneMineralVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CRANE_MINERAL_VANGUARD);
    }

    public function getCutlass() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_CUTLASS);
    }

    public function getDart() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DART);
    }

    public function getDefenceDrone() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DEFENCE_DRONE);
    }

    public function getDemeterSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DEMETER_SENTINEL);
    }

    public function getDemeterVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DEMETER_VANGUARD);
    }

    public function getDiscovererSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DISCOVERER_SENTINEL);
    }

    public function getDiscovererVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DISCOVERER_VANGUARD);
    }

    public function getDolphin() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DOLPHIN);
    }

    public function getDonia() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DONIA);
    }

    public function getDragon() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DRAGON);
    }

    public function getDragonRaider() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DRAGON_RAIDER);
    }

    public function getDrillMineralSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DRILL_MINERAL_SENTINEL);
    }

    public function getDrillMineralVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DRILL_MINERAL_VANGUARD);
    }

    public function getDroneComponentProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_DRONE_COMPONENT_PRODUCTION);
    }

    public function getEclipseSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ECLIPSE_SENTINEL);
    }

    public function getEclipseVanguard_01A() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ECLIPSE_VANGUARD_01_A);
    }

    public function getEclipseVanguard_02A() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ECLIPSE_VANGUARD_02_A);
    }

    public function getElephant() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ELEPHANT);
    }

    public function getEliteSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ELITE_SENTINEL);
    }

    public function getEliteSport() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ELITE_SPORT);
    }

    public function getEliteVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ELITE_VANGUARD);
    }

    public function getEmpMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_EMP_MISSILE);
    }

    public function getEnergyCellProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ENERGY_CELL_PRODUCTION);
    }

    public function getEnginePartProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ENGINE_PART_PRODUCTION);
    }

    public function getErlking() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ERLKING);
    }

    public function getErlkingLTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ERLKING_L_TURRET);
    }

    public function getErlkingMTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ERLKING_M_TURRET);
    }

    public function getErlkingMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ERLKING_MAIN_BATTERY);
    }

    public function getErlkingXlEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ERLKING_XL_ENGINE);
    }

    public function getErlkingXlShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ERLKING_XL_SHIELD_GENERATOR);
    }

    public function getF() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_F);
    }

    public function getFalconSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FALCON_SENTINEL);
    }

    public function getFalconVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FALCON_VANGUARD);
    }

    public function getFalx() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FALX);
    }

    public function getFieldCoilProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FIELD_COIL_PRODUCTION);
    }

    public function getFlares() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FLARES);
    }

    public function getFoodRationProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FOOD_RATION_PRODUCTION);
    }

    public function getFriendFoeMine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FRIEND_FOE_MINE);
    }

    public function getFrog() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_FROG);
    }

    public function getGamblingDen() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GAMBLING_DEN);
    }

    public function getGannascus() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GANNASCUS);
    }

    public function getGasCollectorDrone() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GAS_COLLECTOR_DRONE);
    }

    public function getGladius() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GLADIUS);
    }

    public function getGorgonSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GORGON_SENTINEL);
    }

    public function getGorgonVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GORGON_VANGUARD);
    }

    public function getGrapheneProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GRAPHENE_PRODUCTION);
    }

    public function getGrouperMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GROUPER_MINERAL);
    }

    public function getGuillemotSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GUILLEMOT_SENTINEL);
    }

    public function getGuillemotVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GUILLEMOT_VANGUARD);
    }

    public function getGuppy() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_GUPPY);
    }

    public function getH() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_H);
    }

    public function getHeavyBarrageMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_BARRAGE_MISSILE);
    }

    public function getHeavyClusterMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_CLUSTER_MISSILE);
    }

    public function getHeavyDumbfireMissile_Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_DUMBFIRE_MISSILE_MK1);
    }

    public function getHeavyDumbfireMissile_Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_DUMBFIRE_MISSILE_MK2);
    }

    public function getHeavyGuidedMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_GUIDED_MISSILE);
    }

    public function getHeavyHeatseekerMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_HEATSEEKER_MISSILE);
    }

    public function getHeavyScatterMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_SCATTER_MISSILE);
    }

    public function getHeavySmartMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_SMART_MISSILE);
    }

    public function getHeavyStarburstMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_STARBURST_MISSILE);
    }

    public function getHeavySwarmMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_SWARM_MISSILE);
    }

    public function getHeavyTorpedoMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HEAVY_TORPEDO_MISSILE);
    }

    public function getHeliosE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HELIOS_E);
    }

    public function getHeliosSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HELIOS_SENTINEL);
    }

    public function getHeliosVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HELIOS_VANGUARD);
    }

    public function getHeraclesSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HERACLES_SENTINEL);
    }

    public function getHeraclesVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HERACLES_VANGUARD);
    }

    public function getHermesSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HERMES_SENTINEL);
    }

    public function getHermesVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HERMES_VANGUARD);
    }

    public function getHeronE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HERON_E);
    }

    public function getHeronSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HERON_SENTINEL);
    }

    public function getHeronVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HERON_VANGUARD);
    }

    public function getHokkaidoGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HOKKAIDO_GAS);
    }

    public function getHokkaidoMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HOKKAIDO_MINERAL);
    }

    public function getHonshu() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HONSHU);
    }

    public function getHullPartProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HULL_PART_PRODUCTION);
    }

    public function getHydra() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HYDRA);
    }

    public function getHyperion() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_HYPERION);
    }

    public function getIdesSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_IDES_SENTINEL);
    }

    public function getIdesVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_IDES_VANGUARD);
    }

    public function getIncarcaturaSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_INCARCATURA_SENTINEL);
    }

    public function getIncarcaturaVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_INCARCATURA_VANGUARD);
    }

    public function getIrukandji() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_IRUKANDJI);
    }

    public function getJaguar() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_JAGUAR);
    }

    public function getJian() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_JIAN);
    }

    public function getKalis() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KALIS);
    }

    public function getKatana() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KATANA);
    }

    public function getKestrelSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KESTREL_SENTINEL);
    }

    public function getKestrelSport() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KESTREL_SPORT);
    }

    public function getKestrelVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KESTREL_VANGUARD);
    }

    public function getKopisMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KOPIS_MINERAL);
    }

    public function getKukri() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KUKRI);
    }

    public function getKuraokami() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KURAOKAMI);
    }

    public function getKyd() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KYD);
    }

    public function getKyushu() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_KYUSHU);
    }

    public function getLAllRoundThrusters_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK1);
    }

    public function getLAllRoundThrusters_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK2);
    }

    public function getLAllRoundThrusters_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_L_ALL_ROUND_THRUSTERS_01_MK3);
    }

    public function getLShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_L_SHIP_FABRICATION_BAY);
    }

    public function getLShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_L_SHIP_MAINTENANCE_BAY);
    }

    public function getLaserTower_01AS() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LASER_TOWER_01_A_S);
    }

    public function getLaserTower_01AXs() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LASER_TOWER_01_A_XS);
    }

    public function getLightBarrageMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_BARRAGE_MISSILE);
    }

    public function getLightClusterMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_CLUSTER_MISSILE);
    }

    public function getLightDisruptorMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_DISRUPTOR_MISSILE);
    }

    public function getLightDumbfireMissile_Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_DUMBFIRE_MISSILE_MK1);
    }

    public function getLightDumbfireMissile_Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_DUMBFIRE_MISSILE_MK2);
    }

    public function getLightGuidedMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_GUIDED_MISSILE);
    }

    public function getLightHeatseekerMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_HEATSEEKER_MISSILE);
    }

    public function getLightInterceptorMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_INTERCEPTOR_MISSILE);
    }

    public function getLightSmartMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_SMART_MISSILE);
    }

    public function getLightSwarmMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_SWARM_MISSILE);
    }

    public function getLightTorpedoMissile() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LIGHT_TORPEDO_MISSILE);
    }

    public function getLux() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_LUX);
    }

    public function getMAllRoundThrusters_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK1);
    }

    public function getMAllRoundThrusters_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK2);
    }

    public function getMAllRoundThrusters_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_ALL_ROUND_THRUSTERS_01_MK3);
    }

    public function getMBeamEmitter_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_BEAM_EMITTER_01_MK1);
    }

    public function getMBeamEmitter_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_BEAM_EMITTER_01_MK2);
    }

    public function getMBoltRepeater_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_BOLT_REPEATER_01_MK1);
    }

    public function getMBoltRepeater_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_BOLT_REPEATER_01_MK2);
    }

    public function getMCombatThrusters_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_COMBAT_THRUSTERS_01_MK1);
    }

    public function getMCombatThrusters_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_COMBAT_THRUSTERS_01_MK2);
    }

    public function getMCombatThrusters_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_COMBAT_THRUSTERS_01_MK3);
    }

    public function getMDumbfireLauncher_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_DUMBFIRE_LAUNCHER_01_MK1);
    }

    public function getMDumbfireLauncher_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_DUMBFIRE_LAUNCHER_01_MK2);
    }

    public function getMMiningDrill_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_MINING_DRILL_01_MK1);
    }

    public function getMMiningDrill_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_MINING_DRILL_01_MK2);
    }

    public function getMPlasmaCannon_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_PLASMA_CANNON_01_MK1);
    }

    public function getMPlasmaCannon_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_PLASMA_CANNON_01_MK2);
    }

    public function getMPulseLaser_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_PULSE_LASER_01_MK1);
    }

    public function getMPulseLaser_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_PULSE_LASER_01_MK2);
    }

    public function getMShardBattery_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_SHARD_BATTERY_01_MK1);
    }

    public function getMShardBattery_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_SHARD_BATTERY_01_MK2);
    }

    public function getMTorpedoLauncher_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_TORPEDO_LAUNCHER_01_MK1);
    }

    public function getMTorpedoLauncher_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_TORPEDO_LAUNCHER_01_MK2);
    }

    public function getMTrackingLauncher_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_TRACKING_LAUNCHER_01_MK1);
    }

    public function getMTrackingLauncher_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_M_TRACKING_LAUNCHER_01_MK2);
    }

    public function getMagnetarGasSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAGNETAR_GAS_SENTINEL);
    }

    public function getMagnetarGasVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAGNETAR_GAS_VANGUARD);
    }

    public function getMagnetarMineralSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAGNETAR_MINERAL_SENTINEL);
    }

    public function getMagnetarMineralVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAGNETAR_MINERAL_VANGUARD);
    }

    public function getMagpieMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAGPIE_MINERAL);
    }

    public function getMagpieSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAGPIE_SENTINEL);
    }

    public function getMagpieVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAGPIE_VANGUARD);
    }

    public function getMajaDustProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAJA_DUST_PRODUCTION);
    }

    public function getMajaSnailProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAJA_SNAIL_PRODUCTION);
    }

    public function getMako() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAKO);
    }

    public function getMamba() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAMBA);
    }

    public function getMammothSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAMMOTH_SENTINEL);
    }

    public function getMammothVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MAMMOTH_VANGUARD);
    }

    public function getManorinaGasSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MANORINA_GAS_SENTINEL);
    }

    public function getManorinaGasVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MANORINA_GAS_VANGUARD);
    }

    public function getManorinaMineralSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MANORINA_MINERAL_SENTINEL);
    }

    public function getManorinaMineralVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MANORINA_MINERAL_VANGUARD);
    }

    public function getManticore() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MANTICORE);
    }

    public function getMeatProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MEAT_PRODUCTION);
    }

    public function getMercurySentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MERCURY_SENTINEL);
    }

    public function getMercuryVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MERCURY_VANGUARD);
    }

    public function getMetallicMicrolatticeProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_METALLIC_MICROLATTICE_PRODUCTION);
    }

    public function getMicrochipProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MICROCHIP_PRODUCTION);
    }

    public function getMine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MINE);
    }

    public function getMiningDrone() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MINING_DRONE);
    }

    public function getMinotaurRaider() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MINOTAUR_RAIDER);
    }

    public function getMinotaurSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MINOTAUR_SENTINEL);
    }

    public function getMinotaurVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MINOTAUR_VANGUARD);
    }

    public function getMissileComponentProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MISSILE_COMPONENT_PRODUCTION);
    }

    public function getMokosiSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MOKOSI_SENTINEL);
    }

    public function getMokosiVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MOKOSI_VANGUARD);
    }

    public function getMonitor() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MONITOR);
    }

    public function getMoreya() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_MOREYA);
    }

    public function getNavBeacon() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NAV_BEACON);
    }

    public function getNemesisSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NEMESIS_SENTINEL);
    }

    public function getNemesisVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NEMESIS_VANGUARD);
    }

    public function getNimcha() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NIMCHA);
    }

    public function getNodanSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NODAN_SENTINEL);
    }

    public function getNodanVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NODAN_VANGUARD);
    }

    public function getNomadSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NOMAD_SENTINEL);
    }

    public function getNomadVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NOMAD_VANGUARD);
    }

    public function getNostropOilProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NOSTROP_OIL_PRODUCTION);
    }

    public function getNovaSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NOVA_SENTINEL);
    }

    public function getNovaVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_NOVA_VANGUARD);
    }

    public function getOdachi() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ODACHI);
    }

    public function getOdysseusE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ODYSSEUS_E);
    }

    public function getOdysseusSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ODYSSEUS_SENTINEL);
    }

    public function getOdysseusVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ODYSSEUS_VANGUARD);
    }

    public function getOkinawa() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_OKINAWA);
    }

    public function getOrca() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ORCA);
    }

    public function getOsaka() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_OSAKA);
    }

    public function getOspreySentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_OSPREY_SENTINEL);
    }

    public function getOspreyVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_OSPREY_VANGUARD);
    }

    public function getParHyperionMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_HYPERION_MAIN_BATTERY);
    }

    public function getParLAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_ALL_ROUND_ENGINE);
    }

    public function getParLBeamTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_BEAM_TURRET);
    }

    public function getParLDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_DUMBFIRE_TURRET);
    }

    public function getParLMiningTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_MINING_TURRET);
    }

    public function getParLPlasmaTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_PLASMA_TURRET);
    }

    public function getParLPulseTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_PULSE_TURRET);
    }

    public function getParLShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_SHIELD_GENERATOR_01_MK1);
    }

    public function getParLShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_SHIELD_GENERATOR_01_MK2);
    }

    public function getParLTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_TRACKING_TURRET);
    }

    public function getParLTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_L_TRAVEL_ENGINE);
    }

    public function getParMAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getParMAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getParMAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getParMBeamTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_BEAM_TURRET_01_MK1);
    }

    public function getParMBeamTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_BEAM_TURRET_02_MK1);
    }

    public function getParMBoltTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_BOLT_TURRET_01_MK1);
    }

    public function getParMBoltTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_BOLT_TURRET_02_MK1);
    }

    public function getParMCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK1);
    }

    public function getParMCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK2);
    }

    public function getParMCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_COMBAT_ENGINE_01_MK3);
    }

    public function getParMDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_DUMBFIRE_TURRET);
    }

    public function getParMMassDriver_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_MASS_DRIVER_01_MK1);
    }

    public function getParMMassDriver_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_MASS_DRIVER_01_MK2);
    }

    public function getParMMiningTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_MINING_TURRET_01_MK1);
    }

    public function getParMMiningTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_MINING_TURRET_02_MK1);
    }

    public function getParMPlasmaTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_PLASMA_TURRET_01_MK1);
    }

    public function getParMPlasmaTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_PLASMA_TURRET_02_MK1);
    }

    public function getParMPulseTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_PULSE_TURRET_01_MK1);
    }

    public function getParMPulseTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_PULSE_TURRET_02_MK1);
    }

    public function getParMShardTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_SHARD_TURRET_01_MK1);
    }

    public function getParMShardTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_SHARD_TURRET_02_MK1);
    }

    public function getParMShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_01_MK1);
    }

    public function getParMShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_01_MK2);
    }

    public function getParMShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_02_MK1);
    }

    public function getParMShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_SHIELD_GENERATOR_02_MK2);
    }

    public function getParMTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_TRACKING_TURRET);
    }

    public function getParMTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK1);
    }

    public function getParMTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK2);
    }

    public function getParMTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_M_TRAVEL_ENGINE_01_MK3);
    }

    public function getParOdysseusMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_ODYSSEUS_MAIN_BATTERY);
    }

    public function getParSAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getParSAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getParSAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getParSCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK1);
    }

    public function getParSCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK2);
    }

    public function getParSCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_COMBAT_ENGINE_01_MK3);
    }

    public function getParSMassDriver_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_MASS_DRIVER_01_MK1);
    }

    public function getParSMassDriver_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_MASS_DRIVER_01_MK2);
    }

    public function getParSRacingEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_RACING_ENGINE);
    }

    public function getParSShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK1);
    }

    public function getParSShieldGenerator_01Mk1Racing() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK1_RACING);
    }

    public function getParSShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK2);
    }

    public function getParSShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_SHIELD_GENERATOR_01_MK3);
    }

    public function getParSTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK1);
    }

    public function getParSTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK2);
    }

    public function getParSTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_S_TRAVEL_ENGINE_01_MK3);
    }

    public function getParXlAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_XL_ALL_ROUND_ENGINE);
    }

    public function getParXlShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_XL_SHIELD_GENERATOR);
    }

    public function getParXlTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAR_XL_TRAVEL_ENGINE);
    }

    public function getParanid1DockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_1_DOCK_PIER);
    }

    public function getParanid3DockEPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_3_DOCK_E_PIER);
    }

    public function getParanid3DockTPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_3_DOCK_T_PIER);
    }

    public function getParanidAdministrativeCentre() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_ADMINISTRATIVE_CENTRE);
    }

    public function getParanidBaseConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_01);
    }

    public function getParanidBaseConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_02);
    }

    public function getParanidBaseConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_BASE_CONNECTION_STRUCTURE_03);
    }

    public function getParanidBridgeDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_BRIDGE_DEFENCE_PLATFORM);
    }

    public function getParanidCrossConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_01);
    }

    public function getParanidCrossConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_02);
    }

    public function getParanidCrossConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_CROSS_CONNECTION_STRUCTURE_03);
    }

    public function getParanidDiscDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_DISC_DEFENCE_PLATFORM);
    }

    public function getParanidFactionCapital() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_FACTION_CAPITAL);
    }

    public function getParanidLContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_L_CONTAINER_STORAGE);
    }

    public function getParanidLDome() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_L_DOME);
    }

    public function getParanidLLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_L_LIQUID_STORAGE);
    }

    public function getParanidLSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_L_SOLID_STORAGE);
    }

    public function getParanidMContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_M_CONTAINER_STORAGE);
    }

    public function getParanidMDome() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_M_DOME);
    }

    public function getParanidMLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_M_LIQUID_STORAGE);
    }

    public function getParanidMSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_M_SOLID_STORAGE);
    }

    public function getParanidMedicalSupplyProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_MEDICAL_SUPPLY_PRODUCTION);
    }

    public function getParanidSContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_S_CONTAINER_STORAGE);
    }

    public function getParanidSDome() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_S_DOME);
    }

    public function getParanidSLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_S_LIQUID_STORAGE);
    }

    public function getParanidSSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_S_SOLID_STORAGE);
    }

    public function getParanidVerticalConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_VERTICAL_CONNECTION_STRUCTURE_01);
    }

    public function getParanidVerticalConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PARANID_VERTICAL_CONNECTION_STRUCTURE_02);
    }

    public function getPavilionObservationDeck() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PAVILION_OBSERVATION_DECK);
    }

    public function getPe() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PE);
    }

    public function getPegasusSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PEGASUS_SENTINEL);
    }

    public function getPegasusVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PEGASUS_VANGUARD);
    }

    public function getPelicanSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PELICAN_SENTINEL);
    }

    public function getPelicanVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PELICAN_VANGUARD);
    }

    public function getPenthouseObservationDeck() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PENTHOUSE_OBSERVATION_DECK);
    }

    public function getPeregrineSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PEREGRINE_SENTINEL);
    }

    public function getPeregrineVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PEREGRINE_VANGUARD);
    }

    public function getPerseusSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PERSEUS_SENTINEL);
    }

    public function getPerseusVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PERSEUS_VANGUARD);
    }

    public function getPheromoneArtGallery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PHEROMONE_ART_GALLERY);
    }

    public function getPhoenixE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PHOENIX_E);
    }

    public function getPhoenixSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PHOENIX_SENTINEL);
    }

    public function getPhoenixVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PHOENIX_VANGUARD);
    }

    public function getPiranha() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PIRANHA);
    }

    public function getPlanktonProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PLANKTON_PRODUCTION);
    }

    public function getPlasmaConductorProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PLASMA_CONDUCTOR_PRODUCTION);
    }

    public function getPlutusGasSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PLUTUS_GAS_SENTINEL);
    }

    public function getPlutusGasVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PLUTUS_GAS_VANGUARD);
    }

    public function getPlutusMineralSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PLUTUS_MINERAL_SENTINEL);
    }

    public function getPlutusMineralVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PLUTUS_MINERAL_VANGUARD);
    }

    public function getPorpoiseGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PORPOISE_GAS);
    }

    public function getPorpoiseMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PORPOISE_MINERAL);
    }

    public function getPrometheus() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PROMETHEUS);
    }

    public function getProtectyonShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PROTECTYON_SHIELD_GENERATOR);
    }

    public function getProteinPasteProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PROTEIN_PASTE_PRODUCTION);
    }

    public function getPulsarVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_PULSAR_VANGUARD);
    }

    public function getQuantumTubeProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_QUANTUM_TUBE_PRODUCTION);
    }

    public function getQuasarVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_QUASAR_VANGUARD);
    }

    public function getRaleighCondensate() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RALEIGH_CONDENSATE);
    }

    public function getRaleighContainer() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RALEIGH_CONTAINER);
    }

    public function getRapier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RAPIER);
    }

    public function getRaptor() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RAPTOR);
    }

    public function getRattlesnake() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RATTLESNAKE);
    }

    public function getRay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RAY);
    }

    public function getRefinedMetalProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_REFINED_METAL_PRODUCTION);
    }

    public function getRepairDrone() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_REPAIR_DRONE);
    }

    public function getResourceProbe() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RESOURCE_PROBE);
    }

    public function getRorqualGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RORQUAL_GAS);
    }

    public function getRorqualMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_RORQUAL_MINERAL);
    }

    public function getSAllRoundThrusters_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK1);
    }

    public function getSAllRoundThrusters_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK2);
    }

    public function getSAllRoundThrusters_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_ALL_ROUND_THRUSTERS_01_MK3);
    }

    public function getSBeamEmitter_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BEAM_EMITTER_01_MK1);
    }

    public function getSBeamEmitter_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BEAM_EMITTER_01_MK2);
    }

    public function getSBlastMortar_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BLAST_MORTAR_01_MK1);
    }

    public function getSBlastMortar_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BLAST_MORTAR_01_MK2);
    }

    public function getSBoltRepeater_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BOLT_REPEATER_01_MK1);
    }

    public function getSBoltRepeater_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BOLT_REPEATER_01_MK2);
    }

    public function getSBurstRay_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BURST_RAY_01_MK1);
    }

    public function getSBurstRay_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_BURST_RAY_01_MK2);
    }

    public function getSCombatThrusters_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_COMBAT_THRUSTERS_01_MK1);
    }

    public function getSCombatThrusters_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_COMBAT_THRUSTERS_01_MK2);
    }

    public function getSCombatThrusters_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_COMBAT_THRUSTERS_01_MK3);
    }

    public function getSDumbfireLauncher_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_DUMBFIRE_LAUNCHER_01_MK1);
    }

    public function getSDumbfireLauncher_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_DUMBFIRE_LAUNCHER_01_MK2);
    }

    public function getSMShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_M_SHIP_FABRICATION_BAY);
    }

    public function getSMShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_M_SHIP_MAINTENANCE_BAY);
    }

    public function getSMVentureSendoffDock() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_M_VENTURE_SENDOFF_DOCK);
    }

    public function getSMiningDrill_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_MINING_DRILL_01_MK1);
    }

    public function getSMiningDrill_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_MINING_DRILL_01_MK2);
    }

    public function getSPlasmaCannon_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_PLASMA_CANNON_01_MK1);
    }

    public function getSPlasmaCannon_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_PLASMA_CANNON_01_MK2);
    }

    public function getSPulseLaser_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_PULSE_LASER_01_MK1);
    }

    public function getSPulseLaser_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_PULSE_LASER_01_MK2);
    }

    public function getSRacingEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_RACING_ENGINE_01_MK1);
    }

    public function getSRacingEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_RACING_ENGINE_01_MK2);
    }

    public function getSRacingShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_RACING_SHIELD_GENERATOR);
    }

    public function getSShardBattery_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_SHARD_BATTERY_01_MK1);
    }

    public function getSShardBattery_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_SHARD_BATTERY_01_MK2);
    }

    public function getSTorpedoLauncher_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_TORPEDO_LAUNCHER_01_MK1);
    }

    public function getSTorpedoLauncher_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_TORPEDO_LAUNCHER_01_MK2);
    }

    public function getSTrackingLauncher_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_TRACKING_LAUNCHER_01_MK1);
    }

    public function getSTrackingLauncher_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_S_TRACKING_LAUNCHER_01_MK2);
    }

    public function getSapporo() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SAPPORO);
    }

    public function getSatellite() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SATELLITE);
    }

    public function getScanningArrayProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SCANNING_ARRAY_PRODUCTION);
    }

    public function getScrapProcessor() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SCRAP_PROCESSOR);
    }

    public function getScrapRecycler() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SCRAP_RECYCLER);
    }

    public function getScrapTractor() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SCRAP_TRACTOR);
    }

    public function getScruffinProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SCRUFFIN_PRODUCTION);
    }

    public function getSe() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SE);
    }

    public function getSeleneSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SELENE_SENTINEL);
    }

    public function getSeleneVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SELENE_VANGUARD);
    }

    public function getShark() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SHARK);
    }

    public function getShieldComponentProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SHIELD_COMPONENT_PRODUCTION);
    }

    public function getShih() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SHIH);
    }

    public function getShuyakuSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SHUYAKU_SENTINEL);
    }

    public function getShuyakuVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SHUYAKU_VANGUARD);
    }

    public function getSiliconCarbideProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SILICON_CARBIDE_PRODUCTION);
    }

    public function getSiliconWaferProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SILICON_WAFER_PRODUCTION);
    }

    public function getSmartChipProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SMART_CHIP_PRODUCTION);
    }

    public function getSojaBeanProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SOJA_BEAN_PRODUCTION);
    }

    public function getSojaHuskProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SOJA_HUSK_PRODUCTION);
    }

    public function getSonraSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SONRA_SENTINEL);
    }

    public function getSonraVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SONRA_VANGUARD);
    }

    public function getSpacefuelProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPACEFUEL_PRODUCTION);
    }

    public function getSpaceweedProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPACEWEED_PRODUCTION);
    }

    public function getSpiceProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPICE_PRODUCTION);
    }

    public function getSplLAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_ALL_ROUND_ENGINE);
    }

    public function getSplLBeamTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_BEAM_TURRET);
    }

    public function getSplLDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_DUMBFIRE_TURRET);
    }

    public function getSplLMiningTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_MINING_TURRET);
    }

    public function getSplLPlasmaTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_PLASMA_TURRET);
    }

    public function getSplLPulseTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_PULSE_TURRET);
    }

    public function getSplLShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_SHIELD_GENERATOR_01_MK1);
    }

    public function getSplLShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_SHIELD_GENERATOR_01_MK2);
    }

    public function getSplLTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_TRACKING_TURRET);
    }

    public function getSplLTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_L_TRAVEL_ENGINE);
    }

    public function getSplMAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getSplMAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getSplMAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getSplMBeamTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_BEAM_TURRET_01_MK1);
    }

    public function getSplMBeamTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_BEAM_TURRET_02_MK1);
    }

    public function getSplMBoltTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_BOLT_TURRET_01_MK1);
    }

    public function getSplMBoltTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_BOLT_TURRET_02_MK1);
    }

    public function getSplMBosonLance_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_BOSON_LANCE_01_MK1);
    }

    public function getSplMBosonLance_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_BOSON_LANCE_01_MK2);
    }

    public function getSplMCombatEngineMk4() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_COMBAT_ENGINE_MK4);
    }

    public function getSplMCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK1);
    }

    public function getSplMCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK2);
    }

    public function getSplMCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_COMBAT_ENGINE_01_MK3);
    }

    public function getSplMDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_DUMBFIRE_TURRET);
    }

    public function getSplMFlakTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_FLAK_TURRET_01_MK1);
    }

    public function getSplMFlakTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_FLAK_TURRET_02_MK1);
    }

    public function getSplMMiningTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_MINING_TURRET_01_MK1);
    }

    public function getSplMMiningTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_MINING_TURRET_02_MK1);
    }

    public function getSplMNeutronGatling_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_NEUTRON_GATLING_01_MK1);
    }

    public function getSplMNeutronGatling_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_NEUTRON_GATLING_01_MK2);
    }

    public function getSplMPlasmaTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_PLASMA_TURRET_01_MK1);
    }

    public function getSplMPlasmaTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_PLASMA_TURRET_02_MK1);
    }

    public function getSplMPulseTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_PULSE_TURRET_01_MK1);
    }

    public function getSplMPulseTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_PULSE_TURRET_02_MK1);
    }

    public function getSplMShardTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_SHARD_TURRET_01_MK1);
    }

    public function getSplMShardTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_SHARD_TURRET_02_MK1);
    }

    public function getSplMShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_01_MK1);
    }

    public function getSplMShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_01_MK2);
    }

    public function getSplMShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_02_MK1);
    }

    public function getSplMShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_SHIELD_GENERATOR_02_MK2);
    }

    public function getSplMTauAccelerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_TAU_ACCELERATOR_01_MK1);
    }

    public function getSplMTauAccelerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_TAU_ACCELERATOR_01_MK2);
    }

    public function getSplMThermalDisintegrator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_THERMAL_DISINTEGRATOR_01_MK1);
    }

    public function getSplMThermalDisintegrator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_THERMAL_DISINTEGRATOR_01_MK2);
    }

    public function getSplMTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_TRACKING_TURRET);
    }

    public function getSplMTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK1);
    }

    public function getSplMTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK2);
    }

    public function getSplMTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_M_TRAVEL_ENGINE_01_MK3);
    }

    public function getSplRattlesnakeMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_RATTLESNAKE_MAIN_BATTERY);
    }

    public function getSplSAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getSplSAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getSplSAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getSplSBosonLance_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_BOSON_LANCE_01_MK1);
    }

    public function getSplSBosonLance_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_BOSON_LANCE_01_MK2);
    }

    public function getSplSCombatEngineMk4() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_COMBAT_ENGINE_MK4);
    }

    public function getSplSCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK1);
    }

    public function getSplSCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK2);
    }

    public function getSplSCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_COMBAT_ENGINE_01_MK3);
    }

    public function getSplSNeutronGatling_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_NEUTRON_GATLING_01_MK1);
    }

    public function getSplSNeutronGatling_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_NEUTRON_GATLING_01_MK2);
    }

    public function getSplSShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK1);
    }

    public function getSplSShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK2);
    }

    public function getSplSShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_SHIELD_GENERATOR_01_MK3);
    }

    public function getSplSTauAccelerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_TAU_ACCELERATOR_01_MK1);
    }

    public function getSplSTauAccelerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_TAU_ACCELERATOR_01_MK2);
    }

    public function getSplSThermalDisintegrator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_THERMAL_DISINTEGRATOR_01_MK1);
    }

    public function getSplSThermalDisintegrator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_THERMAL_DISINTEGRATOR_01_MK2);
    }

    public function getSplSTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK1);
    }

    public function getSplSTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK2);
    }

    public function getSplSTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_S_TRAVEL_ENGINE_01_MK3);
    }

    public function getSplXlAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_XL_ALL_ROUND_ENGINE);
    }

    public function getSplXlShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_XL_SHIELD_GENERATOR);
    }

    public function getSplXlTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPL_XL_TRAVEL_ENGINE);
    }

    public function getSplit1DockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_1_DOCK_PIER);
    }

    public function getSplit3DockEPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_3_DOCK_E_PIER);
    }

    public function getSplit4DockTPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_4_DOCK_T_PIER);
    }

    public function getSplitAdministrativeCentre() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_ADMINISTRATIVE_CENTRE);
    }

    public function getSplitBaseConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_01);
    }

    public function getSplitBaseConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_02);
    }

    public function getSplitBaseConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_BASE_CONNECTION_STRUCTURE_03);
    }

    public function getSplitBridgeDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_BRIDGE_DEFENCE_PLATFORM);
    }

    public function getSplitCrossConnectionStructure() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_CROSS_CONNECTION_STRUCTURE);
    }

    public function getSplitDiscDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_DISC_DEFENCE_PLATFORM);
    }

    public function getSplitLContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_L_CONTAINER_STORAGE);
    }

    public function getSplitLLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_L_LIQUID_STORAGE);
    }

    public function getSplitLParlour() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_L_PARLOUR);
    }

    public function getSplitLSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_L_SOLID_STORAGE);
    }

    public function getSplitMContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_M_CONTAINER_STORAGE);
    }

    public function getSplitMLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_M_LIQUID_STORAGE);
    }

    public function getSplitMParlour() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_M_PARLOUR);
    }

    public function getSplitMSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_M_SOLID_STORAGE);
    }

    public function getSplitMedicalSupplyProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_MEDICAL_SUPPLY_PRODUCTION);
    }

    public function getSplitSContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_S_CONTAINER_STORAGE);
    }

    public function getSplitSLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_S_LIQUID_STORAGE);
    }

    public function getSplitSParlour() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_S_PARLOUR);
    }

    public function getSplitSSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_S_SOLID_STORAGE);
    }

    public function getSplitVerticalConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_VERTICAL_CONNECTION_STRUCTURE_01);
    }

    public function getSplitVerticalConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SPLIT_VERTICAL_CONNECTION_STRUCTURE_02);
    }

    public function getStimulantProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_STIMULANT_PRODUCTION);
    }

    public function getStorkSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_STORK_SENTINEL);
    }

    public function getStorkVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_STORK_VANGUARD);
    }

    public function getSturgeon() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_STURGEON);
    }

    public function getSunderGasSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SUNDER_GAS_SENTINEL);
    }

    public function getSunderGasVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SUNDER_GAS_VANGUARD);
    }

    public function getSunriseFlowerProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SUNRISE_FLOWER_PRODUCTION);
    }

    public function getSuperfluidCoolantProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SUPERFLUID_COOLANT_PRODUCTION);
    }

    public function getSwampPlantProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SWAMP_PLANT_PRODUCTION);
    }

    public function getSyn() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_SYN);
    }

    public function getTakoba() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TAKOBA);
    }

    public function getTelLAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_ALL_ROUND_ENGINE);
    }

    public function getTelLBeamTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_BEAM_TURRET);
    }

    public function getTelLDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_DUMBFIRE_TURRET);
    }

    public function getTelLMiningTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_MINING_TURRET);
    }

    public function getTelLPlasmaTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_PLASMA_TURRET);
    }

    public function getTelLPulseTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_PULSE_TURRET);
    }

    public function getTelLShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_SHIELD_GENERATOR_01_MK1);
    }

    public function getTelLShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_SHIELD_GENERATOR_01_MK2);
    }

    public function getTelLTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_TRACKING_TURRET);
    }

    public function getTelLTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_L_TRAVEL_ENGINE);
    }

    public function getTelMAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getTelMAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getTelMAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getTelMBeamTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_BEAM_TURRET_01_MK1);
    }

    public function getTelMBeamTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_BEAM_TURRET_02_MK1);
    }

    public function getTelMBoltTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_BOLT_TURRET_01_MK1);
    }

    public function getTelMBoltTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_BOLT_TURRET_02_MK1);
    }

    public function getTelMCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK1);
    }

    public function getTelMCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK2);
    }

    public function getTelMCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_COMBAT_ENGINE_01_MK3);
    }

    public function getTelMDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_DUMBFIRE_TURRET);
    }

    public function getTelMMiningTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_MINING_TURRET_01_MK1);
    }

    public function getTelMMiningTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_MINING_TURRET_02_MK1);
    }

    public function getTelMMuonCharger_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_MUON_CHARGER_01_MK1);
    }

    public function getTelMMuonCharger_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_MUON_CHARGER_01_MK2);
    }

    public function getTelMPlasmaTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_PLASMA_TURRET_01_MK1);
    }

    public function getTelMPlasmaTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_PLASMA_TURRET_02_MK1);
    }

    public function getTelMPulseTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_PULSE_TURRET_01_MK1);
    }

    public function getTelMPulseTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_PULSE_TURRET_02_MK1);
    }

    public function getTelMShardTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_SHARD_TURRET_01_MK1);
    }

    public function getTelMShardTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_SHARD_TURRET_02_MK1);
    }

    public function getTelMShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_01_MK1);
    }

    public function getTelMShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_01_MK2);
    }

    public function getTelMShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_02_MK1);
    }

    public function getTelMShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_SHIELD_GENERATOR_02_MK2);
    }

    public function getTelMTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_TRACKING_TURRET);
    }

    public function getTelMTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK1);
    }

    public function getTelMTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK2);
    }

    public function getTelMTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_M_TRAVEL_ENGINE_01_MK3);
    }

    public function getTelPhoenixMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_PHOENIX_MAIN_BATTERY);
    }

    public function getTelSAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getTelSAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getTelSAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getTelSCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK1);
    }

    public function getTelSCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK2);
    }

    public function getTelSCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_COMBAT_ENGINE_01_MK3);
    }

    public function getTelSMuonCharger_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_MUON_CHARGER_01_MK1);
    }

    public function getTelSMuonCharger_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_MUON_CHARGER_01_MK2);
    }

    public function getTelSRacingEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_RACING_ENGINE);
    }

    public function getTelSShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK1);
    }

    public function getTelSShieldGenerator_01Mk1Racing() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK1_RACING);
    }

    public function getTelSShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK2);
    }

    public function getTelSShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_SHIELD_GENERATOR_01_MK3);
    }

    public function getTelSTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK1);
    }

    public function getTelSTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK2);
    }

    public function getTelSTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_S_TRAVEL_ENGINE_01_MK3);
    }

    public function getTelXlAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_XL_ALL_ROUND_ENGINE);
    }

    public function getTelXlShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_XL_SHIELD_GENERATOR);
    }

    public function getTelXlTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEL_XL_TRAVEL_ENGINE);
    }

    public function getTeladi1DockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_1_DOCK_PIER);
    }

    public function getTeladi3DockEPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_3_DOCK_E_PIER);
    }

    public function getTeladi3DockTPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_3_DOCK_T_PIER);
    }

    public function getTeladiAdministrativeCentre() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_ADMINISTRATIVE_CENTRE);
    }

    public function getTeladiAdvancedCompositeProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_ADVANCED_COMPOSITE_PRODUCTION);
    }

    public function getTeladiBaseConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_01);
    }

    public function getTeladiBaseConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_02);
    }

    public function getTeladiBaseConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_BASE_CONNECTION_STRUCTURE_03);
    }

    public function getTeladiBridgeDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_BRIDGE_DEFENCE_PLATFORM);
    }

    public function getTeladiCrossConnectionStructure() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_CROSS_CONNECTION_STRUCTURE);
    }

    public function getTeladiDiscDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_DISC_DEFENCE_PLATFORM);
    }

    public function getTeladiEnginePartProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_ENGINE_PART_PRODUCTION);
    }

    public function getTeladiHullPartProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_HULL_PART_PRODUCTION);
    }

    public function getTeladiLBiome() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_L_BIOME);
    }

    public function getTeladiLContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_L_CONTAINER_STORAGE);
    }

    public function getTeladiLLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_L_LIQUID_STORAGE);
    }

    public function getTeladiLSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_L_SOLID_STORAGE);
    }

    public function getTeladiMBiome() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_M_BIOME);
    }

    public function getTeladiMContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_M_CONTAINER_STORAGE);
    }

    public function getTeladiMLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_M_LIQUID_STORAGE);
    }

    public function getTeladiMSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_M_SOLID_STORAGE);
    }

    public function getTeladiMedicalSupplyProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_MEDICAL_SUPPLY_PRODUCTION);
    }

    public function getTeladiSBiome() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_S_BIOME);
    }

    public function getTeladiSContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_S_CONTAINER_STORAGE);
    }

    public function getTeladiSLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_S_LIQUID_STORAGE);
    }

    public function getTeladiSSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_S_SOLID_STORAGE);
    }

    public function getTeladiScanningArrayProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_SCANNING_ARRAY_PRODUCTION);
    }

    public function getTeladiVerticalConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_VERTICAL_CONNECTION_STRUCTURE_01);
    }

    public function getTeladiVerticalConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADI_VERTICAL_CONNECTION_STRUCTURE_02);
    }

    public function getTeladianiumProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TELADIANIUM_PRODUCTION);
    }

    public function getTerLAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_ALL_ROUND_ENGINE);
    }

    public function getTerLBeamTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_BEAM_TURRET);
    }

    public function getTerLBoltTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_BOLT_TURRET);
    }

    public function getTerLDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_DUMBFIRE_TURRET);
    }

    public function getTerLFrontierEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_FRONTIER_ENGINE);
    }

    public function getTerLFrontierShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK1);
    }

    public function getTerLFrontierShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK2);
    }

    public function getTerLFrontierShieldGenerator_02Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_FRONTIER_SHIELD_GENERATOR_02_MK3);
    }

    public function getTerLMiningTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_MINING_TURRET);
    }

    public function getTerLPulseTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_PULSE_TURRET);
    }

    public function getTerLShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK1);
    }

    public function getTerLShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK2);
    }

    public function getTerLShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_SHIELD_GENERATOR_01_MK3);
    }

    public function getTerLTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_TRACKING_TURRET);
    }

    public function getTerLTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_L_TRAVEL_ENGINE);
    }

    public function getTerMAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getTerMAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getTerMAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getTerMBeamTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_BEAM_TURRET_01_MK1);
    }

    public function getTerMBeamTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_BEAM_TURRET_02_MK1);
    }

    public function getTerMBoltTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_BOLT_TURRET_01_MK1);
    }

    public function getTerMBoltTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_BOLT_TURRET_02_MK1);
    }

    public function getTerMCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK1);
    }

    public function getTerMCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK2);
    }

    public function getTerMCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_COMBAT_ENGINE_01_MK3);
    }

    public function getTerMDumbfireTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_DUMBFIRE_TURRET);
    }

    public function getTerMElectromagneticCannon() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_ELECTROMAGNETIC_CANNON);
    }

    public function getTerMElectromagneticTurret_03Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_ELECTROMAGNETIC_TURRET_03_MK1);
    }

    public function getTerMElectromagneticTurret_04Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_ELECTROMAGNETIC_TURRET_04_MK1);
    }

    public function getTerMFrontierEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_FRONTIER_ENGINE);
    }

    public function getTerMFrontierShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK1);
    }

    public function getTerMFrontierShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK2);
    }

    public function getTerMFrontierShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_01_MK3);
    }

    public function getTerMFrontierShieldGenerator_04Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK1);
    }

    public function getTerMFrontierShieldGenerator_04Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK2);
    }

    public function getTerMFrontierShieldGenerator_04Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_FRONTIER_SHIELD_GENERATOR_04_MK3);
    }

    public function getTerMMesonStream_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_MESON_STREAM_01_MK1);
    }

    public function getTerMMesonStream_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_MESON_STREAM_01_MK2);
    }

    public function getTerMMiningTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_MINING_TURRET_01_MK1);
    }

    public function getTerMMiningTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_MINING_TURRET_02_MK1);
    }

    public function getTerMProtonBarrage_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_PROTON_BARRAGE_01_MK1);
    }

    public function getTerMProtonBarrage_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_PROTON_BARRAGE_01_MK2);
    }

    public function getTerMPulseLaser_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_PULSE_LASER_01_MK1);
    }

    public function getTerMPulseLaser_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_PULSE_LASER_01_MK2);
    }

    public function getTerMPulseTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_PULSE_TURRET_01_MK1);
    }

    public function getTerMPulseTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_PULSE_TURRET_02_MK1);
    }

    public function getTerMShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK1);
    }

    public function getTerMShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK2);
    }

    public function getTerMShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_SHIELD_GENERATOR_01_MK3);
    }

    public function getTerMShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK1);
    }

    public function getTerMShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK2);
    }

    public function getTerMShieldGenerator_02Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_SHIELD_GENERATOR_02_MK3);
    }

    public function getTerMTrackingTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_TRACKING_TURRET);
    }

    public function getTerMTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK1);
    }

    public function getTerMTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK2);
    }

    public function getTerMTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_M_TRAVEL_ENGINE_01_MK3);
    }

    public function getTerSAllRoundEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK1);
    }

    public function getTerSAllRoundEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK2);
    }

    public function getTerSAllRoundEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_ALL_ROUND_ENGINE_01_MK3);
    }

    public function getTerSCombatEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK1);
    }

    public function getTerSCombatEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK2);
    }

    public function getTerSCombatEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_COMBAT_ENGINE_01_MK3);
    }

    public function getTerSElectromagneticGun() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_ELECTROMAGNETIC_GUN);
    }

    public function getTerSFrontierEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_FRONTIER_ENGINE);
    }

    public function getTerSFrontierShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_FRONTIER_SHIELD_GENERATOR);
    }

    public function getTerSFrontierShieldGeneratorMk5() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_FRONTIER_SHIELD_GENERATOR_MK5);
    }

    public function getTerSMesonStream_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_MESON_STREAM_01_MK1);
    }

    public function getTerSMesonStream_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_MESON_STREAM_01_MK2);
    }

    public function getTerSProtonBarrage_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_PROTON_BARRAGE_01_MK1);
    }

    public function getTerSProtonBarrage_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_PROTON_BARRAGE_01_MK2);
    }

    public function getTerSPulseLaser_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_PULSE_LASER_01_MK1);
    }

    public function getTerSPulseLaser_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_PULSE_LASER_01_MK2);
    }

    public function getTerSShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK1);
    }

    public function getTerSShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK2);
    }

    public function getTerSShieldGenerator_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_SHIELD_GENERATOR_01_MK3);
    }

    public function getTerSTravelEngine_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK1);
    }

    public function getTerSTravelEngine_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK2);
    }

    public function getTerSTravelEngine_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_S_TRAVEL_ENGINE_01_MK3);
    }

    public function getTerSapporoLauncherArray() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_SAPPORO_LAUNCHER_ARRAY);
    }

    public function getTerXlAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_XL_ALL_ROUND_ENGINE);
    }

    public function getTerXlShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_XL_SHIELD_GENERATOR_01_MK1);
    }

    public function getTerXlShieldGenerator_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_XL_SHIELD_GENERATOR_01_MK2);
    }

    public function getTerXlTravelEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TER_XL_TRAVEL_ENGINE);
    }

    public function getTernSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERN_SENTINEL);
    }

    public function getTernVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERN_VANGUARD);
    }

    public function getTerran1DockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_1_DOCK_PIER);
    }

    public function getTerran3DockEPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_3_DOCK_E_PIER);
    }

    public function getTerran3DockTPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_3_DOCK_T_PIER);
    }

    public function getTerran4DockTPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_4_DOCK_T_PIER);
    }

    public function getTerran4m10sLuxuryDockArea() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_4M10S_LUXURY_DOCK_AREA);
    }

    public function getTerranAdministrativeCentre() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_ADMINISTRATIVE_CENTRE);
    }

    public function getTerranBaseConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_01);
    }

    public function getTerranBaseConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_02);
    }

    public function getTerranBaseConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_BASE_CONNECTION_STRUCTURE_03);
    }

    public function getTerranBridgeDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_BRIDGE_DEFENCE_PLATFORM);
    }

    public function getTerranCrossConnectionStructure() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_CROSS_CONNECTION_STRUCTURE);
    }

    public function getTerranDiscDefencePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_DISC_DEFENCE_PLATFORM);
    }

    public function getTerranEnergyCellProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_ENERGY_CELL_PRODUCTION);
    }

    public function getTerranLContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_L_CONTAINER_STORAGE);
    }

    public function getTerranLLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_L_LIQUID_STORAGE);
    }

    public function getTerranLLivingQuarters() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_L_LIVING_QUARTERS);
    }

    public function getTerranLShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_L_SHIP_FABRICATION_BAY);
    }

    public function getTerranLShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_L_SHIP_MAINTENANCE_BAY);
    }

    public function getTerranLSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_L_SOLID_STORAGE);
    }

    public function getTerranMContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_M_CONTAINER_STORAGE);
    }

    public function getTerranMLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_M_LIQUID_STORAGE);
    }

    public function getTerranMLivingQuarters() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_M_LIVING_QUARTERS);
    }

    public function getTerranMSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_M_SOLID_STORAGE);
    }

    public function getTerranMainBattery() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_MAIN_BATTERY);
    }

    public function getTerranMedicalSupplyProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_MEDICAL_SUPPLY_PRODUCTION);
    }

    public function getTerranMreProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_MRE_PRODUCTION);
    }

    public function getTerranSContainerStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_S_CONTAINER_STORAGE);
    }

    public function getTerranSLiquidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_S_LIQUID_STORAGE);
    }

    public function getTerranSLivingQuarters() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_S_LIVING_QUARTERS);
    }

    public function getTerranSMShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_S_M_SHIP_FABRICATION_BAY);
    }

    public function getTerranSMShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_S_M_SHIP_MAINTENANCE_BAY);
    }

    public function getTerranSSolidStorage() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_S_SOLID_STORAGE);
    }

    public function getTerranScrapRecycler() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_SCRAP_RECYCLER);
    }

    public function getTerranTradingStationHexaDockPier() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_TRADING_STATION_HEXA_DOCK_PIER);
    }

    public function getTerranVerticalConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_VERTICAL_CONNECTION_STRUCTURE_01);
    }

    public function getTerranVerticalConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_VERTICAL_CONNECTION_STRUCTURE_02);
    }

    public function getTerranXlShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_XL_SHIP_FABRICATION_BAY);
    }

    public function getTerranXlShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAN_XL_SHIP_MAINTENANCE_BAY);
    }

    public function getTerrapin() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TERRAPIN);
    }

    public function getTethysMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TETHYS_MINERAL);
    }

    public function getTethysSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TETHYS_SENTINEL);
    }

    public function getTethysVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TETHYS_VANGUARD);
    }

    public function getTeuta() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TEUTA);
    }

    public function getTheseusSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_THESEUS_SENTINEL);
    }

    public function getTheseusSport() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_THESEUS_SPORT);
    }

    public function getTheseusVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_THESEUS_VANGUARD);
    }

    public function getThresher() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_THRESHER);
    }

    public function getTokyo() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TOKYO);
    }

    public function getTrackerMine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TRACKER_MINE);
    }

    public function getTuatara() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TUATARA);
    }

    public function getTuataraMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TUATARA_MINERAL);
    }

    public function getTurretComponentProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_TURRET_COMPONENT_PRODUCTION);
    }

    public function getVelesSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VELES_SENTINEL);
    }

    public function getVelesVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VELES_VANGUARD);
    }

    public function getVentureBaseConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_01);
    }

    public function getVentureBaseConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_02);
    }

    public function getVentureBaseConnectionStructure_03() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VENTURE_BASE_CONNECTION_STRUCTURE_03);
    }

    public function getVentureCrossConnectionStructure() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VENTURE_CROSS_CONNECTION_STRUCTURE);
    }

    public function getVenturePlatform() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VENTURE_PLATFORM);
    }

    public function getVentureVerticalConnectionStructure_01() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VENTURE_VERTICAL_CONNECTION_STRUCTURE_01);
    }

    public function getVentureVerticalConnectionStructure_02() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VENTURE_VERTICAL_CONNECTION_STRUCTURE_02);
    }

    public function getVultureSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VULTURE_SENTINEL);
    }

    public function getVultureVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_VULTURE_VANGUARD);
    }

    public function getWalrus() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_WALRUS);
    }

    public function getWaterProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_WATER_PRODUCTION);
    }

    public function getWeaponComponentProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_WEAPON_COMPONENT_PRODUCTION);
    }

    public function getWheatProduction() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_WHEAT_PRODUCTION);
    }

    public function getWideAreaSensorArray() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_WIDE_AREA_SENSOR_ARRAY);
    }

    public function getWyvernGas() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_WYVERN_GAS);
    }

    public function getWyvernMineral() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_WYVERN_MINERAL);
    }

    public function getXenLAllRoundEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_L_ALL_ROUND_ENGINE);
    }

    public function getXenLSeismicChargeTurret() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_L_SEISMIC_CHARGE_TURRET);
    }

    public function getXenLShieldGenerator_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_L_SHIELD_GENERATOR_02_MK1);
    }

    public function getXenLShieldGenerator_02Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_L_SHIELD_GENERATOR_02_MK2);
    }

    public function getXenMCombatEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_COMBAT_ENGINE);
    }

    public function getXenMImpulseProjector() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_IMPULSE_PROJECTOR);
    }

    public function getXenMMiningDrill() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_MINING_DRILL);
    }

    public function getXenMNeedlerTurret_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_NEEDLER_TURRET_01_MK1);
    }

    public function getXenMNeedlerTurret_02Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_NEEDLER_TURRET_02_MK1);
    }

    public function getXenMPlasmaCutterBeam() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_PLASMA_CUTTER_BEAM);
    }

    public function getXenMShieldGenerator_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_SHIELD_GENERATOR_01_MK1);
    }

    public function getXenMShieldGenerator_04Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_M_SHIELD_GENERATOR_04_MK1);
    }

    public function getXenSCombatEngine() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_S_COMBAT_ENGINE);
    }

    public function getXenSNeedlerGun() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_S_NEEDLER_GUN);
    }

    public function getXenSShieldGenerator() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XEN_S_SHIELD_GENERATOR);
    }

    public function getXlAllRoundThrusters_01Mk1() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK1);
    }

    public function getXlAllRoundThrusters_01Mk2() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK2);
    }

    public function getXlAllRoundThrusters_01Mk3() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XL_ALL_ROUND_THRUSTERS_01_MK3);
    }

    public function getXlShipFabricationBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XL_SHIP_FABRICATION_BAY);
    }

    public function getXlShipMaintenanceBay() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XL_SHIP_MAINTENANCE_BAY);
    }

    public function getXperimentalShuttle() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_XPERIMENTAL_SHUTTLE);
    }

    public function getZeusE() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ZEUS_E);
    }

    public function getZeusSentinel() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ZEUS_SENTINEL);
    }

    public function getZeusVanguard() : BlueprintDef
    {
        return $this->defs->getByID(self::BLUEPRINT_ZEUS_VANGUARD);
    }
}
