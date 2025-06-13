<?php
/**
 * @package X4 Database
 * @subpackage Ships 
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

/**
 * 
 * 
 * This utility is meant to be used in tandem with the main
 * collection class {@see ShipDefs}. When you want to access
 * items manually in your code, the getter methods are a great
 * help to find what you need.
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
 * @subpackage Ships 
 */
class KnownShips
{
    public const SHIP_ALBATROSS_SENTINEL = 'ship_tel_xl_builder_01_b';
    public const SHIP_ALBATROSS_VANGUARD = 'ship_tel_xl_builder_01_a';
    public const SHIP_ALLIGATOR_GAS = 'ship_spl_m_miner_liquid_01_a';
    public const SHIP_ALLIGATOR_MINERAL = 'ship_spl_m_miner_solid_01_a';
    public const SHIP_ARES = 'ship_par_s_heavyfighter_01_a';
    public const SHIP_ASGARD = 'ship_atf_xl_battleship_01_a';
    public const SHIP_ASP = 'ship_spl_s_fighter_02_a';
    public const SHIP_ASP_RAIDER = 'ship_spl_s_fighter_02_b';
    public const SHIP_ASTRID = 'ship_gen_m_yacht_01_a';
    public const SHIP_ATLAS_E = 'ship_par_xl_resupplier_02_a';
    public const SHIP_ATLAS_SENTINEL = 'ship_par_xl_resupplier_01_b';
    public const SHIP_ATLAS_VANGUARD = 'ship_par_xl_resupplier_01_a';
    public const SHIP_B = 'ship_xen_m_corvette_01_a';
    public const SHIP_BALAUR = 'ship_spl_s_heavyfighter_02_a';
    public const SHIP_BALDRIC = 'ship_ter_m_trans_container_01_a';
    public const SHIP_BARBAROSSA_01_A = 'ship_pir_l_scavenger_01_a';
    public const SHIP_BARBAROSSA_01_A_STORYHIGHCAPACITY = 'ship_pir_l_scavenger_01_a_storyhighcapacity';
    public const SHIP_BARRACUDA = 'ship_bor_s_heavyfighter_01_a';
    public const SHIP_BEHEMOTH_E = 'ship_arg_l_destroyer_02_a';
    public const SHIP_BEHEMOTH_SENTINEL = 'ship_arg_l_destroyer_01_b';
    public const SHIP_BEHEMOTH_VANGUARD = 'ship_arg_l_destroyer_01_a';
    public const SHIP_BOA = 'ship_spl_m_trans_container_01_a';
    public const SHIP_BOLO_GAS = 'ship_ter_m_miner_liquid_01_a';
    public const SHIP_BOLO_MINERAL = 'ship_ter_m_miner_solid_01_a';
    public const SHIP_BUFFALO = 'ship_spl_l_trans_container_01_a';
    public const SHIP_BUZZARD_SENTINEL = 'ship_tel_s_fighter_02_b';
    public const SHIP_BUZZARD_VANGUARD = 'ship_tel_s_fighter_02_a';
    public const SHIP_CALLISTO_SENTINEL = 'ship_arg_s_trans_container_02_b';
    public const SHIP_CALLISTO_VANGUARD = 'ship_arg_s_trans_container_02_a';
    public const SHIP_CERBERUS_SENTINEL = 'ship_arg_m_frigate_01_b';
    public const SHIP_CERBERUS_VANGUARD = 'ship_arg_m_frigate_01_a';
    public const SHIP_CHIMERA = 'ship_spl_s_heavyfighter_01_a';
    public const SHIP_CHTHONIOS_E_GAS = 'ship_par_l_miner_liquid_02_a';
    public const SHIP_CHTHONIOS_E_MINERAL = 'ship_par_l_miner_solid_02_a';
    public const SHIP_CHTHONIOS_GAS_SENTINEL = 'ship_par_l_miner_liquid_01_b';
    public const SHIP_CHTHONIOS_GAS_VANGUARD = 'ship_par_l_miner_liquid_01_a';
    public const SHIP_CHTHONIOS_MINERAL_SENTINEL = 'ship_par_l_miner_solid_01_b';
    public const SHIP_CHTHONIOS_MINERAL_VANGUARD = 'ship_par_l_miner_solid_01_a';
    public const SHIP_COBRA = 'ship_spl_m_frigate_01_a';
    public const SHIP_COLOSSUS_E = 'ship_arg_xl_carrier_02_a';
    public const SHIP_COLOSSUS_SENTINEL = 'ship_arg_xl_carrier_01_b';
    public const SHIP_COLOSSUS_VANGUARD = 'ship_arg_xl_carrier_01_a';
    public const SHIP_CONDOR_SENTINEL = 'ship_tel_xl_carrier_01_b';
    public const SHIP_CONDOR_VANGUARD = 'ship_tel_xl_carrier_01_a';
    public const SHIP_CORMORANT_VANGUARD = 'ship_tel_m_trans_container_03_a';
    public const SHIP_COURIER_MINERAL = 'ship_arg_s_miner_solid_01_a';
    public const SHIP_COURIER_SENTINEL = 'ship_arg_s_trans_container_01_b';
    public const SHIP_COURIER_VANGUARD = 'ship_arg_s_trans_container_01_a';
    public const SHIP_CRANE_E_GAS = 'ship_tel_l_miner_liquid_02_a';
    public const SHIP_CRANE_E_MINERAL = 'ship_tel_l_miner_solid_02_a';
    public const SHIP_CRANE_GAS_SENTINEL = 'ship_tel_l_miner_liquid_01_b';
    public const SHIP_CRANE_GAS_VANGUARD = 'ship_tel_l_miner_liquid_01_a';
    public const SHIP_CRANE_MINERAL_SENTINEL = 'ship_tel_l_miner_solid_01_b';
    public const SHIP_CRANE_MINERAL_VANGUARD = 'ship_tel_l_miner_solid_01_a';
    public const SHIP_CUTLASS = 'ship_ter_s_fighter_04_a';
    public const SHIP_DART = 'ship_gen_s_racer_01_a';
    public const SHIP_DEMETER_SENTINEL = 'ship_par_m_trans_container_01_b';
    public const SHIP_DEMETER_VANGUARD = 'ship_par_m_trans_container_01_a';
    public const SHIP_DISCOVERER_SENTINEL = 'ship_arg_s_scout_01_b';
    public const SHIP_DISCOVERER_VANGUARD = 'ship_arg_s_scout_01_a';
    public const SHIP_DOLPHIN = 'ship_bor_m_trans_container_01_a';
    public const SHIP_DONIA = 'ship_pir_l_miner_solid_01_a';
    public const SHIP_DRAGON = 'ship_spl_m_corvette_01_a';
    public const SHIP_DRAGON_RAIDER = 'ship_spl_m_corvette_01_b';
    public const SHIP_DRILL_MINERAL_SENTINEL = 'ship_arg_m_miner_solid_01_b';
    public const SHIP_DRILL_MINERAL_VANGUARD = 'ship_arg_m_miner_solid_01_a';
    public const SHIP_ECLIPSE_SENTINEL = 'ship_arg_s_heavyfighter_01_b';
    public const SHIP_ECLIPSE_VANGUARD_01_A = 'ship_arg_s_heavyfighter_01_a';
    public const SHIP_ECLIPSE_VANGUARD_02_A = 'ship_arg_s_heavyfighter_02_a';
    public const SHIP_ELEPHANT = 'ship_spl_xl_builder_01_a';
    public const SHIP_ELITE_SENTINEL = 'ship_arg_s_fighter_02_b';
    public const SHIP_ELITE_SPORT = 'ship_arg_s_racer_01_a';
    public const SHIP_ELITE_VANGUARD = 'ship_arg_s_fighter_02_a';
    public const SHIP_ERLKING = 'ship_pir_xl_battleship_01_a';
    public const SHIP_F = 'ship_xen_s_heavyfighter_01_a';
    public const SHIP_FALCON_SENTINEL = 'ship_tel_s_fighter_01_b';
    public const SHIP_FALCON_VANGUARD = 'ship_tel_s_fighter_01_a';
    public const SHIP_FALX = 'ship_ter_m_frigate_01_a';
    public const SHIP_FORAGER = 'ship_kha_s_fighter_02_a';
    public const SHIP_FROG = 'ship_ter_s_trans_container_01_a';
    public const SHIP_GANNASCUS = 'ship_pir_xl_builder_01';
    public const SHIP_GLADIUS = 'ship_ter_s_heavyfighter_01_a';
    public const SHIP_GORGON_SENTINEL = 'ship_par_m_frigate_01_b';
    public const SHIP_GORGON_VANGUARD = 'ship_par_m_frigate_01_a';
    public const SHIP_GROUPER_MINERAL_01_A = 'ship_bor_s_miner_solid_01_a';
    public const SHIP_GROUPER_MINERAL_01_STORY = 'ship_bor_s_miner_solid_01_story';
    public const SHIP_GUILLEMOT_SENTINEL = 'ship_tel_s_scout_02_b';
    public const SHIP_GUILLEMOT_VANGUARD = 'ship_tel_s_scout_02_a';
    public const SHIP_GUPPY = 'ship_bor_l_carrier_01_a';
    public const SHIP_H = 'ship_xen_l_terraformer_01_a';
    public const SHIP_HELIOS_E = 'ship_par_l_trans_container_03_a';
    public const SHIP_HELIOS_SENTINEL = 'ship_par_l_trans_container_01_b';
    public const SHIP_HELIOS_VANGUARD = 'ship_par_l_trans_container_01_a';
    public const SHIP_HERACLES_SENTINEL = 'ship_par_xl_builder_01_b';
    public const SHIP_HERACLES_VANGUARD = 'ship_par_xl_builder_01_a';
    public const SHIP_HERMES_SENTINEL = 'ship_par_m_trans_container_02_b';
    public const SHIP_HERMES_VANGUARD = 'ship_par_m_trans_container_02_a';
    public const SHIP_HERON_E = 'ship_tel_l_trans_container_03_a';
    public const SHIP_HERON_SENTINEL = 'ship_tel_l_trans_container_02_b';
    public const SHIP_HERON_VANGUARD = 'ship_tel_l_trans_container_02_a';
    public const SHIP_HIVE_GUARD = 'ship_kha_m_fighter_02_a';
    public const SHIP_HOKKAIDO_GAS = 'ship_ter_l_miner_liquid_01_a';
    public const SHIP_HOKKAIDO_MINERAL = 'ship_ter_l_miner_solid_01_a';
    public const SHIP_HONSHU = 'ship_ter_xl_resupplier_01_a';
    public const SHIP_HYDRA = 'ship_bor_m_corvette_01_a';
    public const SHIP_HYDRA_REGAL = 'ship_bor_m_corvette_02_a';
    public const SHIP_HYPERION = 'ship_par_l_expeditionary_01_a';
    public const SHIP_I = 'ship_xen_xl_carrier_01_a';
    public const SHIP_IDES_SENTINEL = 'ship_arg_m_trans_container_02_b';
    public const SHIP_IDES_VANGUARD = 'ship_arg_m_trans_container_02_a';
    public const SHIP_INCARCATURA_SENTINEL = 'ship_arg_l_trans_container_03_b';
    public const SHIP_INCARCATURA_VANGUARD = 'ship_arg_l_trans_container_03_a';
    public const SHIP_IRUKANDJI = 'ship_bor_s_scout_02_a';
    public const SHIP_JAGUAR = 'ship_spl_s_scout_01_a';
    public const SHIP_JIAN = 'ship_ter_m_gunboat_01_a';
    public const SHIP_K = 'ship_xen_xl_destroyer_01_a';
    public const SHIP_KALIS = 'ship_ter_s_fighter_02_a';
    public const SHIP_KATANA = 'ship_ter_m_corvette_01_a';
    public const SHIP_KESTREL_SENTINEL = 'ship_tel_s_scout_01_b';
    public const SHIP_KESTREL_SPORT = 'ship_tel_s_racer_01_a';
    public const SHIP_KESTREL_VANGUARD = 'ship_tel_s_scout_01_a';
    public const SHIP_KOPIS_MINERAL = 'ship_ter_s_miner_solid_01_a';
    public const SHIP_KUKRI = 'ship_ter_s_fighter_01_a';
    public const SHIP_KURAOKAMI = 'ship_yak_m_corvette_01_a';
    public const SHIP_KYD = 'ship_pir_s_fighter_01_a';
    public const SHIP_KYUSHU = 'ship_ter_xl_builder_01_a';
    public const SHIP_LUX = 'ship_pir_s_fighter_02_a';
    public const SHIP_M = 'ship_xen_s_fighter_02_a';
    public const SHIP_M0 = 'ship_xen_xl_mothership_01';
    public const SHIP_MAGNETAR_GAS_SENTINEL = 'ship_arg_l_miner_liquid_01_b';
    public const SHIP_MAGNETAR_GAS_VANGUARD = 'ship_arg_l_miner_liquid_01_a';
    public const SHIP_MAGNETAR_MINERAL_SENTINEL = 'ship_arg_l_miner_solid_01_b';
    public const SHIP_MAGNETAR_MINERAL_VANGUARD = 'ship_arg_l_miner_solid_01_a';
    public const SHIP_MAGPIE_MINERAL = 'ship_tel_s_miner_solid_01_a';
    public const SHIP_MAGPIE_SENTINEL = 'ship_tel_s_trans_container_01_b';
    public const SHIP_MAGPIE_VANGUARD = 'ship_tel_s_trans_container_01_a';
    public const SHIP_MAKO = 'ship_bor_s_fighter_01_a';
    public const SHIP_MAMBA = 'ship_spl_s_fighter_01_a';
    public const SHIP_MAMMOTH_SENTINEL = 'ship_arg_xl_builder_01_b';
    public const SHIP_MAMMOTH_VANGUARD = 'ship_arg_xl_builder_01_a';
    public const SHIP_MANORINA_GAS_SENTINEL = 'ship_tel_m_miner_liquid_01_b';
    public const SHIP_MANORINA_GAS_VANGUARD = 'ship_tel_m_miner_liquid_01_a';
    public const SHIP_MANORINA_MINERAL_SENTINEL = 'ship_tel_m_miner_solid_01_b';
    public const SHIP_MANORINA_MINERAL_VANGUARD = 'ship_tel_m_miner_solid_01_a';
    public const SHIP_MANTICORE = 'ship_gen_m_tugboat_01_a';
    public const SHIP_MERCURY_SENTINEL = 'ship_arg_m_trans_container_01_b';
    public const SHIP_MERCURY_VANGUARD = 'ship_arg_m_trans_container_01_a';
    public const SHIP_MINOTAUR_RAIDER = 'ship_arg_m_bomber_02_a';
    public const SHIP_MINOTAUR_SENTINEL = 'ship_arg_m_bomber_01_b';
    public const SHIP_MINOTAUR_VANGUARD = 'ship_arg_m_bomber_01_a';
    public const SHIP_MOKOSI_SENTINEL = 'ship_arg_l_trans_container_02_b';
    public const SHIP_MOKOSI_VANGUARD = 'ship_arg_l_trans_container_02_a';
    public const SHIP_MONITOR = 'ship_spl_xl_resupplier_01_a';
    public const SHIP_MOREYA = 'ship_yak_s_fighter_01_a';
    public const SHIP_N = 'ship_xen_s_fighter_01_a';
    public const SHIP_NEMESIS_SENTINEL = 'ship_par_m_corvette_01_b';
    public const SHIP_NEMESIS_VANGUARD = 'ship_par_m_corvette_01_a';
    public const SHIP_NIMCHA = 'ship_ter_s_scout_02_a';
    public const SHIP_NODAN_SENTINEL = 'ship_gen_s_fighter_01_b';
    public const SHIP_NODAN_VANGUARD = 'ship_gen_s_fighter_01_a';
    public const SHIP_NOMAD_SENTINEL = 'ship_arg_xl_resupplier_01_b';
    public const SHIP_NOMAD_VANGUARD = 'ship_arg_xl_resupplier_01_a';
    public const SHIP_NOVA_SENTINEL = 'ship_arg_s_fighter_01_b';
    public const SHIP_NOVA_VANGUARD = 'ship_arg_s_fighter_01_a';
    public const SHIP_OBLITERATOR = 'ship_kha_xl_battleship_01_a';
    public const SHIP_ODACHI = 'ship_ter_m_corvette_02_a';
    public const SHIP_ODYSSEUS_E = 'ship_par_l_destroyer_02_a';
    public const SHIP_ODYSSEUS_SENTINEL = 'ship_par_l_destroyer_01_b';
    public const SHIP_ODYSSEUS_VANGUARD = 'ship_par_l_destroyer_01_a';
    public const SHIP_OKINAWA = 'ship_ter_l_trans_container_01_a';
    public const SHIP_OKINAWA_RESEARCH = 'ship_ter_l_research_01_a';
    public const SHIP_ORCA = 'ship_bor_xl_resupplier_01_a';
    public const SHIP_OSAKA = 'ship_ter_l_destroyer_01_a';
    public const SHIP_OSPREY_SENTINEL = 'ship_tel_m_frigate_01_b';
    public const SHIP_OSPREY_VANGUARD = 'ship_tel_m_frigate_01_a';
    public const SHIP_P = 'ship_xen_m_fighter_01_a';
    public const SHIP_PE = 'ship_xen_m_corvette_02_a';
    public const SHIP_PEGASUS_SENTINEL = 'ship_par_s_scout_01_b';
    public const SHIP_PEGASUS_VANGUARD = 'ship_par_s_scout_01_a';
    public const SHIP_PELICAN_SENTINEL = 'ship_tel_l_trans_container_01_b';
    public const SHIP_PELICAN_VANGUARD = 'ship_tel_l_trans_container_01_a';
    public const SHIP_PEREGRINE_SENTINEL = 'ship_tel_m_bomber_01_b';
    public const SHIP_PEREGRINE_VANGUARD = 'ship_tel_m_bomber_01_a';
    public const SHIP_PERSEUS_SENTINEL = 'ship_par_s_fighter_01_b';
    public const SHIP_PERSEUS_VANGUARD = 'ship_par_s_fighter_01_a';
    public const SHIP_PHOENIX_E = 'ship_tel_l_destroyer_02_a';
    public const SHIP_PHOENIX_SENTINEL = 'ship_tel_l_destroyer_01_b';
    public const SHIP_PHOENIX_VANGUARD = 'ship_tel_l_destroyer_01_a';
    public const SHIP_PIRANHA = 'ship_bor_s_scout_01_a';
    public const SHIP_PLUTUS_GAS_SENTINEL = 'ship_par_m_miner_liquid_01_b';
    public const SHIP_PLUTUS_GAS_VANGUARD = 'ship_par_m_miner_liquid_01_a';
    public const SHIP_PLUTUS_MINERAL_SENTINEL = 'ship_par_m_miner_solid_01_b';
    public const SHIP_PLUTUS_MINERAL_VANGUARD = 'ship_par_m_miner_solid_01_a';
    public const SHIP_PORPOISE_GAS = 'ship_bor_m_miner_liquid_01_a';
    public const SHIP_PORPOISE_MINERAL = 'ship_bor_m_miner_solid_01_a';
    public const SHIP_PROMETHEUS = 'ship_par_m_trans_container_03_a';
    public const SHIP_PROTECTOR = 'ship_kha_s_fighter_01_a';
    public const SHIP_PULSAR_VANGUARD = 'ship_arg_s_fighter_03_a';
    public const SHIP_QUASAR_VANGUARD = 'ship_arg_s_fighter_04_a';
    public const SHIP_QUEENS_GUARD = 'ship_kha_m_fighter_01_a';
    public const SHIP_RALEIGH_CONDENSATE = 'ship_pir_s_trans_condensate_01_a';
    public const SHIP_RALEIGH_CONTAINER = 'ship_pir_s_trans_container_01_a';
    public const SHIP_RAPIER = 'ship_ter_s_scout_01_a';
    public const SHIP_RAPTOR = 'ship_spl_xl_carrier_01_a';
    public const SHIP_RATTLESNAKE = 'ship_spl_l_destroyer_01_a';
    public const SHIP_RAVAGER = 'ship_kha_l_destroyer_01_a';
    public const SHIP_RAVEN = 'ship_tel_s_trans_container_02_a';
    public const SHIP_RAY = 'ship_bor_l_destroyer_01_a';
    public const SHIP_RORQUAL_GAS = 'ship_bor_l_miner_liquid_01_a';
    public const SHIP_RORQUAL_MINERAL = 'ship_bor_l_miner_solid_01_a';
    public const SHIP_S = 'ship_xen_m_miner_01_a';
    public const SHIP_SAPPORO = 'ship_ter_l_flagship_01_a';
    public const SHIP_SE = 'ship_xen_m_miner_solid_01_a';
    public const SHIP_SELENE_SENTINEL = 'ship_par_l_trans_container_02_b';
    public const SHIP_SELENE_VANGUARD = 'ship_par_l_trans_container_02_a';
    public const SHIP_SHARK = 'ship_bor_xl_carrier_01_a';
    public const SHIP_SHIH = 'ship_pir_s_heavyfighter_01_a';
    public const SHIP_SHUYAKU_SENTINEL = 'ship_arg_l_trans_container_04_b';
    public const SHIP_SHUYAKU_VANGUARD = 'ship_arg_l_trans_container_04_a';
    public const SHIP_SONRA_SENTINEL = 'ship_arg_l_trans_container_05_b';
    public const SHIP_SONRA_VANGUARD = 'ship_arg_l_trans_container_05_a';
    public const SHIP_STORK_SENTINEL = 'ship_tel_xl_resupplier_01_b';
    public const SHIP_STORK_VANGUARD = 'ship_tel_xl_resupplier_01_a';
    public const SHIP_STURGEON = 'ship_bor_l_trans_container_01_a';
    public const SHIP_SUNDER_GAS_SENTINEL = 'ship_arg_m_miner_liquid_01_b';
    public const SHIP_SUNDER_GAS_VANGUARD = 'ship_arg_m_miner_liquid_01_a';
    public const SHIP_SYN = 'ship_atf_l_destroyer_01_a';
    public const SHIP_T = 'ship_xen_s_scout_01_a';
    public const SHIP_TAKOBA = 'ship_ter_s_fighter_03_a';
    public const SHIP_TERN_SENTINEL = 'ship_tel_m_trans_container_02_b';
    public const SHIP_TERN_VANGUARD = 'ship_tel_m_trans_container_02_a';
    public const SHIP_TERRAPIN = 'ship_bor_s_trans_container_01_a';
    public const SHIP_TETHYS_MINERAL = 'ship_par_s_miner_solid_01_a';
    public const SHIP_TETHYS_SENTINEL = 'ship_par_s_trans_container_01_b';
    public const SHIP_TETHYS_VANGUARD = 'ship_par_s_trans_container_01_a';
    public const SHIP_TEUTA = 'ship_pir_l_scrapper_01';
    public const SHIP_THESEUS_SENTINEL = 'ship_par_s_fighter_02_b';
    public const SHIP_THESEUS_SPORT = 'ship_par_s_racer_01_a';
    public const SHIP_THESEUS_VANGUARD = 'ship_par_s_fighter_02_a';
    public const SHIP_THRESHER = 'ship_bor_m_gunboat_01_a';
    public const SHIP_TOKYO = 'ship_ter_xl_carrier_01_a';
    public const SHIP_TUATARA = 'ship_spl_s_trans_container_01_a';
    public const SHIP_TUATARA_MINERAL = 'ship_spl_s_miner_solid_01_a';
    public const SHIP_VELES_SENTINEL = 'ship_arg_l_trans_container_01_b';
    public const SHIP_VELES_VANGUARD = 'ship_arg_l_trans_container_01_a';
    public const SHIP_VULTURE_SENTINEL = 'ship_tel_m_trans_container_01_b';
    public const SHIP_VULTURE_VANGUARD = 'ship_tel_m_trans_container_01_a';
    public const SHIP_WALRUS = 'ship_bor_xl_builder_01_a';
    public const SHIP_WYVERN_GAS = 'ship_spl_l_miner_liquid_01_a';
    public const SHIP_WYVERN_MINERAL = 'ship_spl_l_miner_solid_01_a';
    public const SHIP_XPERIMENTAL_SHUTTLE = 'ship_ter_s_xperimental_01_a';
    public const SHIP_ZEUS_E = 'ship_par_xl_carrier_02_a';
    public const SHIP_ZEUS_SENTINEL = 'ship_par_xl_carrier_01_b';
    public const SHIP_ZEUS_VANGUARD = 'ship_par_xl_carrier_01_a';
    
    public const SHIPS = array(
        self::SHIP_ALBATROSS_SENTINEL,
        self::SHIP_ALBATROSS_VANGUARD,
        self::SHIP_ALLIGATOR_GAS,
        self::SHIP_ALLIGATOR_MINERAL,
        self::SHIP_ARES,
        self::SHIP_ASGARD,
        self::SHIP_ASP,
        self::SHIP_ASP_RAIDER,
        self::SHIP_ASTRID,
        self::SHIP_ATLAS_E,
        self::SHIP_ATLAS_SENTINEL,
        self::SHIP_ATLAS_VANGUARD,
        self::SHIP_B,
        self::SHIP_BALAUR,
        self::SHIP_BALDRIC,
        self::SHIP_BARBAROSSA_01_A,
        self::SHIP_BARBAROSSA_01_A_STORYHIGHCAPACITY,
        self::SHIP_BARRACUDA,
        self::SHIP_BEHEMOTH_E,
        self::SHIP_BEHEMOTH_SENTINEL,
        self::SHIP_BEHEMOTH_VANGUARD,
        self::SHIP_BOA,
        self::SHIP_BOLO_GAS,
        self::SHIP_BOLO_MINERAL,
        self::SHIP_BUFFALO,
        self::SHIP_BUZZARD_SENTINEL,
        self::SHIP_BUZZARD_VANGUARD,
        self::SHIP_CALLISTO_SENTINEL,
        self::SHIP_CALLISTO_VANGUARD,
        self::SHIP_CERBERUS_SENTINEL,
        self::SHIP_CERBERUS_VANGUARD,
        self::SHIP_CHIMERA,
        self::SHIP_CHTHONIOS_E_GAS,
        self::SHIP_CHTHONIOS_E_MINERAL,
        self::SHIP_CHTHONIOS_GAS_SENTINEL,
        self::SHIP_CHTHONIOS_GAS_VANGUARD,
        self::SHIP_CHTHONIOS_MINERAL_SENTINEL,
        self::SHIP_CHTHONIOS_MINERAL_VANGUARD,
        self::SHIP_COBRA,
        self::SHIP_COLOSSUS_E,
        self::SHIP_COLOSSUS_SENTINEL,
        self::SHIP_COLOSSUS_VANGUARD,
        self::SHIP_CONDOR_SENTINEL,
        self::SHIP_CONDOR_VANGUARD,
        self::SHIP_CORMORANT_VANGUARD,
        self::SHIP_COURIER_MINERAL,
        self::SHIP_COURIER_SENTINEL,
        self::SHIP_COURIER_VANGUARD,
        self::SHIP_CRANE_E_GAS,
        self::SHIP_CRANE_E_MINERAL,
        self::SHIP_CRANE_GAS_SENTINEL,
        self::SHIP_CRANE_GAS_VANGUARD,
        self::SHIP_CRANE_MINERAL_SENTINEL,
        self::SHIP_CRANE_MINERAL_VANGUARD,
        self::SHIP_CUTLASS,
        self::SHIP_DART,
        self::SHIP_DEMETER_SENTINEL,
        self::SHIP_DEMETER_VANGUARD,
        self::SHIP_DISCOVERER_SENTINEL,
        self::SHIP_DISCOVERER_VANGUARD,
        self::SHIP_DOLPHIN,
        self::SHIP_DONIA,
        self::SHIP_DRAGON,
        self::SHIP_DRAGON_RAIDER,
        self::SHIP_DRILL_MINERAL_SENTINEL,
        self::SHIP_DRILL_MINERAL_VANGUARD,
        self::SHIP_ECLIPSE_SENTINEL,
        self::SHIP_ECLIPSE_VANGUARD_01_A,
        self::SHIP_ECLIPSE_VANGUARD_02_A,
        self::SHIP_ELEPHANT,
        self::SHIP_ELITE_SENTINEL,
        self::SHIP_ELITE_SPORT,
        self::SHIP_ELITE_VANGUARD,
        self::SHIP_ERLKING,
        self::SHIP_F,
        self::SHIP_FALCON_SENTINEL,
        self::SHIP_FALCON_VANGUARD,
        self::SHIP_FALX,
        self::SHIP_FORAGER,
        self::SHIP_FROG,
        self::SHIP_GANNASCUS,
        self::SHIP_GLADIUS,
        self::SHIP_GORGON_SENTINEL,
        self::SHIP_GORGON_VANGUARD,
        self::SHIP_GROUPER_MINERAL_01_A,
        self::SHIP_GROUPER_MINERAL_01_STORY,
        self::SHIP_GUILLEMOT_SENTINEL,
        self::SHIP_GUILLEMOT_VANGUARD,
        self::SHIP_GUPPY,
        self::SHIP_H,
        self::SHIP_HELIOS_E,
        self::SHIP_HELIOS_SENTINEL,
        self::SHIP_HELIOS_VANGUARD,
        self::SHIP_HERACLES_SENTINEL,
        self::SHIP_HERACLES_VANGUARD,
        self::SHIP_HERMES_SENTINEL,
        self::SHIP_HERMES_VANGUARD,
        self::SHIP_HERON_E,
        self::SHIP_HERON_SENTINEL,
        self::SHIP_HERON_VANGUARD,
        self::SHIP_HIVE_GUARD,
        self::SHIP_HOKKAIDO_GAS,
        self::SHIP_HOKKAIDO_MINERAL,
        self::SHIP_HONSHU,
        self::SHIP_HYDRA,
        self::SHIP_HYDRA_REGAL,
        self::SHIP_HYPERION,
        self::SHIP_I,
        self::SHIP_IDES_SENTINEL,
        self::SHIP_IDES_VANGUARD,
        self::SHIP_INCARCATURA_SENTINEL,
        self::SHIP_INCARCATURA_VANGUARD,
        self::SHIP_IRUKANDJI,
        self::SHIP_JAGUAR,
        self::SHIP_JIAN,
        self::SHIP_K,
        self::SHIP_KALIS,
        self::SHIP_KATANA,
        self::SHIP_KESTREL_SENTINEL,
        self::SHIP_KESTREL_SPORT,
        self::SHIP_KESTREL_VANGUARD,
        self::SHIP_KOPIS_MINERAL,
        self::SHIP_KUKRI,
        self::SHIP_KURAOKAMI,
        self::SHIP_KYD,
        self::SHIP_KYUSHU,
        self::SHIP_LUX,
        self::SHIP_M,
        self::SHIP_M0,
        self::SHIP_MAGNETAR_GAS_SENTINEL,
        self::SHIP_MAGNETAR_GAS_VANGUARD,
        self::SHIP_MAGNETAR_MINERAL_SENTINEL,
        self::SHIP_MAGNETAR_MINERAL_VANGUARD,
        self::SHIP_MAGPIE_MINERAL,
        self::SHIP_MAGPIE_SENTINEL,
        self::SHIP_MAGPIE_VANGUARD,
        self::SHIP_MAKO,
        self::SHIP_MAMBA,
        self::SHIP_MAMMOTH_SENTINEL,
        self::SHIP_MAMMOTH_VANGUARD,
        self::SHIP_MANORINA_GAS_SENTINEL,
        self::SHIP_MANORINA_GAS_VANGUARD,
        self::SHIP_MANORINA_MINERAL_SENTINEL,
        self::SHIP_MANORINA_MINERAL_VANGUARD,
        self::SHIP_MANTICORE,
        self::SHIP_MERCURY_SENTINEL,
        self::SHIP_MERCURY_VANGUARD,
        self::SHIP_MINOTAUR_RAIDER,
        self::SHIP_MINOTAUR_SENTINEL,
        self::SHIP_MINOTAUR_VANGUARD,
        self::SHIP_MOKOSI_SENTINEL,
        self::SHIP_MOKOSI_VANGUARD,
        self::SHIP_MONITOR,
        self::SHIP_MOREYA,
        self::SHIP_N,
        self::SHIP_NEMESIS_SENTINEL,
        self::SHIP_NEMESIS_VANGUARD,
        self::SHIP_NIMCHA,
        self::SHIP_NODAN_SENTINEL,
        self::SHIP_NODAN_VANGUARD,
        self::SHIP_NOMAD_SENTINEL,
        self::SHIP_NOMAD_VANGUARD,
        self::SHIP_NOVA_SENTINEL,
        self::SHIP_NOVA_VANGUARD,
        self::SHIP_OBLITERATOR,
        self::SHIP_ODACHI,
        self::SHIP_ODYSSEUS_E,
        self::SHIP_ODYSSEUS_SENTINEL,
        self::SHIP_ODYSSEUS_VANGUARD,
        self::SHIP_OKINAWA,
        self::SHIP_OKINAWA_RESEARCH,
        self::SHIP_ORCA,
        self::SHIP_OSAKA,
        self::SHIP_OSPREY_SENTINEL,
        self::SHIP_OSPREY_VANGUARD,
        self::SHIP_P,
        self::SHIP_PE,
        self::SHIP_PEGASUS_SENTINEL,
        self::SHIP_PEGASUS_VANGUARD,
        self::SHIP_PELICAN_SENTINEL,
        self::SHIP_PELICAN_VANGUARD,
        self::SHIP_PEREGRINE_SENTINEL,
        self::SHIP_PEREGRINE_VANGUARD,
        self::SHIP_PERSEUS_SENTINEL,
        self::SHIP_PERSEUS_VANGUARD,
        self::SHIP_PHOENIX_E,
        self::SHIP_PHOENIX_SENTINEL,
        self::SHIP_PHOENIX_VANGUARD,
        self::SHIP_PIRANHA,
        self::SHIP_PLUTUS_GAS_SENTINEL,
        self::SHIP_PLUTUS_GAS_VANGUARD,
        self::SHIP_PLUTUS_MINERAL_SENTINEL,
        self::SHIP_PLUTUS_MINERAL_VANGUARD,
        self::SHIP_PORPOISE_GAS,
        self::SHIP_PORPOISE_MINERAL,
        self::SHIP_PROMETHEUS,
        self::SHIP_PROTECTOR,
        self::SHIP_PULSAR_VANGUARD,
        self::SHIP_QUASAR_VANGUARD,
        self::SHIP_QUEENS_GUARD,
        self::SHIP_RALEIGH_CONDENSATE,
        self::SHIP_RALEIGH_CONTAINER,
        self::SHIP_RAPIER,
        self::SHIP_RAPTOR,
        self::SHIP_RATTLESNAKE,
        self::SHIP_RAVAGER,
        self::SHIP_RAVEN,
        self::SHIP_RAY,
        self::SHIP_RORQUAL_GAS,
        self::SHIP_RORQUAL_MINERAL,
        self::SHIP_S,
        self::SHIP_SAPPORO,
        self::SHIP_SE,
        self::SHIP_SELENE_SENTINEL,
        self::SHIP_SELENE_VANGUARD,
        self::SHIP_SHARK,
        self::SHIP_SHIH,
        self::SHIP_SHUYAKU_SENTINEL,
        self::SHIP_SHUYAKU_VANGUARD,
        self::SHIP_SONRA_SENTINEL,
        self::SHIP_SONRA_VANGUARD,
        self::SHIP_STORK_SENTINEL,
        self::SHIP_STORK_VANGUARD,
        self::SHIP_STURGEON,
        self::SHIP_SUNDER_GAS_SENTINEL,
        self::SHIP_SUNDER_GAS_VANGUARD,
        self::SHIP_SYN,
        self::SHIP_T,
        self::SHIP_TAKOBA,
        self::SHIP_TERN_SENTINEL,
        self::SHIP_TERN_VANGUARD,
        self::SHIP_TERRAPIN,
        self::SHIP_TETHYS_MINERAL,
        self::SHIP_TETHYS_SENTINEL,
        self::SHIP_TETHYS_VANGUARD,
        self::SHIP_TEUTA,
        self::SHIP_THESEUS_SENTINEL,
        self::SHIP_THESEUS_SPORT,
        self::SHIP_THESEUS_VANGUARD,
        self::SHIP_THRESHER,
        self::SHIP_TOKYO,
        self::SHIP_TUATARA,
        self::SHIP_TUATARA_MINERAL,
        self::SHIP_VELES_SENTINEL,
        self::SHIP_VELES_VANGUARD,
        self::SHIP_VULTURE_SENTINEL,
        self::SHIP_VULTURE_VANGUARD,
        self::SHIP_WALRUS,
        self::SHIP_WYVERN_GAS,
        self::SHIP_WYVERN_MINERAL,
        self::SHIP_XPERIMENTAL_SHUTTLE,
        self::SHIP_ZEUS_E,
        self::SHIP_ZEUS_SENTINEL,
        self::SHIP_ZEUS_VANGUARD
    );

    private ShipDefs $defs;

    private function __construct()
    {
        $this->defs = ShipDefs::getInstance();
    }

    private static ?KnownShips $instance = null;

    public static function getInstance() : KnownShips
    {
        if (!isset(self::$instance)) {
            self::$instance = new KnownShips();
        }

        return self::$instance;
    }

    public function getAlbatrossSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ALBATROSS_SENTINEL);
    }

    public function getAlbatrossVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ALBATROSS_VANGUARD);
    }

    public function getAlligatorGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ALLIGATOR_GAS);
    }

    public function getAlligatorMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ALLIGATOR_MINERAL);
    }

    public function getAres() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ARES);
    }

    public function getAsgard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ASGARD);
    }

    public function getAsp() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ASP);
    }

    public function getAspRaider() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ASP_RAIDER);
    }

    public function getAstrid() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ASTRID);
    }

    public function getAtlasE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ATLAS_E);
    }

    public function getAtlasSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ATLAS_SENTINEL);
    }

    public function getAtlasVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ATLAS_VANGUARD);
    }

    public function getB() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_B);
    }

    public function getBalaur() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BALAUR);
    }

    public function getBaldric() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BALDRIC);
    }

    public function getBarbarossa_01A() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BARBAROSSA_01_A);
    }

    public function getBarbarossa_01A_storyhighcapacity() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BARBAROSSA_01_A_STORYHIGHCAPACITY);
    }

    public function getBarracuda() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BARRACUDA);
    }

    public function getBehemothE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BEHEMOTH_E);
    }

    public function getBehemothSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BEHEMOTH_SENTINEL);
    }

    public function getBehemothVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BEHEMOTH_VANGUARD);
    }

    public function getBoa() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BOA);
    }

    public function getBoloGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BOLO_GAS);
    }

    public function getBoloMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BOLO_MINERAL);
    }

    public function getBuffalo() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BUFFALO);
    }

    public function getBuzzardSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BUZZARD_SENTINEL);
    }

    public function getBuzzardVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_BUZZARD_VANGUARD);
    }

    public function getCallistoSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CALLISTO_SENTINEL);
    }

    public function getCallistoVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CALLISTO_VANGUARD);
    }

    public function getCerberusSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CERBERUS_SENTINEL);
    }

    public function getCerberusVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CERBERUS_VANGUARD);
    }

    public function getChimera() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CHIMERA);
    }

    public function getChthoniosEGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CHTHONIOS_E_GAS);
    }

    public function getChthoniosEMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CHTHONIOS_E_MINERAL);
    }

    public function getChthoniosGasSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CHTHONIOS_GAS_SENTINEL);
    }

    public function getChthoniosGasVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CHTHONIOS_GAS_VANGUARD);
    }

    public function getChthoniosMineralSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CHTHONIOS_MINERAL_SENTINEL);
    }

    public function getChthoniosMineralVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CHTHONIOS_MINERAL_VANGUARD);
    }

    public function getCobra() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_COBRA);
    }

    public function getColossusE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_COLOSSUS_E);
    }

    public function getColossusSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_COLOSSUS_SENTINEL);
    }

    public function getColossusVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_COLOSSUS_VANGUARD);
    }

    public function getCondorSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CONDOR_SENTINEL);
    }

    public function getCondorVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CONDOR_VANGUARD);
    }

    public function getCormorantVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CORMORANT_VANGUARD);
    }

    public function getCourierMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_COURIER_MINERAL);
    }

    public function getCourierSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_COURIER_SENTINEL);
    }

    public function getCourierVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_COURIER_VANGUARD);
    }

    public function getCraneEGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CRANE_E_GAS);
    }

    public function getCraneEMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CRANE_E_MINERAL);
    }

    public function getCraneGasSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CRANE_GAS_SENTINEL);
    }

    public function getCraneGasVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CRANE_GAS_VANGUARD);
    }

    public function getCraneMineralSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CRANE_MINERAL_SENTINEL);
    }

    public function getCraneMineralVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CRANE_MINERAL_VANGUARD);
    }

    public function getCutlass() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_CUTLASS);
    }

    public function getDart() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DART);
    }

    public function getDemeterSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DEMETER_SENTINEL);
    }

    public function getDemeterVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DEMETER_VANGUARD);
    }

    public function getDiscovererSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DISCOVERER_SENTINEL);
    }

    public function getDiscovererVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DISCOVERER_VANGUARD);
    }

    public function getDolphin() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DOLPHIN);
    }

    public function getDonia() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DONIA);
    }

    public function getDragon() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DRAGON);
    }

    public function getDragonRaider() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DRAGON_RAIDER);
    }

    public function getDrillMineralSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DRILL_MINERAL_SENTINEL);
    }

    public function getDrillMineralVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_DRILL_MINERAL_VANGUARD);
    }

    public function getEclipseSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ECLIPSE_SENTINEL);
    }

    public function getEclipseVanguard_01A() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ECLIPSE_VANGUARD_01_A);
    }

    public function getEclipseVanguard_02A() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ECLIPSE_VANGUARD_02_A);
    }

    public function getElephant() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ELEPHANT);
    }

    public function getEliteSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ELITE_SENTINEL);
    }

    public function getEliteSport() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ELITE_SPORT);
    }

    public function getEliteVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ELITE_VANGUARD);
    }

    public function getErlking() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ERLKING);
    }

    public function getF() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_F);
    }

    public function getFalconSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_FALCON_SENTINEL);
    }

    public function getFalconVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_FALCON_VANGUARD);
    }

    public function getFalx() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_FALX);
    }

    public function getForager() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_FORAGER);
    }

    public function getFrog() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_FROG);
    }

    public function getGannascus() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GANNASCUS);
    }

    public function getGladius() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GLADIUS);
    }

    public function getGorgonSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GORGON_SENTINEL);
    }

    public function getGorgonVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GORGON_VANGUARD);
    }

    public function getGrouperMineral_01A() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GROUPER_MINERAL_01_A);
    }

    public function getGrouperMineral_01Story() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GROUPER_MINERAL_01_STORY);
    }

    public function getGuillemotSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GUILLEMOT_SENTINEL);
    }

    public function getGuillemotVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GUILLEMOT_VANGUARD);
    }

    public function getGuppy() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_GUPPY);
    }

    public function getH() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_H);
    }

    public function getHeliosE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HELIOS_E);
    }

    public function getHeliosSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HELIOS_SENTINEL);
    }

    public function getHeliosVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HELIOS_VANGUARD);
    }

    public function getHeraclesSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HERACLES_SENTINEL);
    }

    public function getHeraclesVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HERACLES_VANGUARD);
    }

    public function getHermesSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HERMES_SENTINEL);
    }

    public function getHermesVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HERMES_VANGUARD);
    }

    public function getHeronE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HERON_E);
    }

    public function getHeronSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HERON_SENTINEL);
    }

    public function getHeronVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HERON_VANGUARD);
    }

    public function getHiveGuard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HIVE_GUARD);
    }

    public function getHokkaidoGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HOKKAIDO_GAS);
    }

    public function getHokkaidoMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HOKKAIDO_MINERAL);
    }

    public function getHonshu() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HONSHU);
    }

    public function getHydra() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HYDRA);
    }

    public function getHydraRegal() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HYDRA_REGAL);
    }

    public function getHyperion() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_HYPERION);
    }

    public function getI() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_I);
    }

    public function getIdesSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_IDES_SENTINEL);
    }

    public function getIdesVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_IDES_VANGUARD);
    }

    public function getIncarcaturaSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_INCARCATURA_SENTINEL);
    }

    public function getIncarcaturaVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_INCARCATURA_VANGUARD);
    }

    public function getIrukandji() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_IRUKANDJI);
    }

    public function getJaguar() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_JAGUAR);
    }

    public function getJian() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_JIAN);
    }

    public function getK() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_K);
    }

    public function getKalis() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KALIS);
    }

    public function getKatana() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KATANA);
    }

    public function getKestrelSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KESTREL_SENTINEL);
    }

    public function getKestrelSport() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KESTREL_SPORT);
    }

    public function getKestrelVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KESTREL_VANGUARD);
    }

    public function getKopisMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KOPIS_MINERAL);
    }

    public function getKukri() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KUKRI);
    }

    public function getKuraokami() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KURAOKAMI);
    }

    public function getKyd() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KYD);
    }

    public function getKyushu() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_KYUSHU);
    }

    public function getLux() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_LUX);
    }

    public function getM() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_M);
    }

    public function getM0() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_M0);
    }

    public function getMagnetarGasSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAGNETAR_GAS_SENTINEL);
    }

    public function getMagnetarGasVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAGNETAR_GAS_VANGUARD);
    }

    public function getMagnetarMineralSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAGNETAR_MINERAL_SENTINEL);
    }

    public function getMagnetarMineralVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAGNETAR_MINERAL_VANGUARD);
    }

    public function getMagpieMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAGPIE_MINERAL);
    }

    public function getMagpieSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAGPIE_SENTINEL);
    }

    public function getMagpieVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAGPIE_VANGUARD);
    }

    public function getMako() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAKO);
    }

    public function getMamba() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAMBA);
    }

    public function getMammothSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAMMOTH_SENTINEL);
    }

    public function getMammothVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MAMMOTH_VANGUARD);
    }

    public function getManorinaGasSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MANORINA_GAS_SENTINEL);
    }

    public function getManorinaGasVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MANORINA_GAS_VANGUARD);
    }

    public function getManorinaMineralSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MANORINA_MINERAL_SENTINEL);
    }

    public function getManorinaMineralVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MANORINA_MINERAL_VANGUARD);
    }

    public function getManticore() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MANTICORE);
    }

    public function getMercurySentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MERCURY_SENTINEL);
    }

    public function getMercuryVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MERCURY_VANGUARD);
    }

    public function getMinotaurRaider() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MINOTAUR_RAIDER);
    }

    public function getMinotaurSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MINOTAUR_SENTINEL);
    }

    public function getMinotaurVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MINOTAUR_VANGUARD);
    }

    public function getMokosiSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MOKOSI_SENTINEL);
    }

    public function getMokosiVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MOKOSI_VANGUARD);
    }

    public function getMonitor() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MONITOR);
    }

    public function getMoreya() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_MOREYA);
    }

    public function getN() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_N);
    }

    public function getNemesisSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NEMESIS_SENTINEL);
    }

    public function getNemesisVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NEMESIS_VANGUARD);
    }

    public function getNimcha() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NIMCHA);
    }

    public function getNodanSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NODAN_SENTINEL);
    }

    public function getNodanVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NODAN_VANGUARD);
    }

    public function getNomadSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NOMAD_SENTINEL);
    }

    public function getNomadVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NOMAD_VANGUARD);
    }

    public function getNovaSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NOVA_SENTINEL);
    }

    public function getNovaVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_NOVA_VANGUARD);
    }

    public function getObliterator() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_OBLITERATOR);
    }

    public function getOdachi() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ODACHI);
    }

    public function getOdysseusE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ODYSSEUS_E);
    }

    public function getOdysseusSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ODYSSEUS_SENTINEL);
    }

    public function getOdysseusVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ODYSSEUS_VANGUARD);
    }

    public function getOkinawa() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_OKINAWA);
    }

    public function getOkinawaResearch() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_OKINAWA_RESEARCH);
    }

    public function getOrca() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ORCA);
    }

    public function getOsaka() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_OSAKA);
    }

    public function getOspreySentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_OSPREY_SENTINEL);
    }

    public function getOspreyVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_OSPREY_VANGUARD);
    }

    public function getP() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_P);
    }

    public function getPe() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PE);
    }

    public function getPegasusSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PEGASUS_SENTINEL);
    }

    public function getPegasusVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PEGASUS_VANGUARD);
    }

    public function getPelicanSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PELICAN_SENTINEL);
    }

    public function getPelicanVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PELICAN_VANGUARD);
    }

    public function getPeregrineSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PEREGRINE_SENTINEL);
    }

    public function getPeregrineVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PEREGRINE_VANGUARD);
    }

    public function getPerseusSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PERSEUS_SENTINEL);
    }

    public function getPerseusVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PERSEUS_VANGUARD);
    }

    public function getPhoenixE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PHOENIX_E);
    }

    public function getPhoenixSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PHOENIX_SENTINEL);
    }

    public function getPhoenixVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PHOENIX_VANGUARD);
    }

    public function getPiranha() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PIRANHA);
    }

    public function getPlutusGasSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PLUTUS_GAS_SENTINEL);
    }

    public function getPlutusGasVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PLUTUS_GAS_VANGUARD);
    }

    public function getPlutusMineralSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PLUTUS_MINERAL_SENTINEL);
    }

    public function getPlutusMineralVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PLUTUS_MINERAL_VANGUARD);
    }

    public function getPorpoiseGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PORPOISE_GAS);
    }

    public function getPorpoiseMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PORPOISE_MINERAL);
    }

    public function getPrometheus() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PROMETHEUS);
    }

    public function getProtector() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PROTECTOR);
    }

    public function getPulsarVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_PULSAR_VANGUARD);
    }

    public function getQuasarVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_QUASAR_VANGUARD);
    }

    public function getQueensGuard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_QUEENS_GUARD);
    }

    public function getRaleighCondensate() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RALEIGH_CONDENSATE);
    }

    public function getRaleighContainer() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RALEIGH_CONTAINER);
    }

    public function getRapier() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RAPIER);
    }

    public function getRaptor() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RAPTOR);
    }

    public function getRattlesnake() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RATTLESNAKE);
    }

    public function getRavager() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RAVAGER);
    }

    public function getRaven() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RAVEN);
    }

    public function getRay() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RAY);
    }

    public function getRorqualGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RORQUAL_GAS);
    }

    public function getRorqualMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_RORQUAL_MINERAL);
    }

    public function getS() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_S);
    }

    public function getSapporo() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SAPPORO);
    }

    public function getSe() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SE);
    }

    public function getSeleneSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SELENE_SENTINEL);
    }

    public function getSeleneVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SELENE_VANGUARD);
    }

    public function getShark() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SHARK);
    }

    public function getShih() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SHIH);
    }

    public function getShuyakuSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SHUYAKU_SENTINEL);
    }

    public function getShuyakuVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SHUYAKU_VANGUARD);
    }

    public function getSonraSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SONRA_SENTINEL);
    }

    public function getSonraVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SONRA_VANGUARD);
    }

    public function getStorkSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_STORK_SENTINEL);
    }

    public function getStorkVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_STORK_VANGUARD);
    }

    public function getSturgeon() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_STURGEON);
    }

    public function getSunderGasSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SUNDER_GAS_SENTINEL);
    }

    public function getSunderGasVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SUNDER_GAS_VANGUARD);
    }

    public function getSyn() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_SYN);
    }

    public function getT() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_T);
    }

    public function getTakoba() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TAKOBA);
    }

    public function getTernSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TERN_SENTINEL);
    }

    public function getTernVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TERN_VANGUARD);
    }

    public function getTerrapin() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TERRAPIN);
    }

    public function getTethysMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TETHYS_MINERAL);
    }

    public function getTethysSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TETHYS_SENTINEL);
    }

    public function getTethysVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TETHYS_VANGUARD);
    }

    public function getTeuta() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TEUTA);
    }

    public function getTheseusSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_THESEUS_SENTINEL);
    }

    public function getTheseusSport() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_THESEUS_SPORT);
    }

    public function getTheseusVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_THESEUS_VANGUARD);
    }

    public function getThresher() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_THRESHER);
    }

    public function getTokyo() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TOKYO);
    }

    public function getTuatara() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TUATARA);
    }

    public function getTuataraMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_TUATARA_MINERAL);
    }

    public function getVelesSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_VELES_SENTINEL);
    }

    public function getVelesVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_VELES_VANGUARD);
    }

    public function getVultureSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_VULTURE_SENTINEL);
    }

    public function getVultureVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_VULTURE_VANGUARD);
    }

    public function getWalrus() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_WALRUS);
    }

    public function getWyvernGas() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_WYVERN_GAS);
    }

    public function getWyvernMineral() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_WYVERN_MINERAL);
    }

    public function getXperimentalShuttle() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_XPERIMENTAL_SHUTTLE);
    }

    public function getZeusE() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ZEUS_E);
    }

    public function getZeusSentinel() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ZEUS_SENTINEL);
    }

    public function getZeusVanguard() : ShipDef
    {
        return $this->defs->getByID(self::SHIP_ZEUS_VANGUARD);
    }
}
